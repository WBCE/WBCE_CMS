<?php
/**
 * OutputFilter Dashboard — AJAX save endpoint for CSS files
 *
 * Receives CSS content from the editor and writes it directly to the
 * filter's CSS file on disk (resolving SYSVAR placeholders from DB).
 *
 * POST fields:
 *   code_area_text  — raw editor content (sent by CodeEditorToolbar)
 *   idKey           — IDKEY created with $admin->getIDKEY($filter_id) in tool_edit_css.php
 *
 * @package  outputfilter_dashboard
 */

require_once '../../config.php';
require_once __DIR__ . '/functions.php';

$admin = new Admin('admintools', 'admintools', false);
header('Content-Type: application/json; charset=utf-8');

// ── Permission check ──────────────────────────────────────────────────────────
if (!$admin->get_permission('admintools')) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}

// ── IDKEY — non-consuming, survives repeated saves in the same session ────────
$filter_id = $admin->checkIDKEY('idKey', false, 'POST', true);
if ($filter_id === false) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}
$filter_id = (int) $filter_id;

// ── CSS content from editor ───────────────────────────────────────────────────
$css = $_POST['code_area_text'] ?? '';

// ── Resolve file path from DB ─────────────────────────────────────────────────
$csspath = opf_db_query_vars("SELECT `csspath` FROM `{TP_OPFD}` WHERE `id`=%d", $filter_id);
$plugin  = opf_db_query_vars("SELECT `plugin`  FROM `{TP_OPFD}` WHERE `id`=%d", $filter_id);

if (!$csspath) {
    http_response_code(404);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false, 'reason' => 'no_csspath']));
}

// Resolve {SYSVAR:WB_PATH}, {OPF:PLUGIN_PATH}, etc.
$csspath = opf_replace_sysvar($csspath, (string)$plugin);

if (!$csspath || !file_exists($csspath) || !is_writable($csspath)) {
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false, 'reason' => 'not_writable']));
}

// ── Write CSS to disk ─────────────────────────────────────────────────────────
$fh    = fopen($csspath, 'wb');
$bytes = fwrite($fh, $css);
fclose($fh);

if ($bytes === false) {
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false, 'reason' => 'write_failed']));
}

(new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
exit(json_encode(['ok' => true]));
