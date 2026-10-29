<?php
/**
 * WBCE Update-Assistent - ZIP Repack Helper
 *
 * Intelligent ZIP repack function for GitHub releases
 *
 * @category    module
 * @package     wbce_updater
 * @version     1.0.2
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

/**
 * Automatically finds the WBCE subfolder inside a GitHub ZIP
 *
 * @param ZipArchive $zip Opened ZIP archive
 * @return string|false Path to the WBCE folder, or false
 */
function findWbceFolder($zip) {
    $foundPaths = [];

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $stat = $zip->statIndex($i);
        $path = $stat['name'];

        // Look for files that are guaranteed to exist in the wbce/ root and
        // are specific enough not to accidentally match inside a module.
        // Note: don't use 'config.php' - not present in the release ZIP
        // (only config.php.new), but present in modules like ckeditor/filemanager.
        $wbceMarkers = [
            'framework/class.admin.php',
            'framework/class.wb.php',
            'admin/admintools/tool.php',
            'install/index.php',
        ];

        foreach ($wbceMarkers as $marker) {
            if (substr($path, -strlen($marker)) === $marker) {
                // Extract the base path (everything before the marker)
                $basePath = substr($path, 0, strrpos($path, $marker));
                $foundPaths[] = $basePath;
            }
        }
    }

    if (empty($foundPaths)) {
        return false;
    }

    // The most frequently occurring path is most likely the correct one
    $pathCounts = array_count_values($foundPaths);
    arsort($pathCounts);

    return key($pathCounts);
}

/**
 * Alternative: look for a folder with a specific name
 *
 * @param ZipArchive $zip Opened ZIP archive
 * @param string $folderName Name of the folder to look for (e.g. 'wbce')
 * @return string|false Full path to the folder
 */
function findFolderByName($zip, $folderName = 'wbce') {
    $candidates = [];

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $stat = $zip->statIndex($i);
        $path = $stat['name'];

        if (preg_match('#/?' . preg_quote($folderName, '#') . '/#', $path)) {
            // Extract the path up to and including the folder we're looking for
            $pos = strpos($path, $folderName . '/');
            if ($pos !== false) {
                $candidate = substr($path, 0, $pos + strlen($folderName) + 1);
                $candidates[] = $candidate;
            }
        }
    }

    if (empty($candidates)) {
        return false;
    }

    // The shortest path is most likely the correct one
    usort($candidates, function($a, $b) {
        return strlen($a) - strlen($b);
    });

    return $candidates[0];
}

/**
 * Repacks a ZIP with automatic path detection
 *
 * @param string $sourceZip Source ZIP file
 * @param string $targetZip Target ZIP file
 * @param string|null $subPath Optional subfolder path (null = auto-detect)
 * @param string $targetFolderName Folder name to look for during auto-detect
 * @return array ['success' => bool, 'message' => string, 'found_path' => string]
 */
function repackZip($sourceZip, $targetZip, $subPath = null, $targetFolderName = 'wbce') {
    $zip = new ZipArchive();
    $newZip = new ZipArchive();
    $result = [
        'success' => false,
        'message' => '',
        'found_path' => ''
    ];

    if ($zip->open($sourceZip) !== TRUE) {
        $result['message'] = 'Konnte Quell-ZIP nicht öffnen: ' . $sourceZip;
        return $result;
    }

    if ($subPath === null) {
        // Method 1: look for typical WBCE files
        $detectedPath = findWbceFolder($zip);

        // Method 2 (fallback): look for a folder name
        if (!$detectedPath) {
            $detectedPath = findFolderByName($zip, $targetFolderName);
        }

        if (!$detectedPath) {
            $zip->close();
            $result['message'] = 'WBCE-Ordner konnte nicht automatisch gefunden werden';
            return $result;
        }

        $subPath = $detectedPath;
        $result['found_path'] = $subPath;
    }

    // Normalize the path (must end with / if not empty)
    $subPath = rtrim($subPath, '/');
    if ($subPath !== '') {
        $subPath .= '/';
    }

    if ($newZip->open($targetZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        $zip->close();
        $result['message'] = 'Konnte Ziel-ZIP nicht erstellen: ' . $targetZip;
        return $result;
    }

    $filesAdded = 0;
    $errors = [];

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $stat = $zip->statIndex($i);
        $fullPath = $stat['name'];

        if ($subPath === '' || strpos($fullPath, $subPath) === 0) {
            // Strip the prefix to get the new relative path
            $relativePath = $subPath === '' ? $fullPath : substr($fullPath, strlen($subPath));

            // Security: Check for path traversal attempts
            if (strpos($relativePath, '..') !== false) {
                $errors[] = 'Sicherheitswarnung: Ungültiger Pfad erkannt: ' . $relativePath;
                continue;
            }

            // Only add if it's not an empty folder name
            if ($relativePath !== false && $relativePath !== "" && substr($relativePath, -1) !== '/') {
                $content = $zip->getFromIndex($i);

                if ($content === false) {
                    $errors[] = 'Konnte Datei nicht lesen: ' . $fullPath;
                    continue;
                }

                if ($newZip->addFromString($relativePath, $content)) {
                    $filesAdded++;
                } else {
                    $errors[] = 'Konnte Datei nicht hinzufügen: ' . $relativePath;
                }
            }
        }
    }

    $newZip->close();
    $zip->close();

    if ($filesAdded === 0) {
        $result['message'] = 'Keine Dateien gefunden im Pfad: ' . $subPath;
        @unlink($targetZip); // Delete the empty ZIP
        return $result;
    }

    $result['success'] = true;
    $result['message'] = $filesAdded . ' Dateien erfolgreich umgepackt' .
                         ($subPath !== '' ? ' aus ' . $subPath : '');

    if (!empty($errors)) {
        $result['message'] .= ' (' . count($errors) . ' Fehler)';
        $result['errors'] = $errors;
    }

    return $result;
}

