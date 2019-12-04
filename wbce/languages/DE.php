<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 *
 * Made whith help of Automated Language File tool Copyright heimsath.org
 */

//no direct file access
if(count(get_included_files()) ==1){$z="HTTP/1.0 404 Not Found";header($z);die($z);}

// Set the language information
$language_code = 'DE';
$language_name = 'German'; // Deutsch
$language_version = '3.2';
$language_platform = '1.3.0';
$language_author = 'Stefan Braunewell, Matthias Gallas, Florian Meerwinck';
$language_license = 'GNU General Public License';


$MENU['ACCESS'] = 'Benutzerverwaltung';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Erweiterungen';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'Sie sind hier: ';
$MENU['FORGOT'] = 'Anmelde-Details anfordern';
$MENU['GROUP'] = 'Gruppe';
$MENU['GROUPS'] = 'Gruppen';
$MENU['HELP'] = 'Hilfe';
$MENU['LANGUAGES'] = 'Sprachen';
$MENU['LOGIN'] = 'Anmeldung';
$MENU['LOGOUT'] = 'Abmelden';
$MENU['MEDIA'] = 'Medien';
$MENU['MODULES'] = 'Module';
$MENU['PAGES'] = 'Seiten';
$MENU['PREFERENCES'] = 'Meine Daten';
$MENU['SETTINGS'] = 'Grundeinstellungen';
$MENU['START'] = 'Dashboard';
$MENU['TEMPLATES'] = 'Templates';
$MENU['USERS'] = 'Benutzer';
$MENU['VIEW'] = 'Ansicht';


