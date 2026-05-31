<?php
/**
 * wysiwyg — AJAX save endpoint
 *
 * Receives the section content from the AjaxSave form submission in modify.php
 * and writes it to {TP}mod_wysiwyg without a page reload.
 *
 * Security: IDKEY (session-backed, non-consuming so repeated saves work).
 */

require '../../config.php';

$admin = new Admin('pages', 'pages_modify', false);

header('Content-Type: application/json; charset=utf-8');

// ── IDKEY validation ──────────────────────────────────────────────────────────
$key = $admin->checkIDKEY('idKey', false, 'POST', true);
if ($key === false) {
    http_response_code(403);
    exit(json_encode(['ok' => false, 'error' => 'Invalid or expired key']));
}

// ── Input validation ──────────────────────────────────────────────────────────
$section_id = (int) ($_POST['section_id'] ?? 0);
$page_id    = (int) ($_POST['page_id']    ?? 0);

if (!$section_id || !$page_id) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'Missing section_id or page_id']));
}

// ── Content processing (identical to save.php) ────────────────────────────────
$sMediaUrl = WB_URL . MEDIA_DIRECTORY;
$content   = $_POST['content' . $section_id] ?? '';

// Replace absolute media URLs with portable placeholder
$content = preg_replace(
    '@(<[^>]*=\s*")(' . preg_quote($sMediaUrl, '@') . ')([^">]*".*>)@siU',
    '$1{SYSVAR:MEDIA_REL}$3',
    $content
);

$database->upsertRow('{TP}mod_wysiwyg', 'section_id', [
    'section_id' => $section_id,
    'page_id'    => $page_id,
    'content'    => $content,
    'text'       => strip_tags($content),
]);

if ($database->hasError()) {
    http_response_code(500);
    (new Alerts(false))->toast($database->getError(), 'error');
    exit(json_encode(['ok' => false, 'error' => $database->getError()]));
}

// ── Touch timestamps ──────────────────────────────────────────────────────────
$admin->touchSection($section_id);
// ── Toast via HX-Trigger header (read by ajax_save.js, works without HTMX) ───
(new Alerts(false))->toast('MESSAGE:PAGES_SAVED', 'success');

exit(json_encode(['ok' => true]));
