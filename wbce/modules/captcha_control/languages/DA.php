<?php
/**
 * captcha_control — DA.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Spam-beskyttelse';
$module_description = 'Administrationsværktøj til konfiguration af ALTCHA captcha';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Spam-beskyttelse';
$CAPTCHA['HOWTO']              = 'Konfigurer ALTCHA proof-of-work captcha og Honeypot-spamfilteret. Begge beskytter formularer på hele webstedet uden brugerfriktioner.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Captcha-type';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA er en selvhostet, privatlivsvenlig proof-of-work captcha. Ingen tredjepartstjeneste kræves.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Aktiver captcha for registreringer &amp; formularer';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Avanceret spambeskyttelse (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Et skjult felt fanger bots, der automatisk udfylder alle inputfelter. Kræver ingen brugerhandling. Fungerer uafhængigt af captchaen ovenfor.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>VIGTIGT:</b> Individuelle moduler som <i>MiniForm</i>, <i>Guestbook</i> m.fl. har deres egne indstillinger for, om Captcha skal bruges i modulets formular. <br><b>Kontroller venligst indstillingerne for de respektive moduler</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Widget-tilpasning';
$CAPTCHA['AUTO_LABEL']         = 'Starttilstand';
$CAPTCHA['AUTO_OFF']           = 'Manuel (klik)';
$CAPTCHA['AUTO_ONLOAD']        = 'Automatisk';
$CAPTCHA['AUTO_ONSUBMIT']      = 'Ved formularindsendelse';
$CAPTCHA['DELAY_LABEL']        = 'Forsinkelse';
$CAPTCHA['DELAY_HINT']         = 'ms pause før PoW starter — gør automatiserede angreb sværere';
$CAPTCHA['HIDEFOOTER']         = 'Skjul "Powered by ALTCHA"-footer';
$CAPTCHA['HIDELOGO']           = 'Skjul ALTCHA-logo';
$CAPTCHA['COLOR_BRAND']        = 'Accentfarve';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; ramme';
$CAPTCHA['COLOR_SUCCESS']      = 'Flueben-farve';
$CAPTCHA['COLOR_BASE']         = 'Widget-baggrund';
$CAPTCHA['COLOR_CHECKBOX']     = 'Afkrydsningsfelt-baggrund';
$CAPTCHA['COLOR_TEXT']         = 'Tekstfarve';
$CAPTCHA['BORDER_RADIUS']      = 'Hjørneradius';
$CAPTCHA['COLOR_DEFAULT']      = 'Standard';
$CAPTCHA['CORNER_SQUARE']      = 'Firkantet';
$CAPTCHA['CORNER_LIGHT']       = 'Let';
$CAPTCHA['CORNER_ROUND']       = 'Rund';
$CAPTCHA['PREVIEW']            = 'Forhåndsvisning';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Beskyttet af ALTCHA';
