<?php
/**
 * WBCE Update-Assistent - Download Handler
 *
 * Lädt Update-Paket herunter und bereitet Update vor
 *
 * @category    module
 * @package     wbce_updater
 * @version     1.0.2
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

if (!empty($wbce_updater_disabled)) {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
    exit;
}

// FTAN Check
if (!$admin->checkFTAN()) {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
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
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
    exit;
}

// Security: Validate download URL to prevent SSRF attacks
$parsed_url = parse_url($download_url);
if (!$parsed_url) {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
    exit;
}

// Only allow HTTPS protocol
if (!isset($parsed_url['scheme']) || $parsed_url['scheme'] !== 'https') {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
    exit;
}

// Custom source: allow only if URL matches the exact configured value (server-side whitelist)
$is_custom_source = !empty($wbce_updater_custom_source_url)
    && hash_equals($wbce_updater_custom_source_url, $download_url);

// Standard path: only allow GitHub domains
if (!$is_custom_source) {
    if (!isset($parsed_url['host']) ||
        !preg_match('/^(api\.)?github\.com$/i', $parsed_url['host'])) {
        header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
        exit;
    }
}

// Security: Validate version format
if (!empty($target_version) && !preg_match('/^v?\d+\.\d+(\.\d+)?$/i', $target_version)) {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
    exit;
}

if (!$backup_confirmed) {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
    exit;
}

// Check write permissions
if (!is_writable(WB_PATH)) {
    header('Location: ' . ADMIN_URL . '/admintools/tool.php?tool=wbce_updater');
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
            'timeout' => WBCE_UPDATER_HTTP_TIMEOUT * 2,
            'follow_location' => 1,
            'ignore_errors' => true
        ],
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true
        ]
    ]);

    $zip_content = @file_get_contents($download_url, false, $context);

    if ($zip_content === false || $zip_content === '') {
        throw new Exception($LANG['ERROR_DOWNLOAD_FAILED'] . ': Leere oder fehlgeschlagene Antwort vom Server');
    }

    // ZIP-Signatur prüfen bevor die Datei geschrieben wird
    if (strlen($zip_content) < 4 || substr($zip_content, 0, 2) !== 'PK') {
        throw new Exception($LANG['ERROR_DOWNLOAD_FAILED'] . ': Keine gültige ZIP-Datei empfangen. ' .
            'Serverantwort beginnt mit: ' . htmlspecialchars(substr($zip_content, 0, 200)));
    }

    // Temporäres ZIP speichern
    $temp_zip_path = WB_PATH . '/temp_download.zip';
    $bytes_written = file_put_contents($temp_zip_path, $zip_content);

    if ($bytes_written === false || $bytes_written === 0) {
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
$maintenance_activated      = false;
$maintenance_already_active = false;

if ($success && $enable_maintenance) {
    require_once __DIR__ . '/maintenance_helper.php';
    $maint = wbce_updater_enable_maintenance($errors, $LANG);
    $maintenance_activated      = $maint['activated'];
    $maintenance_already_active = $maint['already_active'];
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
        .loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .loading-overlay.active {
            display: flex;
        }
        .spinner {
            width: 52px;
            height: 52px;
            border: 5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-overlay .loading-text {
            margin-top: 18px;
            color: #fff;
            font-size: 17px;
            font-weight: bold;
        }
        .loading-overlay .loading-subtext {
            margin-top: 8px;
            color: rgba(255,255,255,0.75);
            font-size: 13px;
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

            <a href="<?php echo $update_url; ?>" class="button" id="start-update-btn" onclick="startUpdate(this)">
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

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">⏳ <?php echo $LANG['LOADING_DOWNLOAD']; ?>...</div>
        <div class="loading-subtext"><?php echo $LANG['DOWNLOAD_PLEASE_WAIT']; ?></div>
    </div>

    <script>
    function startUpdate(link) {
        document.getElementById('loading-overlay').classList.add('active');
        link.style.pointerEvents = 'none';
        link.style.opacity = '0.6';
    }
    </script>
</body>
</html>
