<?php
/**
 * WBCE Update-Assistent - Upload Handler
 *
 * Verarbeitet manuell hochgeladene WBCE ZIP-Dateien und bereitet Update vor
 *
 * @category    module
 * @package     wbce_updater
 * @version     1.0.2
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// Increase time limits
set_time_limit(300);

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
$backup_confirmed = isset($_POST['backup_confirmed_upload']) && $_POST['backup_confirmed_upload'] === '1';
$enable_maintenance = isset($_POST['enable_maintenance_upload']) && $_POST['enable_maintenance_upload'] === '1';
$target_version = trim($_POST['target_version_upload'] ?? '');
if (!empty($target_version) && !preg_match('/^v?\d+\.\d+(\.\d+)?$/i', $target_version)) {
    $target_version = ''; // Ungültiges Format ignorieren
}

// Validate backup confirmation
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
$uploaded_checksum = null;

// Step 1: Validate and process uploaded ZIP file
try {

    // Check if file was uploaded
    if (!isset($_FILES['zip_file']) || $_FILES['zip_file']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception($LANG['ERROR_NO_FILE_UPLOADED']);
    }


    // Check for upload errors
    switch ($_FILES['zip_file']['error']) {
        case UPLOAD_ERR_OK:
            // No error - continue processing
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception($LANG['ERROR_ZIP_TOO_LARGE']);
        case UPLOAD_ERR_PARTIAL:
            throw new Exception($LANG['ERROR_UPLOAD_PARTIAL']);
        case UPLOAD_ERR_NO_TMP_DIR:
            throw new Exception($LANG['ERROR_UPLOAD_NO_TMP_DIR']);
        case UPLOAD_ERR_CANT_WRITE:
            throw new Exception($LANG['ERROR_UPLOAD_CANT_WRITE']);
        default:
            // Generic error message to avoid information disclosure
            throw new Exception($LANG['ERROR_UPLOAD_FAILED']);
    }

    $uploaded_file = $_FILES['zip_file']['tmp_name'];
    $uploaded_name = $_FILES['zip_file']['name'];


    // Validate ZIP file
    if (!is_uploaded_file($uploaded_file)) {
        throw new Exception($LANG['ERROR_UPLOAD_FAILED']);
    }

    // Security: Validate file extension
    $file_extension = strtolower(pathinfo($uploaded_name, PATHINFO_EXTENSION));
    if ($file_extension !== 'zip') {
        throw new Exception(sprintf($LANG['ERROR_ZIP_ONLY'], htmlspecialchars($uploaded_name)));
    }

    // Security: Validate MIME type using finfo (OOP style, PHP 8.5 compatible)
    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($uploaded_file);

        $allowed_mime_types = [
            'application/zip',
            'application/x-zip',
            'application/x-zip-compressed',
            'application/octet-stream' // Some servers report this for ZIP
        ];

        if (!in_array($mime_type, $allowed_mime_types)) {
            throw new Exception(sprintf($LANG['ERROR_INVALID_MIME_TYPE'], htmlspecialchars($mime_type)));
        }
    }

    // Security: Check file size (configurable max size)
    if ($_FILES['zip_file']['size'] > WBCE_UPDATER_MAX_UPLOAD_SIZE) {
        throw new Exception(sprintf($LANG['ERROR_FILE_TOO_LARGE_MB'], round(WBCE_UPDATER_MAX_UPLOAD_SIZE / 1024 / 1024)));
    }


    // Check if file is a valid ZIP
    $zip = new ZipArchive();
    $zip_check = $zip->open($uploaded_file, ZipArchive::CHECKCONS);

    if ($zip_check !== true) {
        throw new Exception($LANG['ERROR_INVALID_ZIP']);
    }
    $zip->close();


    // Move uploaded file to temporary location
    $temp_zip_path = WB_PATH . '/temp_upload.zip';
    if (!move_uploaded_file($uploaded_file, $temp_zip_path)) {
        throw new Exception($LANG['ERROR_SAVE_FAILED']);
    }

    // Calculate checksum for informational purposes
    $uploaded_checksum = calculateFileHash($temp_zip_path);


    // Check if ZIP is already in correct format (files directly in root)
    $zip_test = new ZipArchive();
    $zip_test->open($temp_zip_path);

    $has_wbce_marker = false;
    $markers_found = [];

    // Check first 20 files to see structure (more thorough check)
    for ($i = 0; $i < min(20, $zip_test->numFiles); $i++) {
        $stat = $zip_test->statIndex($i);
        $path = $stat['name'];

        // Look for typical WBCE directories/files in root
        // index.php, admin/, framework/, modules/, templates/, include/, languages/
        if (preg_match('#^(index\.php|admin/|framework/|modules/|templates/|include/|languages/|media/)#', $path)) {
            $has_wbce_marker = true;
            $markers_found[] = $path;

            // Need at least 2 markers to be sure it's correct format
            if (count($markers_found) >= 2) {
                break;
            }
        }
    }
    $zip_test->close();

    // Auto-detect target version from CHANGELOG.md if not provided by user
    if (empty($target_version)) {
        $zip_ver = new ZipArchive();
        if ($zip_ver->open($temp_zip_path) === true) {
            $changelog_content = false;
            for ($i = 0; $i < min(50, $zip_ver->numFiles); $i++) {
                $stat = $zip_ver->statIndex($i);
                $entry_name = $stat['name'];
                $depth = substr_count(rtrim($entry_name, '/'), '/');
                if ($depth <= 1 && basename($entry_name) === 'CHANGELOG.md') {
                    $changelog_content = $zip_ver->getFromIndex($i);
                    if ($changelog_content !== false) {
                        break;
                    }
                }
            }
            $zip_ver->close();

            if ($changelog_content !== false &&
                preg_match('/^#{1,3}\s+\[?(\d+\.\d+\.\d+)/m', $changelog_content, $ver_match)) {
                $target_version = $ver_match[1];
            }
        }
    }

    $final_zip_path = WB_PATH . '/wbceup.zip';

    if ($has_wbce_marker) {
        // ZIP is already in correct format - just copy it

        if (!copy($temp_zip_path, $final_zip_path)) {
            @unlink($temp_zip_path);
            throw new Exception($LANG['ERROR_SAVE_FAILED']);
        }

        @unlink($temp_zip_path);

    } else {
        // ZIP needs repacking (probably GitHub format)

        require_once __DIR__ . '/repack_helper.php';
        $repack_result = repackZip($temp_zip_path, $final_zip_path, null, 'wbce');


        // Delete temporary file
        @unlink($temp_zip_path);

        if (!$repack_result['success']) {
            throw new Exception($LANG['ERROR_REPACK_FAILED'] . ': ' . $repack_result['message']);
        }

    }


} catch (Exception $e) {
    $errors[] = $e->getMessage();
    $success = false;
    // Cleanup
    @unlink(WB_PATH . '/temp_upload.zip');
    @unlink(WB_PATH . '/wbceup.zip');
}

// Step 2: Prepare update execution (using integrated script)
// NOTE: No longer downloading external wbce_update_unzip.php
// Using integrated execute_update.php from this module instead

// Step 3: Enable maintenance mode (optional)
$maintenance_activated    = false;
$maintenance_already_active = false;

if ($success && $enable_maintenance) {
    require_once __DIR__ . '/maintenance_helper.php';
    $maint = wbce_updater_enable_maintenance($errors, $LANG);
    $maintenance_activated      = $maint['activated'];
    $maintenance_already_active = $maint['already_active'];
}

// Generate output
$update_url = WB_URL . '/modules/wbce_updater/execute_update.php'
    . (!empty($target_version) ? '?version=' . urlencode($target_version) : '');

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
            <h2 class="success">✓ <?php echo $LANG['UPLOAD_SUCCESS_TITLE']; ?></h2>

            <div class="info-box">
                <strong><?php echo $LANG['UPLOAD_FILES_PREPARED']; ?></strong>
                <ul class="file-list">
                    <li><code>wbceup.zip</code> - <?php echo $LANG['SUCCESS_UPDATE_PACKAGE']; ?></li>
                    <li><code>execute_update.php</code> - <?php echo $LANG['SUCCESS_UPDATE_SCRIPT']; ?> (integriert)</li>
                </ul>
            </div>

            <?php if ($uploaded_checksum): ?>
            <div class="info-box">
                <strong><?php echo $LANG['CHECKSUM_INFO']; ?>:</strong><br>
                <code style="word-break: break-all; display: block; margin-top: 8px; background: #f4f4f4; padding: 8px; border-radius: 4px; font-size: 12px;"><?php echo htmlspecialchars($uploaded_checksum); ?></code>
                <p style="font-size: 13px; margin-top: 8px; color: #666;">
                    <?php echo $LANG['CHECKSUM_VERIFY_INFO'] ?? 'Verify this checksum matches the official release checksum before proceeding.'; ?>
                </p>
                <?php if (!WBCE_UPDATER_VERIFY_CHECKSUMS): ?>
                <p style="font-size: 13px; margin-top: 8px; color: #dc3545; font-weight: bold;">
                    ⚠️ <?php echo $LANG['WARNING_CHECKSUM_DISABLED_MANUAL']; ?>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

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
