<?php
/**
 * captcha_control — SK.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Ochrana pred spamom';
$module_description = 'Administr&aacute;torsk&yacute; n&aacute;stroj na konfigur&aacute;ciu ALTCHA captcha';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Ochrana pred spamom';
$CAPTCHA['HOWTO']              = 'Nakonfigurujte ALTCHA proof-of-work captcha a Honeypot filter spamu. Obe chr&aacute;nia formul&aacute;re na celej str&aacute;nke bez ak&eacute;jko&ľvek z&aacute;ťaže pre použ&iacute;vate&ľov.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Typ captcha';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA je vlastne hostovan&aacute;, ochranu s&uacute;kromia re&scaron;pektuj&uacute;ca proof-of-work captcha. Nevyžaduje žiadne extern&eacute; služby.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Aktivova&#357; captcha pre registr&aacute;cie';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Pokro&#269;il&aacute; ochrana pred spamom (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Skryt&eacute; pole odhal&iacute; botov, ktor&iacute; automaticky vyp&#314;&nacute;aj&uacute; v&scaron;etky vstupn&eacute; polia. Nevyžaduje žiadnu akciu od použ&iacute;vate&ľa. Funguje nez&aacute;visle od captcha vyš&scaron;ie.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>D&Ocirc;LE&#381;IT&Eacute;:</b> Jednotliv&eacute; moduly ako <i>MiniForm</i>, <i>Guestbook</i> at&#271;. maj&uacute; vlastn&eacute; nastavenia pre pou&#382;itie Captcha vo formul&aacute;ri modulu. <br><b>Skontrolujte nastavenia pr&iacute;slu&scaron;n&yacute;ch modulov</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Prispôsobenie widgetu';
$CAPTCHA['AUTO_LABEL']         = 'Režim spustenia';
$CAPTCHA['AUTO_OFF']           = 'Ru&#269;ne (klik)';
$CAPTCHA['AUTO_ONLOAD']        = 'Automaticky';
$CAPTCHA['AUTO_ONSUBMIT']      = 'Pri odoslan&iacute; formul&aacute;ra';
$CAPTCHA['DELAY_LABEL']        = 'Oneskorenie';
$CAPTCHA['DELAY_HINT']         = 'ms pauzy pred spusten&iacute;m PoW — s&#357;ažuje automatizovan&eacute; &uacute;toky';
$CAPTCHA['HIDEFOOTER']         = 'Skry&#357; p&auml;tu "Powered by ALTCHA"';
$CAPTCHA['HIDELOGO']           = 'Skry&#357; logo ALTCHA';
$CAPTCHA['COLOR_BRAND']        = 'Farba zvýraznenia';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; r&aacute;me&#269;ek';
$CAPTCHA['COLOR_SUCCESS']      = 'Farba za&scaron;krtnutia';
$CAPTCHA['COLOR_BASE']         = 'Pozadie widgetu';
$CAPTCHA['COLOR_CHECKBOX']     = 'Pozadie za&scaron;krt&aacute;vacieho pol&iacute;&#269;ka';
$CAPTCHA['COLOR_TEXT']         = 'Farba textu';
$CAPTCHA['BORDER_RADIUS']      = 'Zaoblenie rohov';
$CAPTCHA['COLOR_DEFAULT']      = 'Predvolen&eacute;';
$CAPTCHA['CORNER_SQUARE']      = 'Hrnat&yacute;';
$CAPTCHA['CORNER_LIGHT']       = 'Mierne';
$CAPTCHA['CORNER_ROUND']       = 'Okr&uacute;hly';
$CAPTCHA['PREVIEW']            = 'N&aacute;h&ľad';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Chr&aacute;nen&eacute; ALTCHA';
