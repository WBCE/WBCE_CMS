<?php
/**
 * captcha_control — PL.php
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

$module_name        = 'Captcha &amp; Ochrona przed spamem';
$module_description = 'Narzędzie administracyjne do konfiguracji captchy ALTCHA';

// ── General ──────────────────────────────────────────────────────────────────
$CAPTCHA['HEADING']            = 'Captcha &amp; Ochrona przed spamem';
$CAPTCHA['HOWTO']              = 'Skonfiguruj captchę ALTCHA proof-of-work oraz filtr antyspamowy Honeypot. Oba mechanizmy chronią formularze w całej witrynie bez żadnych niedogodności dla użytkowników.';
$CAPTCHA['CAPTCHA_TYPE']       = 'Typ captchy';
$CAPTCHA['CAPTCHA_EXP']        = 'ALTCHA to samodzielnie hostowana, przyjazna prywatności captcha proof-of-work. Nie wymaga zewnętrznych usług.';
$CAPTCHA['USE_SIGNUP_CAPTCHA'] = 'Włącz captchę dla rejestracji';

// ── Advanced Spam Protection ──────────────────────────────────────────────────
$CAPTCHA['ASP_LABEL']             = 'Zaawansowana ochrona przed spamem (Honeypot)';
$CAPTCHA['ASP_DESCRIPTION']       = 'Ukryte pole wykrywa boty automatycznie wypełniające wszystkie pola formularza. Nie wymaga żadnej akcji ze strony użytkownika. Działa niezależnie od captchy powyżej.';
$CAPTCHA['MODULES_SETTINGS_INFO'] = '<b>WAŻNE:</b> Poszczególne moduły, takie jak <i>MiniForm</i>, <i>Guestbook</i> itp., mają własne ustawienia dotyczące użycia Captcha w formularzu modułu. <br><b>Sprawdź ustawienia poszczególnych modułów</b>.';

// ── Widget customization ──────────────────────────────────────────────────────
$CAPTCHA['WIDGET_HEADING']     = 'Dostosowanie widżetu';
$CAPTCHA['AUTO_LABEL']         = 'Tryb uruchamiania';
$CAPTCHA['AUTO_OFF']           = 'Ręczny (kliknięcie)';
$CAPTCHA['AUTO_ONLOAD']        = 'Automatyczny';
$CAPTCHA['AUTO_ONSUBMIT']      = 'Przy wysyłaniu formularza';
$CAPTCHA['DELAY_LABEL']        = 'Opóźnienie';
$CAPTCHA['DELAY_HINT']         = 'ms przerwy przed uruchomieniem PoW — utrudnia zautomatyzowane ataki';
$CAPTCHA['HIDEFOOTER']         = 'Ukryj stopkę "Powered by ALTCHA"';
$CAPTCHA['HIDELOGO']           = 'Ukryj logo ALTCHA';
$CAPTCHA['COLOR_BRAND']        = 'Kolor akcentu';
$CAPTCHA['COLOR_BRAND_HINT']   = 'Spinner &amp; obramowanie';
$CAPTCHA['COLOR_SUCCESS']      = 'Kolor zatwierdzenia';
$CAPTCHA['COLOR_BASE']         = 'Tło widżetu';
$CAPTCHA['COLOR_CHECKBOX']     = 'Tło pola wyboru';
$CAPTCHA['COLOR_TEXT']         = 'Kolor tekstu';
$CAPTCHA['BORDER_RADIUS']      = 'Zaokrąglenie narożników';
$CAPTCHA['COLOR_DEFAULT']      = 'Domyślny';
$CAPTCHA['CORNER_SQUARE']      = 'Kwadratowy';
$CAPTCHA['CORNER_LIGHT']       = 'Lekki';
$CAPTCHA['CORNER_ROUND']       = 'Okrągły';
$CAPTCHA['PREVIEW']            = 'Podgląd';
$CAPTCHA['WIDGET_FOOTER_TEXT'] = 'Chronione przez ALTCHA';
