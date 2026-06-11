<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require '../../config.php';

// Setup admin object — skip header so FTAN can be checked first
$admin = new admin('Addons', 'templates_install', false, true);
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
$admin->print_header();

if (empty($_FILES['userfile'])) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// ── Stage the uploaded ZIP, then install ─────────────────────────────────────

$service      = new AddonService();
$stageSignals = $service->stageFromUpload($_FILES['userfile']);
$stageOk      = !$service->hasError();

$installSignals = [];
if ($stageOk) {
    $stagedDir = null;
    foreach ($stageSignals as $r) {
        if ($r['signal'] === 'ADDON_STAGED') { $stagedDir = $r['label']; break; }
    }
    if ($stagedDir !== null) {
        $installSignals = $service->installFromStaged($stagedDir, 'template');
    }
}

// ── Collect feedback ─────────────────────────────────────────────────────────

$keySignals = ['ADDON_PRECHECK_OK',
               'ADDON_INSERTED_OK', 'ADDON_UPDATED_OK', 'ADDON_ALREADY_CURRENT',
               'ADDON_PRECHECK_FAILED', 'ADDON_SCRIPT_ERROR', 'ADDON_DB_ERROR',
               'ADDON_NOT_WRITABLE', 'ADDON_PATH_NOT_FOUND', 'ADDON_INFO_INVALID'];

$msgs           = [];
$hasErr         = false;
$alreadyCurrent = false;
$rawOutput      = '';

// Stage errors only — staging-OK signals are internal
foreach ($stageSignals as $r) {
    if ($service->isOkSignal($r['signal'])) continue;
    $msgs[] = isset($SIGNAL[$r['signal']])
        ? sprintf($SIGNAL[$r['signal']], 'template', $r['label'])
        : $r['label'];
    $hasErr = true;
}

foreach ($installSignals as $r) {
    if (!empty($r['raw'])) {
        $rawOutput .= $r['raw'];
    }
    if (!$service->isOkSignal($r['signal'])) {
        $msgs[]  = isset($SIGNAL[$r['signal']])
            ? sprintf($SIGNAL[$r['signal']], 'template', $r['label'])
            : $r['label'];
        $hasErr = true;
    } elseif (in_array($r['signal'], $keySignals, true)) {
        $msgs[] = isset($SIGNAL[$r['signal']])
            ? sprintf($SIGNAL[$r['signal']], 'template', $r['label'])
            : $r['label'];
        if ($r['signal'] === 'ADDON_ALREADY_CURRENT') {
            $alreadyCurrent = true;
        }
    }
}

// ── Output ───────────────────────────────────────────────────────────────────

// Echo output from install/upgrade.php (e.g. progress messages, error notices)
if ($rawOutput !== '') {
    echo $rawOutput;
}

if ($hasErr) {
    $admin->print_error($msgs ?: [$MESSAGE['GENERIC_ERROR'] ?? 'Error'], 'index.php');
} elseif ($alreadyCurrent) {
    $admin->messageBox($msgs, 'info', 'index.php', false, false);
} elseif ($rawOutput !== '') {
    // Script produced output — stay on page so the user can read it
    $admin->messageBox($msgs, 'success', 'index.php', false, false);
} else {
    $admin->messageBox($msgs, 'success', 'index.php', false, true);
}

$admin->print_footer();