$TEXT['ACCOUNT_SIGNUP'] = 'Registrierung';
$TEXT['ACTIONS'] = 'Aktionen';
$TEXT['ACTIVE'] = 'Aktiv';
$TEXT['ADD'] = 'Hinzufügen';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'Abschnitt hinzufügen';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Verwaltung';
$TEXT['ADMINISTRATION_TOOL'] = 'Verwaltungsprogramme';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Seite bearbeiten (Backend)';
$TEXT['ADVANCED'] = 'Erweitert';
$TEXT['ADVANCED_SEARCH'] = 'Erweiterte Suche';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Hochladbare Dateitypen';
$TEXT['ALLOWED_VIEWERS'] = 'Seite sehen (Frontend)';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Mehrfachauswahl';
$TEXT['ALL_WORDS'] = 'Alle Wörter';
$TEXT['ANCHOR'] = 'Anker';
$TEXT['ANONYMOUS'] = 'Anonym';
$TEXT['ANY_WORDS'] = 'Einzelne Wörter';
$TEXT['APP_NAME'] = 'Verwaltungswerkzeuge';
$TEXT['ARE_YOU_SURE'] = 'Aktion wirklich durchführen?';
$TEXT['AUTHOR'] = 'Autor';
$TEXT['BACK'] = 'Zurück';
$TEXT['BACKEND'] = 'Backend';
$TEXT['BACKUP'] = 'Sichern';
$TEXT['BACKUP_ALL_TABLES'] = 'komplette Datenbank sichern';
$TEXT['BACKUP_DATABASE'] = 'Datenbank sichern';
$TEXT['BACKUP_MEDIA'] = 'Dateien sichern';
$TEXT['BACKUP_WB_SPECIFIC'] = 'nur WBCE-Tabellen sichern';
$TEXT['BASIC'] = 'Einfach';
$TEXT['BLOCK'] = 'Block';
$TEXT['BUTTON_SEND_TESTMAIL'] = 'E-Mail-Konfiguration testen';
$TEXT['CALENDAR'] = 'Kalender';
$TEXT['CANCEL'] = 'Abbrechen';
$TEXT['CAN_DELETE_HIMSELF'] = 'Kann sich selber löschen';
$TEXT['CAPTCHA_VERIFICATION'] = 'Spam-Schutz';
$TEXT['CAP_EDIT_CSS'] = 'Stylesheet bearbeiten';
$TEXT['CHANGE'] = 'Ändern';
$TEXT['CHANGES'] = 'Änderungen';
$TEXT['CHANGE_SETTINGS'] = 'Einstellungen ändern';
$TEXT['CHARACTERS'] = 'Zeichen';
$TEXT['CHARSET'] = 'Zeichensatz';
$TEXT['CHECKBOX_GROUP'] = 'Kontrollkästchen';
$TEXT['CLOSE'] = 'Schließen';
$TEXT['CODE'] = 'Code';
$TEXT['CODE_SNIPPET'] = 'Snippet';
$TEXT['COLLAPSE'] = 'Ausblenden';
$TEXT['COMMENT'] = 'Kommentar';
$TEXT['COMMENTING'] = 'kommentieren';
$TEXT['COMMENTS'] = 'Kommentare';
$TEXT['CREATE_FOLDER'] = 'Ordner anlegen';
$TEXT['CURRENT'] = 'Aktuell';
$TEXT['CURRENT_FOLDER'] = 'Aktueller Ordner';
$TEXT['CURRENT_PAGE'] = 'Aktuelle Seite';
$TEXT['CURRENT_PASSWORD'] = 'Bisheriges Passwort';
$TEXT['CUSTOM'] = 'Benutzerdefiniert';
$TEXT['DATABASE'] = 'Datenbank';
$TEXT['DATE'] = 'Datum';
$TEXT['DATE_FORMAT'] = 'Datumsformat';
$TEXT['DEFAULT'] = 'Standard';
$TEXT['DEFAULT_CHARSET'] = 'Standard-Zeichensatz';
$TEXT['DEFAULT_TEXT'] = 'Standardtext';
$TEXT['DELETE'] = 'Löschen';
$TEXT['DELETED'] = 'Gelöscht';
$TEXT['DELETE_DATE'] = 'Datum löschen';
$TEXT['DELETE_ZIP'] = 'Zip-Archiv nach dem Entpacken löschen';
$TEXT['DESCRIPTION'] = 'Beschreibung';
$TEXT['DESIGNED_FOR'] = 'Entworfen für';
$TEXT['DIRECTORIES'] = 'Verzeichnisse';
$TEXT['DIRECTORY_MODE'] = 'Verzeichnismodus';
$TEXT['DISABLED'] = 'Deaktiviert';
$TEXT['DISPLAY_NAME'] = 'Angezeigter Name';
$TEXT['EMAIL'] = 'E-Mail';
$TEXT['EMAIL_ADDRESS'] = 'E-Mail-Adresse';
$TEXT['EMPTY_TRASH'] = 'Papierkorb leeren';
$TEXT['ENABLED'] = 'Aktiviert';
$TEXT['END'] = 'Ende';
$TEXT['ERROR'] = 'Fehler';
$TEXT['ERR_USE_SYSTEM_DEFAULT'] = 'Verwende Systemeinstellung (php.ini)';
$TEXT['ERR_HIDE_ERRORS_NOTICES'] = 'Fehler und Warnungen unterdrücken (WWW)';
$TEXT['ERR_SHOW_ERRORS_NOTICES'] = 'Fehler und Warnungen anzeigen (Entwicklung)';
$TEXT['ERR_SHOW_ERRORS_HIDE_NOTICES'] = 'Nur Fehler, keine Warnungen anzeigen';
$TEXT['EXACT_MATCH'] = 'Genaue Wortfolge';
$TEXT['EXECUTE'] = 'Ausführen';
$TEXT['EXPAND'] = 'Ausklappen';
$TEXT['EXTENSION'] = 'Erweiterung';
$TEXT['FIELD'] = 'Feld';
$TEXT['FILE'] = 'Datei';
$TEXT['FILENAME'] = 'Dateiname';
$TEXT['FILES'] = 'Dateien';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Zugriffsrechte';
$TEXT['FILE_MODE'] = 'Dateimodus';
$TEXT['FINISH_PUBLISHING'] = 'Ablaufdatum';
$TEXT['FOLDER'] = 'Ordner';
$TEXT['FOLDERS'] = 'Ordner';
$TEXT['FOOTER'] = 'Fußzeile';
$TEXT['FORGOTTEN_DETAILS'] = 'Haben Sie Ihre Zugangsdaten vergessen?';
$TEXT['FORGOT_DETAILS'] = 'Haben Sie Ihre Zugangsdaten vergessen?';
$TEXT['FROM'] = 'von';
$TEXT['FRONTEND'] = 'Frontend';
$TEXT['FULL_NAME'] = 'Vollständiger Name';
$TEXT['FUNCTION'] = 'Funktion';
$TEXT['GROUP'] = 'Gruppe';
$TEXT['HEADER'] = 'Kopfzeile';
$TEXT['HEADING'] = 'Überschrift';
$TEXT['HEADING_ADD_USER'] = 'Benutzer hinzufügen';
$TEXT['HEADING_MODIFY_USER'] = 'Benutzer bearbeiten';
$TEXT['HEADING_CSS_FILE'] = 'Aktuelle Moduldatei: ';
$TEXT['HEIGHT'] = 'Höhe';
$TEXT['HIDDEN'] = 'Versteckt';
$TEXT['HIDE'] = 'Verstecken';
$TEXT['HIDE_ADVANCED'] = 'Erweiterte Optionen ausblenden';
$TEXT['HOME'] = 'Home';
$TEXT['HOMEPAGE_REDIRECTION'] = 'URL-Umleitung zur ersten Seite';
$TEXT['HOME_FOLDER'] = 'Persönlicher Ordner';
$TEXT['HOME_FOLDERS'] = 'Persönliche Ordner';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Symbol';
$TEXT['IMAGE'] = 'Bild';
$TEXT['INLINE'] = 'Integriert';
$TEXT['INSTALL'] = 'Installieren';
$TEXT['INSTALLATION'] = 'Installation';
$TEXT['INSTALLATION_PATH'] = 'Installationspfad';
$TEXT['INSTALLATION_URL'] = 'Installationsadresse(URL)';
$TEXT['INSTALLED'] = 'installiert';
$TEXT['INTRO'] = 'Eingangs';
$TEXT['INTRO_PAGE'] = 'Vorschaltseite';
$TEXT['INVALID_SIGNS'] = 'muss mit einem Buchstaben beginnen oder beinhaltet unzulässige Zeichen';
$TEXT['KEYWORDS'] = 'Schlüsselwörter';
$TEXT['LANGUAGE'] = 'Sprache';
$TEXT['LAST_UPDATED_BY'] = 'zuletzt geändert von';
$TEXT['LENGTH'] = 'Länge';
$TEXT['LEVEL'] = 'Ebene';
$TEXT['LICENSE'] = 'Lizenz';
$TEXT['LINK'] = 'Link';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix-basierend';
$TEXT['LIST_OPTIONS'] = 'Auswahlliste';
$TEXT['LOGGED_IN'] = 'Angemeldet';
$TEXT['LOGIN'] = 'Anmeldung';
$TEXT['LONG'] = 'Lang';
$TEXT['LONG_TEXT'] = 'Langtext';
$TEXT['LOOP'] = 'Schleife';
$TEXT['MAIN'] = 'Hauptblock';
$TEXT['MAINTENANCE_ON'] = 'Wartung an';
$TEXT['MAINTENANCE_OFF'] = 'Wartung aus';
$TEXT['MANAGE'] = 'Manage';
$TEXT['MANAGE_GROUPS'] = 'Gruppen verwalten';
$TEXT['MANAGE_USERS'] = 'Benutzer verwalten';
$TEXT['MATCH'] = 'Übereinstimmung';
$TEXT['MATCHING'] = 'passende';
$TEXT['MAX_EXCERPT'] = 'Max. Anzahl Zitate';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Max. Eintragungen pro Stunde';
$TEXT['MEDIA_DIRECTORY'] = 'Medienverzeichnis';
$TEXT['MENU'] = 'Menü';
$TEXT['MENU_ICON_0'] = 'Menü-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menü-Icon mouseover';
$TEXT['MENU_TITLE'] = 'Menütitel';
$TEXT['MESSAGE'] = 'Nachricht';
$TEXT['MODIFY'] = 'Ändern';
$TEXT['MODIFY_CONTENT'] = 'Inhalt ändern';
$TEXT['MODIFY_SETTINGS'] = 'Einstellungen ändern';
$TEXT['MODULE_ORDER'] = 'Modulreihenfolge für die Suche';
$TEXT['MODULE_PERMISSIONS'] = 'Modulberechtigungen';
$TEXT['MORE'] = 'Mehr';
$TEXT['MOVE_DOWN'] = 'Abwärts verschieben';
$TEXT['MOVE_UP'] = 'Aufwärts verschieben';
$TEXT['MULTIPLE_MENUS'] = 'Mehrere Menüs';
$TEXT['MULTISELECT'] = 'Mehrfachauswahl';
$TEXT['NAME'] = 'Name';
$TEXT['NEED_CURRENT_PASSWORD'] = 'mit aktuellem Passwort bestätigen';
$TEXT['NEED_TO_LOGIN'] = 'Wollen Sie sich anmelden?';
$TEXT['NEW_PASSWORD'] = 'Neues Passwort';
$TEXT['NEW_WINDOW'] = 'Neues Fenster';
$TEXT['NEXT'] = 'nächste';
$TEXT['NEXT_PAGE'] = 'nächste Seite';
$TEXT['NO'] = 'Nein';
$TEXT['NONE'] = 'Keine';
$TEXT['NONE_FOUND'] = 'Nichts gefunden';
$TEXT['NOT_FOUND'] = 'Nicht gefunden';
$TEXT['NOT_INSTALLED'] = 'nicht installiert';
$TEXT['NO_IMAGE_SELECTED'] = 'Kein Bild ausgewählt';
$TEXT['NO_RESULTS'] = 'Keine Ergebnisse';
$TEXT['OF'] = 'von';
$TEXT['ON'] = 'am';
$TEXT['OPEN'] = 'öffnen';
$TEXT['OPTION'] = 'Option';
$TEXT['OTHERS'] = 'Alle';
$TEXT['OUT_OF'] = 'von';
$TEXT['OVERWRITE_EXISTING'] = 'Überschreibe gleichnamige';
$TEXT['PAGE'] = 'Seite';
$TEXT['PAGES_DIRECTORY'] = 'Seitenverzeichnis';
$TEXT['PAGES_PERMISSION'] = 'Seitenberechtigung';
$TEXT['PAGES_PERMISSIONS'] = 'Seitenerechtigungen';
$TEXT['PAGE_EXTENSION'] = 'Dateiendungen';
$TEXT['PAGE_ICON'] = 'Seitenbild';
$TEXT['PAGE_ICON_DIR'] = 'Verzeichnis für Seiten-/Menübilder';
$TEXT['PAGE_LANGUAGES'] = 'Mehrsprachige Webseite';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Limit der Seitenebenen';
$TEXT['PAGE_SPACER'] = 'Leerzeichen';
$TEXT['PAGE_TITLE'] = 'Seitentitel';
$TEXT['PAGE_TRASH'] = 'Papierkorb';
$TEXT['PARENT'] = 'Übergeordnete Seite';
$TEXT['PASSWORD'] = 'Passwort';
$TEXT['PATH'] = 'Pfad';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP-Fehlermeldungen';
$TEXT['PLEASE_LOGIN'] = 'Bitte anmelden';
$TEXT['PLEASE_SELECT'] = 'Bitte auswählen';
$TEXT['POST'] = 'Beitrag';
$TEXT['POSTS_PER_PAGE'] = 'Nachrichten pro Seite';
$TEXT['POST_FOOTER'] = 'Nachrichten-Fußzeile';
$TEXT['POST_HEADER'] = 'Nachrichten-Kopfzeile';
$TEXT['PREVIOUS'] = 'vorherige';
$TEXT['PREVIOUS_PAGE'] = 'vorherige Seite';
$TEXT['PRIVATE'] = 'Privat';
$TEXT['PRIVATE_VIEWERS'] = 'Dürfen private Seiten sehen';
$TEXT['PROFILES_EDIT'] = 'Profil ändern';
$TEXT['PUBLIC'] = 'öffentlich';
$TEXT['PUBL_END_DATE'] = 'Ablaufdatum';
$TEXT['PUBL_START_DATE'] = 'Startdatum';
$TEXT['QUICK_SEARCH_STRG_F'] = 'Drücken Sie <b>Strg + f</b> für die Schnellsuche oder benutzen Sie die';
$TEXT['RADIO_BUTTON_GROUP'] = 'Optionsfeld';
$TEXT['READ'] = 'Lesen';
$TEXT['READ_MORE'] = 'Weiterlesen';
$TEXT['REDIRECT_AFTER'] = 'Anzeigedauer für Hinweise';
$TEXT['REGISTERED'] = 'Registriert';
$TEXT['REGISTERED_VIEWERS'] = 'Darf Seiten sehen (Frontend)';
$TEXT['RELOAD'] = 'Neu laden';
$TEXT['REMAINING'] = 'verbleiben';
$TEXT['REMEMBER_ME'] = '';
$TEXT['RENAME'] = 'Umbenennen';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'Diese Dateitypen nicht hochladen';
$TEXT['REQUIRED'] = 'Erforderlich';
$TEXT['REQUIREMENT'] = 'Voraussetzung';
$TEXT['RESET'] = 'Zurücksetzen';
$TEXT['RESIZE'] = 'Größe ändern';
$TEXT['RESIZE_IMAGE_TO'] = 'Bildgröße verändern auf';
$TEXT['RESTORE'] = 'Wiederherstellen';
$TEXT['RESTORE_DATABASE'] = 'Datenbank wiederherstellen';
$TEXT['RESTORE_MEDIA'] = 'Dateien wiederherstellen';
$TEXT['RESULTS'] = 'Ergebnisse';
$TEXT['RESULTS_FOOTER'] = 'Ergebnisse-Fußzeile';
$TEXT['RESULTS_FOR'] = 'Ergebnisse für';
$TEXT['RESULTS_HEADER'] = 'Ergebnisse-Überschrift';
$TEXT['RESULTS_LOOP'] = 'Ergebnisse-Schleife';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Neues Passwort wiederholen';
$TEXT['RETYPE_PASSWORD'] = 'Neues Passwort wiederholen';
$TEXT['SAME_WINDOW'] = 'Gleiches Fenster';
$TEXT['SAVE'] = 'Speichern';
$TEXT['SEARCH'] = 'Suche';
$TEXT['SEARCHING'] = 'Suchen';
$TEXT['SECTION'] = 'Abschnitt';
$TEXT['SECTION_BLOCKS'] = 'Blöcke';
$TEXT['SEC_ANCHOR'] = 'Text Abschnitts-Anker';
$TEXT['SELECT_BOX'] = 'Auswahlliste';
$TEXT['SEND_DETAILS'] = 'Anmeldedaten senden';
$TEXT['SEND_TESTMAIL'] = 'Um zu prüfen, ob die E-Mail-Einstellungen korrekt sind, kann hier eine Test-E-Mail an die o.g. Adresse versendet werden. Bitte beachten, dass Änderungen an den Einstellungen zunächst gespeichert werden müssen.';
$TEXT['SEPARATE'] = 'Separat';
$TEXT['SEPERATOR'] = 'Trenner';
$TEXT['SERVER_EMAIL'] = 'Server-E-Mail';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Server-Betriebssystem';
$TEXT['SESSION_IDENTIFIER'] = 'Sitzungs-ID';
$TEXT['SETTINGS'] = 'Optionen';
$TEXT['SHORT'] = 'Kurz';
$TEXT['SHORT_TEXT'] = 'Kurztext';
$TEXT['SHOW'] = 'Zeigen';
$TEXT['SHOW_ADVANCED'] = 'Erweiterte Optionen anzeigen';
$TEXT['SIGNUP'] = 'Registrierung';
$TEXT['SIZE'] = 'Größe';
$TEXT['SMART_LOGIN'] = 'Intelligente Anmeldung';
$TEXT['START'] = 'Start';
$TEXT['START_PUBLISHING'] = 'Startdatum';
$TEXT['SUBJECT'] = 'Betreff';
$TEXT['SUBMISSIONS'] = 'Eintragungen';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Max. gespeicherte Eintragungen';
$TEXT['SUBMISSION_ID'] = 'Eintragungs-ID';
$TEXT['SUBMITTED'] = 'eingetragen';
$TEXT['SUCCESS'] = 'Erfolgreich';
$TEXT['SYSTEM_DEFAULT'] = 'Standardeinstellung';
$TEXT['SYSTEM_PERMISSIONS'] = 'Zugangsberechtigungen';
$TEXT['TABLE_PREFIX'] = 'Tabellenpräfix';
$TEXT['TARGET'] = 'Ziel';
$TEXT['TARGET_FOLDER'] = 'Zielordner';
$TEXT['TEMPLATE'] = 'Template';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Zugriffsrechte für Templates';
$TEXT['TEXT'] = 'Text';
$TEXT['TEXTAREA'] = 'Langtext';
$TEXT['TEXTFIELD'] = 'Kurztext';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['THEME_COPY_CURRENT'] = 'Backend-Theme kopieren';
$TEXT['THEME_NEW_NAME'] = 'Name des neuen Backend-Themes';
$TEXT['THEME_CURRENT'] = 'Aktuelles Backend-Theme';
$TEXT['THEME_START_COPY'] = 'Kopieren';
$TEXT['THEME_IMPORT_HTT'] = 'Templatedateien importieren';
$TEXT['THEME_SELECT_HTT'] = 'Templatedateien auswählen';
$TEXT['THEME_NOMORE_HTT'] = 'Keine weiteren vorhanden';
$TEXT['THEME_START_IMPORT'] = 'Importieren';
$TEXT['TIME'] = 'Zeit';
$TEXT['TIMEZONE'] = 'Zeitzone';
$TEXT['TIME_FORMAT'] = 'Zeitformat';
$TEXT['TIME_LIMIT'] = 'Zeitlimit zur Erstellung der Zitate pro Modul';
$TEXT['TITLE'] = 'Titel';
$TEXT['TO'] = 'zu';
$TEXT['TOP_FRAME'] = 'Frameset sprengen';
$TEXT['TRASH_EMPTIED'] = 'Papierkorb geleert';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Styleangaben bearbeiten';
$TEXT['TYPE'] = 'Art';
$TEXT['UNDER_CONSTRUCTION'] = 'In Bearbeitung';
$TEXT['UNINSTALL'] = 'Deinstallieren';
$TEXT['UNKNOWN'] = 'Unbekannt';
$TEXT['UNLIMITED'] = 'Unbegrenzt';
$TEXT['UNZIP_FILE'] = 'Zip-Archiv hochladen und entpacken';
$TEXT['UP'] = 'Aufwärts';
$TEXT['UPGRADE'] = 'Aktualisieren';
$TEXT['UPLOAD_FILES'] = 'Datei(en) übertragen';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Benutzer';
$TEXT['USERNAME'] = 'Benutzername';
$TEXT['USERS_ACTIVE'] = 'Benutzer ist aktiv';
$TEXT['USERS_CAN_SELFDELETE'] = 'Selbstlöschung möglich';
$TEXT['USERS_CHANGE_SETTINGS'] = 'Benutzer kann eigene Einstellungen ändern';
$TEXT['USERS_DELETED'] = 'Benutzer ist als gelöscht markiert';
$TEXT['USERS_FLAGS'] = 'Benutzerflags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'Benutzer kann erweitertes Profil anlegen';
$TEXT['VERIFICATION'] = 'Prüfziffer';
$TEXT['VERSION'] = 'Version';
$TEXT['VIEW'] = 'Ansicht';
$TEXT['VIEW_DELETED_PAGES'] = 'Gelöschte Seiten anschauen';
$TEXT['VIEW_DETAILS'] = 'Details';
$TEXT['VISIBILITY'] = 'Sichtbarkeit';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Standard-Absenderadresse';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Standard-Absendername';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Bitte geben Sie eine Standard-Absenderadresse und einen Absendernamen an. Als Absender-E-Mail-Adresse empfiehlt sich ein Format wie: <strong>existierendespostfach@ihredomain.tld</strong>. Die Standardwerte werden nur verwendet, wenn keine anderen Werte von WBCE CMS bzw. installierten Modulen gesetzt wurden.';
$TEXT['WBMAILER_FUNCTION'] = 'E-Mail-Versandmethode';
$TEXT['WBMAILER_NOTICE'] = '<strong>SMTP-Maileinstellungen:</strong><br />Die nachfolgenden Einstellungen müssen nur angepasst werden, wenn Sie E-Mails über <abbr title="Simple Mail Transfer Protocol">SMTP</abbr> verschicken wollen. Wenn Sie die Zugangsdaten nicht kennen oder Sie sich unsicher bei den Einstellungen sind, verwenden Sie die Methode sendmail (PHP).';
$TEXT['WBMAILER_PHP'] = 'sendmail (PHP)';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP-Authentifizierung';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'muss aktiviert werden, wenn SMTP-Authentifizierung erforderlich ist';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP-Host';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP-Passwort';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP-Loginname';
$TEXT['WEBSITE'] = 'Website';
$TEXT['WEBSITE_DESCRIPTION'] = 'Website-Beschreibung';
$TEXT['WEBSITE_FOOTER'] = 'Fußzeile';
$TEXT['WEBSITE_HEADER'] = 'Kopfzeile';
$TEXT['WEBSITE_KEYWORDS'] = 'Schlüsselwörter';
$TEXT['WEBSITE_TITLE'] = 'Website-Titel';
$TEXT['WELCOME_BACK'] = 'Willkommen zurück';
$TEXT['WIDTH'] = 'Breite';
$TEXT['WINDOW'] = 'Fenster';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Einstellungen für Schreibrechte';
$TEXT['WRITE'] = 'Schreiben';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG-Editor';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG-Stil';
$TEXT['YES'] = 'Ja';


