<?php
/**
 * captcha_control — install.php
 *
 * Sets default settings for a fresh installation.
 * Generates a one-time ALTCHA HMAC key stored in the settings table.
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

// no direct file access
if (count(get_included_files()) == 1) {
    header('Location: ../index.php', true, 301);
    exit;
}

// Core settings
Settings::Set('enabled_captcha', 'true');
Settings::Set('enabled_asp',     'true');
Settings::Set('captcha_type',    'altcha');

// ALTCHA tuning
Settings::Set('captcha_altcha_max', '50000');
Settings::Set('captcha_altcha_ttl', '600');

// Generate a random HMAC key (64-char hex) — stored once, never regenerated
require_once WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';
Settings::Set('captcha_altcha_hmac_key', AltchaLib::generateHmacKey());
