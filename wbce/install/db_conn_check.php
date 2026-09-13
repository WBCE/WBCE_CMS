<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/db_conn_check.php
 * @brief      AJAX endpoint — tests a PDO MySQL/MariaDB connection during installation.
 *             POST fields: db_host, db_name, db_user, db_pass
 *             Returns JSON: { "ok": true|false, "message": "..." }
 * @author     Christian M. Stefan
 * @copyright  2025-2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */

// Buffer all output so PHP notices/warnings (e.g. from display_errors=On in dev
// environments) cannot corrupt the JSON response body. json_out() discards the
// buffer before echoing, guaranteeing a clean JSON-only response.
ob_start();
ini_set('display_errors', '0');

if (!defined('WBCE_INSTALLER')) {
    // Allow direct AJAX calls only (no browser navigation)
    $acceptsJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
    $isXhr       = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if (!$acceptsJson && !$isXhr) {
        http_response_code(403);
        exit('Forbidden');
    }
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');


// Require helper functions
require_once("helper_functions.php");

// ── INCLUDE LANGUAGE FILE(S) ────────────────────────────────────────────────
// We always include English first as base/fallback
$langDir = __DIR__ . '/languages/';
$enFile = $langDir . 'EN.php';
if (!file_exists($enFile) || !is_file($enFile) || !is_readable($enFile)) {
    die('Critical Error: Base language file (EN) not found!');
}
include $enFile;
// If another language is selected, we include it second.
$langCode = strtoupper(trim($_POST['lang'] ?? $_GET['lang'] ?? 'EN'));
if ($langCode !== 'EN' && preg_match('/^[A-Z]{1,5}$/', $langCode)) {
    $filePath = $langDir . $langCode . '.php';
    if (file_exists($filePath) && is_file($filePath) && is_readable($filePath)) {
        include $filePath;
    }
}

function json_out(bool $ok, string $msg, array $extra = []): void
{
    ob_end_clean(); // discard any PHP notices/warnings captured in the buffer
    echo json_encode(['ok' => $ok, 'message' => $msg] + $extra, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Builds the `prefix_*` part of the JSON response for a live connection.
 *
 * Reported as a warning, not an error: reinstalling over an existing prefix is
 * a legitimate (if destructive) thing to want. The installer only insists that
 * the user confirms it knowingly.
 */
function prefix_payload(PDO $pdo, string $dbType, string $prefix, array $MSG): array
{
    $tables = find_prefixed_tables($pdo, $dbType, $prefix);
    if (!$tables) {
        return ['prefix_in_use' => false];
    }

    $shown = array_slice($tables, 0, 8);
    $list  = implode(', ', array_map('htmlspecialchars', $shown));
    if (count($tables) > count($shown)) {
        $list .= sprintf($MSG['db_prefix_more'], count($tables) - count($shown));
    }

    return [
        'prefix_in_use' => true,
        'prefix_count'  => count($tables),
        'prefix_msg'    => sprintf($MSG['db_prefix_in_use'], htmlspecialchars($prefix), count($tables))
                         . ':<br><code>' . $list . '</code>',
    ];
}

// Table prefix the user typed — validated the same way install_save.php does.
$prefix = trim($_POST['db_prefix'] ?? '');
if (preg_match('/[^a-z0-9_]/', $prefix)) {
    $prefix = '';
}

// ── Input validation ─────────────────────────────────────────────────────────
$dbType = trim($_POST['db_type'] ?? 'mysql');

// SQLite is a hidden, staged feature — never trust the client. Even if the
// browser posts db_type=sqlite (tampered form or stale page), fall back to
// mysql unless the server-side flag file says otherwise.
if ($dbType === 'sqlite' && !allow_sqlite()) {
    $dbType = 'mysql';
}

if ($dbType === 'sqlite') {
    // ── SQLite check: no server/credentials — just verify the driver and
    //    that the target directory exists (or can be created) and is writable.
    if (!class_exists('PDO') || !in_array('sqlite', PDO::getAvailableDrivers(), true)) {
        json_out(false, $MSG['db_sqlite_pdo_missing']);
    }

    $relPath = trim($_POST['db_path'] ?? '');
    if ($relPath === '') {
        $relPath = 'var/database/wbce.sqlite';
    }

    // wbceSafePath() (framework/functions.php) requires the target to already
    // exist and isn't loaded this early in the install flow anyway — the
    // sqlite file doesn't exist yet at this point, so we validate the
    // *directory* ourselves instead (same "must resolve inside WB_PATH" rule).
    $wbPath   = dirname(__DIR__);
    $fullPath = $wbPath . '/' . ltrim(str_replace('\\', '/', $relPath), '/');

    // Reject path traversal outside the WBCE root
    $realWbPath = realpath($wbPath);
    $dir        = dirname($fullPath);
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        json_out(false, sprintf($MSG['db_sqlite_dir_missing'], $dir));
    }
    $realDir = realpath($dir);
    if ($realWbPath === false || $realDir === false || !str_starts_with($realDir, $realWbPath)) {
        json_out(false, $MSG['db_sqlite_path_unsafe']);
    }
    if (!is_writable($realDir)) {
        json_out(false, sprintf($MSG['db_sqlite_not_writable'], $realDir));
    }

    try {
        $pdo = new PDO('sqlite:' . $fullPath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $pdo->query('SELECT 1');
        json_out(true, sprintf($MSG['db_sqlite_success'], $fullPath),
                 prefix_payload($pdo, 'sqlite', $prefix, $MSG));
    } catch (PDOException $e) {
        json_out(false, sprintf($MSG['db_sqlite_failed'], $e->getMessage()));
    }
}

$host = trim($_POST['db_host'] ?? '');
$name = trim($_POST['db_name'] ?? '');
$user = trim($_POST['db_user'] ?? '');
$pass = $_POST['db_pass'] ?? '';        // do NOT strip tags or trim password

if ($host === '' || $name === '' || $user === '') {
    json_out(false, $MSG['db_fill_required']);
}

// ── Extract optional port (support host:port syntax) ─────────────────────────
$port = null;
if (strpos($host, ':') !== false) {
    [$host, $portStr] = explode(':', $host, 2);
    $port = is_numeric($portStr) ? (int)$portStr : null;
}

// ── PDO Connection Test ──────────────────────────────────────────────────────
if (!class_exists('PDO')) {
    json_out(false, $MSG['db_pdo_missing']);
}

try {
    // Build DSN
    $dsn = 'mysql:host=' . $host . ';dbname=' . $name;
    if ($port !== null) {
        $dsn .= ';port=' . $port;
    }

    // Additional options for better security and compatibility
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    // Get server version
    $version = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);

    // Optional: Quick test query to ensure we can actually use the database
    $pdo->query('SELECT 1');
    
    // Success
    json_out(true, sprintf($MSG['db_success'], htmlspecialchars($version)),
             prefix_payload($pdo, 'mysql', $prefix, $MSG));

} catch (PDOException $e) {
    $errorMsg = $e->getMessage();

    // Clean up common error messages for better UX

    if (strpos($errorMsg, 'Access denied') !== false) {
        $msg = $MSG['db_access_denied'];
    } elseif (strpos($errorMsg, 'Unknown database') !== false) {
        $msg = $MSG['db_unknown_db'];
    } elseif (strpos($errorMsg, 'Connection refused') !== false || strpos($errorMsg, 'No such host') !== false) {
        $msg = $MSG['db_connection_refused'];
    } else {
        $msg = sprintf($MSG['db_connection_failed'], htmlspecialchars($errorMsg));
    }

    json_out(false, $msg);
}