<?php 
/**
 * @file    NO.php
 * @brief   Norwegian Bokmål language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'NO';
$INFO['language_name'] = 'Norsk Bokmål';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS installasjonsveiviser';
$TXT['welcome_heading']      = 'Installasjonsveiviser';
$TXT['welcome_sub']          = 'Fullfør alle trinnene nedenfor for å fullføre installasjonen';

$TXT['step1_heading']        = 'Trinn 1 — Systemkrav';
$TXT['step1_desc']           = 'Kontrollerer at serveren oppfyller alle forutsetninger';
$TXT['step2_heading']        = 'Trinn 2 — Nettstedsinnstillinger';
$TXT['step2_desc']           = 'Konfigurer grunnleggende nettstedsparametere og lokalitet';
$TXT['step3_heading']        = 'Trinn 3 — Database';
$TXT['step3_desc']           = 'Skriv inn tilkoblingsdetaljer for MySQL / MariaDB';
$TXT['step4_heading']        = 'Trinn 4 — Administrator-konto';
$TXT['step4_desc']           = 'Opprett innloggingsinformasjon for administrasjonspanelet';
$TXT['step5_heading']        = 'Trinn 5 — Installer WBCE CMS';
$TXT['step5_desc']           = 'Gjennomgå lisensen og start installasjonen';

$TXT['req_php_version']      = 'PHP-versjon >=';
$TXT['req_php_sessions']     = 'PHP-sesjonsstøtte';
$TXT['req_server_charset']   = 'Standard tegnsett på serveren';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Fil- og mappetillatelser';
$TXT['file_perm_descr']      = 'Følgende stier må være skrivbare for webserveren';
$TXT['hint_not_empty']       = 'Må fylles ut!';
$TXT['wbce_already_installed'] = 'Er WBCE allerede installert?';

$TXT['lbl_website_title']    = 'Nettstedstittel';
$TXT['lbl_absolute_url']     = 'Absolutt URL';
$TXT['lbl_timezone']         = 'Standard tidssone';
$TXT['lbl_language']         = 'Standardspråk';
$TXT['lbl_server_os']        = 'Serverens operativsystem';
$TXT['lbl_linux']            = 'Linux / Unix-basert';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Verdensskrivbare filtillatelser (777)';

$TXT['lbl_db_host']          = 'Vertsnavn';
$TXT['lbl_db_name']          = 'Databasenavn';
$TXT['lbl_db_prefix']        = 'Tabellprefiks';
$TXT['lbl_db_user']          = 'Brukernavn';
$TXT['lbl_db_pass']          = 'Passord';
$TXT['btn_test_db']          = 'Test tilkobling';
$TXT['db_testing']           = 'Kobler til…';
$TXT['db_retest']            = 'Test på nytt';

$TXT['lbl_admin_login']      = 'Innloggingsnavn';
$TXT['lbl_admin_email']      = 'E-postadresse';
$TXT['lbl_admin_pass']       = 'Passord';
$TXT['lbl_admin_repass']     = 'Gjenta passord';
$TXT['btn_gen_password']     = '⚙ Generer';
$TXT['pw_copy_hint']         = 'Kopier passord';

$TXT['btn_install']          = '▶  Installer WBCE CMS';
$TXT['btn_check_settings']   = 'Sjekk innstillingene i Trinn 1 og last siden på nytt med F5';

$TXT['error_prefix']         = 'Feil';
$TXT['version_prefix']       = 'WBCE-versjon';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';

// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP-sesjonsstøtte kan vises som deaktivert hvis nettleseren din ikke støtter informasjonskapsler.';

$MSG['charset_warning'] =
    'Webserveren din er konfigurert til å levere kun <b>%1$s</b>-tegnsett. '
    . 'For å vise nasjonale spesialtegn riktig, vennligst deaktiver denne forhåndsinnstillingen '
    . '(eller spør vertsleverandøren din). Du kan også velge <b>%1$s</b> i WBCE-innstillingene, '
    . 'selv om dette kan påvirke enkelte modulutdata.';

$MSG['world_writeable_warning'] =
    'Kun anbefalt for testmiljøer. '
    . 'Du kan endre denne innstillingen senere i administrasjonspanelet.';

$MSG['license_notice'] =
    'WBCE CMS er utgitt under <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Ved å klikke på installasjonsknappen nedenfor, bekrefter du '
    . 'at du har lest og aksepterer vilkårene i lisensen.';

// JS validation messages
$MSG['val_required']       = 'Dette feltet er obligatorisk.';
$MSG['val_url']            = 'Vennligst skriv inn en gyldig URL (som starter med http:// eller https://).';
$MSG['val_email']          = 'Vennligst skriv inn en gyldig e-postadresse.';
$MSG['val_pw_mismatch']    = 'Passordene er ikke like.';
$MSG['val_pw_short']       = 'Passordet må være minst 12 tegn langt.';
$MSG['val_db_untested']    = 'Vennligst test databaseforbindelsen vellykket før du installerer.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Vennligst fyll inn vertsnavn, databasenavn og brukernavn først.';
$MSG['db_pdo_missing']        = 'PDO-utvidelsen er ikke tilgjengelig på denne serveren.';
$MSG['db_success']            = 'Tilkobling vellykket: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Tilgang nektet. Sjekk brukernavn og passord.';
$MSG['db_unknown_db']         = 'Databasen finnes ikke. Opprett den først eller sjekk navnet.';
$MSG['db_connection_refused'] = 'Kunne ikke koble til verten. Sjekk vertsnavn og port.';
$MSG['db_connection_failed']  = 'Tilkobling mislyktes: %s';

// ─── Streaming Progress Log (reduced & multilingual) ─────────────────────────
$TXT['log_writing_config']      = 'Skriver config.php';
$TXT['log_connecting_db']       = 'Kobler til databasen';
$TXT['log_importing_sql']       = 'Importerer SQL-struktur og data';
$TXT['log_writing_settings']    = 'Skriver systeminnstillinger';
$TXT['log_creating_admin']      = 'Oppretter administratorkonto';
$TXT['log_booting_framework']   = 'Starter WBCE CMS-rammeverket';
$TXT['log_installing_modules']  = 'Installerer moduler';
$TXT['log_installing_templates']= 'Installerer maler';
$TXT['log_installing_languages']= 'Installerer språk';
$TXT['log_required_mod_missing']= 'Manglende nødvendige moduler: ';
$TXT['log_finalizing']          = 'Fullfører installasjonen';

$TXT['log_done']                = '✓ Ferdig';
$TXT['log_complete']            = '━━━ Installasjon fullført ━━━';
$TXT['log_failed']              = 'Installasjon mislyktes – se feilmeldinger ovenfor';

// ─── Keys for install_stream.js  ─────────────────────────────────────────────
$TXT['language']                = 'Språk';
$TXT['module']                  = 'Modul';
$TXT['template']                = 'Mal';
$TXT['progress_title']          = 'Installerer WBCE CMS';
$TXT['progress_starting']       = 'Starter installasjon…';
$TXT['progress_done']           = 'Installasjon fullført';
$TXT['progress_btn_installing'] = 'Installerer…';
$TXT['progress_success']        = 'Installasjon fullført!';
$TXT['progress_failed']         = 'Installasjon mislyktes — se feilmeldinger ovenfor.';
$TXT['progress_go_frontend']    = 'Gå til forsiden (Frontend)';
$TXT['progress_go_admin']       = 'Gå til admin-innlogging ›';
$TXT['progress_try_again']      = '↻ Prøv igjen';

// ─── Upgrade Script specific ─────────────────────────────────────────────────
$TXT['upgrade_heading']         = 'Database- og tilleggsmigrering';
$TXT['upgrade_subheading']      = 'Dette skriptet endrer databasen og erstatter filer.';
$TXT['current_version']         = 'Gjeldende versjon';
$TXT['target_version']          = 'Målversjon';
$TXT['upgrade_warning']         = 'Oppdateringsskriptet endrer den eksisterende databasen og filstrukturen. Det er <strong>sterkt anbefalt</strong> å opprette en manuell sikkerhetskopi av mappen <tt>%s</tt> og hele databasen før du fortsetter.';
$TXT['upgrade_confirm']         = 'Jeg bekrefter at jeg har opprettet en manuell sikkerhetskopi av mappen <tt>%s</tt> og databasen.';
$TXT['proceed_upgrade']         = 'Fortsett med oppgraderingen';

$TXT['db_table_is_ready']       = 'Tabell `%s` er klar';
$TXT['db_field_added_to_table'] = ' Felt `%s` ble lagt til i tabell `%s`';
$TXT['db_field_table_error']    = 'Felt `%s` i tabell `%s`: '; 
$TXT['db_field_already_in_table']= 'Felt `%s`.`%s` finnes allerede — hoppet over';
$TXT['module_already_configured']= 'Modul `%s` er allerede konfigurert — hoppet over'; 

$TXT['update_in_progress']      = 'Oppdatering pågår…';
$TXT['starting_update']         = 'Starter oppdatering…';
$TXT['update_complete']         = 'Oppdatering fullført!';
$TXT['update_failed']           = 'Oppdateringen hadde feil — sjekk loggen ovenfor.';
$TXT['run_again']               = '↻ Kjør på nytt';

$TXT['wbce_seems_installed']    = "WBCE CMS ser ut til å være installert allerede.";
$TXT['ask_wbce_upgrade']        = "Vil du oppgradere WBCE CMS?";
$TXT['disclaimer']              = "<b>ANSVARSFRASKRIVELSE:</b> WBCE-oppdateringsskriptet distribueres i håp om at det vil være nyttig, men UTEN NOEN GARANTI; selv uten den underforståtte garantien for SALGBARHET eller EGNETHET TIL ET BESTEMT FORMÅL. Du må bekrefte at en manuell sikkerhetskopi av mappen /pages (inkludert alle filer og undermapper i den) og en sikkerhetskopi av hele WBCE-databasen er opprettet før du kan fortsette.";