/**
 * Detects an individually renamed admin directory and renames the
 * "admin/" folder in the already packed update ZIP accordingly.
 *
 * WBCE release packages always contain an "admin/" folder. Without this
 * adjustment, updating with a renamed admin directory would just create a
 * new, unused "admin/" folder and leave the actually active directory unpatched.
 *
 * @param string $zipPath Path to the already packed wbceup.zip (modified in-place)
 * @return array ['renamed' => int, 'admin_dir' => string]
 */
function adjustAdminFolderName($zipPath) {
    $result = ['renamed' => 0, 'admin_dir' => 'admin'];

    if (!defined('WB_URL') || !defined('ADMIN_URL')) {
        return $result;
    }

    // Derive the physical name of the active admin directory from ADMIN_URL
    $adminDirName = trim(str_replace(rtrim(WB_URL, '/'), '', ADMIN_URL), '/');

    // Only act if clearly detected, different from the default, and safe
    // (no path separators, dots, etc.)
    if ($adminDirName === '' || $adminDirName === 'admin' || !preg_match('/^[A-Za-z0-9_-]+$/', $adminDirName)) {
        return $result;
    }

    $result['admin_dir'] = $adminDirName;

    $zip = new ZipArchive();
    if ($zip->open($zipPath) !== TRUE) {
        return $result;
    }

    $oldPrefix = 'admin/';
    $newPrefix = $adminDirName . '/';
    $renamed = 0;

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if ($name !== false && strpos($name, $oldPrefix) === 0) {
            $newName = $newPrefix . substr($name, strlen($oldPrefix));
            if ($zip->renameIndex($i, $newName)) {
                $renamed++;
            }
        }
    }

    $zip->close();
    $result['renamed'] = $renamed;

    return $result;
}

/**
 * Determines which currently active templates/themes are WBCE standard
 * templates and therefore should be protected from being overwritten on update.
 *
 * Extracted into its own pure function (instead of inline in execute_update.php)
 * so it can be unit-tested without a full WBCE bootstrap.
 *
 * @param string|null $activeTemplate Value of DEFAULT_TEMPLATE, or null
 * @param string|null $activeTheme Value of DEFAULT_THEME, or null
 * @param string $standardTemplatesCsv Comma-separated list (WBCE_UPDATER_STANDARD_TEMPLATES)
 * @return array List of folder names to protect (0, 1 or 2 entries)
 */
function getProtectedTemplateFolders($activeTemplate, $activeTheme, $standardTemplatesCsv) {
    $standardTemplates = array_map('trim', explode(',', $standardTemplatesCsv));
    $protected = [];

    if ($activeTemplate !== null && $activeTemplate !== '' && in_array($activeTemplate, $standardTemplates, true)) {
        $protected[] = $activeTemplate;
    }

    if ($activeTheme !== null && $activeTheme !== '' && $activeTheme !== $activeTemplate
        && in_array($activeTheme, $standardTemplates, true)) {
        $protected[] = $activeTheme;
    }

    return $protected;
}

/**
 * Checks whether a ZIP entry name lies inside one of the protected template folders.
 *
 * @param string $filename Entry name in the ZIP (e.g. "templates/wbcetik/index.php")
 * @param array $protectedTemplateFolders Result of getProtectedTemplateFolders()
 * @return bool
 */
function isProtectedTemplateFile($filename, array $protectedTemplateFolders) {
    foreach ($protectedTemplateFolders as $protectedFolder) {
        if (strpos($filename, 'templates/' . $protectedFolder . '/') === 0) {
            return true;
        }
    }
    return false;
}

/**
 * Debug function: shows the structure of a ZIP
 *
 * @param string $zipPath Path to the ZIP file
 * @param int $maxDepth Maximum directory depth (0 = top level only)
 * @return array List of entries
 */
function debugZipStructure($zipPath, $maxDepth = 2) {
    $zip = new ZipArchive();
    $structure = [];

    if ($zip->open($zipPath) !== TRUE) {
        return ['error' => 'ZIP konnte nicht geöffnet werden'];
    }

    for ($i = 0; $i < min($zip->numFiles, 100); $i++) { // Cap at 100 entries for safety
        $stat = $zip->statIndex($i);
        $path = $stat['name'];
        $depth = substr_count($path, '/');

        if ($maxDepth === 0 || $depth <= $maxDepth) {
            $structure[] = [
                'path' => $path,
                'size' => $stat['size'],
                'depth' => $depth
            ];
        }
    }

    $zip->close();
    return $structure;
}

// Example call with auto-detection:
/*
$result = repackZip(
    'github-download.zip',  // source ZIP
    'wbceup.zip',          // target ZIP
    null,                  // auto-detect
    'wbce'                 // look for a 'wbce' folder
);

if ($result['success']) {
    echo $result['message'];
    echo "\nFound path: " . $result['found_path'];
} else {
    echo "Error: " . $result['message'];
}
*/

// Example call with a fixed path (old method):
/*
repackZip(
    'pack.zip',
    'result.zip',
    'WBCE_CMS-1.6.5/wbce/'  // fixed path
);
*/
?>
