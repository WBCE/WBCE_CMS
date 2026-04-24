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

function json_out(bool $ok, string $msg): void
{
    echo json_encode(['ok' => $ok, 'message' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── Input validation ─────────────────────────────────────────────────────────
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
    json_out(true, sprintf($MSG['db_success'], htmlspecialchars($version)));

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