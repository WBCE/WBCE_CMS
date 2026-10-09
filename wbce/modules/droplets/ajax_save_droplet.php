<?php
/**
 * Droplets — AJAX save endpoint
 *
 * Saves the whole modify-droplet form (title, active, admin_view, admin_edit,
 * description, code, comments) exactly like the regular full-page submit
 * (save_droplet.php) does — not just the editor's code. Checks PHP syntax
 * BEFORE writing to DB; if syntax is broken the save is blocked and an error
 * toast is returned.
 *
 * POST fields:
 *   code_area_text  — raw editor content (sent by CodeEditorToolbar)
 *   idKey           — IDKEY created with $admin->getIDKEY($droplet_id) in tool.php
 *   title, description, comments, active, admin_edit, admin_view, show_wysiwyg
 *                   — same form fields save_droplet.php reads, now sent
 *                     alongside code_area_text by CodeEditorToolbar's
 *                     doAjaxSave() (which serializes the whole form)
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
// (chosen over FTAN specifically for that reason: FTAN is single-use, which
// would break every AjaxSave after the first one in a page load without a
// reload; IDKEY isn't, and still pins this request to one specific droplet.)
$droplet_id = $admin->checkIDKEY('idKey', false, 'POST', true);
if ($droplet_id === false) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}
$droplet_id = (int) $droplet_id;

// The posted "droplet_id" must agree with the id IDKEY was issued for --
// otherwise a tampered id could point the save at a different, unauthorized
// droplet, since everything below trusts $_POST directly.
if ((int) ($_POST['droplet_id'] ?? -1) !== $droplet_id) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}

$sName = $admin->get_post('title');
if ($sName === '') {
    http_response_code(422);
    (new Alerts(false))->toast($MESSAGE['GENERIC_FILL_IN_ALL'] ?? 'Please fill in all fields', 'error');
    exit(json_encode(['ok' => false]));
}

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

// ── Save the whole form ────────────────────────────────────────────────────────
// Same field set + shape as save_droplet.php's $aUpdate -- this used to only
// write `code`/`modified_when`/`modified_by`, so AjaxSave silently discarded
// any other field the admin had changed on the same page load (title,
// description, comments, active, admin_edit/admin_view).
$aUpdate = [
    'id'            => $droplet_id,
    'name'          => $sName,
    'active'        => (int) $admin->get_post('active'),
    'admin_view'    => (int) $admin->get_post('admin_view'),
    'admin_edit'    => (int) $admin->get_post('admin_edit'),
    'show_wysiwyg'  => (int) $admin->get_post('show_wysiwyg'),
    'description'   => $admin->get_post('description'),
    'code'          => $code,
    'comments'      => $admin->get_post('comments'),
    'modified_when' => time(),
    'modified_by'   => (int) $admin->get_user_id(),
];

$database->upsertRow('{TP}mod_droplets', 'id', $aUpdate);

if ($database->hasError()) {
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false]));
}

(new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
exit(json_encode(['ok' => true]));
