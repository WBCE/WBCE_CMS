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

// Force captcha_type to altcha — old image types are gone
Settings::Set('captcha_type', 'altcha');

// ALTCHA tuning — only set if not already configured
if (!Settings::Get('captcha_altcha_max')) {
    Settings::Set('captcha_altcha_max', '50000');
}
if (!Settings::Get('captcha_altcha_ttl')) {
    Settings::Set('captcha_altcha_ttl', '600');
}

// Generate HMAC key only if not already set
if (!Settings::Get('captcha_altcha_hmac_key')) {
    require_once WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';
    Settings::Set('captcha_altcha_hmac_key', AltchaLib::generateHmacKey());
}

// Remove obsolete settings from previous versions
foreach (['asp_session_min_age', 'asp_view_min_age', 'asp_input_min_age', 'ct_text'] as $key) {
    // Settings::Delete() if available, otherwise leave — harmless extra rows
    if (method_exists('Settings', 'Delete')) {
        Settings::Delete($key);
    }
}
