<?php
/**
 * ALTCHA challenge endpoint — hardened for WBCE.
 *
 * MUST return clean JSON and nothing else. Uses output buffering to catch
 * any stray output (notices, warnings, BOM) that PHP or included files emit
 * before we're ready to output JSON.
 */

// ── 1. Start output buffer immediately — catches any stray output ─────────────
ob_start();

// ── 2. Bootstrap WBCE ─────────────────────────────────────────────────────────
$_config_found = false;
$_dir = __DIR__;
for ($i = 0; $i < 6; $i++) {
    $_dir = dirname($_dir);
    if (file_exists($_dir . '/config.php')) {
        require_once $_dir . '/config.php';
        $_config_found = true;
        break;
    }
}

// ── 3. Helper: bail with a JSON error (discards any buffered stray output) ────
function altcha_json_error(int $httpCode, string $message): never
{
    ob_end_clean(); // discard any stray output before us
    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode(['error' => $message]);
    exit;
}

if (!$_config_found || !defined('WB_PATH')) {
    altcha_json_error(500, 'WBCE config.php not found. Check the directory depth in altcha_challenge.php.');
}

// ── 4. Load AltchaLib ─────────────────────────────────────────────────────────
$_lib = WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';
if (!file_exists($_lib)) {
    altcha_json_error(500, 'AltchaLib.php not found at: ' . $_lib);
}
require_once $_lib;

// ── 5. Load ALTCHA provider settings ─────────────────────────────────────────
//
// Settings::setup() defines CAPTCHA_ALTCHA as a JSON string constant.
// Fall back to a direct DB read if the constant is not yet available.
//
$altchaCfg = [];

if (defined('CAPTCHA_ALTCHA') && CAPTCHA_ALTCHA !== '') {
    // Happy path: constant defined by Settings::setup() during WBCE bootstrap.
    $altchaCfg = json_decode(CAPTCHA_ALTCHA, true) ?? [];

} elseif (isset($database)) {
    // Fallback: read directly from the settings table.
    $raw = $database->fetchValue(
        "SELECT `value` FROM `{TP}settings` WHERE `name` = 'captcha_altcha'"
    );
    if (!empty($raw)) {
        $altchaCfg = json_decode($raw, true) ?? [];
    }
}

$hmacKey   = $altchaCfg['hmac_key'] ?? '';
$maxNumber = (int)($altchaCfg['max'] ?? 50000) ?: 50000;
$ttl       = (int)($altchaCfg['ttl'] ?? 600)   ?: 600;

// Backward-compat: installations mid-upgrade may still have the old flat key
if (empty($hmacKey) && isset($database)) {
    $hmacKey = (string)($database->fetchValue(
        "SELECT `value` FROM `{TP}settings` WHERE `name` = 'captcha_altcha_hmac_key'"
    ) ?? '');
}

if (empty($hmacKey)) {
    altcha_json_error(500,
        'ALTCHA HMAC key not configured. ' .
        'Open Admin Tools → captcha_control and save the settings once to generate a key.'
    );
}

// ── 7. Light rate limiting (session-based, per minute) ────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
$_bucket = 'altcha_req_' . date('YmdHi'); // new bucket every minute
$_count  = (int)($_SESSION[$_bucket] ?? 0);
if ($_count > 60) {
    altcha_json_error(429, 'Too many requests. Please wait a moment.');
}
$_SESSION[$_bucket] = $_count + 1;

// ── 8. Generate challenge and respond ─────────────────────────────────────────
$strayOutput = ob_get_clean(); // discard anything emitted before this point

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
// CORS: allow the widget (same origin usually, but explicit is safer)
header('Access-Control-Allow-Origin: ' . WB_URL);

$lib = new AltchaLib($hmacKey, $maxNumber, $ttl);
echo json_encode($lib->createChallenge());
exit;
