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

// Flat settings — each becomes a PHP constant via Settings::setup(),
// making them readable by all modules without any code changes.
Settings::set('enabled_captcha', 'true',   false);
Settings::set('enabled_asp',     'true',   false);
Settings::set('captcha_type',    'altcha', false);

// ALTCHA provider internals — stored as JSON, not needed as constants
require_once WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';
Settings::set('captcha_altcha', json_encode([
    'hmac_key'      => AltchaLib::generateHmacKey(), // generated once, never regenerated
    'max'           => 50000,
    'ttl'           => 600,
    // Widget customization defaults (empty = ALTCHA built-in defaults)
    'auto'          => 'off',
    'delay'         => 0,
    'hidefooter'    => false,
    'hidelogo'      => false,
    'color_brand'   => '',
    'color_base'    => '',
    'color_text'    => '',
    'border_radius' => '',
]), false);
