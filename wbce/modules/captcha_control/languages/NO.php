<?php
/**
 * captcha_control — NO.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Spambeskyttelse';
$module_description = 'Administrasjonsverkt&oslash;y for konfigurering av ALTCHA captcha';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Spambeskyttelse';
$CAPTCHA['HOWTO']              = 'Konfigurer ALTCHA proof-of-work captcha og Honeypot-spamfilteret. Begge beskytter skjemaer p&aring; hele nettstedet uten brukerbelastning.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Captcha-type';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA er en selvhostet, personvernvennlig proof-of-work captcha. Ingen tredjepartstjeneste kreves.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Aktiver captcha for registreringer';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Avansert spambeskyttelse (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Et skjult felt fanger bots som automatisk fyller ut alle inndatafelt. Krever ingen brukerhandling. Fungerer uavhengig av captchaen ovenfor.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>VIKTIG:</b> Individuelle moduler som <i>MiniForm</i>, <i>Guestbook</i> osv. har egne innstillinger for om Captcha skal brukes i modulens skjema. <br><b>Kontroller innstillingene for de respektive modulene</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Widget-tilpasning';
$CAPTCHA['AUTO_LABEL']         = 'Startmodus';
$CAPTCHA['AUTO_OFF']           = 'Manuell (klikk)';
$CAPTCHA['AUTO_ONLOAD']        = 'Automatisk';
$CAPTCHA['AUTO_ONSUBMIT']      = 'Ved skjemainnsending';
$CAPTCHA['DELAY_LABEL']        = 'Forsinkelse';
$CAPTCHA['DELAY_HINT']         = 'ms pause f&oslash;r PoW starter — gj&oslash;r automatiserte angrep vanskeligere';
$CAPTCHA['HIDEFOOTER']         = 'Skjul "Powered by ALTCHA"-footer';
$CAPTCHA['HIDELOGO']           = 'Skjul ALTCHA-logo';
$CAPTCHA['COLOR_BRAND']        = 'Aksentfarge';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; ramme';
$CAPTCHA['COLOR_SUCCESS']      = 'Hake-farge';
$CAPTCHA['COLOR_BASE']         = 'Widget-bakgrunn';
$CAPTCHA['COLOR_CHECKBOX']     = 'Avmerkingsboks-bakgrunn';
$CAPTCHA['COLOR_TEXT']         = 'Tekstfarge';
$CAPTCHA['BORDER_RADIUS']      = 'Hjørneradius';
$CAPTCHA['COLOR_DEFAULT']      = 'Standard';
$CAPTCHA['CORNER_SQUARE']      = 'Firkantet';
$CAPTCHA['CORNER_LIGHT']       = 'Litt avrundet';
$CAPTCHA['CORNER_ROUND']       = 'Rund';
$CAPTCHA['PREVIEW']            = 'Forh&aring;ndsvisning';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Beskyttet av ALTCHA';
