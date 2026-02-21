<?php
/**
 * WBCE Updater - Execute Update
 *
 * Entpackt das Update-Paket und startet den WBCE Update-Prozess
 * Ersetzt das externe wbce_update_unzip.php Script
 *
 * @category    module
 * @package     wbce_updater
 * @version     0.9.16
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

// Security check: Admin only (full admin class validation)
// This checks both authentication AND admin permissions
$admin = new admin('Admintools', 'admintools', false, false);

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
    <title><?php echo $LANG['TOOL_NAME']; ?> - Update Execution</title>
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
        <h2>Update Execution</h2>

        <div class="progress">
<?php

$success = true;
$errors = [];
$warnings = [];

// Step 1: PHP Compatibility Check (Dynamic from requirements.json)
// IMPORTANT: Only WARNING, never blocking! User might need to update WBCE first, then change PHP version.
echo '<div class="step">';
echo '<strong>Schritt 1: PHP-Kompatibilität prüfen...</strong><br>';

if (!empty($targetVersion)) {
    $compatibility = checkPhpCompatibility($targetVersion, PHP_VERSION);

    if (!$compatibility['compatible']) {
        // WARNING, but do NOT block!
        $phpMin = $compatibility['details']['php_min'] ?? '?';
        $phpMax = $compatibility['details']['php_max'] ?? '?';
        $phpRecommended = $compatibility['details']['php_recommended'] ?? $phpMin;

        echo '<span style="color: #dc3545; font-weight: bold;">⚠️ WARNUNG: PHP-Inkompatibilität erkannt!</span><br>';
        echo '<span style="color: #856404;">';
        echo 'Aktuelle PHP-Version: <strong>' . PHP_VERSION . '</strong><br>';
        echo 'Benötigt für WBCE ' . htmlspecialchars($targetVersion) . ': <strong>' . htmlspecialchars($phpMin) . ' - ' . htmlspecialchars($phpMax) . '</strong><br>';
        echo 'Empfohlen: <strong>' . htmlspecialchars($phpRecommended) . '</strong><br><br>';
        echo '<em>Hinweis: Update wird trotzdem durchgeführt. Bitte ändern Sie die PHP-Version nach dem Update!</em>';
        echo '</span>';

        // Add to warnings (not errors - non-blocking)
        $warnings[] = sprintf(
            'PHP %s ist nicht kompatibel mit WBCE %s (benötigt: %s - %s). Bitte PHP-Version nach dem Update ändern!',
            PHP_VERSION,
            $targetVersion,
            $phpMin,
            $phpMax
        );
    } else {
        echo '<span style="color: #28a745;">✅ PHP ' . PHP_VERSION . ' ist kompatibel mit WBCE ' . htmlspecialchars($targetVersion) . '</span>';

        // EOL Warning (non-blocking)
        if (isset($compatibility['details']['warning']) && !empty($compatibility['details']['warning'])) {
            echo '<br><span style="color: #856404;">⚠️ ' . htmlspecialchars($compatibility['details']['warning']) . '</span>';
        }
    }
} else {
    echo '<span style="color: #856404;">⚠️ Keine Zielversion angegeben, PHP-Check übersprungen</span>';
}

echo '</div>';

// Step 2: Check if ZIP file exists
if ($success) {
    echo '<div class="step">';
    echo '<strong>Schritt 2: Update-Paket prüfen...</strong><br>';

    $zipFile = WB_PATH . '/wbceup.zip';

    if (!file_exists($zipFile)) {
        $success = false;
        $error = 'Update-Paket (wbceup.zip) nicht gefunden!';
        $errors[] = $error;
        echo '<span style="color: #dc3545;">❌ ' . htmlspecialchars($error) . '</span>';
    } else {
        $fileSize = filesize($zipFile);
        echo '<span style="color: #28a745;">✅ wbceup.zip gefunden (' . round($fileSize / 1024 / 1024, 2) . ' MB)</span>';
    }

    echo '</div>';
}

// Step 3: Extract ZIP
if ($success) {
    echo '<div class="step">';
    echo '<strong>Schritt 3: Update-Paket entpacken...</strong><br>';

    try {
        $zip = new ZipArchive;
        $res = $zip->open($zipFile);

        if ($res === TRUE) {
            $path = WB_PATH;
            $numFiles = $zip->numFiles;

            // Security: Validate all file paths before extraction to prevent ZIP-Slip attacks
            $realBasePath = realpath($path);
            if ($realBasePath === false) {
                throw new Exception('Zielverzeichnis konnte nicht aufgelöst werden');
            }

            for ($i = 0; $i < $numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $filename = $stat['name'];

                // Check for directory traversal patterns
                if (strpos($filename, '../') !== false || strpos($filename, '..\\') !== false) {
                    $zip->close();
                    throw new Exception('Sicherheitswarnung: Ungültiger Dateipfad im ZIP-Archiv erkannt: ' . htmlspecialchars($filename));
                }

                // Build full path and resolve it
                $fullPath = $path . DIRECTORY_SEPARATOR . $filename;
                $realPath = realpath(dirname($fullPath));

                // For new files, realpath might return false, so check parent directory
                if ($realPath === false) {
                    // File doesn't exist yet, check parent directory
                    $parentDir = dirname($fullPath);
                    if (!file_exists($parentDir)) {
                        // Parent doesn't exist, will be created by extractTo
                        continue;
                    }
                    $realPath = realpath($parentDir);
                }

                // Ensure the resolved path is within the base path
                if ($realPath !== false && strpos($realPath, $realBasePath) !== 0) {
                    $zip->close();
                    throw new Exception('Sicherheitswarnung: Versuch erkannt, Dateien außerhalb des Zielverzeichnisses zu extrahieren');
                }
            }

            // All paths validated, now extract
            $zip->extractTo($path);
            $zip->close();

            echo '<span style="color: #28a745;">✅ ' . $numFiles . ' Dateien sicher nach ' . htmlspecialchars($path) . ' entpackt</span>';
        } else {
            // Generic error message to avoid information disclosure
            // Detailed error codes are logged but not displayed
            throw new Exception('ZIP-Archiv konnte nicht geöffnet werden. Bitte überprüfen Sie die Datei.');
        }
    } catch (Exception $e) {
        $success = false;
        $error = 'Entpacken fehlgeschlagen: ' . $e->getMessage();
        $errors[] = $error;
        echo '<span style="color: #dc3545;">❌ ' . htmlspecialchars($error) . '</span>';
    }

    echo '</div>';
}

// Step 4: Check if install/update.php exists
if ($success) {
    echo '<div class="step">';
    echo '<strong>Schritt 4: WBCE Update-Script prüfen...</strong><br>';

    $updateScript = WB_PATH . '/install/update.php';

    if (!file_exists($updateScript)) {
        $success = false;
        $error = 'WBCE Update-Script (install/update.php) nicht gefunden!';
        $errors[] = $error;
        echo '<span style="color: #dc3545;">❌ ' . htmlspecialchars($error) . '</span>';
    } else {
        echo '<span style="color: #28a745;">✅ install/update.php gefunden</span>';
    }

    echo '</div>';
}

// Step 5: Cleanup (delete ZIP - Script selbst bleibt für Debugging)
if ($success) {
    echo '<div class="step">';
    echo '<strong>Schritt 5: Cleanup...</strong><br>';

    if (@unlink($zipFile)) {
        echo '<span style="color: #28a745;">✅ wbceup.zip gelöscht</span>';
    } else {
        echo '<span style="color: #856404;">⚠️ wbceup.zip konnte nicht gelöscht werden (nicht kritisch)</span>';
    }

    echo '</div>';
}

?>
        </div>

<?php if ($success): ?>
        <h2 class="success">✅ Update-Paket erfolgreich entpackt!</h2>

        <?php if (!empty($warnings)): ?>
        <div class="error" style="background: #fff3cd; border-color: #ffc107; color: #856404;">
            <strong>⚠️ Wichtige Warnungen:</strong>
            <ul>
                <?php foreach ($warnings as $warning): ?>
                    <li><?php echo htmlspecialchars($warning); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="info-box">
            <strong>Nächster Schritt:</strong>
            <p>Das WBCE Update-Script ist bereit. Klicken Sie auf den Button unten, um den Update-Prozess zu starten.</p>
            <p><strong>Wichtig:</strong> Der Update-Prozess kann einige Minuten dauern. Schließen Sie das Fenster nicht!</p>
            <?php if (!empty($warnings)): ?>
            <p style="color: #dc3545; font-weight: bold;">⚠️ Nach dem Update: Ändern Sie die PHP-Version auf Ihrem Server!</p>
            <?php endif; ?>
        </div>

        <a href="<?php echo WB_URL; ?>/install/update.php" class="button">
            🚀 WBCE Update jetzt starten
        </a>

        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            Oder rufen Sie manuell auf: <code><?php echo WB_URL; ?>/install/update.php</code>
        </p>

<?php else: ?>
        <h2 class="error">❌ Fehler beim Update!</h2>

        <div class="error">
            <strong>Folgende Fehler sind aufgetreten:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <p>
            <a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=wbce_updater" class="button">
                ← Zurück zum Update-Assistenten
            </a>
        </p>
<?php endif; ?>

    </div>
</body>
</html>
