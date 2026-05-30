<?php
/**
 * captcha_control — EN.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

// ── MOD_CAPTCHA_CONTROL namespace (used via L_() and legacy $MOD_CAPTCHA_CONTROL) ───
$MOD_CAPTCHA_CONTROL['HEADING']            = 'Captcha &amp; Spam Protection';
$MOD_CAPTCHA_CONTROL['HOWTO']              = 'Configure the ALTCHA proof-of-work captcha and the Honeypot spam filter. Both protect forms across the site without any user friction.';
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF']       = 'Captcha Settings';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE']       = 'Captcha type';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP']        = 'ALTCHA is a self-hosted, privacy-friendly proof-of-work captcha. No third-party service required.';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA'] = 'Enable captcha for registrations &amp; forms';
$MOD_CAPTCHA_CONTROL['ENABLED']            = 'Enabled';
$MOD_CAPTCHA_CONTROL['DISABLED']           = 'Disabled';

// ── CAPTCHA namespace — used via L_('CAPTCHA:ASP_LABEL') in Twig ─────────────
$CAPTCHA['ASP_LABEL']       = 'Advanced Spam Protection (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION'] = 'A hidden field catches bots that auto-fill all inputs. Requires no user action. Works independently of the captcha above.';
