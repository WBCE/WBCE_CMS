<?php 
/**
 * @file    SV.php
 * @brief   Swedish language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'SV';
$INFO['language_name'] = 'Svenska';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Installationsguide';
$TXT['welcome_heading']      = 'Installationsguide';
$TXT['welcome_sub']          = 'Genomför alla stegen nedan för att slutföra installationen';

$TXT['step1_heading']        = 'Steg 1 — Systemkrav';
$TXT['step1_desc']           = 'Kontrollerar om din server uppfyller alla krav';
$TXT['step2_heading']        = 'Steg 2 — Webbplatsinställningar';
$TXT['step2_desc']           = 'Konfigurera grundläggande webbplatsinställningar och locale';
$TXT['step3_heading']        = 'Steg 3 — Databas';
$TXT['step3_desc']           = 'Ange dina MySQL / MariaDB-anslutningsuppgifter';
$TXT['step4_heading']        = 'Steg 4 — Administratörskonto';
$TXT['step4_desc']           = 'Skapa dina inloggningsuppgifter till adminpanelen';
$TXT['step5_heading']        = 'Steg 5 — Installera WBCE CMS';
$TXT['step5_desc']           = 'Granska licensen och starta installationen';

$TXT['req_php_version']      = 'PHP-version >=';
$TXT['req_php_sessions']     = 'PHP sessionsstöd';
$TXT['req_server_charset']   = 'Serverns standardteckenuppsättning';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Fil- &amp; mappbehörigheter';
$TXT['file_perm_descr']      = 'Följande sökvägar måste vara skrivbara för webbservern';

$TXT['lbl_website_title']    = 'Webbplatstitel';
$TXT['lbl_absolute_url']     = 'Absolut URL';
$TXT['lbl_timezone']         = 'Standardtidszon';
$TXT['lbl_language']         = 'Standardspråk';
$TXT['lbl_server_os']        = 'Serverns operativsystem';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Världsskrivbara filrättigheter (777)';

$TXT['lbl_db_host']          = 'Värdnamn';
$TXT['lbl_db_name']          = 'Databasnamn';
$TXT['lbl_db_prefix']        = 'Tabellprefix';
$TXT['lbl_db_user']          = 'Användarnamn';
$TXT['lbl_db_pass']          = 'Lösenord';
$TXT['btn_test_db']          = 'Testa anslutning';
$TXT['db_testing']           = 'Ansluter…';
$TXT['db_retest']            = 'Testa igen';

$TXT['lbl_admin_login']      = 'Inloggningsnamn';
$TXT['lbl_admin_email']      = 'E-postadress';
$TXT['lbl_admin_pass']       = 'Lösenord';
$TXT['lbl_admin_repass']     = 'Upprepa lösenord';
$TXT['btn_gen_password']     = '⚙ Generera';
$TXT['pw_copy_hint']         = 'Kopiera lösenord';

$TXT['btn_install']          = '▶  Installera WBCE CMS';
$TXT['btn_check_settings']   = 'Kontrollera dina inställningar i Steg 1 och ladda om sidan med F5';

$TXT['error_prefix']         = 'Fel';
$TXT['version_prefix']       = 'WBCE-version';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP sessionsstöd kan visas som inaktiverat om din webbläsare inte accepterar cookies.';

$MSG['charset_warning'] =
    'Din webbserver är konfigurerad att endast leverera teckenuppsättningen <b>%1$s</b>. '
    . 'För att visa nationella specialtecken korrekt, inaktivera denna förinställning '
    . '(eller fråga din webbhotellleverantör). Du kan också välja <b>%1$s</b> i WBCE-inställningarna, '
    . 'men detta kan påverka utdata från vissa moduler.';

$MSG['world_writeable_warning'] =
    'Rekommenderas endast för testmiljöer. '
    . 'Du kan ändra denna inställning senare i adminpanelen.';

$MSG['license_notice'] =
    'WBCE CMS distribueras under licensen <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Genom att klicka på installationsknappen nedan bekräftar du '
    . 'att du har läst och godkänner licensvillkoren.';

// JS validation messages
$MSG['val_required']       = 'Detta fält är obligatoriskt.';
$MSG['val_url']            = 'Ange en giltig URL (som börjar med http:// eller https://).';
$MSG['val_email']          = 'Ange en giltig e-postadress.';
$MSG['val_pw_mismatch']    = 'Lösenorden stämmer inte överens.';
$MSG['val_pw_short']       = 'Lösenordet måste vara minst 12 tecken långt.';
$MSG['val_db_untested']    = 'Testa databasanslutningen framgångsrikt innan du installerar.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Fyll först i värdnamn, databasnamn och användarnamn.';
$MSG['db_pdo_missing']        = 'PDO-tillägget är inte tillgängligt på denna server.';
$MSG['db_success']            = 'Anslutning lyckades: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Åtkomst nekad. Kontrollera användarnamn och lösenord.';
$MSG['db_unknown_db']         = 'Databasen finns inte. Skapa den först eller kontrollera namnet.';
$MSG['db_connection_refused'] = 'Kunde inte ansluta till värden. Kontrollera värdnamn och port.';
$MSG['db_connection_failed']  = 'Anslutning misslyckades: %s';