$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On Voraussetzungen nicht erfüllt';
$HEADING['ADD_CHILD_PAGE'] = 'Unterseite hinzufügen';
$HEADING['ADD_GROUP'] = 'Gruppe hinzufügen';
$HEADING['ADD_GROUPS'] = 'Gruppen hinzufügen';
$HEADING['ADD_HEADING'] = 'Überschrift hinzufügen';
$HEADING['ADD_PAGE'] = 'Seite hinzufügen';
$HEADING['ADD_USER'] = 'Benutzer hinzufügen';
$HEADING['ADMINISTRATION_TOOLS'] = 'Admin-Tools';
$HEADING['BROWSE_MEDIA'] = 'Medienordner durchsuchen';
$HEADING['CREATE_FOLDER'] = 'Ordner erstellen';
$HEADING['DEFAULT_SETTINGS'] = 'Standardeinstellungen';
$HEADING['DELETED_PAGES'] = 'Gelöschte Seiten';
$HEADING['FILESYSTEM_SETTINGS'] = 'Dateisystemoptionen';
$HEADING['GENERAL_SETTINGS'] = 'Allgemeine Optionen';
$HEADING['INSTALL_LANGUAGE'] = 'Sprache hinzufügen';
$HEADING['INSTALL_MODULE'] = 'Modul installieren';
$HEADING['INSTALL_TEMPLATE'] = 'Template installieren';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Sprachdateien manuell ausführen';
$HEADING['INVOKE_MODULE_FILES'] = 'Moduldateien manuell ausführen';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Templatedateien manuell ausführen';
$HEADING['LANGUAGE_DETAILS'] = 'Details zur Sprache';
$HEADING['MANAGE_SECTIONS'] = 'Abschnitte verwalten';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Erweiterte Seiteneinstellungen ändern';
$HEADING['MODIFY_DELETE_GROUP'] = 'Gruppe ändern/löschen';
$HEADING['MODIFY_DELETE_PAGE'] = 'Seite ändern/löschen';
$HEADING['MODIFY_DELETE_USER'] = 'Benutzer ändern/löschen';
$HEADING['MODIFY_GROUP'] = 'Gruppe ändern';
$HEADING['MODIFY_GROUPS'] = 'Gruppen ändern';
$HEADING['MODIFY_INTRO_PAGE'] = 'Vorschaltseite ändern';
$HEADING['MODIFY_PAGE'] = 'Seite ändern';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Seiteneinstellungen ändern';
$HEADING['MODIFY_USER'] = 'Benutzer ändern';
$HEADING['MODULE_DETAILS'] = 'Details zum Modul';
$HEADING['MY_EMAIL'] = 'E-Mail-Adresse';
$HEADING['MY_PASSWORD'] = 'Passwort';
$HEADING['MY_SETTINGS'] = 'Einstellungen';
$HEADING['SEARCH_SETTINGS'] = 'Einstellungen für die Suche';
$HEADING['SERVER_SETTINGS'] = 'Servereinstellungen';
$HEADING['TEMPLATE_DETAILS'] = 'Details zum Template';
$HEADING['UNINSTALL_LANGUAGE'] = 'Sprache deinstallieren';
$HEADING['UNINSTALL_MODULE'] = 'Modul deinstallieren';
$HEADING['UNINSTALL_TEMPLATE'] = 'Template deinstallieren';
$HEADING['UPGRADE_LANGUAGE'] = 'Sprache registrieren/aktualisieren (Upgrade)';
$HEADING['UPLOAD_FILES'] = 'Datei(en) übertragen';
$HEADING['WBMAILER_SETTINGS'] = 'Maileinstellungen';
$HEADING['WBMAILER_CFG_OVERRIDE_HINT'] = '<b>BITTE BEACHTEN:</b> die unten aufgeführten '.$HEADING['WBMAILER_SETTINGS'].' werden momentan mit den Einstellungen aus der Datei <code>[WB_PATH]/include/PHPMailer/config_mail.php</code> überschrieben.<br />'
                                        . 'Um die HIER DRUNTER aufgeführten '.$HEADING['WBMAILER_SETTINGS'].' zu verwenden müssen die Einstellungen in der genannten Datei entfernt werden.';


