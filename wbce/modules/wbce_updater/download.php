<?php
/**
 * WBCE Update-Assistent - Download Handler
 *
 * Lädt Update-Paket herunter und bereitet Update vor
 *
 * @category    module
 * @package     wbce_updater
 * @version     0.9.16
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// Include WBCE framework
require '../../config.php';
require_once WB_PATH . '/framework/class.admin.php';

// Load central configuration
require_once __DIR__ . '/config_defaults.php';

// Include checksum validator
require_once __DIR__ . '/checksum_validator.php';

// Load language file
$lang = (file_exists(__DIR__ . '/languages/' . LANGUAGE . '.php'))
    ? __DIR__ . '/languages/' . LANGUAGE . '.php'
    : __DIR__ . '/languages/EN.php';
require $lang;

// Security check: Admin only
$admin = new admin('Admintools', 'admintools', false, false);

// FTAN Check
if (!$admin->checkFTAN()) {
    $admin->print_error($LANG['ERROR_FTAN']);
    exit;
}

// Check if POST data is present
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
    exit;
}

// Get POST parameters
$download_url = $_POST['download_url'] ?? '';
$target_version = $_POST['target_version'] ?? '';
$checksum = $_POST['checksum'] ?? ''; // Checksum from frontend
$backup_confirmed = isset($_POST['backup_confirmed']) && $_POST['backup_confirmed'] === '1';
$enable_maintenance = isset($_POST['enable_maintenance']) && $_POST['enable_maintenance'] === '1';

// Validate inputs
if (empty($download_url)) {
    $admin->print_error($LANG['ERROR_NO_URL']);
    exit;
}

// Security: Validate download URL to prevent SSRF attacks
$parsed_url = parse_url($download_url);
if (!$parsed_url) {
    $admin->print_error('Ungültige URL-Format');
    exit;
}

// Only allow HTTPS protocol
if (!isset($parsed_url['scheme']) || $parsed_url['scheme'] !== 'https') {
    $admin->print_error('Nur HTTPS URLs sind erlaubt. HTTP und andere Protokolle sind aus Sicherheitsgründen nicht zulässig.');
    exit;
}

// Only allow GitHub domains (api.github.com or github.com)
if (!isset($parsed_url['host']) ||
    !preg_match('/^(api\.)?github\.com$/i', $parsed_url['host'])) {
    $admin->print_error('Nur Downloads von github.com sind erlaubt. Angegebener Host: ' . htmlspecialchars($parsed_url['host']));
    exit;
}

// Security: Validate version format
if (!empty($target_version) && !preg_match('/^v?\d+\.\d+(\.\d+)?$/i', $target_version)) {
    $admin->print_error('Ungültiges Versionsformat: ' . htmlspecialchars($target_version));
    exit;
}

if (!$backup_confirmed) {
    $admin->print_error($LANG['ERROR_BACKUP_NOT_CONFIRMED']);
    exit;
}

// Check write permissions
if (!is_writable(WB_PATH)) {
    $admin->print_error($LANG['ERROR_NO_WRITE_PERMISSION']);
    exit;
}

// Start process
$errors = [];
$success = true;

// Step 1: Download and repack update package
try {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'User-Agent: WBCE-Updater/1.0',
            'timeout' => WBCE_UPDATER_HTTP_TIMEOUT * 2, // Double timeout for large downloads
            'follow_location' => 1
        ]
    ]);

    $zip_content = @file_get_contents($download_url, false, $context);

    if ($zip_content === false) {
        throw new Exception($LANG['ERROR_DOWNLOAD_FAILED']);
    }

    // Temporäres ZIP speichern
    $temp_zip_path = WB_PATH . '/temp_download.zip';
    $bytes_written = file_put_contents($temp_zip_path, $zip_content);

    if ($bytes_written === false) {
        throw new Exception($LANG['ERROR_SAVE_FAILED']);
    }

    // Validate checksum if enabled and provided
    if (WBCE_UPDATER_VERIFY_CHECKSUMS) {
        if (!empty($checksum)) {
            $expectedHash = extractDigestHash($checksum); // "sha256:HASH" -> "HASH"

            if ($expectedHash && !validateFileChecksum($temp_zip_path, $expectedHash)) {
                @unlink($temp_zip_path);
                throw new Exception($LANG['ERROR_CHECKSUM_MISMATCH']);
            }
        } elseif (!empty($target_version)) {
            // No checksum provided - add warning but don't block
            $errors[] = $LANG['WARNING_NO_CHECKSUM'];
        }
    }
    // Note: No warning when checksum verification is disabled (default)

    // ZIP umpacken (nur wbce/ Ordner extrahieren)
    require_once __DIR__ . '/repack_helper.php';

    $final_zip_path = WB_PATH . '/wbceup.zip';
    $repack_result = repackZip($temp_zip_path, $final_zip_path, null, 'wbce');

    // Temporäre Datei löschen
    @unlink($temp_zip_path);

    if (!$repack_result['success']) {
        throw new Exception($LANG['ERROR_REPACK_FAILED'] . ': ' . $repack_result['message']);
    }

} catch (Exception $e) {
    $errors[] = $e->getMessage();
    $success = false;
    // Cleanup
    @unlink(WB_PATH . '/temp_download.zip');
}

// Step 2: Prepare update execution (using integrated script)
// NOTE: No longer downloading external wbce_update_unzip.php
// Using integrated execute_update.php from this module instead

// Step 3: Enable maintenance mode (optional)
$maintenance_activated = false;
$maintenance_already_active = false;

if ($success && $enable_maintenance) {
    try {
        // Use WBCE Settings Class (same method as Maintenance Mode Switcher module)
        require_once WB_PATH . '/framework/class.settings.php';

        // Check if maintenance mode is already active BEFORE trying to set it
        // Use same method as maintainance_mode module: cast to string, check if not empty
        $currentStatus = (string)Settings::Get('wb_maintainance_mode');

        if ($currentStatus) {
            // Already active - no action needed
            $maintenance_already_active = true;
            $maintenance_activated = true;
        } else {
            // Check if maintenance template exists (warning only, don't block)
            $maintenance_template_exists = false;
            $template_paths = [
                WB_PATH . '/templates/systemplates/maintainance.tpl.php',
                WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/systemplates/maintainance.tpl.php'
            ];

            foreach ($template_paths as $tpl_path) {
                if (file_exists($tpl_path)) {
                    $maintenance_template_exists = true;
                    break;
                }
            }

            if (!$maintenance_template_exists) {
                // Just a warning, still try to set maintenance mode
                $errors[] = $LANG['WARNING_NO_MAINTENANCE_TEMPLATE'];
            }

            // Set maintenance mode via Settings class - use "1" as string like the original module
            Settings::Set("wb_maintainance_mode", "1");

            // Verify that maintenance mode was actually activated
            // Re-read from database to confirm the setting was saved
            $verifiedStatus = (string)Settings::Get('wb_maintainance_mode');

            if ($verifiedStatus) {
                $maintenance_activated = true;
            } else {
                // Setting failed - throw exception to trigger warning
                throw new Exception("Maintenance mode setting could not be verified");
            }
        }

    } catch (Exception $e) {
        // Non-fatal error
        $errors[] = $LANG['WARNING_MAINTENANCE_FAILED'] . ': ' . $e->getMessage();
    }
}

// Generate output
$update_url = WB_URL . '/modules/wbce_updater/execute_update.php?version=' . urlencode($target_version);

?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE; ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo $LANG['TOOL_NAME']; ?></title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b8daff;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
        }
        .button:hover {
            background: #0056b3;
        }
        .button-secondary {
            background: #6c757d;
        }
        .button-secondary:hover {
            background: #545b62;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .file-list {
            margin: 15px 0;
            padding-left: 20px;
        }
        .file-list li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <h2 class="success">✓ <?php echo $LANG['SUCCESS_TITLE']; ?></h2>

            <div class="info-box">
                <strong><?php echo $LANG['SUCCESS_FILES_DOWNLOADED']; ?></strong>
                <ul class="file-list">
                    <li><code>wbceup.zip</code> - <?php echo $LANG['SUCCESS_UPDATE_PACKAGE']; ?></li>
                    <li><code>execute_update.php</code> - <?php echo $LANG['SUCCESS_UPDATE_SCRIPT']; ?> (integriert)</li>
                </ul>
            </div>

            <?php if ($maintenance_activated && $maintenance_already_active): ?>
                <div class="info-box">
                    <strong>✓ <?php echo $LANG['MAINTENANCE_ALREADY_ACTIVE']; ?></strong>
                    <p><?php echo $LANG['MAINTENANCE_ALREADY_ACTIVE_INFO']; ?></p>
                    <p><strong><?php echo $LANG['MAINTENANCE_DISABLE_INFO']; ?></strong></p>
                </div>
            <?php elseif ($maintenance_activated): ?>
                <div class="warning">
                    <strong>✓ <?php echo $LANG['MAINTENANCE_ENABLED']; ?></strong>
                    <p><?php echo $LANG['MAINTENANCE_INFO']; ?></p>
                    <p><strong><?php echo $LANG['MAINTENANCE_DISABLE_INFO']; ?></strong></p>
                </div>
            <?php elseif ($enable_maintenance && !$maintenance_activated): ?>
                <div class="warning">
                    <strong>⚠ <?php echo $LANG['MAINTENANCE_NOT_ACTIVATED']; ?></strong>
                    <p><?php echo $LANG['MAINTENANCE_MANUAL_INFO']; ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="warning">
                    <strong><?php echo $LANG['WARNINGS_OCCURRED']; ?></strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <p><strong><?php echo $LANG['READY_TO_UPDATE']; ?></strong></p>
            <p><?php echo $LANG['CLICK_BUTTON_TO_START']; ?></p>

            <a href="<?php echo $update_url; ?>" class="button">
                🚀 <?php echo $LANG['START_UPDATE_NOW']; ?>
            </a>

            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                <?php echo $LANG['OR_MANUAL']; ?>: <code><?php echo $update_url; ?></code>
            </p>

            <p style="margin-top: 20px;">
                <a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=wbce_updater" class="button button-secondary">
                    ← <?php echo $LANG['BACK_TO_UPDATER']; ?>
                </a>
            </p>

        <?php else: ?>
            <h2 class="error">✗ <?php echo $LANG['ERROR_TITLE']; ?></h2>

            <div class="error">
                <strong><?php echo $LANG['ERROR_OCCURRED']; ?></strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <p>
                <a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=wbce_updater" class="button button-secondary">
                    ← <?php echo $LANG['BACK_TO_UPDATER']; ?>
                </a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
