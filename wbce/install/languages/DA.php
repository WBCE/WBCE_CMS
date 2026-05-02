<?php 
/**
 * @file    DA.php
 * @brief   Danish language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'DA';
$INFO['language_name'] = 'Dansk';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Installationsguide';
$TXT['welcome_heading']      = 'Installationsguide';
$TXT['welcome_sub']          = 'Gennemfør alle trinene nedenfor for at fuldføre installationen';

$TXT['step1_heading']        = 'Trin 1 — Systemkrav';
$TXT['step1_desc']           = 'Kontrollerer om din server opfylder alle krav';
$TXT['step2_heading']        = 'Trin 2 — Hjemmesideindstillinger';
$TXT['step2_desc']           = 'Konfigurer de grundlæggende indstillinger for hjemmesiden og locale';
$TXT['step3_heading']        = 'Trin 3 — Database';
$TXT['step3_desc']           = 'Indtast dine MySQL / MariaDB-forbindelsesoplysninger';
$TXT['step4_heading']        = 'Trin 4 — Administrator-konto';
$TXT['step4_desc']           = 'Opret dine loginoplysninger til administrationspanelet';
$TXT['step5_heading']        = 'Trin 5 — Installer WBCE CMS';
$TXT['step5_desc']           = 'Gennemgå licensen og start installationen';

$TXT['req_php_version']      = 'PHP-version >=';
$TXT['req_php_sessions']     = 'PHP-sessionsunderstøttelse';
$TXT['req_server_charset']   = 'Serverens standardtegnsæt';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Fil- &amp; mappe-tilladelser';
$TXT['file_perm_descr']      = 'Følgende stier skal være skrivbare for webserveren';

$TXT['lbl_website_title']    = 'Hjemmesidetitel';
$TXT['lbl_absolute_url']     = 'Absolut URL';
$TXT['lbl_timezone']         = 'Standardtidszone';
$TXT['lbl_language']         = 'Standardsprog';
$TXT['lbl_server_os']        = 'Serverens operativsystem';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Verdensskrivbare filrettigheder (777)';

$TXT['lbl_db_host']          = 'Værtsnavn (Host)';
$TXT['lbl_db_name']          = 'Databasenavn';
$TXT['lbl_db_prefix']        = 'Tabelpræfiks';
$TXT['lbl_db_user']          = 'Brugernavn';
$TXT['lbl_db_pass']          = 'Adgangskode';
$TXT['btn_test_db']          = 'Test forbindelse';
$TXT['db_testing']           = 'Forbinder…';
$TXT['db_retest']            = 'Test igen';

$TXT['lbl_admin_login']      = 'Login-navn';
$TXT['lbl_admin_email']      = 'E-mailadresse';
$TXT['lbl_admin_pass']       = 'Adgangskode';
$TXT['lbl_admin_repass']     = 'Gentag adgangskode';
$TXT['btn_gen_password']     = '⚙ Generer';
$TXT['pw_copy_hint']         = 'Kopier adgangskode';

$TXT['btn_install']          = '▶  Installer WBCE CMS';
$TXT['btn_check_settings']   = 'Tjek dine indstillinger i Trin 1 og genindlæs siden med F5';

$TXT['error_prefix']         = 'Fejl';
$TXT['version_prefix']       = 'WBCE-version';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP-sessionsunderstøttelse kan vises som deaktiveret, hvis din browser ikke understøtter cookies.';

$MSG['charset_warning'] =
    'Din webserver er konfigureret til kun at sende tegnsættet <b>%1$s</b>. '
    . 'For at vise nationale specialtegn korrekt, skal du deaktivere denne forudindstilling '
    . '(eller spørge din hostingudbyder). Du kan også vælge <b>%1$s</b> i WBCE-indstillingerne, '
    . 'men det kan påvirke output fra nogle moduler.';

$MSG['world_writeable_warning'] =
    'Anbefales kun til testmiljøer. '
    . 'Du kan ændre denne indstilling senere i administrationspanelet.';

$MSG['license_notice'] =
    'WBCE CMS udgives under licensen <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Ved at klikke på installationsknappen nedenfor bekræfter du, '
    . 'at du har læst og accepterer licensbetingelserne.';

// JS validation messages
$MSG['val_required']       = 'Dette felt er påkrævet.';
$MSG['val_url']            = 'Indtast venligst en gyldig URL (der starter med http:// eller https://).';
$MSG['val_email']          = 'Indtast venligst en gyldig e-mailadresse.';
$MSG['val_pw_mismatch']    = 'Adgangskoderne matcher ikke.';
$MSG['val_pw_short']       = 'Adgangskoden skal være mindst 12 tegn lang.';
$MSG['val_db_untested']    = 'Test venligst databaseforbindelsen med succes, før du installerer.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Udfyld venligst først værtsnavn, databasenavn og brugernavn.';
$MSG['db_pdo_missing']        = 'PDO-udvidelsen er ikke tilgængelig på denne server.';
$MSG['db_success']            = 'Forbindelse lykkedes: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Adgang nægtet. Kontroller brugernavn og adgangskode.';
$MSG['db_unknown_db']         = 'Databasen findes ikke. Opret den først eller kontroller navnet.';
$MSG['db_connection_refused'] = 'Kunne ikke oprette forbindelse til værten. Kontroller værtsnavn og port.';
$MSG['db_connection_failed']  = 'Forbindelse mislykkedes: %s';