$MESSAGE['ADDON_ERROR_RELOAD'] = 'Fehler beim Abgleich der Add-On-Informationen.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Sprachen erfolgreich geladen';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>ACHTUNG!</strong> Überspielen Sie Sprachdateien aus Sicherheitsgründen nur über FTP in den Ordner /languages/ und benutzen die Upgrade-Funktion zum Registrieren oder Aktualisieren.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Warnung: Eventuell vorhandene Datenbankeinträge eines Moduls gehen verloren.';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'Beim Hochladen oder Löschen von Modulen per FTP werden Modulfunktionen wie <code>install</code>, <code>upgrade</code> oder <code>uninstall</code> nicht automatisch ausgeführt. Solche Module funktionieren daher meist nicht richtig bzw. hinterlassen Datenbankeinträge beim Löschen per FTP.<br /><br /> Nachfolgend können die Modulfunktionen von per FTP hochgeladenen Modulen manuell ausgeführt werden.';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Warnung: Eventuell vorhandene Datenbankeinträge eines Moduls gehen verloren. Bitte nur bei Problemen mit per FTP hochgeladenen Modulen verwenden.';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Warnung: Eventuell vorhandene Datenbankeinträge eines Moduls gehen verloren.';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Module erfolgreich geladen';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = 'Überschreibe neuere Dateien';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Installation fehlgeschlagen. Ihr System erfüllt nicht alle Voraussetzungen für diese Erweiterung. Um diese Erweiterung nutzen zu können, müssen nachfolgende Updates durchgeführt werden.';
$MESSAGE['ADDON_RELOAD'] = 'Abgleich der Datenbank mit den Informationen aus den Add-On-Dateien (z.B. nach FTP Upload).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Templates erfolgreich geladen';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Ungenügende Zugangsberechtigungen';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Das Passwort kann nur einmal pro Stunde zurückgesetzt werden';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Das Passwort konnte nicht versendet werden, bitte kontaktieren Sie den Systemadministrator';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Die angegebene E-Mail-Adresse ist nicht registriert';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Bitte geben Sie Ihre E-Mail-Adresse an';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Ihr Benutzername und Ihr Passwort wurden an Ihre E-Mail-Adresse gesendet';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Kein aktiver Inhalt auf dieser Seite vorhanden';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Sie haben keine Leserechte für diese Inhalte';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Bereits installiert';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Kann im Zielverzeichnis nicht schreiben';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Bitte haben Sie etwas Geduld.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Deinstallation fehlgeschlagen';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_CORE_MODULES'] = 'Deinstallation verweigert!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Deinstallation nicht möglich: Erweiterung wird noch benutzt.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = 'Das {{type}} <strong>{{type_name}}</strong> kann nicht deinstalliert werden, weil es auf {{pages}} benutzt wird:';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'folgender Seite;folgenden Seiten';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Das Template <strong>{{name}}</strong> kann nicht deinstalliert werden, weil es als Standard-Template hinterlegt ist.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Das Template <strong>{{name}}</strong> kann nicht deinstalliert werden, weil es das derzeit verwendete Backend-Theme ist.';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Fehler beim Entpacken';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Die Datei kann nicht übertragen werden';
$MESSAGE['GENERIC_COMPARE'] = ' erfolgreich';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Fehler beim öffnen der Datei.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' fehlgeschlagen';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Bitte beachten Sie, dass Sie nur den folgenden Dateityp auswählen können:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Bitte beachten Sie, dass Sie nur folgende Dateitypen auswählen können:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Bitte alle Felder ausfüllen';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'Es wurde nichts ausgewählt.';
$MESSAGE['GENERIC_INSTALLED'] = 'Erfolgreich installiert';
$MESSAGE['GENERIC_INVALID'] = 'Die übertragene Datei ist ungültig';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Ungültige Installationsdatei. Bitte *.zip Format prüfen.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Ungültige Sprachdatei';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Ungültige Moduldatei';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Ungültige Templatedatei';
$MESSAGE['GENERIC_IN_USE'] = ' aber benutzt in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Fehlende Archivdatei!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'Das Modul ist nicht ordnungsgemäß installiert!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' nicht möglich';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Nicht installiert';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Aktualisierung nicht möglich';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Die Datenbanksicherung kann je nach Größe der Datenbank einige Minuten dauern.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Bitte versuchen Sie es später noch einmal ...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Sicherheitsverletzung! Zugriff wurde verweigert!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Sicherheitsverletzung! Das Speichern der Daten wurde verweigert!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Erfolgreich deinstalliert';
$MESSAGE['GENERIC_UPGRADED'] = 'Erfolgreich aktualisiert';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Versionsabgleich';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade erforderlich';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Momentan in Bearbeitung';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'Diese Seite ist wegen Wartungsarbeiten vorübergehend nicht erreichbar';
$MESSAGE['GROUP_HAS_MEMBERS'] = 'Die Gruppe hat noch Mitglieder';
$MESSAGE['GROUPS_ADDED'] = 'Die Gruppe wurde erfolgreich hinzugefügt';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Sind Sie sicher, dass Sie die ausgewählte Gruppe löschen möchten (Nur Gruppen ohne Benutzer können gelöscht werden)?';
$MESSAGE['GROUPS_DELETED'] = 'Die Gruppe wurde erfolgreich gelöscht';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Der Gruppenname wurde nicht angegeben';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Der Gruppenname existiert bereits';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Keine Gruppen gefunden';
$MESSAGE['GROUPS_SAVED'] = 'Die Gruppe wurde erfolgreich gespeichert';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Der Benutzername oder das Passwort ist nicht korrekt';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Bitte geben Sie unten Benutzername und Passwort ein';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Bitte geben Sie Ihr Passwort ein';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Das angegebene Passwort ist zu lang';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Das angegebene Passwort ist zu kurz';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Bitte geben Sie Ihren Benutzernamen ein';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Der angegebene Benutzername ist zu lang';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Der angegebene Benutzername ist zu kurz';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Sie haben keine Dateiendung angegeben';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Sie haben keinen neuen Namen angegeben';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Das ausgewählte Verzeichnis konnte nicht gelöscht werden';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Die ausgewählte Datei konnte nicht gelöscht werden';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Das Umbenennen war nicht erfolgreich';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Sind Sie sicher, dass Sie diese Datei bzw. dieses Verzeichnis löschen möchten?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Das Verzeichnis wurde gelöscht';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Die Datei wurde gelöscht';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Verzeichnis existiert nicht oder Zugriff verweigert.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Verzeichnis existiert nicht';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Der Verzeichnisname darf nicht ../ enthalten';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Ein Verzeichnis mit dem angegebenen Namen existiert bereits';
$MESSAGE['MEDIA_DIR_MADE'] = 'Das Verzeichnis wurde erfolgreich angelegt';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Das Verzeichnis konnte nicht angelegt werden';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Eine Datei mit dem angegebenen Namen existiert bereits';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Die Datei konnte nicht gefunden werden';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Der Name darf nicht ../ enthalten';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Der Dateiname index.php kann nicht verwendet werden';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'Es wurde keine Datei übertragen';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Verzeichnis ist leer';
$MESSAGE['MEDIA_RENAMED'] = 'Die Umbenennung ist erfolgt';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = 'Datei wurde übertragen';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Der Name des Zielverzeichnisses darf nicht ../ enthalten';
$MESSAGE['MEDIA_UPLOADED'] = 'Dateien wurden übertragen';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Dieses Formular wurde zu oft aufgerufen. Bitte versuchen Sie es in einer Stunde noch einmal.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Die eingegebene Prüfziffer ist nicht korrekt.';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Bitte folgende Angaben ergänzen';
$MESSAGE['PAGES_ADDED'] = 'Die Seite wurde erfolgreich hinzugefügt';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Seitenkopf erfolgreich hinzugefügt';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Bitte geben Sie einen Menütitel ein';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Bitte geben Sie einen Titel für die Seite ein';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Das Accessfile konnte im Seiten-Verzeichnis nicht angelegt werden (ungenügende Schreibrechte?)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Das Accessfile konnte im Seiten-Verzeichnis nicht gelöscht werden (ungenügende Schreibrechte?)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Bei der Zusammenstellung der Seite ist ein Fehler aufgetreten';
$MESSAGE['PAGES_DELETED'] = 'Die Seite wurde erfolgreich gelöscht';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Sind Sie sicher, dass Sie die ausgewählte Seite (und ggf. deren Unterseiten!) löschen möchten';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Sie dürfen diese Seite nicht ändern';
$MESSAGE['PAGES_INTRO_LINK'] = 'Vorschaltseite bearbeiten';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Die Vorschaltseite konnte nicht bearbeitet werden (ungenügende Schreibrechte?)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Vorschaltseite wurde erfolgreich gespeichert';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Die letzte Änderung wurde durchgeführt von';
$MESSAGE['PAGES_NOT_FOUND'] = 'Die Seite wurde nicht gefunden';
$MESSAGE['PAGES_NOT_SAVED'] = 'Fehler beim Speichern der Seite';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Eine Seite mit diesem Titel existiert bereits';
$MESSAGE['PAGES_REORDERED'] = 'Die Seite wurde erfolgreich neu zusammengestellt';
$MESSAGE['PAGES_RESTORED'] = 'Die Seite wurde erfolgreich wiederhergestellt';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Zurück zur Seitenverwaltung';
$MESSAGE['PAGES_SAVED'] = 'Die Seite wurde erfolgreich gespeichert';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Die Seiteneinstellungen wurden erfolgreich gespeichert';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Einstellungen für diesen Abschnitt erfolgreich gespeichert';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Das eingegebene Bestätigungs-Passwort ist nicht korrekt. <br /><b>Änderungen müssen mit dem aktuellen Passwort bestätigt werden</b>.';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Persönliche Daten wurden erfolgreich gespeichert';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'E-Mail-Einstellung geändert';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Es wurden unzulässige Zeichen für das Passwort verwendet';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Das Passwort wurde erfolgreich geändert';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'Änderung des Datensatzes ist fehlgeschlagen.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'Datensatz wurde erfolgreich aktualisiert.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Hinzufügen eines neuen Datensatzes ist fehlgeschlagen.';
$MESSAGE['RECORD_NEW_SAVED'] = 'Neuer Datensatz wurde erfolgreich hinzugefügt.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Bitte beachten Sie: Wenn Sie dieses Feld anklicken, gehen alle ungespeicherten Änderungen verloren';
$MESSAGE['SETTINGS_SAVED'] = 'Die Grundeinstellungen wurden erfolgreich gespeichert';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Konfigurationsdatei konnte nicht geöffnet werden';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Die Konfigurationsdatei konnte nicht geschrieben werden';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Bitte beachten Sie: Dies wird nur zu Testzwecken empfohlen';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
Es wurde ein neuer User regisriert.

