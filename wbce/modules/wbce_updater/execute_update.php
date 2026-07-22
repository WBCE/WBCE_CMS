<?php
/**
 * WBCE Updater - Execute Update
 *
 * Entpackt das Update-Paket und startet den WBCE Update-Prozess
 * Ersetzt das externe wbce_update_unzip.php Script
 *
 * @category    module
 * @package     wbce_updater
 * @version     1.0.2
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// Include WBCE framework
$configFile = dirname(dirname(dirname(__FILE__))) . '/config.php';
if (!file_exists($configFile)) {
    die('Configuration file not found');
}
require $configFile;
require_once WB_PATH . '/framework/class.admin.php';

// Load central configuration
require_once __DIR__ . '/config_defaults.php';

// Include compatibility checker for dynamic PHP version check
require_once __DIR__ . '/compatibility_checker.php';

$admin = new admin('Admintools', 'admintools', false, false);

if (!$admin->is_authenticated() || !$admin->isAdmin()) {
    header('Location: ' . ADMIN_URL . '/index.php');
    exit;
}

// Load language file
$lang = (file_exists(__DIR__ . '/languages/' . LANGUAGE . '.php'))
    ? __DIR__ . '/languages/' . LANGUAGE . '.php'
    : __DIR__ . '/languages/EN.php';
require $lang;

// Get target version from GET parameter
$targetVersion = $_GET['version'] ?? '';

// Security: Validate version format
if (!empty($targetVersion) && !preg_match('/^v?\d+\.\d+(\.\d+)?$/i', $targetVersion)) {
    http_response_code(400);
    die('Invalid version format: ' . htmlspecialchars($targetVersion));
}

// Start output
?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE; ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo $LANG['TOOL_NAME']; ?> - <?php echo $LANG['EXEC_TITLE']; ?></title>
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
        .progress {
            margin: 20px 0;
        }
        .step {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-left: 4px solid #6c757d;
        }
        .step.success {
            border-left-color: #28a745;
            background: #d4edda;
        }
        .step.error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 <?php echo $LANG['TOOL_NAME']; ?></h1>
        <h2><?php echo $LANG['EXEC_TITLE']; ?></h2>

        <div class="progress">
<?php

$success = true;
$errors = [];
$warnings = [];

// Step 1: PHP Compatibility Check (Dynamic from requirements.json)
// IMPORTANT: Only WARNING, never blocking! User might need to update WBCE first, then change PHP version.
echo '<div class="step">';
echo '<strong>' . $LANG['EXEC_STEP1'] . '</strong><br>';

if (!empty($targetVersion)) {
    $compatibility = checkPhpCompatibility($targetVersion, PHP_VERSION);

    if (!$compatibility['compatible']) {
        $phpMin = $compatibility['details']['php_min'] ?? null;
        $phpMax = $compatibility['details']['php_max'] ?? null;

        if ($phpMin === null && $phpMax === null) {
            $detailMsg = $compatibility['details']['error'] ?? 'Keine Anforderungsdaten verfügbar';
            echo '<span style="color: #856404;">⚠️ ' . sprintf($LANG['EXEC_PHP_CANNOT_CHECK'], htmlspecialchars($detailMsg)) . '</span>';
        } else {
            $phpMinStr      = $phpMin ?? '?';
            $phpMaxStr      = $phpMax ?? '?';
            $phpRecommended = $compatibility['details']['php_recommended'] ?? $phpMinStr;

            echo '<span style="color: #dc3545; font-weight: bold;">⚠️ ' . $LANG['EXEC_PHP_INCOMPAT'] . '</span><br>';
            echo '<span style="color: #856404;">';
            echo $LANG['EXEC_PHP_CURRENT'] . ' <strong>' . PHP_VERSION . '</strong><br>';
            echo sprintf($LANG['EXEC_PHP_REQUIRED_FOR'], htmlspecialchars($targetVersion)) . ' <strong>' . htmlspecialchars($phpMinStr) . ' - ' . htmlspecialchars($phpMaxStr) . '</strong><br>';
            echo $LANG['EXEC_PHP_RECOMMENDED'] . ' <strong>' . htmlspecialchars($phpRecommended) . '</strong><br><br>';
            echo '<em>' . $LANG['EXEC_PHP_CONTINUE_HINT'] . '</em>';
            echo '</span>';

            $warnings[] = sprintf($LANG['EXEC_PHP_COMPAT_WARN'], PHP_VERSION, $targetVersion, $phpMinStr, $phpMaxStr);
        }
    } else {
        echo '<span style="color: #28a745;">✅ ' . sprintf($LANG['EXEC_PHP_COMPATIBLE_MSG'], PHP_VERSION, htmlspecialchars($targetVersion)) . '</span>';

        if (isset($compatibility['details']['warning']) && !empty($compatibility['details']['warning'])) {
            echo '<br><span style="color: #856404;">⚠️ ' . htmlspecialchars($compatibility['details']['warning']) . '</span>';
        }
    }
} else {
    echo '<span style="color: #856404;">⚠️ ' . $LANG['EXEC_PHP_SKIPPED'] . '</span>';
}

echo '</div>';

// Step 2: Check if ZIP file exists
if ($success) {
    echo '<div class="step">';
    echo '<strong>' . $LANG['EXEC_STEP2'] . '</strong><br>';

    $zipFile = WB_PATH . '/wbceup.zip';

    if (!file_exists($zipFile)) {
        $success  = false;
        $error    = $LANG['EXEC_ZIP_MISSING'];
        $errors[] = $error;
        echo '<span style="color: #dc3545;">❌ ' . htmlspecialchars($error) . '</span>';
    } else {
        $fileSize = filesize($zipFile);
        echo '<span style="color: #28a745;">✅ ' . sprintf($LANG['EXEC_ZIP_FOUND'], round($fileSize / 1024 / 1024, 2)) . '</span>';
    }

    echo '</div>';
}

// Step 3: Extract ZIP
if ($success) {
    echo '<div class="step">';
    echo '<strong>' . $LANG['EXEC_STEP3'] . '</strong><br>';

    try {
        $zip = new ZipArchive;
        $res = $zip->open($zipFile);

        if ($res === TRUE) {
            $path         = WB_PATH;
            $numFiles     = $zip->numFiles;
            $realBasePath = realpath($path);

            if ($realBasePath === false) {
                throw new Exception($LANG['EXEC_DIR_RESOLVE_ERROR']);
            }

            for ($i = 0; $i < $numFiles; $i++) {
                $stat     = $zip->statIndex($i);
                $filename = $stat['name'];

                if (strpos($filename, '../') !== false || strpos($filename, '..\\') !== false ||
                    strpos($filename, "\0") !== false) {
                    $zip->close();
                    throw new Exception(sprintf($LANG['EXEC_SEC_BAD_PATH'], htmlspecialchars($filename)));
                }

                if (substr($filename, 0, 1) === '/' || (strlen($filename) >= 2 && $filename[1] === ':')) {
                    $zip->close();
                    throw new Exception(sprintf($LANG['EXEC_SEC_ABS_PATH'], htmlspecialchars($filename)));
                }

                $fullPath    = $realBasePath . DIRECTORY_SEPARATOR
                    . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filename), DIRECTORY_SEPARATOR);
                $resolvedDir = realpath(dirname($fullPath));

                if ($resolvedDir !== false) {
                    if (strncmp($resolvedDir . DIRECTORY_SEPARATOR,
                                $realBasePath . DIRECTORY_SEPARATOR,
                                strlen($realBasePath) + 1) !== 0) {
                        $zip->close();
                        throw new Exception($LANG['EXEC_SEC_TRAVERSAL']);
                    }
                } else {
                    if (strncmp($fullPath,
                                $realBasePath . DIRECTORY_SEPARATOR,
                                strlen($realBasePath) + 1) !== 0) {
                        $zip->close();
                        throw new Exception($LANG['EXEC_SEC_TRAVERSAL']);
                    }
                }
            }

            $zip->extractTo($path);
            $zip->close();

            echo '<span style="color: #28a745;">✅ ' . sprintf($LANG['EXEC_FILES_EXTRACTED'], $numFiles, htmlspecialchars($path)) . '</span>';
        } else {
            throw new Exception($LANG['EXEC_ZIP_OPEN_FAILED']);
        }
    } catch (Exception $e) {
        $success  = false;
        $error    = sprintf($LANG['EXEC_EXTRACT_FAILED'], $e->getMessage());
        $errors[] = $error;
        echo '<span style="color: #dc3545;">❌ ' . htmlspecialchars($error) . '</span>';
    }

    echo '</div>';
}

// Step 4: Check if install/update.php exists
if ($success) {
    echo '<div class="step">';
    echo '<strong>' . $LANG['EXEC_STEP4'] . '</strong><br>';

    $updateScript = WB_PATH . '/install/update.php';

    if (!file_exists($updateScript)) {
        $success  = false;
        $error    = $LANG['EXEC_SCRIPT_MISSING'];
        $errors[] = $error;
        echo '<span style="color: #dc3545;">❌ ' . htmlspecialchars($error) . '</span>';
    } else {
        echo '<span style="color: #28a745;">✅ ' . $LANG['EXEC_SCRIPT_FOUND'] . '</span>';
    }

    echo '</div>';
}

// Step 5: Cleanup (delete ZIP)
if ($success) {
    echo '<div class="step">';
    echo '<strong>' . $LANG['EXEC_STEP5'] . '</strong><br>';

    if (@unlink($zipFile)) {
        echo '<span style="color: #28a745;">✅ ' . $LANG['EXEC_ZIP_DELETED'] . '</span>';
    } else {
        echo '<span style="color: #856404;">⚠️ ' . $LANG['EXEC_ZIP_DELETE_FAILED'] . '</span>';
    }

    echo '</div>';
}

?>
        </div>

<?php if ($success): ?>
        <h2 class="success">✅ <?php echo $LANG['EXEC_SUCCESS_TITLE']; ?></h2>

        <?php if (!empty($warnings)): ?>
        <div class="error" style="background: #fff3cd; border-color: #ffc107; color: #856404;">
            <strong>⚠️ <?php echo $LANG['EXEC_WARNINGS_TITLE']; ?></strong>
            <ul>
                <?php foreach ($warnings as $warning): ?>
                    <li><?php echo htmlspecialchars($warning); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="info-box">
            <strong><?php echo $LANG['EXEC_NEXT_STEP_TITLE']; ?></strong>
            <p><?php echo $LANG['EXEC_NEXT_STEP_INFO']; ?></p>
            <p><strong><?php echo $LANG['EXEC_WINDOW_HINT']; ?></strong></p>
            <?php if (!empty($warnings)): ?>
            <p style="color: #dc3545; font-weight: bold;">⚠️ <?php echo $LANG['EXEC_PHP_CHANGE_REMINDER']; ?></p>
            <?php endif; ?>
        </div>

        <a href="<?php echo WB_URL; ?>/install/update.php" class="button">
            🚀 <?php echo $LANG['EXEC_START_UPDATE_BTN']; ?>
        </a>

        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            <?php echo $LANG['OR_MANUAL']; ?>: <code><?php echo WB_URL; ?>/install/update.php</code>
        </p>

<?php else: ?>
        <h2 class="error">❌ <?php echo $LANG['EXEC_ERROR_TITLE']; ?></h2>

        <div class="error">
            <strong><?php echo $LANG['ERROR_OCCURRED']; ?></strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <p>
            <a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=wbce_updater" class="button">
                ← <?php echo $LANG['BACK_TO_UPDATER']; ?>
            </a>
        </p>
<?php endif; ?>

    </div>
</body>
</html>
