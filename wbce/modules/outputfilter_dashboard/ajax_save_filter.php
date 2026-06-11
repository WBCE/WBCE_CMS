<?php
/**
 * OutputFilter Dashboard — AJAX save endpoint for inline filter functions
 *
 * Receives the editor content, checks PHP syntax BEFORE writing to DB.
 * If syntax is broken the save is blocked and an error toast is returned.
 *
 * POST fields:
 *   code_area_text  — raw editor content (sent by CodeEditorToolbar)
 *   idKey           — IDKEY created with $admin->getIDKEY($filter_id) in tool_edit_filter.php
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

// ── Raw code from editor ──────────────────────────────────────────────────────
$rawCode = $_POST['code_area_text'] ?? '';

// Strip PHP open/close tags — filter functions are stored as pure PHP body.
$code = str_replace(['<?php', '?>', '<?'], '', $rawCode);

// ── PHP syntax check — block save if broken ───────────────────────────────────
$syntaxError = null;
$wrapped = "if(0){{$code}\n}";
try {
    @eval($wrapped);
} catch (\ParseError $e) {
    $syntaxError = $e->getMessage();
}

if ($syntaxError !== null) {
    http_response_code(422);
    $label = (class_exists('Lang') && Lang::has('L', 'TXT_INVALIDCODE'))
        ? Lang::get('L', 'TXT_INVALIDCODE')
        : 'Invalid PHP code';
    (new Alerts(false))->toast($label . ': ' . $syntaxError, 'error');
    exit(json_encode(['ok' => false, 'syntax_error' => $syntaxError]));
}

// ── Save to database ──────────────────────────────────────────────────────────
$database->query(
    "UPDATE `{TP_OPFD}` SET `func` = ? WHERE `id` = ?",
    [$code, $filter_id]
);

if ($database->hasError()) {
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false]));
}

(new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
exit(json_encode(['ok' => true]));
