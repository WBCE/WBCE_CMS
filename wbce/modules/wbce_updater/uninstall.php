<?php
/**
 * WBCE Update-Assistent - Uninstall Script
 *
 * Wird beim Deinstallieren des Moduls ausgeführt
 * Räumt temporäre Dateien und Cache auf
 *
 * @category    module
 * @package     wbce_updater
 * @version     0.9.16
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) {
    exit('Direct access to this file is not allowed');
}

// Clean up cache file
$cache_file = WB_PATH . '/temp/.wbce_releases_cache.json';
if (file_exists($cache_file)) {
    @unlink($cache_file);
}

// Clean up any leftover temporary files
$temp_files = [
    WB_PATH . '/temp_download.zip',
    WB_PATH . '/temp_upload.zip',
    WB_PATH . '/wbceup.zip',
    WB_PATH . '/wbce_update_unzip.php'
];

foreach ($temp_files as $temp_file) {
    if (file_exists($temp_file)) {
        @unlink($temp_file);
    }
}

// Note: We don't delete files that might have been prepared for an update
// (wbceup.zip, wbce_update_unzip.php) as the user might still want to use them

// Return success status to WBCE
return true;

