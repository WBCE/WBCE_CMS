<?php 
/**
 * @file    LV.php
 * @brief   Latvian language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'LV';
$INFO['language_name'] = 'Latviešu';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS instalācijas vednis';
$TXT['welcome_heading']      = 'Instalācijas vednis';
$TXT['welcome_sub']          = 'Izpildi visus tālāk norādītos soļus, lai pabeigtu instalāciju';

$TXT['step1_heading']        = '1. solis — Sistēmas prasības';
$TXT['step1_desc']           = 'Pārbauda, vai tavs serveris atbilst visām prasībām';
$TXT['step2_heading']        = '2. solis — Tīmekļvietnes iestatījumi';
$TXT['step2_desc']           = 'Konfigurē pamata vietnes parametrus un lokālos iestatījumus';
$TXT['step3_heading']        = '3. solis — Datubāze';
$TXT['step3_desc']           = 'Ievadi savus MySQL / MariaDB savienojuma datus';
$TXT['step4_heading']        = '4. solis — Administratora konts';
$TXT['step4_desc']           = 'Izveido savus pieteikšanās datus administrācijas panelim';
$TXT['step5_heading']        = '5. solis — Instalēt WBCE CMS';
$TXT['step5_desc']           = 'Pārskati licenci un sāc instalāciju';

$TXT['req_php_version']      = 'PHP versija >=';
$TXT['req_php_sessions']     = 'PHP sesiju atbalsts';
$TXT['req_server_charset']   = 'Servera noklusējuma rakstzīmju kopa';
$TXT['req_safe_mode']        = 'PHP drošais režīms';
$TXT['files_and_dirs_perms'] = 'Datņu un mapju atļaujas';
$TXT['file_perm_descr']      = 'Šīm mapēm un datnēm jābūt rakstāmām no tīmekļa servera puses';

$TXT['lbl_website_title']    = 'Tīmekļvietnes nosaukums';
$TXT['lbl_absolute_url']     = 'Absolūtais URL';
$TXT['lbl_timezone']         = 'Noklusējuma laika josla';
$TXT['lbl_language']         = 'Noklusējuma valoda';
$TXT['lbl_server_os']        = 'Servera operētājsistēma';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Vispārējas rakstīšanas atļaujas (777)';

$TXT['lbl_db_host']          = 'Servera nosaukums (Host)';
$TXT['lbl_db_name']          = 'Datubāzes nosaukums';
$TXT['lbl_db_prefix']        = 'Tabulu prefikss';
$TXT['lbl_db_user']          = 'Lietotājvārds';
$TXT['lbl_db_pass']          = 'Parole';
$TXT['btn_test_db']          = 'Pārbaudīt savienojumu';
$TXT['db_testing']           = 'Savienojas…';
$TXT['db_retest']            = 'Pārbaudīt vēlreiz';

$TXT['lbl_admin_login']      = 'Lietotājvārds';
$TXT['lbl_admin_email']      = 'E-pasta adrese';
$TXT['lbl_admin_pass']       = 'Parole';
$TXT['lbl_admin_repass']     = 'Atkārtot paroli';
$TXT['btn_gen_password']     = '⚙ Ģenerēt';
$TXT['pw_copy_hint']         = 'Kopēt paroli';

$TXT['btn_install']          = '▶  Instalēt WBCE CMS';
$TXT['btn_check_settings']   = 'Pārbaudi savus iestatījumus 1. solī un atsvaidzini lapu ar F5';

$TXT['error_prefix']         = 'Kļūda';
$TXT['version_prefix']       = 'WBCE versija';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP sesiju atbalsts var izskatīties izslēgts, ja tavs pārlūks neatbalsta sīkfailus.';

$MSG['charset_warning'] =
    'Tavs tīmekļa serveris ir konfigurēts piegādāt tikai rakstzīmju kopu <b>%1$s</b>. '
    . 'Lai pareizi attēlotu nacionālos speciālos simbolus, lūdzu, atspējo šo priekšiestatījumu '
    . '(vai jautā savam hostinga nodrošinātājam). Tu vari arī izvēlēties <b>%1$s</b> WBCE iestatījumos, '
    . 'tomēr tas var ietekmēt dažu moduļu izvadi.';

$MSG['world_writeable_warning'] =
    'Ieteicams tikai testēšanas vidēs. '
    . 'Šo iestatījumu vari mainīt vēlāk administrācijas panelī.';

$MSG['license_notice'] =
    'WBCE CMS tiek izplatīts saskaņā ar licenci <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Noklikšķinot uz instalācijas pogas zemāk, tu apstiprini, '
    . 'ka esi izlasījis un piekrīti licences noteikumiem.';

// JS validation messages
$MSG['val_required']       = 'Šis lauks ir obligāts.';
$MSG['val_url']            = 'Lūdzu, ievadi derīgu URL (sākas ar http:// vai https://).';
$MSG['val_email']          = 'Lūdzu, ievadi derīgu e-pasta adresi.';
$MSG['val_pw_mismatch']    = 'Paroles nesakrīt.';
$MSG['val_pw_short']       = 'Parolei jābūt vismaz 12 rakstzīmēm garai.';
$MSG['val_db_untested']    = 'Lūdzu, vispirms veiksmīgi pārbaudi datubāzes savienojumu pirms instalācijas.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Lūdzu, vispirms aizpildi hosta nosaukumu, datubāzes nosaukumu un lietotājvārdu.';
$MSG['db_pdo_missing']        = 'PDO paplašinājums nav pieejams šajā serverī.';
$MSG['db_success']            = 'Savienojums veiksmīgs: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Piekļuve liegta. Pārbaudi lietotājvārdu un paroli.';
$MSG['db_unknown_db']         = 'Datubāze neeksistē. Vispirms to izveido vai pārbaudi nosaukumu.';
$MSG['db_connection_refused'] = 'Neizdevās savienoties ar hostu. Pārbaudi hosta nosaukumu un portu.';
$MSG['db_connection_failed']  = 'Savienojums neizdevās: %s';