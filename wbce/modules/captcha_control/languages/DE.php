<?php
/**
 * captcha_control — DE.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Spam-Schutz';
$module_description = 'Admin-Tool zum Konfigurieren des ALTCHA-Captchas';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Spam-Schutz';
$CAPTCHA['HOWTO']              = 'Hier kann das ALTCHA Proof-of-Work Captcha und der Honeypot-Spamfilter konfiguriert werden. Beide Schutzmaßnahmen wirken seitenübergreifend ohne Benutzeraufwand.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Captcha-Typ';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA ist ein selbst-gehostetes, datenschutzfreundliches Proof-of-Work-Captcha. Kein Drittanbieter erforderlich.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Captcha für Registrierungen';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Erweiterter Spam-Schutz (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Ein verstecktes Feld überführt Bots, die alle Eingabefelder automatisch ausfüllen. Kein Benutzeraufwand. Funktioniert unabhängig vom Captcha oben.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>WICHTIG:</b> Einzelne Module wie <i>MiniForm</i>, <i>Guestbook</i> etc. haben ihre eigenen Einstellungen, ob Captcha im Formular des Moduls verwendet werden soll. <br><b>Bitte in den Einstellungen jeweiliger Module nachschauen</b>.';

// ── Widget-Anpassung ──────────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Widget-Anpassung';
$CAPTCHA['AUTO_LABEL']         = 'Start-Modus';
$CAPTCHA['AUTO_OFF']           = 'Manuell (Klick)';
$CAPTCHA['AUTO_ONLOAD']        = 'Automatisch';
$CAPTCHA['AUTO_ONSUBMIT']      = 'Bei Formular-Submit';
$CAPTCHA['DELAY_LABEL']        = 'Verzögerung';
$CAPTCHA['DELAY_HINT']         = 'ms Pause vor PoW-Start — erschwert automatisierte Angriffe';
$CAPTCHA['HIDEFOOTER']         = '„Powered by ALTCHA"-Footer ausblenden';
$CAPTCHA['HIDELOGO']           = 'ALTCHA-Logo ausblenden';
$CAPTCHA['COLOR_BRAND']        = 'Akzentfarbe';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; Rahmen';
$CAPTCHA['COLOR_SUCCESS']      = 'Check-Farbe';
$CAPTCHA['COLOR_BASE']         = 'Widget-Hintergrund';
$CAPTCHA['COLOR_CHECKBOX']     = 'Checkbox-Hintergrund';
$CAPTCHA['COLOR_TEXT']         = 'Textfarbe';
$CAPTCHA['BORDER_RADIUS']      = 'Eckenrundung';
$CAPTCHA['COLOR_DEFAULT']      = 'Vorgabewert';
$CAPTCHA['CORNER_SQUARE']      = 'Eckig';
$CAPTCHA['CORNER_LIGHT']       = 'Leicht';
$CAPTCHA['CORNER_ROUND']       = 'Rund';
$CAPTCHA['PREVIEW']            = 'Vorschau';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Geschützt durch ALTCHA';
