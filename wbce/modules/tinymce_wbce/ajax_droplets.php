<?php
/**
 * tinymce_wbce — ajax_droplets.php
 * Returns active WBCE droplets as JSON array.
 * Output: [{"name":"...","description":"...","usage":"..."}, ...]
 * @author  SC-Peet
 * @license GNU GPL2
 */

// Bootstrap WBCE (Session + DB + Auth)
if (!defined('WB_PATH')) {
    $configPath = realpath(dirname(__FILE__) . '/../../config.php');
    if (!$configPath || !file_exists($configPath)) {
        http_response_code(403); exit('Access denied');
    }
    require_once $configPath;
}

// Auth via the WBCE Admin class (same as CKEditor's pages.php)
require_once WB_PATH . '/framework/class.admin.php';
$admin = new Admin('Pages', 'pages_modify', false, false);
if (!$admin->is_authenticated()) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

global $database;

// Check whether the mod_droplets table exists
$check = $database->fetchRow("SHOW TABLES LIKE '{TP}mod_droplets'");
if (!$check) {
    echo json_encode([]);
    exit;
}

$rows = $database->fetchAll(
    'SELECT `name`, `description`, `comments`
       FROM `{TP}mod_droplets`
      ORDER BY `name` ASC'
);

$result = array_map(function (array $row): array {
    return [
        'name'        => $row['name'],
        'description' => strip_tags($row['description'] ?? ''),
        'usage'       => strip_tags($row['comments']    ?? ''),
    ];
}, $rows ?: []);

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
