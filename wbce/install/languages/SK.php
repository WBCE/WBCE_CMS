<?php 
/**
 * @file    SK.php
 * @brief   Slovak language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'SK';
$INFO['language_name'] = 'Slovenský';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Sprievodca inštaláciou WBCE CMS';
$TXT['welcome_heading']      = 'Sprievodca inštaláciou';
$TXT['welcome_sub']          = 'Dokonči všetky nasledujúce kroky na dokončenie inštalácie';

$TXT['step1_heading']        = 'Krok 1 — Systémové požiadavky';
$TXT['step1_desc']           = 'Kontrola, či tvoj server spĺňa všetky požiadavky';
$TXT['step2_heading']        = 'Krok 2 — Nastavenia webu';
$TXT['step2_desc']           = 'Nastav základné parametre stránky a locale';
$TXT['step3_heading']        = 'Krok 3 — Databáza';
$TXT['step3_desc']           = 'Zadaj svoje prihlasovacie údaje k MySQL / MariaDB';
$TXT['step4_heading']        = 'Krok 4 — Administrátorský účet';
$TXT['step4_desc']           = 'Vytvor svoje prihlasovacie údaje do administrácie';
$TXT['step5_heading']        = 'Krok 5 — Inštalácia WBCE CMS';
$TXT['step5_desc']           = 'Skontroluj licenciu a spusti inštaláciu';

$TXT['req_php_version']      = 'Verzia PHP >=';
$TXT['req_php_sessions']     = 'Podpora PHP sessions';
$TXT['req_server_charset']   = 'Predvolené kódovanie znakov servera';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Práva súborov a priečinkov';
$TXT['file_perm_descr']      = 'Nasledujúce cesty musia byť zapisovateľné webovým serverom';

$TXT['lbl_website_title']    = 'Názov webu';
$TXT['lbl_absolute_url']     = 'Absolútna URL';
$TXT['lbl_timezone']         = 'Predvolené časové pásmo';
$TXT['lbl_language']         = 'Predvolený jazyk';
$TXT['lbl_server_os']        = 'Operačný systém servera';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Práva na zápis pre všetkých (777)';

$TXT['lbl_db_host']          = 'Názov hosta';
$TXT['lbl_db_name']          = 'Názov databázy';
$TXT['lbl_db_prefix']        = 'Prefix tabuliek';
$TXT['lbl_db_user']          = 'Meno používateľa';
$TXT['lbl_db_pass']          = 'Heslo';
$TXT['btn_test_db']          = 'Otestovať pripojenie';
$TXT['db_testing']           = 'Pripájam…';
$TXT['db_retest']            = 'Otestovať znova';

$TXT['lbl_admin_login']      = 'Prihlasovacie meno';
$TXT['lbl_admin_email']      = 'E-mailová adresa';
$TXT['lbl_admin_pass']       = 'Heslo';
$TXT['lbl_admin_repass']     = 'Opakovať heslo';
$TXT['btn_gen_password']     = '⚙ Generovať';
$TXT['pw_copy_hint']         = 'Kopírovať heslo';

$TXT['btn_install']          = '▶  Inštalovať WBCE CMS';
$TXT['btn_check_settings']   = 'Skontroluj svoje nastavenia v Kroku 1 a obnov stránku klávesom F5';

$TXT['error_prefix']         = 'Chyba';
$TXT['version_prefix']       = 'Verzia WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Podpora PHP sessions sa môže zdať vypnutá, ak tvoj prehliadač nepodporuje cookies.';

$MSG['charset_warning'] =
    'Tvoj webový server je nastavený tak, že odosiela iba kódovanie <b>%1$s</b>. '
    . 'Aby sa národné špeciálne znaky zobrazovali správne, vypni prosím toto prednastavenie '
    . '(alebo sa obráť na svojho hostingového poskytovateľa). Môžeš tiež vybrať <b>%1$s</b> '
    . 'v nastaveniach WBCE, avšak môže to ovplyvniť výstup niektorých modulov.';

$MSG['world_writeable_warning'] =
    'Odporúča sa iba pre testovacie prostredia. '
    . 'Toto nastavenie môžeš neskôr zmeniť v administrácii.';

$MSG['license_notice'] =
    'WBCE CMS je vydaný pod licenciou <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Kliknutím na tlačidlo inštalovať nižšie potvrdzuješ, '
    . 'že si prečítal a akceptuješ podmienky licencie.';

// JS validation messages
$MSG['val_required']       = 'Toto pole je povinné.';
$MSG['val_url']            = 'Prosím, zadaj platnú URL (začínajúcu na http:// alebo https://).';
$MSG['val_email']          = 'Prosím, zadaj platnú e-mailovú adresu.';
$MSG['val_pw_mismatch']    = 'Heslá sa nezhodujú.';
$MSG['val_pw_short']       = 'Heslo musí mať najmenej 12 znakov.';
$MSG['val_db_untested']    = 'Pred inštaláciou najprv úspešne otestuj pripojenie k databáze.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Najprv vyplň hosta, názov databázy a meno používateľa.';
$MSG['db_pdo_missing']        = 'Rozšírenie PDO nie je na tomto serveri dostupné.';
$MSG['db_success']            = 'Pripojenie úspešné: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Prístup zamietnutý. Skontroluj meno používateľa a heslo.';
$MSG['db_unknown_db']         = 'Databáza neexistuje. Najprv ju vytvor alebo skontroluj názov.';
$MSG['db_connection_refused'] = 'Nepodarilo sa pripojiť k hostovi. Skontroluj názov hosta a port.';
$MSG['db_connection_failed']  = 'Pripojenie zlyhalo: %s';