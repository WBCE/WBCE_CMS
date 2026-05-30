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

// ── 5. Resolve HMAC key ───────────────────────────────────────────────────────
//
// WBCE defines settings-table rows as constants only for rows it explicitly
// loads (typically in config.php via a loop over wb_settings). If you added
// captcha_altcha_hmac_key to the settings table but WBCE hasn't picked it up
// as a constant yet, we fall back to a direct DB read here.
//
$hmacKey = '';

if (defined('CAPTCHA_ALTCHA_HMAC_KEY') && CAPTCHA_ALTCHA_HMAC_KEY !== '') {
    // Happy path: already defined as a constant by WBCE bootstrap.
    $hmacKey = CAPTCHA_ALTCHA_HMAC_KEY;

} elseif (isset($database)) {
    // Fallback: read directly from the settings table.
    // $database is the WBCE global DB object (available after config.php).
    $row = $database->get_one(
        "SELECT `value` FROM `" . TABLE_PREFIX . "settings` WHERE `name` = 'captcha_altcha_hmac_key' LIMIT 1"
    );
    if (!empty($row)) {
        $hmacKey = $row;
        // Define for the rest of this request so other code can use it.
        define('CAPTCHA_ALTCHA_HMAC_KEY', $hmacKey);
    }
}

if (empty($hmacKey)) {
    altcha_json_error(500,
        'ALTCHA HMAC key not found. ' .
        'Add a row (name=captcha_altcha_hmac_key, value=<64-char hex>) to ' .
        TABLE_PREFIX . 'settings, or define CAPTCHA_ALTCHA_HMAC_KEY in config.php.'
    );
}

// ── 6. Resolve optional tuning settings (same fallback pattern) ───────────────
$maxNumber = 50000;
$ttl       = 600;

if (defined('CAPTCHA_ALTCHA_MAX') && (int)CAPTCHA_ALTCHA_MAX > 0) {
    $maxNumber = (int)CAPTCHA_ALTCHA_MAX;
} elseif (isset($database)) {
    $row = $database->get_one("SELECT `value` FROM `" . TABLE_PREFIX . "settings` WHERE `name` = 'captcha_altcha_max' LIMIT 1");
    if (!empty($row)) $maxNumber = (int)$row;
}

if (defined('CAPTCHA_ALTCHA_TTL') && (int)CAPTCHA_ALTCHA_TTL > 0) {
    $ttl = (int)CAPTCHA_ALTCHA_TTL;
} elseif (isset($database)) {
    $row = $database->get_one("SELECT `value` FROM `" . TABLE_PREFIX . "settings` WHERE `name` = 'captcha_altcha_ttl' LIMIT 1");
    if (!empty($row)) $ttl = (int)$row;
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
