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

// ── PHP syntax + security check — block save if broken or unsafe ──────────────
$label = (class_exists('Lang') && Lang::has('L', 'TXT_INVALIDCODE'))
    ? Lang::get('L', 'TXT_INVALIDCODE')
    : 'Invalid PHP code';

$syntaxError = CodeVet::checkSyntax($code, $syntaxLine);
if ($syntaxError !== null) {
    http_response_code(422);
    (new Alerts(false))->toast($label . ': ' . $syntaxError, 'error');
    exit(json_encode(['ok' => false, 'syntax_error' => $syntaxError, 'line' => $syntaxLine]));
}

$findings = CodeVet::scan($code, CodeVetProfile::Outputfilter);
if ($findings !== []) {
    CodeVet::logEvent('outputfilter_save_blocked', CodeVetProfile::Outputfilter, $findings, ['filter_id' => $filter_id]);
    http_response_code(422);
    (new Alerts(false))->toast($label . ': ' . $findings[0]->message, 'error');
    exit(json_encode(['ok' => false, 'syntax_error' => $findings[0]->message, 'line' => $findings[0]->line]));
}

// ── Save to database ──────────────────────────────────────────────────────────
// Store with a leading <?php tag: opf_apply_filters() runs inline funcs via
// eval('?>'.$func), which starts in HTML mode and needs the body to re-open a
// PHP context — otherwise the function source is echoed as plain text.
$stored = "<?php\n" . trim($code) . "\n";
$database->query(
    "UPDATE `{TP_OPFD}` SET `func` = ? WHERE `id` = ?",
    [$stored, $filter_id]
);

if ($database->hasError()) {
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false]));
}

(new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
exit(json_encode(['ok' => true]));
