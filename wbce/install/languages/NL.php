<?php 
/**
 * @file    NL.php
 * @brief   Dutch language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'NL';
$INFO['language_name'] = 'Nederlands';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Installatiewizard';
$TXT['welcome_heading']      = 'Installatiewizard';
$TXT['welcome_sub']          = 'Voltooi alle onderstaande stappen om de installatie te voltooien';

$TXT['step1_heading']        = 'Stap 1 — Systeemeisen';
$TXT['step1_desc']           = 'Controleren of uw server aan alle vereisten voldoet';
$TXT['step2_heading']        = 'Stap 2 — Website-instellingen';
$TXT['step2_desc']           = 'Configureer basiswebsiteparameters en locale';
$TXT['step3_heading']        = 'Stap 3 — Database';
$TXT['step3_desc']           = 'Voer uw MySQL / MariaDB verbindingsgegevens in';
$TXT['step4_heading']        = 'Stap 4 — Beheerdersaccount';
$TXT['step4_desc']           = 'Maak uw backend-loginreferenties aan';
$TXT['step5_heading']        = 'Stap 5 — WBCE CMS installeren';
$TXT['step5_desc']           = 'Bekijk de licentie en start de installatie';

$TXT['req_php_version']      = 'PHP-versie >=';
$TXT['req_php_sessions']     = 'PHP Session-ondersteuning';
$TXT['req_server_charset']   = 'Standaard tekenset van de server';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Bestands- &amp; maprechten';
$TXT['file_perm_descr']      = 'De volgende paden moeten beschrijfbaar zijn voor de webserver';
$TXT['hint_not_empty']       = 'Niet leeg!';
$TXT['wbce_already_installed'] = 'WBCE al geïnstalleerd?';

$TXT['lbl_website_title']    = 'Websitetitel';
$TXT['lbl_absolute_url']     = 'Absolute URL';
$TXT['lbl_timezone']         = 'Standaard tijdzone';
$TXT['lbl_language']         = 'Standaardtaal';
$TXT['lbl_server_os']        = 'Serverbesturingssysteem';
$TXT['lbl_linux']            = 'Linux / Unix-gebaseerd';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Wereld-beschrijfbare bestandsrechten (777)';

$TXT['lbl_db_host']          = 'Hostnaam';
$TXT['lbl_db_name']          = 'Databasenaam';
$TXT['lbl_db_prefix']        = 'Tabelvoorvoegsel';
$TXT['lbl_db_user']          = 'Gebruikersnaam';
$TXT['lbl_db_pass']          = 'Wachtwoord';
$TXT['btn_test_db']          = 'Verbinding testen';
$TXT['db_testing']           = 'Verbinden…';
$TXT['db_retest']            = 'Opnieuw testen';

$TXT['lbl_admin_login']      = 'Loginnaam';
$TXT['lbl_admin_email']      = 'E-mailadres';
$TXT['lbl_admin_pass']       = 'Wachtwoord';
$TXT['lbl_admin_repass']     = 'Herhaal wachtwoord';
$TXT['btn_gen_password']     = '⚙ Genereren';
$TXT['pw_copy_hint']         = 'Wachtwoord kopiëren';

$TXT['btn_install']          = '▶  WBCE CMS installeren';
$TXT['btn_check_settings']   = 'Controleer uw instellingen in Stap 1 en herlaad met F5';

$TXT['error_prefix']         = 'Fout';
$TXT['version_prefix']       = 'WBCE-versie';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';

// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP Session-ondersteuning kan uitgeschakeld lijken als uw browser geen cookies ondersteunt.';

$MSG['charset_warning'] =
    'Uw webserver is geconfigureerd om alleen de <b>%1$s</b>-tekenset te leveren. '
    . 'Om nationale speciale tekens correct weer te geven, schakelt u deze voorinstelling uit '
    . '(of vraag uw hostingprovider). U kunt ook <b>%1$s</b> selecteren in de WBCE-'
    . 'instellingen, hoewel dit de uitvoer van sommige modules kan beïnvloeden.';

$MSG['world_writeable_warning'] =
    'Alleen aanbevolen voor testomgevingen. '
    . 'U kunt deze instelling later in de Backend aanpassen.';

$MSG['license_notice'] =
    'WBCE CMS wordt uitgebracht onder de <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Door op de onderstaande installatieknop te klikken, bevestigt u '
    . 'dat u de licentievoorwaarden heeft gelezen en accepteert.';

// JS validation messages
$MSG['val_required']       = 'Dit veld is verplicht.';
$MSG['val_url']            = 'Voer een geldige URL in (beginnend met http:// of https://).';
$MSG['val_email']          = 'Voer een geldig e-mailadres in.';
$MSG['val_pw_mismatch']    = 'Wachtwoorden komen niet overeen.';
$MSG['val_pw_short']       = 'Wachtwoord moet minimaal 12 tekens lang zijn.';
$MSG['val_db_untested']    = 'Test de databaseverbinding eerst succesvol voordat u installeert.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Vul eerst host, databasenaam en gebruikersnaam in.';
$MSG['db_pdo_missing']        = 'PDO-extensie is niet beschikbaar op deze server.';
$MSG['db_success']            = 'Verbinding succesvol: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Toegang geweigerd. Controleer gebruikersnaam en wachtwoord.';
$MSG['db_unknown_db']         = 'Database bestaat niet. Maak deze eerst aan of controleer de naam.';
$MSG['db_connection_refused'] = 'Kon geen verbinding maken met de host. Controleer hostnaam en poort.';
$MSG['db_connection_failed']  = 'Verbinding mislukt: %s';

// ─── Streaming Progress Log (reduced & multilingual) ─────────────────────────
$TXT['log_writing_config']      = 'config.php schrijven';
$TXT['log_connecting_db']       = 'Verbinden met database';
$TXT['log_importing_sql']       = 'SQL-structuur & data importeren';
$TXT['log_writing_settings']    = 'Systeeminstellingen schrijven';
$TXT['log_creating_admin']      = 'Beheerdersaccount aanmaken';
$TXT['log_booting_framework']   = 'WBCE CMS-framework opstarten';
$TXT['log_installing_modules']  = 'Modules installeren';
$TXT['log_installing_templates']= 'Templates installeren';
$TXT['log_installing_languages']= 'Talen installeren';
$TXT['log_required_mod_missing']= 'Vereiste modules ontbreken: ';
$TXT['log_finalizing']          = 'Installatie afronden';

$TXT['log_done']                = '✓ Gereed';
$TXT['log_complete']            = '━━━ Installatie voltooid ━━━';
$TXT['log_failed']              = 'Installatie mislukt – zie fouten hierboven';

// ─── Keys for install_stream.js  ─────────────────────────────────────────────
$TXT['language']                = 'Taal';
$TXT['module']                  = 'Module';
$TXT['template']                = 'Template';
$TXT['progress_title']          = 'WBCE CMS installeren';
$TXT['progress_starting']       = 'Installatie starten…';
$TXT['progress_done']           = 'Installatie voltooid';
$TXT['progress_btn_installing'] = 'Bezig met installeren…';
$TXT['progress_success']        = 'Installatie voltooid!';
$TXT['progress_failed']         = 'Installatie mislukt — zie fouten hierboven.';
$TXT['progress_go_frontend']    = 'Ga naar Frontend';
$TXT['progress_go_admin']       = 'Ga naar Admin Login ›';
$TXT['progress_try_again']      = '↻ Opnieuw proberen';

// ─── Upgrade Script specific ─────────────────────────────────────────────────
$TXT['upgrade_heading']         = 'Database- & add-onmigratie';
$TXT['upgrade_subheading']      = 'Dit script wijzigt de database en vervangt bestanden.';
$TXT['current_version']         = 'Huidige versie';
$TXT['target_version']          = 'Doelversie';
$TXT['upgrade_warning']         = 'Het updatescript wijzigt de bestaande database en bestandsstructuur. Het is <strong>sterk aanbevolen</strong> om een handmatige back-up te maken van de map <tt>%s</tt> en de gehele database voordat u doorgaat.';
$TXT['upgrade_confirm']         = 'Ik bevestig dat ik een handmatige back-up heb gemaakt van de map <tt>%s</tt> en de database.';
$TXT['proceed_upgrade']         = 'Doorgaan met de upgrade';

$TXT['db_table_is_ready']       = 'Tabel `%s` is gereed';
$TXT['db_field_added_to_table'] = ' Veld `%s` is toegevoegd aan tabel `%s`';
$TXT['db_field_table_error']    = 'Veld `%s` in tabel `%s`: '; 
$TXT['db_field_already_in_table']= 'Veld `%s`.`%s` bestaat al — overgeslagen';
$TXT['module_already_configured']= 'Module `%s` al geconfigureerd — overgeslagen'; 

$TXT['update_in_progress']      = 'Update wordt uitgevoerd…';
$TXT['starting_update']         = 'Update starten…';
$TXT['update_complete']         = 'Update voltooid!';
$TXT['update_failed']           = 'Update had fouten — controleer het log hierboven.';
$TXT['run_again']               = '↻ Opnieuw uitvoeren';

$TXT['wbce_seems_installed']    = "WBCE CMS lijkt al geïnstalleerd te zijn.";
$TXT['ask_wbce_upgrade']        = "Wilt u WBCE CMS upgraden?";
$TXT['disclaimer']              = "<b>DISCLAIMER:</b> Het WBCE-update-script wordt verspreid in de hoop dat het nuttig zal zijn, maar ZONDER ENIGE GARANTIE; zelfs zonder de impliciete garantie van VERKOOPBAARHEID of GESCHIKTHEID VOOR EEN BEPAALD DOEL. U moet bevestigen dat een handmatige back-up van de map /pages (inclusief alle daarin opgenomen bestanden en submappen) en een back-up van de gehele WBCE-database is gemaakt voordat u kunt doorgaan.";

// class AddonManager — Minimal Precheck/Install Signal Strings (Dutch)

$SIGNAL['ADDON_PRECHECK_OK']       = '%s "%s": vereiste voldaan.';
$SIGNAL['ADDON_PRECHECK_FAILED']   = '%s "%s": vereiste niet voldaan — installatie afgebroken.';
$SIGNAL['ADDON_INSERTED_OK']       = '%s "%s" succesvol geïnstalleerd.';
$SIGNAL['ADDON_UPDATED_OK']        = '%s "%s" succesvol bijgewerkt.';
$SIGNAL['ADDON_ALREADY_CURRENT']   = '%s "%s" is al up-to-date.';
$SIGNAL['ADDON_SCRIPT_OK']         = '%s "%s": script succesvol uitgevoerd.';
$SIGNAL['ADDON_SCRIPT_NOT_FOUND']  = '%s "%s": geen script gevonden (overgeslagen).';
$SIGNAL['ADDON_SCRIPT_ERROR']      = '%s "%s": script gaf een foutmelding.';
$SIGNAL['ADDON_DB_ERROR']          = '%s "%s": databasefout opgetreden.';
$SIGNAL['ADDON_INFO_INVALID']      = '%s "%s": info.php ontbreekt of is ongeldig.';
$SIGNAL['ADDON_PATH_NOT_FOUND']    = '%s "%s" niet gevonden.';