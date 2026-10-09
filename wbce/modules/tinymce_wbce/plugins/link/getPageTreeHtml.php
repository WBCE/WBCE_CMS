<?php
/**
 * tinymce_wbce — plugins/link/getPageTreeHtml.php
 *
 * SUPERSEDED (2026-07-04) — kept for reference only, no longer called.
 *
 * This endpoint fed the MicroModal + jQuery + wbeSelect version of the merged
 * link plugin (see plugin.min.js.micromodal-bak in this folder). That version
 * needed 3 extra runtime dependencies that the admin theme provides but a plain
 * frontend page (FEE canvas) does not. The current plugin.min.js instead uses
 * editor.windowManager.open() (TinyMCE's own, skin-consistent dialog system —
 * the same approach the bundled "image" plugin and the former standalone
 * "wblink" plugin used) and fetches page data as JSON from the already-working
 * ../../ajax_pages.php, so it needs no extra dependencies at all.
 *
 * Left in place, unwired, alongside the plugin.min.js backup — not deleted.
 *
 * ── Original doc ──────────────────────────────────────────────────────────
 * AJAX endpoint: returns a <select> of all pages for the merged link plugin's
 * internal-page picker (progressively enhanced by wbeSelect, treeView mode).
 * Called by plugin.min.js via fetch() relative to its own script URL.
 *
 * The option value carries the WBCE-native "[pagelink:NN]" token (same convention as
 * modules/tinymce_wbce/ajax_pages.php and the opf_pagelink output filter that
 * resolves it on render via LinkResolver) — NOT a resolved URL. That way a stored
 * link survives the target page being moved or renamed later.
 */

if (!defined('WB_PATH')) {
    $configPath = realpath(dirname(__FILE__) . '/../../../../config.php');
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

header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

$tree         = PageTree::load($admin);
$comboOptions = PageTree::pageTreeCombobox($tree);

$toHtml = '<label class="modal__label" for="page_selector">' . L_('TEXT:PAGE') . '</label>' . PHP_EOL;
$toHtml .= '<select name="page_selector" id="page_selector" class="pageTreeCombobox"'
    . ' data-search-label="' . L_('TEXT:SEARCH') . ' … [' . L_('TEXT:PAGE') . '-ID, ' . L_('TEXT:MENU_TITLE') . ']">' . PHP_EOL;
$toHtml .= '<option value="" style="font-style:italic">(' . L_('TEXT:NONE') . ')</option>' . PHP_EOL;

foreach ($comboOptions as $p) {
    $token = '[pagelink:' . $p['page_id'] . ']';
    $toHtml .= sprintf(
        '<option data-id="%d" data-right="%d" value="%s" data-level="%d" data-prefix="%s"'
            . ' data-class="type-%s" title="%s" data-url="%s"%s%s>%s%s</option>',
        $p['page_id'],
        $p['page_id'],
        h($token),
        $p['level'],
        $p['prefix'],
        h($p['visibility']),
        h($p['page_title']),
        h($token),
        $p['selected'] ? ' selected' : '',
        $p['disabled'] ? ' disabled' : '',
        $p['prefix'],
        h($p['menu_title'])
    ) . PHP_EOL;
}
$toHtml .= '</select>' . PHP_EOL;
echo $toHtml;
