<?php
/**
 * captcha_control — upgrade.php
 *
 * Migrates existing settings when upgrading to 3.0.0 (ALTCHA).
 * Existing enabled_captcha / enabled_asp flags are preserved.
 * captcha_type is forced to 'altcha' (old types no longer exist).
 * HMAC key is generated only if absent.
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

// no direct file access
if (count(get_included_files()) == 1) {
    header('Location: ../index.php', true, 301);
    exit;
}

// ── 1. Ensure flat control settings exist ────────────────────────────────────
//
// These become PHP constants (ENABLED_CAPTCHA etc.) via Settings::setup(),
// so any module can read them. Force captcha_type to 'altcha' — old types gone.

Settings::set('captcha_type', 'altcha');
if (!Settings::exists('enabled_captcha')) Settings::set('enabled_captcha', 'true',  false);
if (!Settings::exists('enabled_asp'))     Settings::set('enabled_asp',     'true',  false);

// ── 2. Migrate ALTCHA provider settings → JSON (runs only once) ──────────────
//
// Collects the three ALTCHA-internal values into one 'captcha_altcha' JSON row.
// Preserves the existing HMAC key — must never be regenerated silently.
// Also handles installations that went through the short-lived 'captcha_cfg'
// all-in-one JSON phase.

if (!Settings::exists('captcha_altcha')) {
    require_once WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';

    // Try flat key first, then captcha_cfg JSON (previous migration attempt)
    $hmac = Settings::get('captcha_altcha_hmac_key', '');
    if (empty($hmac)) {
        $prev = json_decode(Settings::get('captcha_cfg', '{}'), true) ?? [];
        $hmac = $prev['altcha_hmac_key'] ?? '';
    }
    if (empty($hmac)) {
        $hmac = AltchaLib::generateHmacKey();
    }

    Settings::set('captcha_altcha', json_encode([
        'hmac_key' => $hmac,
        'max'      => (int)(Settings::get('captcha_altcha_max', 50000) ?: 50000),
        'ttl'      => (int)(Settings::get('captcha_altcha_ttl', 600)   ?: 600),
    ]));

    // Clean up superseded keys
    foreach (['captcha_altcha_hmac_key', 'captcha_altcha_max', 'captcha_altcha_ttl', 'captcha_cfg'] as $key) {
        if (Settings::exists($key)) Settings::delete($key);
    }
}

// ── 3. Remove legacy keys from pre-3.x versions ──────────────────────────────
foreach (['asp_session_min_age', 'asp_view_min_age', 'asp_input_min_age', 'ct_text'] as $key) {
    if (Settings::exists($key)) Settings::delete($key);
}
