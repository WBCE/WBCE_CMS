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

// ── PHP syntax check — block save if broken ───────────────────────────────────
//
// Uses the same eval() + ParseError technique as check_droplet_syntax().
// We check BEFORE writing so broken code is never persisted.
$syntaxError = null;
$wrapped = "if(0){{$code}\n}";
try {
    @eval($wrapped);
} catch (\ParseError $e) {
    $syntaxError = $e->getMessage();
}

if ($syntaxError !== null) {
    http_response_code(422);
    // Prepend the translated "invalid code" label to the PHP error message.
    $msg = (function_exists('L_') ? L_('DR_TEXT:INVALIDCODE') : 'Invalid PHP code') . ': ' . $syntaxError;
    (new Alerts(false))->toast($msg, 'error');
    exit(json_encode(['ok' => false, 'syntax_error' => $syntaxError]));
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
