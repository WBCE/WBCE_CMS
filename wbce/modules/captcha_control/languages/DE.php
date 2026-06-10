<?php
/**
 * captcha_control — DE.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

// Modulbeschreibung
$module_name        = 'Captcha &amp; Spam-Schutz';
$module_description = 'Admin-Tool zum Konfigurieren des ALTCHA-Captchas und des Honeypot-Spamschutzes.';

// ── MOD_CAPTCHA_CONTROL namespace ────────────────────────────────────────────
$MOD_CAPTCHA_CONTROL['HEADING']            = 'Captcha &amp; Spam-Schutz';
$MOD_CAPTCHA_CONTROL['HOWTO']              = 'Hier kann das ALTCHA Proof-of-Work Captcha und der Honeypot-Spamfilter konfiguriert werden. Beide Schutzmaßnahmen wirken seitenübergreifend ohne Benutzeraufwand.';
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF']       = 'Captcha-Einstellungen';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE']       = 'Captcha-Typ';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP']        = 'ALTCHA ist ein selbst-gehostetes, datenschutzfreundliches Proof-of-Work-Captcha. Kein Drittanbieter erforderlich.';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA'] = 'Captcha für Registrierungen &amp; Formulare aktivieren';

// ── CAPTCHA namespace — used via L_('CAPTCHA:ASP_LABEL') in Twig ─────────────
$CAPTCHA['ASP_LABEL']         = 'Erweiterter Spam-Schutz (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']   = 'Ein verstecktes Feld überführt Bots, die alle Eingabefelder automatisch ausfüllen. Kein Benutzeraufwand. Funktioniert unabhängig vom Captcha oben.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>WICHTIG:</b> Einzelne Module wie <i>MiniForm</i>, <i>Guestbook</i> etc. haben ihre eigenen Einstellungen, ob Captcha im Formular des Moduls verwendet werden soll. <br><b>Bitte in den Einstellungen jeweiliger Module nachschauen</b>.';

// ── Widget-Anpassung ─────────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']    = 'Widget-Anpassung';
$CAPTCHA['AUTO_LABEL']        = 'Start-Modus';
$CAPTCHA['AUTO_OFF']          = 'Manuell (Klick)';
$CAPTCHA['AUTO_ONLOAD']       = 'Automatisch';
$CAPTCHA['AUTO_ONSUBMIT']     = 'Bei Formular-Submit';
$CAPTCHA['DELAY_LABEL']       = 'Verzögerung';
$CAPTCHA['DELAY_HINT']        = 'ms Pause vor PoW-Start — erschwert automatisierte Angriffe';
$CAPTCHA['HIDEFOOTER']        = '„Powered by ALTCHA"-Footer ausblenden';
$CAPTCHA['HIDELOGO']          = 'ALTCHA-Logo ausblenden';
$CAPTCHA['COLOR_BRAND']       = 'Akzentfarbe';
$CAPTCHA['COLOR_BRAND_HINT']  = 'Spinner &amp; Haken';
$CAPTCHA['COLOR_BASE']        = 'Hintergrund';
$CAPTCHA['COLOR_TEXT']        = 'Textfarbe';
$CAPTCHA['BORDER_RADIUS']     = 'Eckenrundung';
$CAPTCHA['COLOR_DEFAULT']     = 'Standard';
$CAPTCHA['COLOR_CUSTOM']      = 'Eigene';
$CAPTCHA['CORNER_SQUARE']     = 'Eckig';
$CAPTCHA['CORNER_LIGHT']      = 'Leicht';
$CAPTCHA['CORNER_ROUND']      = 'Rund';
$CAPTCHA['PREVIEW']           = 'Vorschau';
$CAPTCHA['WIDGET_FOOTER_TEXT']= 'Geschützt durch ALTCHA';
