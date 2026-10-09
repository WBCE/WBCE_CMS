<?php
/**
 * tinymce_wbce — ajax_link_items.php
 *
 * Returns the linkable items (news posts, calendar entries, …) for every
 * module-backed section on one page, for the "link" plugin's page picker —
 * fired once a page is selected, so a second, page-specific picker can be
 * offered underneath. See link_items.php for the contract and the built-in
 * module list.
 *
 * Output: [{"module":"news","section_id":12,
 *           "items":[{"label":"...","value":"https://…"}]}, ...]
 *
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) {
    $configPath = realpath(dirname(__FILE__) . '/../../config.php');
    if (!$configPath || !file_exists($configPath)) {
        http_response_code(403); exit('Access denied');
    }
    require_once $configPath;
}

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

require_once __DIR__ . '/link_items.php';

$pageId = (int) ($_GET['page_id'] ?? 0);
$result = $pageId > 0 ? tinymce_wbce_link_items_for_page($pageId) : [];

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
