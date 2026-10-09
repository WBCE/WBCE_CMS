<?php
/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.7.0
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, 
 *                   Christian M. Stefan  (https://www.wbEasy.de), 
 *                   Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010-2023 Christian M. Stefan, 2016-2023 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            https://addons.wbce.org/pages/addons.php?do=item&item=53
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU General Public License, Version 3
 * @platform        WBCE 1.7.x
 * @requirements    PHP 8.1
 *
 **/

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
// (chosen over FTAN specifically for that reason: FTAN is single-use, which
// would break every AjaxSave after the first one in a page load without a
// reload; IDKEY isn't, and still pins this request to one specific filter id.)
$filter_id = $admin->checkIDKEY('idKey', false, 'POST', true);
if ($filter_id === false) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}
$filter_id = (int) $filter_id;

// The posted "id" field must agree with the id IDKEY was issued for -- opf_save()
// below trusts $_POST['id'] directly, so this is what stops a tampered id from
// pointing the save at a different, unauthorized filter.
if ((int) ($_POST['id'] ?? -1) !== $filter_id) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}

// ── Save the whole form ────────────────────────────────────────────────────────
// opf_save() reads every relevant field straight from $_POST (name, desc,
// type, modules[], pages_parent[], active, func, ...) and persists all of
// them -- not just the editor's code. It runs the same CodeVet syntax/security
// check internally (via opf_register_filter(), see functions_outputfilter.php)
// and, on rejection, sets $GLOBALS['opf_codevet_error'] with the message/line,
// same as tool.php's own (non-AJAX) save path already relies on.
//
// This used to update only the `func` column directly, so AjaxSave silently
// discarded any other field the admin had changed on the same page load
// (name, description, target pages/modules, active, ...) -- fixed by routing
// through the same save function the regular full-page form submit uses.
$result = opf_save();

if (!is_numeric($result)) {
    $codevetError = $GLOBALS['opf_codevet_error'] ?? null;
    $label = (class_exists('Lang') && Lang::has('L', 'TXT_INVALIDCODE'))
        ? Lang::get('L', 'TXT_INVALIDCODE')
        : 'Invalid PHP code';
    if ($codevetError) {
        http_response_code(422);
        (new Alerts(false))->toast($label . ': ' . $codevetError['message'], 'error');
        exit(json_encode(['ok' => false, 'syntax_error' => $codevetError['message'], 'line' => $codevetError['line']]));
    }
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false]));
}

(new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
exit(json_encode(['ok' => true]));