Loginname: {LOGIN_NAME}
UserId: {LOGIN_ID}
E-Mail: {LOGIN_EMAIL}
IP-Adresse: {LOGIN_IP}
Anmeldedatum: {SIGNUP_DATE}

----------------------------------------
Diese E-Mail wurde automatisch erstellt!';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Hallo {LOGIN_DISPLAY_NAME},

Sie erhalten diese E-Mail, weil Sie ein neues Passwort angefordert haben.

Ihre neuen Anmeldedaten für {LOGIN_WEBSITE_TITLE} lauten:

Benutzername: {LOGIN_NAME}
Passwort: {LOGIN_PASSWORD}

Das bisherige Passwort wurde durch das neue Passwort ersetzt.
Dass bedeutet dass das alte nicht mehr gültig ist.
Sollten Sie Fragen oder Probleme mit den neuen Login Daten haben
sollten Sie das Webseiten-Team oder den Administrator von {LOGIN_WEBSITE_TITLE} kontaktieren.
Denken Sie daran den Browser Cache zu löschen bevor Sie das neue Paswort verwenden, um unerwartete Probleme zu vermeiden.

----------------------------------------
Diese E-Mail wurde automatisch erstellt!';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Hallo {LOGIN_DISPLAY_NAME},

Herzlich willkommen bei {LOGIN_WEBSITE_TITLE}

