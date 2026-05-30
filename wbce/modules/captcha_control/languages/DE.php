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
$MOD_CAPTCHA_CONTROL['ENABLED']            = 'Aktiviert';
$MOD_CAPTCHA_CONTROL['DISABLED']           = 'Deaktiviert';

// ── CAPTCHA namespace — used via L_('CAPTCHA:ASP_LABEL') in Twig ─────────────
$CAPTCHA['ASP_LABEL']       = 'Erweiterter Spam-Schutz (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION'] = 'Ein verstecktes Feld überführt Bots, die alle Eingabefelder automatisch ausfüllen. Kein Benutzeraufwand. Funktioniert unabhängig vom Captcha oben.';
