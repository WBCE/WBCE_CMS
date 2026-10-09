<?php
/**
 * ajax_save_doc.php
 *
 * Overwrites the raw content of an existing .md file that reader.php's edit
 * mode is currently showing. Never creates new files, never touches anything
 * outside WB_PATH, never touches anything that isn't a .md file — all three
 * enforced by MdReaderHelper::safePath() (extension whitelist + WB_PATH
 * containment + is_file() already-exists check).
 *
 * POST fields (sent by layout/reader.htt's edit form via fetch/FormData):
 *   <FTAN field>  — CSRF token, name is whatever SecureForm::getFTAN() used
 *   rel_path      — path relative to WB_PATH, exactly what MdReaderLink built
 *   content       — full replacement file content
 *
 * @package  MarkdownWbce 
 * @author   Christian M. Stefan (https://www.wbEasy.de/)
 */
require_once '../../config.php';
require_once __DIR__ . '/MdReaderHelper.php';

header('Content-Type: application/json; charset=utf-8');

function _mdr_json(int $httpStatus, bool $success, string $message): never
{
    http_response_code($httpStatus);
    echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

// Admin check — same as reader.php: backend session required just to reach
// this point (authenticate() redirects/aborts otherwise).
$admin = new Admin('Start', 'start', false, true);

// Write permission mirrors admin/admintools/tool.php:81-86's own per-tool
// check — group 1 always may, everyone else needs the MarkdownWbce AdminTool
// permission specifically.
$canWrite = $admin->isAdmin()
    || in_array('MarkdownWbce_tool', (array) $admin->get_session('MODULE_PERMISSIONS'), true);

if (!$canWrite) {
    _mdr_json(403, false, 'You do not have permission to edit MarkdownWbce documents.');
}

if (!$admin->checkFTAN('POST')) {
    _mdr_json(403, false, 'Invalid or expired security token — please reopen the document and try again.');
}

$relPath = (string) ($_POST['rel_path'] ?? '');
$content = (string) ($_POST['content']  ?? '');

if ($relPath === '') {
    _mdr_json(400, false, 'No file specified.');
}

// safePath() requires: .md extension, inside WB_PATH, and the file must
// already exist (is_file()) — overwrite-only, never creates a new file.
$target = MdReaderHelper::safePath($relPath);
if ($target === null) {
    _mdr_json(400, false, 'Invalid, inaccessible, or non-existent .md file.');
}

if (!is_writable($target)) {
    _mdr_json(403, false, 'File is not writable on disk.');
}

$bytes = @file_put_contents($target, $content);
if ($bytes === false) {
    _mdr_json(500, false, 'Failed to write file.');
}

_mdr_audit_log($admin, $target);

_mdr_json(200, true, 'Saved.');

// ── Audit log ────────────────────────────────────────────────────────────────

/**
 * JSON-lines audit log for every overwrite — same rationale as
 * CodeVet::logEvent() (framework/CodeVet.php) for Droplets/Outputfilter/Code2
 * PHP-code saves: writes reachable from a frontend-adjacent trigger deserve
 * a who/when/what trail, even though Markdown isn't executable like those.
 */
function _mdr_audit_log(Admin $admin, string $absPath): void
{
    $logDir = rtrim(WB_PATH, '/\\') . '/var/markdown_wbce';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
        @file_put_contents($logDir . '/index.php', '<?php header("Location: ../../index.php", true, 301);' . PHP_EOL);
    }
    if (!is_dir($logDir) || !is_writable($logDir)) {
        return;
    }

    $logFile = $logDir . '/save.log';
    if (is_file($logFile) && filesize($logFile) > 5242880) {
        @rename($logFile, $logDir . '/save-' . date('Ymd-His') . '.log');
    }

    $relForLog = '/' . ltrim(str_replace(realpath(WB_PATH), '', $absPath), '/\\');

    $entry = [
        'time'  => date('c'),
        'user'  => (string) ($admin->get_username() ?? ''),
        'path'  => str_replace('\\', '/', $relForLog),
    ];

    @file_put_contents($logFile, json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
}