Ihre Anmeldedaten für {LOGIN_WEBSITE_TITLE} lauten:
Benutzername: {LOGIN_NAME}
Passwort: {LOGIN_PASSWORD}

Hinweis:
Sollten Sie diese Nachricht ohne ihr zutun bekommen haben, bitten wir Sie diese Nachricht zu löschen.

----------------------------------------
Diese E-Mail wurde automatisch erstellt!';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Ihre Anmeldedaten';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Bitte geben Sie Ihre E-Mail-Adresse an';
$MESSAGE['START_CURRENT_USER'] = 'Sie sind angemeldet als:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Das Installations-Verzeichnis "/install" existiert noch! Dies stellt ein Sicherheitsrisiko dar. Bitte löschen.';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Bitte die Datei "upgrade-script.php" löschen.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Willkommen im Backend Ihrer Website';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Bitte beachten Sie: Um ein neu installiertes Template zu verwenden, müssen Sie dieses bei den "Grundeinstellungen" bzw. "Seiteneinstellungen" auswählen';
$MESSAGE['TESTMAIL_SUCCESS'] = "Die Test-E-Mail wurde an <code>%s</code> versendet. Bitte Posteingang prüfen.";
$MESSAGE['TESTMAIL_FAILURE'] = "Die Test-E-Mail konnte nicht an <code>%s</code> versendet werden.<br />Bitte E-Mail-Einstellungen überprüfen und erneut versuchen.";
$MESSAGE['THEME_COPY_CURRENT'] = 'Das derzeitige Backend-Theme kopieren und unter einem neuem Namen abspeichern';
$MESSAGE['THEME_ALREADY_EXISTS'] = 'Neuer Theme-Name existiert bereits.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Theme-Name nicht zulässig';
$MESSAGE['THEME_DESTINATION_READONLY'] = 'Das Zielverzeichnis konnte nicht erstellt werden';
$MESSAGE['THEME_IMPORT_HTT'] = 'Zusätzliche Templatedatei(en) in das aktuelle Theme importieren.<br />Mit diesen Templates können die Default-Templates überschrieben werden.';
$MESSAGE['UPLOAD_ERR_OK'] = 'Die Datei wurde erfolgreich hochgeladen';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'Die Größe der hochgeladenen Datei überschreitet die PHP-Voreinstellung upload_max_filesize';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'Die Größe der hochgeladenen Datei überschreitet die maximale Dateigröße.';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'Die Datei wurde nur teilweise hochgeladen';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'Es wurde keine Datei hochgeladen';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Fehlender temporärer Ordner';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Konnte Datei nicht schreiben. Fehlende Schreibrechte.';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'Erweiterungsfehler';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Unbekannter Upload-Fehler';
$MESSAGE['USERS_ADDED'] = 'Der Benutzer wurde erfolgreich hinzugefügt';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Funktion abgelehnt, Sie können sich nicht selbst löschen!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'In die obigen Felder sollten nur Werte eingeben werden, wenn das Passwort geändert werden soll';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Sind Sie sicher, dass Sie den ausgewählten Benutzer löschen möchten?';
$MESSAGE['USERS_DELETED'] = 'Der Benutzer wurde erfolgreich gelöscht';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Die angegebene E-Mail-Adresse wird bereits verwendet';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Die angegebene E-Mail-Adresse ist ungültig';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Es wurden unzulässige Zeichen für den Benutzernamen verwendet';
$MESSAGE['USERS_NO_GROUP'] = 'Es wurde keine Gruppe ausgewählt';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Die eingegebenen Passworte stimmen nicht überein.'; 
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Das eingegebene Passwort ist zu kurz';
$MESSAGE['USERS_SAVED'] = 'Der Benutzer wurde erfolgreich gespeichert';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'Der angegebene Benutzername wird bereits verwendet';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'Der eingegebene Benutzername ist zu kurz';


