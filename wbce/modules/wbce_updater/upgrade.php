<?php
/**
 * WBCE Update-Assistent - Upgrade Script
 *
 * Wird beim Upgrade des Moduls ausgeführt
 * Kann zukünftig für Migrations-Aufgaben verwendet werden
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

// Get current module version from database (for version-specific migrations)
global $database;
if (!isset($database) || !$database) {
    require_once WB_PATH . '/framework/class.database.php';
    $database = new database();
}

// Security: Validate and sanitize TABLE_PREFIX
$safe_table_prefix = preg_replace('/[^a-zA-Z0-9_]/', '', TABLE_PREFIX);

$result = $database->query(
    "SELECT version FROM " . $safe_table_prefix . "addons
     WHERE directory='wbce_updater' AND type='module'"
);

if ($result && $result->numRows() > 0) {
    $row = $result->fetchRow(MYSQLI_ASSOC);
    $old_version = $row['version'];

    // Version-specific upgrade tasks (migrations)
    // Example: if (version_compare($old_version, '0.9.0', '<')) { /* upgrade code */ }

    // Currently no specific upgrade tasks needed
    // This file is prepared for future upgrades
}

// Clean old cache files on upgrade
$cache_files = [
    WB_PATH . '/temp/.wbce_releases_cache.json',
    WB_PATH . '/temp/.wbce_requirements_cache.json'
];

foreach ($cache_files as $cache_file) {
    if (file_exists($cache_file)) {
        @unlink($cache_file);
    }
}
