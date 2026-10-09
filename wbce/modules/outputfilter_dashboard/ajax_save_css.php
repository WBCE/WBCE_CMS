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
$csspath = $database->fetchValue("SELECT `csspath` FROM `{TP_OPFD}` WHERE `id`=?", [$filter_id]);
$plugin  = $database->fetchValue("SELECT `plugin`  FROM `{TP_OPFD}` WHERE `id`=?", [$filter_id]);

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
