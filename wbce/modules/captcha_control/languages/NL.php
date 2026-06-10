<?php
/**
 * captcha_control — NL.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Spambeveiliging';
$module_description = 'Beheertool voor het configureren van ALTCHA captcha';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Spambeveiliging';
$CAPTCHA['HOWTO']              = 'Configureer de ALTCHA proof-of-work captcha en het Honeypot-spamfilter. Beide beschermen formulieren op de hele website zonder gebruikersfrictie.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Captcha-type';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA is een zelf-gehoste, privacyvriendelijke proof-of-work captcha. Geen externe dienst vereist.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Captcha activeren voor registraties';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Geavanceerde spambeveiliging (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Een verborgen veld detecteert bots die automatisch alle invoervelden invullen. Geen gebruikersactie vereist. Werkt onafhankelijk van de bovenstaande captcha.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>BELANGRIJK:</b> Afzonderlijke modules zoals <i>MiniForm</i>, <i>Guestbook</i>, enz. hebben hun eigen instellingen voor het gebruik van Captcha in het formulier van de module. <br><b>Controleer de instellingen van de betreffende modules</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Widget-aanpassing';
$CAPTCHA['AUTO_LABEL']         = 'Startmodus';
$CAPTCHA['AUTO_OFF']           = 'Handmatig (klik)';
$CAPTCHA['AUTO_ONLOAD']        = 'Automatisch';
$CAPTCHA['AUTO_ONSUBMIT']      = 'Bij formulierinzending';
$CAPTCHA['DELAY_LABEL']        = 'Vertraging';
$CAPTCHA['DELAY_HINT']         = 'ms pauze voordat PoW start — maakt geautomatiseerde aanvallen moeilijker';
$CAPTCHA['HIDEFOOTER']         = 'Voettekst "Powered by ALTCHA" verbergen';
$CAPTCHA['HIDELOGO']           = 'ALTCHA-logo verbergen';
$CAPTCHA['COLOR_BRAND']        = 'Accentkleur';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; rand';
$CAPTCHA['COLOR_SUCCESS']      = 'Vinkjeskleur';
$CAPTCHA['COLOR_BASE']         = 'Widget-achtergrond';
$CAPTCHA['COLOR_CHECKBOX']     = 'Selectievakje-achtergrond';
$CAPTCHA['COLOR_TEXT']         = 'Tekstkleur';
$CAPTCHA['BORDER_RADIUS']      = 'Hoekafronding';
$CAPTCHA['COLOR_DEFAULT']      = 'Standaard';
$CAPTCHA['CORNER_SQUARE']      = 'Vierkant';
$CAPTCHA['CORNER_LIGHT']       = 'Licht';
$CAPTCHA['CORNER_ROUND']       = 'Rond';
$CAPTCHA['PREVIEW']            = 'Voorbeeld';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Beveiligd door ALTCHA';
