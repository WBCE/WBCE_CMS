<?php
/**
 * Droplets — AJAX save endpoint
 *
 * Receives the editor content, checks PHP syntax BEFORE writing to DB.
 * If syntax is broken the save is blocked and an error toast is returned.
 *
 * POST fields:
 *   code_area_text  — raw editor content (sent by CodeEditorToolbar)
 *   idKey           — IDKEY created with $admin->getIDKEY($droplet_id) in tool.php
 *
 * @package  droplets
 */

require_once '../../config.php';

$admin = new Admin('admintools', 'admintools', false);
header('Content-Type: application/json; charset=utf-8');

// ── Permission check ──────────────────────────────────────────────────────────
if (!$admin->get_permission('admintools')) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}

// ── IDKEY — non-consuming, survives repeated saves in the same session ────────
$droplet_id = $admin->checkIDKEY('idKey', false, 'POST', true);
if ($droplet_id === false) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}
$droplet_id = (int) $droplet_id;

// ── Raw code from editor ──────────────────────────────────────────────────────
$rawCode = $_POST['code_area_text'] ?? '';

// Strip PHP open/close tags — droplets store pure PHP body, no wrapper tags.
$tags = ['<?php', '?>', '<?'];
$code = str_replace($tags, '', $rawCode);

// ── PHP syntax + security check — block save if broken or unsafe ──────────────
//
// We check BEFORE writing so broken/blocked code is never persisted.
$syntaxError = CodeVet::checkSyntax($code, $syntaxLine);
if ($syntaxError !== null) {
    http_response_code(422);
    // Prepend the translated "invalid code" label to the PHP error message.
    $msg = (function_exists('L_') ? L_('DR_TEXT:INVALIDCODE') : 'Invalid PHP code') . ': ' . $syntaxError;
    (new Alerts(false))->toast($msg, 'error');
    exit(json_encode(['ok' => false, 'syntax_error' => $syntaxError, 'line' => $syntaxLine]));
}

$findings = CodeVet::scan($code, CodeVetProfile::Droplet);
if ($findings !== []) {
    CodeVet::logEvent('droplet_ajax_save_blocked', CodeVetProfile::Droplet, $findings, ['droplet_id' => $droplet_id]);
    http_response_code(422);
    $msg = (function_exists('L_') ? L_('DR_TEXT:INVALIDCODE') : 'Invalid PHP code') . ': ' . $findings[0]->message;
    (new Alerts(false))->toast($msg, 'error');
    exit(json_encode(['ok' => false, 'syntax_error' => $findings[0]->message, 'line' => $findings[0]->line]));
}

// ── Save to database ──────────────────────────────────────────────────────────
$database->query(
    "UPDATE `{TP}mod_droplets` SET `code` = ?, `modified_when` = ?, `modified_by` = ? WHERE `id` = ?",
    [$code, time(), (int) $admin->get_user_id(), $droplet_id]
);

if ($database->hasError()) {
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false]));
}

(new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
exit(json_encode(['ok' => true]));
