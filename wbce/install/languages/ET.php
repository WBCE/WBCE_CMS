<?php 
/**
 * @file    ET.php
 * @brief   Estonian language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'ET';
$INFO['language_name'] = 'Eesti';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS paigaldusviisard';
$TXT['welcome_heading']      = 'Paigaldusviisard';
$TXT['welcome_sub']          = 'Täida kõik allolevad sammud, et paigaldus lõpetada';

$TXT['step1_heading']        = '1. samm — Süsteeminõuded';
$TXT['step1_desc']           = 'Kontrollin, kas sinu server vastab kõigile nõuetele';
$TXT['step2_heading']        = '2. samm — Veebisite seaded';
$TXT['step2_desc']           = 'Seadista veebilehe põhiparameetrid ja lokaalid';
$TXT['step3_heading']        = '3. samm — Andmebaas';
$TXT['step3_desc']           = 'Sisesta oma MySQL / MariaDB ühenduse andmed';
$TXT['step4_heading']        = '4. samm — Administraatori konto';
$TXT['step4_desc']           = 'Loo oma administraatoripaneeli sisselogimise andmed';
$TXT['step5_heading']        = '5. samm — Paigalda WBCE CMS';
$TXT['step5_desc']           = 'Vaata litsents üle ja alusta paigaldust';

$TXT['req_php_version']      = 'PHP versioon >=';
$TXT['req_php_sessions']     = 'PHP sessioonide tugi';
$TXT['req_server_charset']   = 'Serveri vaikimisi märgistik';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Failide &amp; kaustade õigused';
$TXT['file_perm_descr']      = 'Järgmised teed peavad olema veebiserverile kirjutatavad';

$TXT['lbl_website_title']    = 'Veebisite pealkiri';
$TXT['lbl_absolute_url']     = 'Absoluutne URL';
$TXT['lbl_timezone']         = 'Vaikimisi ajavöönd';
$TXT['lbl_language']         = 'Vaikimisi keel';
$TXT['lbl_server_os']        = 'Serveri operatsioonisüsteem';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Kõigile kirjutatavad failiõigused (777)';

$TXT['lbl_db_host']          = 'Hosti nimi';
$TXT['lbl_db_name']          = 'Andmebaasi nimi';
$TXT['lbl_db_prefix']        = 'Tabelite eesliide';
$TXT['lbl_db_user']          = 'Kasutajanimi';
$TXT['lbl_db_pass']          = 'Parool';
$TXT['btn_test_db']          = 'Testi ühendust';
$TXT['db_testing']           = 'Ühendun…';
$TXT['db_retest']            = 'Testi uuesti';

$TXT['lbl_admin_login']      = 'Sisselogimise nimi';
$TXT['lbl_admin_email']      = 'E-posti aadress';
$TXT['lbl_admin_pass']       = 'Parool';
$TXT['lbl_admin_repass']     = 'Korda parooli';
$TXT['btn_gen_password']     = '⚙ Genereeri';
$TXT['pw_copy_hint']         = 'Kopeeri parool';

$TXT['btn_install']          = '▶  Paigalda WBCE CMS';
$TXT['btn_check_settings']   = 'Kontrolli oma seadeid 1. sammus ja värskenda lehte F5 klahviga';

$TXT['error_prefix']         = 'Viga';
$TXT['version_prefix']       = 'WBCE versioon';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP sessioonide tugi võib tunduda välja lülitatuna, kui sinu brauser ei toeta küpsiseid.';

$MSG['charset_warning'] =
    'Sinu veebiserver on seadistatud saatma ainult märgistikku <b>%1$s</b>. '
    . 'Et erikujundid kuvataks õigesti, lülita see eelseadistus välja '
    . '(või küsi oma hostingupakkujalt). Samuti võid valida <b>%1$s</b> WBCE seadetes, '
    . 'kuid see võib mõjutada mõnede moodulite väljundit.';

$MSG['world_writeable_warning'] =
    'Soovitatav ainult testkeskkondadele. '
    . 'Seda seadet saad hiljem muuta haldusliideses.';

$MSG['license_notice'] =
    'WBCE CMS avaldatakse litsentsi alusel <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Klõpsates allolevale installinupule, kinnitad, '
    . 'et oled litsentsitingimused läbi lugenud ja nõustud nendega.';

// JS validation messages
$MSG['val_required']       = 'See väli on kohustuslik.';
$MSG['val_url']            = 'Palun sisesta kehtiv URL (mis algab http:// või https://).';
$MSG['val_email']          = 'Palun sisesta kehtiv e-posti aadress.';
$MSG['val_pw_mismatch']    = 'Paroolid ei kattu.';
$MSG['val_pw_short']       = 'Parool peab olema vähemalt 12 märki pikk.';
$MSG['val_db_untested']    = 'Palun testi andmebaasiühendus edukalt enne paigaldamist.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Palun täida esmalt hosti nimi, andmebaasi nimi ja kasutajanimi.';
$MSG['db_pdo_missing']        = 'PDO laiendus pole sellel serveril saadaval.';
$MSG['db_success']            = 'Ühendus õnnestus: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Ligipääs keelatud. Kontrolli kasutajanime ja parooli.';
$MSG['db_unknown_db']         = 'Andmebaasi ei eksisteeri. Loo see esmalt või kontrolli nime.';
$MSG['db_connection_refused'] = 'Ei õnnestunud hostiga ühendust luua. Kontrolli hosti nime ja porti.';
$MSG['db_connection_failed']  = 'Ühendus ebaõnnestus: %s';
