<?php
/**
 * tinymce_wbce — ajax_pages.php
 *
 * Returns the WBCE page tree as JSON for the "link" plugin's internal-page
 * picker (modules/tinymce_wbce/plugins/link/plugin.min.js). Built on the core
 * PageTree class (framework/PageTree.php) — the same one admin/pages/ and
 * menu_link use — so the tree connectors, visibility and page titles are
 * always consistent with the rest of the backend, instead of a hand-rolled
 * recursive query.
 *
 * Output: [{"page_id":1,"title":"...","link":"[pagelink:1]","level":0,
 *           "prefix":"└─ ","visibility":"public","disabled":false}, ...]
 *
 * "link" carries the WBCE-native "[pagelink:NN]" token (NOT a resolved URL) —
 * resolved at render time by LinkResolver via opf_pagelink, so a stored link
 * survives its target page being moved or renamed later.
 *
 * @author  SC-Peet
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

$tree    = PageTree::load($admin);
$flat    = PageTree::pageTreeCombobox($tree);

// Page language only matters on multilingual sites — otherwise every entry
// would carry the same redundant badge in the picker.
$withLang = defined('PAGE_LANGUAGES') && PAGE_LANGUAGES;

$pages = array_map(static function (array $p) use ($withLang): array {
    return [
        'page_id'    => $p['page_id'],
        'title'      => $p['menu_title'],
        'link'       => '[pagelink:' . $p['page_id'] . ']',
        'level'      => $p['level'],
        'prefix'     => $p['prefix'],
        'visibility' => $p['visibility'],
        'disabled'   => $p['disabled'],
        'language'   => $withLang ? ($p['language'] ?? '') : '',
    ];
}, $flat);

echo json_encode($pages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
