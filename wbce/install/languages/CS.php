<?php 
/**
 * @file    CS.php
 * @brief   Czech language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'CS';
$INFO['language_name'] = 'Čeština';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Průvodce instalací';
$TXT['welcome_heading']      = 'Průvodce instalací';
$TXT['welcome_sub']          = 'Dokonči všechny následující kroky pro dokončení instalace';

$TXT['step1_heading']        = 'Krok 1 — Systémové požadavky';
$TXT['step1_desc']           = 'Kontrola, zda tvůj server splňuje všechny požadavky';
$TXT['step2_heading']        = 'Krok 2 — Nastavení webu';
$TXT['step2_desc']           = 'Nastav základní parametry webu a locale';
$TXT['step3_heading']        = 'Krok 3 — Databáze';
$TXT['step3_desc']           = 'Zadej své připojovací údaje k MySQL / MariaDB';
$TXT['step4_heading']        = 'Krok 4 — Administrátorský účet';
$TXT['step4_desc']           = 'Vytvoř své přihlašovací údaje do administrace';
$TXT['step5_heading']        = 'Krok 5 — Instalace WBCE CMS';
$TXT['step5_desc']           = 'Zkontroluj licenci a spusť instalaci';

$TXT['req_php_version']      = 'Verze PHP >=';
$TXT['req_php_sessions']     = 'Podpora PHP sessions';
$TXT['req_server_charset']   = 'Výchozí kódování znaků serveru';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Práva souborů &amp; složek';
$TXT['file_perm_descr']      = 'Následující cesty musí být zapisovatelné pro webový server';

$TXT['lbl_website_title']    = 'Název webu';
$TXT['lbl_absolute_url']     = 'Absolutní URL';
$TXT['lbl_timezone']         = 'Výchozí časové pásmo';
$TXT['lbl_language']         = 'Výchozí jazyk';
$TXT['lbl_server_os']        = 'Operační systém serveru';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Práva pro zápis všem (777)';

$TXT['lbl_db_host']          = 'Název hosta';
$TXT['lbl_db_name']          = 'Název databáze';
$TXT['lbl_db_prefix']        = 'Prefix tabulek';
$TXT['lbl_db_user']          = 'Uživatelské jméno';
$TXT['lbl_db_pass']          = 'Heslo';
$TXT['btn_test_db']          = 'Otestovat připojení';
$TXT['db_testing']           = 'Připojuji…';
$TXT['db_retest']            = 'Otestovat znovu';

$TXT['lbl_admin_login']      = 'Přihlašovací jméno';
$TXT['lbl_admin_email']      = 'E-mailová adresa';
$TXT['lbl_admin_pass']       = 'Heslo';
$TXT['lbl_admin_repass']     = 'Opakovat heslo';
$TXT['btn_gen_password']     = '⚙ Generovat';
$TXT['pw_copy_hint']         = 'Kopírovat heslo';

$TXT['btn_install']          = '▶  Instalovat WBCE CMS';
$TXT['btn_check_settings']   = 'Zkontroluj svá nastavení v Kroku 1 a obnov stránku klávesou F5';

$TXT['error_prefix']         = 'Chyba';
$TXT['version_prefix']       = 'Verze WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Podpora PHP sessions se může zdát vypnutá, pokud tvůj prohlížeč nepodporuje cookies.';

$MSG['charset_warning'] =
    'Tvůj webový server je nastaven tak, že odesílá pouze kódování <b>%1$s</b>. '
    . 'Aby se národní speciální znaky zobrazovaly správně, vypni prosím toto přednastavení '
    . '(nebo se zeptej svého hostingového poskytovatele). Můžeš také vybrat <b>%1$s</b> '
    . 'v nastaveních WBCE, avšak může to ovlivnit výstup některých modulů.';

$MSG['world_writeable_warning'] =
    'Doporučeno pouze pro testovací prostředí. '
    . 'Toto nastavení můžeš později změnit v administraci.';

$MSG['license_notice'] =
    'WBCE CMS je vydán pod licencí <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Kliknutím na tlačítko instalovat níže potvrzuješ, '
    . 'že jsi přečetl a souhlasíš s podmínkami licence.';

// JS validation messages
$MSG['val_required']       = 'Toto pole je povinné.';
$MSG['val_url']            = 'Zadej platnou URL (začínající http:// nebo https://).';
$MSG['val_email']          = 'Zadej platnou e-mailovou adresu.';
$MSG['val_pw_mismatch']    = 'Hesla se neshodují.';
$MSG['val_pw_short']       = 'Heslo musí mít alespoň 12 znaků.';
$MSG['val_db_untested']    = 'Nejprve úspěšně otestuj připojení k databázi před instalací.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Nejprve vyplň hosta, název databáze a uživatelské jméno.';
$MSG['db_pdo_missing']        = 'Rozšíření PDO není na tomto serveru dostupné.';
$MSG['db_success']            = 'Připojení úspěšné: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Přístup odepřen. Zkontroluj uživatelské jméno a heslo.';
$MSG['db_unknown_db']         = 'Databáze neexistuje. Nejprve ji vytvoř nebo zkontroluj název.';
$MSG['db_connection_refused'] = 'Nepodařilo se připojit k hostovi. Zkontroluj název hosta a port.';
$MSG['db_connection_failed']  = 'Připojení selhalo: %s';
