<?php
/**
 * tinymce_wbce — fee_config.php
 *
 * Returns the INLINE (FEE) editor config as JSON, resolved from the configurator.
 * modules/wysiwyg/fee_canvas.js fetches this and spreads it into
 * tinymce.init({ inline:true, … }) — so the in-place frontend toolbar comes from
 * the admin tool (global "Inline editor toolbar" setting) instead of hardcoded
 * values. On any failure the caller falls back to its built-in defaults.
 *
 * The plugin list (bundled + external) is derived from the toolbar buttons, so
 * only what the toolbar actually uses gets loaded.
 *
 * Backend auth required (same as the module's other AJAX endpoints).
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

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if (!$admin->is_authenticated()) {
    http_response_code(403);
    exit(json_encode(['error' => 'auth']));
}

require_once __DIR__ . '/presets.php';

// Resolve the inline preset from the second, lightweight list. An optional
// ?preset=<id> selects one by id; otherwise the configured default is used.
$reqPreset = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) ($_GET['preset'] ?? ''));
$inline    = tinymce_wbce_resolve_inline_preset($reqPreset);
$toolbar   = tinymce_wbce_sanitize_toolbar($inline['toolbar'] !== '' ? $inline['toolbar'] : tinymce_wbce_inline_toolbar_default());

// Split the toolbar into button tokens (drop the "|" group separators).
$tokens = array_values(array_filter(preg_split('/[\s|]+/', $toolbar), fn($t) => $t !== ''));

// token → bundled plugin (only buttons that need one; core buttons need none).
$bundledMap = [
    'bullist' => 'lists', 'numlist' => 'lists', 'outdent' => 'lists', 'indent' => 'lists',
    'table' => 'table', 'code' => 'code', 'image' => 'image', 'media' => 'media',
    'charmap' => 'charmap', 'fullscreen' => 'fullscreen', 'emoticons' => 'emoticons',
    'anchor' => 'anchor', 'visualblocks' => 'visualblocks', 'wordcount' => 'wordcount',
    'searchreplace' => 'searchreplace', 'pagebreak' => 'pagebreak', 'hr' => 'nonbreaking',
];
// token → external plugin file (module plugins).
$externalMap = [
    'link'            => 'link/plugin.min.js',
    'wblink'          => 'link/plugin.min.js',
    'wbdroplets'      => 'wbdroplets/plugin.js',
    'fa_picker'       => 'fa_picker/plugin.min.js',
    'wbce_casechange' => 'wbce_casechange/plugin.js',
    'wbce_shy'        => 'wbce_shy/plugin.js',
    'wbce_codesample' => 'wbce_codesample/plugin.js',
    'wbce_history'    => 'wbce_history/plugin.js',
];

$pluginsUrl = WB_URL . '/modules/tinymce_wbce/plugins';
$bundled    = [];
$external   = [];
foreach ($tokens as $tok) {
    if (isset($bundledMap[$tok]))  { $bundled[$bundledMap[$tok]] = true; }
    if (isset($externalMap[$tok])) { $external[$tok === 'wblink' ? 'link' : $tok] = $pluginsUrl . '/' . $externalMap[$tok]; }
}

$out = [
    'toolbar'          => $toolbar,
    'skin'             => $inline['skin'] ?? 'oxide',
    // Inline (no iframe) honours menubar/min_height. content_theme and statusbar
    // were dropped from the inline preset model (2026-07-14): content_theme's
    // content_css never had an iframe to apply to, and TinyMCE's inline mode has
    // no fixed slot for a status bar — both were dead settings.
    'menubar'          => ($inline['menubar'] ?? '0') === '1',
    'min_height'       => (int) ($inline['min_height'] ?? 0),
    'plugins'          => array_keys($bundled),
    'external_plugins' => $external,
    // Endpoints the plugins may need (harmless if the plugin isn't loaded).
    'link_ajax_url'      => WB_URL . '/modules/tinymce_wbce/ajax_pages.php',
    'link_items_url'     => WB_URL . '/modules/tinymce_wbce/ajax_link_items.php',
    'link_include_url'   => defined('INCLUDE_URL') ? INCLUDE_URL : (WB_URL . '/include'),
    'wbdroplets_ajax_url'=> WB_URL . '/modules/tinymce_wbce/ajax_droplets.php',
    'base_url'           => WB_URL . '/modules/tinymce_wbce/tinymce',
];

echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
