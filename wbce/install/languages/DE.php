<?php 
/**
 * @file    DE.php
 * @brief   German language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'DE';
$INFO['language_name'] = 'Deutsch';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Installationsassistent';
$TXT['welcome_heading']      = 'Installationsassistent';
$TXT['welcome_sub']          = 'Führe alle nachfolgenden Schritte aus, um die Installation abzuschließen';

$TXT['step1_heading']        = 'Schritt 1 — Systemvoraussetzungen';
$TXT['step1_desc']           = 'Überprüfung, ob dein Server alle Voraussetzungen erfüllt';
$TXT['step2_heading']        = 'Schritt 2 — Website-Einstellungen';
$TXT['step2_desc']           = 'Konfiguriere die grundlegenden Website-Parameter und die Locale';
$TXT['step3_heading']        = 'Schritt 3 — Datenbank';
$TXT['step3_desc']           = 'Gib deine MySQL / MariaDB-Verbindungsdaten ein';
$TXT['step4_heading']        = 'Schritt 4 — Administrator-Konto';
$TXT['step4_desc']           = 'Erstelle deine Backend-Zugangsdaten';
$TXT['step5_heading']        = 'Schritt 5 — WBCE CMS installieren';
$TXT['step5_desc']           = 'Prüfe die Lizenz und starte die Installation';

$TXT['req_php_version']      = 'PHP-Version >=';
$TXT['req_php_sessions']     = 'PHP Session-Unterstützung';
$TXT['req_server_charset']   = 'Standard-Zeichensatz des Servers';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Datei- &amp; Verzeichnis-Berechtigungen';
$TXT['file_perm_descr']      = 'Die folgenden Pfade müssen vom Webserver beschreibbar sein';
$TXT['hint_not_empty']       = 'Beschrieben!';
$TXT['wbce_already_installed'] = 'Ist WBCE bereits installiert?';

$TXT['lbl_website_title']    = 'Website-Titel';
$TXT['lbl_absolute_url']     = 'Absolute URL';
$TXT['lbl_timezone']         = 'Standard-Zeitzone';
$TXT['lbl_language']         = 'Standardsprache';
$TXT['lbl_server_os']        = 'Server-Betriebssystem';
$TXT['lbl_linux']            = 'Linux / Unix-basiert';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Weltweit beschreibbare Dateiberechtigungen (777)';

$TXT['lbl_db_host']          = 'Host-Name';
$TXT['lbl_db_name']          = 'Datenbank-Name';
$TXT['lbl_db_prefix']        = 'Tabellen-Präfix';
$TXT['lbl_db_user']          = 'Benutzername';
$TXT['lbl_db_pass']          = 'Passwort';
$TXT['btn_test_db']          = 'Verbindung testen';
$TXT['db_testing']           = 'Verbinde…';
$TXT['db_retest']            = 'Erneut testen';

$TXT['lbl_admin_login']      = 'Anmeldename';
$TXT['lbl_admin_email']      = 'E-Mail-Adresse';
$TXT['lbl_admin_pass']       = 'Passwort';
$TXT['lbl_admin_repass']     = 'Passwort wiederholen';
$TXT['btn_gen_password']     = '⚙ Generieren';
$TXT['pw_copy_hint']         = 'Passwort kopieren';

$TXT['btn_install']          = '▶  WBCE CMS installieren';
$TXT['btn_check_settings']   = 'Überprüfe deine Einstellungen in Schritt 1 und lade die Seite mit F5 neu';

$TXT['error_prefix']         = 'Fehler';
$TXT['version_prefix']       = 'WBCE Version';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'PHP Session-Unterstützung kann deaktiviert erscheinen, wenn dein Browser keine Cookies unterstützt.';

$MSG['charset_warning'] =
    'Dein Webserver ist so konfiguriert, dass er nur den Zeichensatz <b>%1$s</b> ausliefert. '
    . 'Um nationale Sonderzeichen korrekt darzustellen, deaktiviere bitte diese Voreinstellung '
    . '(oder frage deinen Hosting-Provider). Du kannst auch <b>%1$s</b> in den WBCE-Einstellungen '
    . 'auswählen, allerdings kann dies die Ausgabe einiger Module beeinflussen.';

$MSG['world_writeable_warning'] =
    'Nur für Testumgebungen empfohlen. '
    . 'Diese Einstellung kannst du später im Backend anpassen.';

$MSG['license_notice'] =
    'WBCE CMS wird unter der <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a> veröffentlicht. Mit dem Klick auf den Installations-Button bestätigst '
    . 'du, dass du die Lizenzbedingungen gelesen hast und akzeptierst.';

// JS validation messages
$MSG['val_required']       = 'Dieses Feld ist erforderlich.';
$MSG['val_url']            = 'Bitte gib eine gültige URL ein (beginnend mit http:// oder https://).';
$MSG['val_email']          = 'Bitte gib eine gültige E-Mail-Adresse ein.';
$MSG['val_pw_mismatch']    = 'Die Passwörter stimmen nicht überein.';
$MSG['val_pw_short']       = 'Das Passwort muss mindestens 12 Zeichen lang sein.';
$MSG['val_db_untested']    = 'Bitte teste die Datenbankverbindung erfolgreich, bevor du installierst.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Bitte fülle zuerst Host, Datenbankname und Benutzername aus.';
$MSG['db_pdo_missing']        = 'Die PDO-Erweiterung ist auf diesem Server nicht verfügbar.';
$MSG['db_success']            = 'Verbindung erfolgreich: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Zugriff verweigert. Bitte prüfe Benutzername und Passwort.';
$MSG['db_unknown_db']         = 'Die Datenbank existiert nicht. Bitte erstelle sie zuerst oder prüfe den Namen.';
$MSG['db_connection_refused'] = 'Verbindung zum Host fehlgeschlagen. Bitte prüfe Hostname und Port.';
$MSG['db_connection_failed']  = 'Verbindung fehlgeschlagen: %s';

// ─── Streaming Progress Log (reduced & multilingual) ─────────────────────────
$TXT['log_writing_config']      = 'Schreibe config.php';
$TXT['log_export_snapshot']     = 'Exportiere Konstants Schnappschuß';
$TXT['log_connecting_db']       = 'Verbinde mit Datenbank';
$TXT['log_importing_sql']       = 'Importiere SQL-Struktur & Daten';
$TXT['log_writing_settings']    = 'Schreibe Systemeinstellungen';
$TXT['log_creating_admin']      = 'Erstelle Administrator-Konto';
$TXT['log_booting_framework']   = 'Starte WBCE CMS Framework';
$TXT['log_installing_modules']  = 'Installiere Module';
$TXT['log_installing_templates']= 'Installiere Templates';
$TXT['log_installing_languages']= 'Installiere Sprachdateien';
$TXT['log_required_mod_missing']= 'Benötigte Module fehlen';
$TXT['log_finalizing']          = 'Schließe Installation ab';
$TXT['log_export_snapshot']     = 'Exportiere `var/sys_constants.php` Snapshot';

$TXT['log_done']                = '✓ erledigt';
$TXT['log_not_found']           = '✗ nicht gefunden';
$TXT['log_cannot_remove']       = '✗ konnte nicht entfernt werden';
$TXT['log_complete']            = '━━━━━━━━━ Installation abgeschlossen ━━━━━━━━━';
$TXT['log_failed']              = 'Installation fehlgeschlagen – siehe Fehler oben';

// ─── Keys for install_stream.js ─────────────────────────────────────────────
$TXT['language']                = 'Sprache';
$TXT['module']                  = 'Modul';
$TXT['template']                = 'Template';
$TXT['progress_title']          = 'WBCE CMS wird installiert';
$TXT['progress_starting']       = 'Installation wird gestartet…';
$TXT['progress_done']           = 'Installation abgeschlossen';
$TXT['progress_btn_installing'] = 'Installiere…';
$TXT['progress_success']        = 'Installation erfolgreich abgeschlossen!';
$TXT['progress_failed']         = 'Installation fehlgeschlagen – siehe Fehler oben.';
$TXT['progress_go_frontend']    = 'Zur Website (Frontend)';
$TXT['progress_go_admin']       = 'Zum Admin-Login ›';
$TXT['progress_try_again']      = '← Erneut versuchen';

// ─── Upgrade Script specific ─────────────────────────────────────────────────
$TXT['upgrade_heading']         = 'Datenbank- & Add-on Migration';
$TXT['upgrade_subheading']      = 'Dieses Skript verändert die Datenbank und ersetzt Dateien.';
$TXT['current_version']         = 'Aktuelle Version';
$TXT['target_version']          = 'Zielversion';
$TXT['upgrade_warning']         = 'Das Update-Skript verändert die bestehende Datenbank und Dateistruktur. Es wird <strong>dringend empfohlen</strong>, vorher ein manuelles Backup des <tt>%s</tt>-Ordners und der gesamten Datenbank zu erstellen.';
$TXT['upgrade_confirm']         = 'Ich bestätige, dass ich ein manuelles Backup des <tt>%s</tt>-Ordners und der Datenbank erstellt habe.';
$TXT['proceed_upgrade']         = 'Mit dem Upgrade fortfahren';
$TXT['db_table_is_ready']       = 'Tabelle `%s` ist vorbereitet';
$TXT['db_field_added_to_table'] = ' Spalte `%s` wurde zur `%s` Tabelle hinzugefügt';
$TXT['db_field_table_error']    = 'Spalte `%s` in Tabelle `%s` Tabelle: '; 
$TXT['db_field_already_in_table']= 'Spalte `%s`.`%s` bereits vorhanden - überspringe'; 
$TXT['module_already_configured']= 'Modul `%s` war bereits konfiguriert - überspringe'; 
$TXT['update_in_progress']      = 'Update wird durchgeführt…';
$TXT['starting_update']         = 'Update wird gestartet…';
$TXT['update_complete']         = 'Update erfolgreich abgeschlossen!';
$TXT['update_failed']           = 'Das Update enthielt Fehler – siehe Log oben.';
$TXT['run_again']               = '↻ Erneut ausführen';
$TXT['wbce_seems_installed']    = "Es scheint WBCE CMS ist auf diesem Server bereits installiert.";
$TXT['ask_wbce_upgrade']        = "Soll ein Upgrade durchgeführt werden?";
$TXT['goto_upgradescript']      = "Zum Upgrade-Skript";
$TXT['disclaimer']              = "<b>DISCLAIMER:</b>  Das WBCE-Update-Skript wird in der Hoffnung verteilt, dass es nützlich sein wird, jedoch OHNE JEGLICHE GEWÄHRLEISTUNG; auch ohne die implizite Gewährleistung der MARKTGÄNGIGKEIT oder der EIGNUNG FÜR EINEN BESTIMMTEN ZWECK. Es muss bestätigt werden, dass ein manueller Backup des Ordners /pages (einschließlich aller darin enthaltenen Dateien und Unterordner) sowie ein Backup der gesamten WBCE-Datenbank erstellt wurde, bevor Sie fortfahren können.";
$TXT['paths_removed']           = 'ENTFERNT: Dateien: %d | Verzeichnisse: %d';
$TXT['failed_to_remove']        = ' | Fehlgeschlagen: %d';

// class AddonManager — Nur die Precheck/Install-Signale
$SIGNAL['ADDON_PRECHECK_OK']       = '%s "%s": Voraussetzung erfüllt.';
$SIGNAL['ADDON_PRECHECK_FAILED']   = '%s "%s": Voraussetzung nicht erfüllt — Installation abgebrochen.';
$SIGNAL['ADDON_INSERTED_OK']       = '%s "%s" wurde erfolgreich installiert.';
$SIGNAL['ADDON_UPDATED_OK']        = '%s "%s" wurde erfolgreich aktualisiert.';
$SIGNAL['ADDON_ALREADY_CURRENT']   = '%s "%s" ist bereits auf dem aktuellen Stand.';
$SIGNAL['ADDON_SCRIPT_OK']         = '%s "%s": Skript erfolgreich ausgeführt.';
$SIGNAL['ADDON_SCRIPT_NOT_FOUND']  = '%s "%s": kein Skript gefunden (übersprungen).';
$SIGNAL['ADDON_SCRIPT_ERROR']      = '%s "%s": Skript meldete einen Fehler.';
$SIGNAL['ADDON_DB_ERROR']          = '%s "%s": Datenbankfehler aufgetreten.';
$SIGNAL['ADDON_INFO_INVALID']      = '%s "%s": info.php fehlt oder ist ungültig.';
$SIGNAL['ADDON_PATH_NOT_FOUND']    = '%s "%s" wurde nicht gefunden.';
