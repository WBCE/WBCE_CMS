<?php
/**
 * tinymce_wbce — link_options_save.php
 *
 * Appends newly-typed class / rel tokens (from the link dialog's creatable
 * selects) to a preset's option lists — called on "Insert". The preset is
 * resolved server-side from the posted id (falls back to the default preset);
 * tokens are sanitized and de-duplicated before being merged in.
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

$presetId = (string) ($_POST['preset'] ?? '');
$cfg = tinymce_wbce_load_cfg();
$id  = ($presetId !== '' && isset($cfg['presets'][$presetId]))
    ? $presetId : ($cfg['default_preset'] ?? '');

if ($id === '' || !isset($cfg['presets'][$id])) {
    http_response_code(400);
    exit(json_encode(['error' => 'preset']));
}

$changed = false;
foreach (['classes' => 'link_classes', 'rels' => 'link_rels'] as $post => $field) {
    $incoming = tinymce_wbce_link_tokens((string) ($_POST[$post] ?? ''));
    if (!$incoming) { continue; }
    $merged = tinymce_wbce_link_tokens((string) ($cfg['presets'][$id][$field] ?? ''));
    foreach ($incoming as $tok) {
        if (!in_array($tok, $merged, true)) { $merged[] = $tok; $changed = true; }
    }
    $cfg['presets'][$id][$field] = implode("\n", $merged);
}

if ($changed) {
    Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

echo json_encode(['ok' => true, 'changed' => $changed]);
