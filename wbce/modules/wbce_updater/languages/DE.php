<?php
/**
 * WBCE Update-Assistent - German Language File
 *
 * @category    module
 * @package     wbce_updater
 * @version     1.0.2
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) {
    exit('Direct access to this file is not allowed');
}

$LANG = [
    // General
    'TOOL_NAME' => 'WBCE Update-Assistent',
    'CURRENT_VERSION' => 'Installierte Version',

    // Backup Section
    'BACKUP_REQUIRED' => 'Backup erforderlich!',
    'BACKUP_BUTTON' => 'Backup Plus öffnen (neues Fenster)',
    'BACKUP_CONFIRMED' => 'Ich habe ein Backup erstellt und heruntergeladen',
    'BACKUP_PLUS_MISSING' => 'Backup Plus ist nicht installiert!',
    'INSTALL_BACKUP_PLUS' => 'Backup Plus installieren',
    'BACKUP_COMPLETE_QUESTION' => 'Haben Sie ein Backup erstellt und heruntergeladen?',

    // Updates Section
    'AVAILABLE_UPDATES' => 'Verfügbare Updates',
    'RECOMMENDED_UPDATE' => 'Empfohlenes Update',
    'OTHER_UPDATES' => 'Weitere verfügbare Updates',
    'CHECK_UPDATES' => 'Updates prüfen',
    'LOADING' => 'Lade Updates',
    'LOADING_DOWNLOAD' => 'Update wird heruntergeladen',
    'DOWNLOAD_PLEASE_WAIT' => 'Bitte warten Sie, dies kann einige Minuten dauern.',
    'LOADING_UPLOAD' => 'Datei wird hochgeladen & verarbeitet',
    'UPLOAD_PLEASE_WAIT' => 'Bitte warten Sie, dies kann einige Sekunden dauern.',
    'NO_UPDATES_AVAILABLE' => 'Keine Updates verfügbar',
    'UP_TO_DATE' => 'Ihre WBCE-Installation ist auf dem neuesten Stand.',
    'SHOW_ADDITIONAL_UPDATES' => 'Weitere Versionen anzeigen',
    'HIDE_ADDITIONAL_UPDATES' => 'Weitere Versionen ausblenden',
    'HIDDEN_UPDATES' => 'ausgeblendet',
    'CACHED_DATA_INFO' => 'Hinweis: Gecachte Daten werden angezeigt (Alter: %s). GitHub API war nicht erreichbar.',
    'GITHUB_TIMEOUT_HINT' => 'GitHub API antwortet nicht (Timeout). Bitte versuchen Sie es später erneut.',

    // Update Actions
    'DOWNLOAD_PREPARE' => 'Update herunterladen & vorbereiten',
    'START_UPDATE_NOW' => 'Update jetzt starten',
    'VIEW_DETAILS' => 'Details anzeigen',
    'RELEASED' => 'Veröffentlicht',

    // Risk Levels
    'RISK_PATCH' => 'Patch-Update (sicher)',
    'RISK_MINOR' => 'Minor-Update (Vorsicht)',
    'RISK_MAJOR' => 'Grosses Update (hohes Risiko)',

    // Manual Upload Section
    'MANUAL_UPLOAD_TITLE' => 'Manueller Upload',
    'MANUAL_UPLOAD_DESCRIPTION' => 'Laden Sie eine eigene WBCE ZIP-Datei hoch (z.B. Custom Build oder vorbereitetes Update-Paket)',
    'SELECT_ZIP_FILE' => 'ZIP-Datei auswählen',
    'UPLOAD_AND_PREPARE' => 'Hochladen & Vorbereiten',
    'UPLOAD_NOTE' => 'Hinweis: Die ZIP-Datei muss einen "wbce" Ordner mit der WBCE-Installation enthalten oder direkt die WBCE-Dateien beinhalten.',
    'MAX_UPLOAD_SIZE' => 'Max. Upload-Größe',
    'UPLOAD_SIZE_WARNING' => 'Warnung: Upload-Limit ist möglicherweise zu klein für WBCE-Updates!',
    'RECOMMENDED' => 'Empfohlen',
    'JUMP_TO_UPLOAD' => 'Zum manuellen Upload',

    // Upload Success/Error Messages
    'UPLOAD_SUCCESS_TITLE' => 'Upload erfolgreich!',
    'UPLOAD_FILES_PREPARED' => 'Folgende Dateien wurden vorbereitet:',
    'ERROR_NO_FILE_UPLOADED' => 'Keine Datei hochgeladen',
    'ERROR_UPLOAD_FAILED' => 'Upload fehlgeschlagen',
    'ERROR_INVALID_ZIP' => 'Ungültige ZIP-Datei',
    'ERROR_ZIP_TOO_LARGE' => 'ZIP-Datei ist zu groß',
    'ERROR_UPLOAD_PARTIAL' => 'Datei wurde nur teilweise hochgeladen',
    'ERROR_UPLOAD_NO_TMP_DIR' => 'Kein temporäres Verzeichnis verfügbar',
    'ERROR_UPLOAD_CANT_WRITE' => 'Schreiben auf Festplatte fehlgeschlagen',

    // Maintenance Mode
    'MAINTENANCE_MODE' => 'Wartungsmodus aktivieren (empfohlen)',
    'MAINTENANCE_ENABLED' => 'Wartungsmodus aktiviert',
    'MAINTENANCE_INFO' => 'Der Wartungsmodus wurde erfolgreich aktiviert. Die Website ist jetzt für normale Besucher nicht erreichbar (Administratoren können sich weiterhin anmelden).',
    'MAINTENANCE_DISABLE_INFO' => 'Nach dem Update: Deaktivieren Sie den Wartungsmodus über Admin-Tools → Maintenance Mode Switcher oder Backend → Einstellungen.',
    'MAINTENANCE_NOT_ACTIVATED' => 'Wartungsmodus konnte nicht aktiviert werden',
    'MAINTENANCE_MANUAL_INFO' => 'Aktivieren Sie den Wartungsmodus manuell über das Backend-Modul "Maintenance Mode Switcher" (Admin-Tools) oder Backend → Einstellungen.',
    'WARNING_NO_MAINTENANCE_TEMPLATE' => 'Keine Wartungsseiten-Vorlage gefunden. Installieren Sie das Maintenance Mode Switcher Modul oder erstellen Sie eine maintainance.tpl.php Datei.',
    'MAINTENANCE_ALREADY_ACTIVE' => 'Wartungsmodus bereits aktiv',
    'MAINTENANCE_ALREADY_ACTIVE_INFO' => 'Der Wartungsmodus war bereits eingeschaltet. Die Website ist für normale Besucher nicht erreichbar.',

    // Confirmations
    'CONFIRM_DOWNLOAD' => 'Möchten Sie das Update-Paket jetzt herunterladen und vorbereiten?',
    'CONFIRM_UPLOAD'   => 'Möchten Sie die ZIP-Datei jetzt hochladen und vorbereiten?',
    'CONFIRM_MINOR_UPDATE' => 'ACHTUNG: Dies ist ein Minor-Update! Es können Änderungen erforderlich sein. Haben Sie ein aktuelles Backup und möchten fortfahren?',
    'CONFIRM_MAJOR_UPDATE' => 'WARNUNG: Dies ist ein grosses Update (Major-Version oder mehrere Minor-Stufen)! Es können erhebliche Änderungen und Inkompatibilitäten auftreten. Bitte lesen Sie die Release-Notes sorgfältig und stellen Sie sicher, dass Sie ein vollständiges Backup haben. Fortfahren?',

    // Success Messages
    'SUCCESS_TITLE' => 'Update-Dateien erfolgreich heruntergeladen!',
    'SUCCESS_FILES_DOWNLOADED' => 'Folgende Dateien wurden im Root-Verzeichnis gespeichert:',
    'SUCCESS_UPDATE_PACKAGE' => 'Update-Paket',
    'SUCCESS_UPDATE_SCRIPT' => 'Update-Skript',
    'READY_TO_UPDATE' => 'Alles bereit für das Update!',
    'CLICK_BUTTON_TO_START' => 'Klicken Sie auf den Button unten, um das Update zu starten.',
    'OR_MANUAL' => 'Oder rufen Sie manuell auf',
    'BACK_TO_UPDATER' => 'Zurück zum Update-Assistenten',
    'WARNINGS_OCCURRED' => 'Warnungen während der Vorbereitung',

    // Error Messages
    'ERROR_TITLE' => 'Fehler beim Herunterladen',
    'ERROR_OCCURRED' => 'Folgende Fehler sind aufgetreten:',
    'ERROR_FTAN' => 'Sicherheitsüberprüfung fehlgeschlagen',
    'ERROR_NO_URL' => 'Keine Download-URL angegeben',
    'ERROR_BACKUP_NOT_CONFIRMED' => 'Bitte bestätigen Sie, dass Sie ein Backup erstellt haben!',
    'ERROR_NO_WRITE_PERMISSION' => 'Keine Schreibrechte im Root-Verzeichnis! Bitte prüfen Sie die Dateiberechtigungen.',
    'ERROR_DOWNLOAD_FAILED' => 'Download des Update-Pakets fehlgeschlagen',
    'ERROR_SAVE_FAILED' => 'Speichern des Update-Pakets fehlgeschlagen',
    'ERROR_REPACK_FAILED' => 'ZIP-Umpacken fehlgeschlagen',
    'ERROR_UNZIP_DOWNLOAD_FAILED' => 'Download des Update-Skripts fehlgeschlagen',
    'ERROR_UNZIP_SAVE_FAILED' => 'Speichern des Update-Skripts fehlgeschlagen',
    'ERROR_CONFIG_READ_FAILED' => 'Konnte config.php nicht lesen',
    'ERROR_CONFIG_WRITE_FAILED' => 'Konnte config.php nicht schreiben',
    'ERROR_LOADING_UPDATES' => 'Fehler beim Laden der Updates',
    'WARNING_MAINTENANCE_FAILED' => 'Wartungsmodus konnte nicht aktiviert werden',

    // PHP Compatibility
    'PHP_COMPATIBLE' => 'PHP kompatibel',
    'PHP_INCOMPATIBLE' => 'PHP nicht kompatibel',
    'PHP_EOL_WARNING' => 'Ihre PHP-Version ist End-of-Life',
    'PHP_CURRENT' => 'Aktuelle PHP-Version',
    'PHP_REQUIRED' => 'Benötigte PHP-Version',
    'PHP_RECOMMENDED' => 'Empfohlene PHP-Version',
    'CONFIRM_PHP_INCOMPATIBLE' => 'WARNUNG: Ihre PHP-Version (%s) ist NICHT mit WBCE %s kompatibel!

Benötigt: PHP %s - %s
Empfohlen: PHP %s

Das Update kann zu Fehlern führen. Bitte aktualisieren Sie zuerst PHP.

Trotzdem fortfahren?',

    // Backup Detection
    'BACKUP_FOUND_HINT' => 'Backup gefunden im /backups-Verzeichnis:',
    'BACKUP_FOUND_MULTIPLE' => 'Backups gefunden im /backups-Verzeichnis (neuestes:',
    'BACKUP_FOUND_TODAY' => 'heute',
    'BACKUP_FOUND_DAYS_AGO' => 'vor %d Tag(en)',

    // Custom Source
    'CUSTOM_SOURCE_TITLE' => 'Eigene Update-Quelle',
    'CUSTOM_SOURCE_CONFIGURED' => 'Eine eigene Update-Quelle ist konfiguriert:',
    'CUSTOM_SOURCE_BUTTON' => 'Update von eigener Quelle laden',
    'CUSTOM_SOURCE_WARNING' => "ACHTUNG: Sie verwenden eine NICHT offizielle Update-Quelle!\n\nQuelle: %s\n\nDas Paket wurde NICHT von der WBCE Community geprüft. Sie sind selbst für die Sicherheit und Korrektheit des Update-Pakets verantwortlich.\n\nFortfahren?",
    'CUSTOM_SOURCE_CONFIRM' => 'Möchten Sie das Update-Paket jetzt von der eigenen Quelle herunterladen und vorbereiten?',

    // Tool Disabled
    'TOOL_DISABLED' => 'Das Update-Tool ist deaktiviert.',
    'TOOL_DISABLED_INFO' => 'Setzen Sie in user_config.php die Variable $wbce_updater_disabled auf false, um das Tool wieder zu aktivieren.',

    // Checksums
    'CHECKSUM_VALIDATED' => 'Download erfolgreich validiert',
    'ERROR_CHECKSUM_MISMATCH' => 'Checksumme stimmt nicht überein! Download möglicherweise beschädigt oder manipuliert.',
    'WARNING_NO_CHECKSUM' => 'Keine Checksumme verfügbar - Download kann nicht validiert werden',
    'WARNING_CHECKSUM_DISABLED' => 'WARNUNG: Checksum-Verifizierung ist deaktiviert. Die Integrität der heruntergeladenen Datei kann nicht garantiert werden.',
    'WARNING_CHECKSUM_DISABLED_MANUAL' => 'HINWEIS: Automatische Checksum-Verifizierung ist deaktiviert. Bitte prüfen Sie die Checksum manuell!',
    'CHECKSUM_INFO' => 'SHA256 Checksumme',
    'CHECKSUM_VERIFY_INFO' => 'Vergleichen Sie diese Checksumme mit der offiziellen Release-Checksumme, bevor Sie fortfahren.',

    // Upload Validation
    'ERROR_ZIP_ONLY'          => 'Nur ZIP-Dateien erlaubt. Hochgeladene Datei: %s',
    'ERROR_INVALID_MIME_TYPE' => 'Ungültiger Dateityp. Nur ZIP-Dateien erlaubt. Erkannter Typ: %s',
    'ERROR_FILE_TOO_LARGE_MB' => 'Datei zu groß. Maximal erlaubt: %s MB',

    // Execute Update – Schrittbezeichnungen
    'EXEC_TITLE'              => 'Update-Durchführung',
    'EXEC_STEP1'              => 'Schritt 1: PHP-Kompatibilität prüfen...',
    'EXEC_STEP2'              => 'Schritt 2: Update-Paket prüfen...',
    'EXEC_STEP3'              => 'Schritt 3: Update-Paket entpacken...',
    'EXEC_STEP4'              => 'Schritt 4: WBCE Update-Script prüfen...',
    'EXEC_STEP5'              => 'Schritt 5: Cleanup...',

    // Execute Update – PHP-Kompatibilität
    'EXEC_PHP_SKIPPED'        => 'Keine Zielversion angegeben, PHP-Check übersprungen',
    'EXEC_PHP_CANNOT_CHECK'   => 'PHP-Kompatibilität kann nicht geprüft werden: %s – Update wird fortgesetzt.',
    'EXEC_PHP_INCOMPAT'       => 'WARNUNG: PHP-Inkompatibilität erkannt!',
    'EXEC_PHP_CURRENT'        => 'Aktuelle PHP-Version:',
    'EXEC_PHP_REQUIRED_FOR'   => 'Benötigt für WBCE %s:',
    'EXEC_PHP_RECOMMENDED'    => 'Empfohlen:',
    'EXEC_PHP_CONTINUE_HINT'  => 'Hinweis: Update wird trotzdem durchgeführt. Bitte ändern Sie die PHP-Version nach dem Update!',
    'EXEC_PHP_COMPATIBLE_MSG' => 'PHP %s ist kompatibel mit WBCE %s',
    'EXEC_PHP_COMPAT_WARN'    => 'PHP %s ist nicht kompatibel mit WBCE %s (benötigt: %s – %s). Bitte PHP-Version nach dem Update ändern!',

    // Execute Update – Paket & Script
    'EXEC_ZIP_FOUND'          => 'wbceup.zip gefunden (%s MB)',
    'EXEC_ZIP_MISSING'        => 'Update-Paket (wbceup.zip) nicht gefunden!',
    'EXEC_SCRIPT_FOUND'       => 'install/update.php gefunden',
    'EXEC_SCRIPT_MISSING'     => 'WBCE Update-Script (install/update.php) nicht gefunden!',
    'EXEC_ZIP_DELETED'        => 'wbceup.zip gelöscht',
    'EXEC_ZIP_DELETE_FAILED'  => 'wbceup.zip konnte nicht gelöscht werden (nicht kritisch)',
    'EXEC_FILES_EXTRACTED'    => '%d Dateien sicher nach %s entpackt',
    'EXEC_EXTRACT_FAILED'     => 'Entpacken fehlgeschlagen: %s',
    'EXEC_ZIP_OPEN_FAILED'    => 'ZIP-Archiv konnte nicht geöffnet werden. Bitte überprüfen Sie die Datei.',
    'EXEC_DIR_RESOLVE_ERROR'  => 'Zielverzeichnis konnte nicht aufgelöst werden',
    'EXEC_SEC_BAD_PATH'       => 'Sicherheitswarnung: Ungültiger Dateipfad im ZIP erkannt: %s',
    'EXEC_SEC_ABS_PATH'       => 'Sicherheitswarnung: Absoluter Pfad im ZIP erkannt: %s',
    'EXEC_SEC_TRAVERSAL'      => 'Sicherheitswarnung: Pfad-Traversal außerhalb des Zielverzeichnisses erkannt',

    // Execute Update – Ergebnisseite
    'EXEC_SUCCESS_TITLE'       => 'Update-Paket erfolgreich entpackt!',
    'EXEC_WARNINGS_TITLE'      => 'Wichtige Warnungen:',
    'EXEC_NEXT_STEP_TITLE'     => 'Nächster Schritt:',
    'EXEC_NEXT_STEP_INFO'      => 'Das WBCE Update-Script ist bereit. Klicken Sie auf den Button unten, um den Update-Prozess zu starten.',
    'EXEC_WINDOW_HINT'         => 'Wichtig: Der Update-Prozess kann einige Minuten dauern. Schließen Sie das Fenster nicht!',
    'EXEC_PHP_CHANGE_REMINDER' => 'Nach dem Update: Ändern Sie die PHP-Version auf Ihrem Server!',
    'EXEC_START_UPDATE_BTN'    => 'WBCE Update jetzt starten',
    'EXEC_ERROR_TITLE'         => 'Fehler beim Update!',
];
