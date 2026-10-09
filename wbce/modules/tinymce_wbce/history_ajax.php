<?php
/**
 * tinymce_wbce — history_ajax.php
 *
 * AJAX endpoint for the wbce_history plugin. Two actions:
 *   snapshot : POST { context, content }        → store a rotating version
 *   list     : POST { context }                 → return the versions timeline
 *
 * The instance key is derived server-side from `context` (sha1); the author is
 * taken from the server session, never from the client. Backend auth required.
 *
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) {
    $configPath = realpath(dirname(__FILE__) . '/../../config.php');
    if (!$configPath || !file_exists($configPath)) {
        http_response_code(403); exit('Access denied');
    }
    require_once $configPath;
}

require_once WB_PATH . '/framework/class.admin.php';
$admin = new Admin('Pages', 'pages_modify', false, false);
if (!$admin->is_authenticated()) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'auth']);
    exit;
}

require_once __DIR__ . '/history.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$action  = $_POST['action'] ?? ($_GET['action'] ?? '');
$context = (string)($_POST['context'] ?? '');
if ($context === '') {
    http_response_code(400);
    echo json_encode(['error' => 'context']);
    exit;
}
$key = tinymce_wbce_history_key($context);

// The editor sends which toolbar preset it uses; settings are resolved from it
// server-side (authoritative — the client value is only a lookup key).
$presetId    = ($_POST['preset'] ?? '') !== '' ? (string) $_POST['preset'] : null;
$settings    = tinymce_wbce_history_settings($presetId);
$maxVersions = $settings['max'];

if ($action === 'snapshot') {
    $content   = (string)($_POST['content'] ?? '');
    $userId    = (int)$admin->get_user_id();
    $userName  = $admin->get_display_name();
    $userLogin = $admin->get_username();
    $stored    = tinymce_wbce_history_snapshot($key, $context, $content, $userId, $userName, $userLogin, $presetId);
    echo json_encode(['ok' => true, 'stored' => $stored]);
    exit;
}

if ($action === 'delete') {
    $index   = isset($_POST['index']) ? (int) $_POST['index'] : -1;
    $ts      = (string) ($_POST['ts'] ?? '');
    $removed = tinymce_wbce_history_delete($key, $index, $ts);
    echo json_encode(['ok' => true, 'removed' => $removed]);
    exit;
}

if ($action === 'list') {
    tinymce_wbce_history_ensure_table();
    $versions = tinymce_wbce_history_load($key);
    // Trim to the max in case an older row held more.
    $versions = array_slice($versions, 0, $maxVersions);
    // Opportunistic age-based cleanup (the "sentinel"): opening the dialog is a
    // trigger too, so histories get pruned even for fields that stopped being
    // edited. Persist the cleaned, still-portable set before resolving media.
    $pruned = tinymce_wbce_history_prune($versions, $settings);
    if (count($pruned) !== count($versions)) {
        tinymce_wbce_history_store($key, $pruned);
    }
    $versions = $pruned;

    // Resolve the portable {SYSVAR:MEDIA_REL} placeholder to the real media URL
    // for editor display — the same substitution the modules do in modify.php
    // (used WBCE-wide: wysiwyg, blockrocker, docs_section, DynamicFields, …).
    // The stored version stays portable; only the served copy is resolved.
    $mediaUrl = WB_URL . MEDIA_DIRECTORY;
    foreach ($versions as &$v) {
        if (isset($v['content']) && is_string($v['content'])) {
            $v['content'] = str_replace('{SYSVAR:MEDIA_REL}', $mediaUrl, $v['content']);
        }
    }
    unset($v);

    echo json_encode(['ok' => true, 'versions' => $versions]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'action']);