$OVERVIEW['ADMINTOOLS'] = 'Zugriff auf die Admin-Tools...';
$OVERVIEW['GROUPS'] = 'Verwaltung von Gruppen und deren Zugangsberechtigungen...';
$OVERVIEW['HELP'] = 'Noch Fragen? Hier finden Sie Antworten';
$OVERVIEW['LANGUAGES'] = 'Sprachen verwalten...';
$OVERVIEW['MEDIA'] = 'Verwaltung der im Medienordner gespeicherten Dateien...';
$OVERVIEW['MODULES'] = 'Verwaltung der Module...';
$OVERVIEW['PAGES'] = 'Verwaltung Ihrer Seiten...';
$OVERVIEW['PREFERENCES'] = 'Ändern persönlicher Einstellungen wie E-Mail-Adresse, Passwort, usw....';
$OVERVIEW['SETTINGS'] = 'Ändern der Optionen für WBCE...';
$OVERVIEW['START'] = 'Backend-Einstiegsseite';
$OVERVIEW['TEMPLATES'] = 'Templates verwalten...';
$OVERVIEW['USERS'] = 'Verwaltung von Benutzern, die sich anmelden dürfen...';
$OVERVIEW['VIEW'] = 'Ansicht Ihrer Website in einem neuen Fenster...';

