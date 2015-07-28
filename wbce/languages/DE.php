<?php
/**
 *
 * @category        framework
 * @package         language
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.website baker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: DE.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/DE.php $
 * @lastmodified    $Date: 2012-03-09 15:30:29 +0100 (Fr, 09. Mrz 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Define that this file is loaded
if(!defined('LANGUAGE_LOADED'))
{
	define('LANGUAGE_LOADED', true);
}

// Set the language information
$language_code = 'DE';
$language_name = 'Deutsch';
$language_version = '3.0';
$language_platform = '2.9';
$language_author = 'Stefan Braunewell, Matthias Gallas';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Benutzerverwaltung';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Erweiterungen';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'Sie sind hier: ';
$MENU['FORGOT'] = 'Anmelde-Details anfordern';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Gruppen';
$MENU['HELP'] = 'Hilfe';
$MENU['LANGUAGES'] = 'Sprachen';
$MENU['LOGIN'] = 'Anmeldung';
$MENU['LOGOUT'] = 'Abmelden';
$MENU['MEDIA'] = 'Medien';
$MENU['MODULES'] = 'Module';
$MENU['PAGES'] = 'Seiten';
$MENU['PREFERENCES'] = 'Einstellungen';
$MENU['SETTINGS'] = 'Optionen';
$MENU['START'] = 'Start';
$MENU['TEMPLATES'] = 'Designvorlagen';
$MENU['USERS'] = 'Benutzer';
$MENU['VIEW'] = 'Ansicht';
$TEXT['ACCOUNT_SIGNUP'] = 'Registrierung';
$TEXT['ACTIONS'] = 'Aktionen';
$TEXT['ACTIVE'] = 'Aktiv';
$TEXT['ADD'] = 'Hinzuf&uuml;gen';
$TEXT['ADDON'] = 'Addon';
$TEXT['ADD_SECTION'] = 'Abschnitt hinzuf&uuml;gen';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Verwaltung';
$TEXT['ADMINISTRATION_TOOL'] = 'Verwaltungsprogramme';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administratoren';
$TEXT['ADVANCED'] = 'Erweitert';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Erlaubte Dateitypen f&uuml;r Hochladen';
$TEXT['ALLOWED_VIEWERS'] = 'genehmigter Besucher';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Mehrfachauswahl';
$TEXT['ALL_WORDS'] = 'Alle W&ouml;rter';
$TEXT['ANCHOR'] = 'Anker';
$TEXT['ANONYMOUS'] = 'Anonym';
$TEXT['ANY_WORDS'] = 'Einzelne W&ouml;rter';
$TEXT['APP_NAME'] = 'Verwaltungswerkzeuge';
$TEXT['ARE_YOU_SURE'] = 'Sind Sie sicher?';
$TEXT['AUTHOR'] = 'Autor';
$TEXT['BACK'] = 'Zur&uuml;ck';
$TEXT['BACKUP'] = 'Sichern';
$TEXT['BACKUP_ALL_TABLES'] = 'komplette Datenbank sichern';
$TEXT['BACKUP_DATABASE'] = 'Datenbank sichern';
$TEXT['BACKUP_MEDIA'] = 'Dateien sichern';
$TEXT['BACKUP_WB_SPECIFIC'] = 'nur WB Tabellen sichern';
$TEXT['BASIC'] = 'Einfach';
$TEXT['BLOCK'] = 'Block';
$TEXT['CALENDAR'] = 'Kalender';
$TEXT['CANCEL'] = 'Abbrechen';
$TEXT['CAN_DELETE_HIMSELF'] = 'Kann sich selber l&ouml;schen';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha Pr&uuml;fziffer';
$TEXT['CAP_EDIT_CSS'] = 'Bearbeite CSS';
$TEXT['CHANGE'] = '&Auml;ndern';
$TEXT['CHANGES'] = '&Auml;nderungen';
$TEXT['CHANGE_SETTINGS'] = 'Einstellungen &auml;ndern';
$TEXT['CHARSET'] = 'Zeichensatz';
$TEXT['CHECKBOX_GROUP'] = 'Kontrollk&auml;stchen';
$TEXT['CLOSE'] = 'Schlie&szlig;en';
$TEXT['CODE'] = 'Code';
$TEXT['CODE_SNIPPET'] = 'Funktionserweiterung';
$TEXT['COLLAPSE'] = 'Reduzieren';
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
$TEXT['DEFAULT_CHARSET'] = 'Standard Zeichensatz';
$TEXT['DEFAULT_TEXT'] = 'Standardtext';
$TEXT['DELETE'] = 'Entfernen';
$TEXT['DELETED'] = 'Gel&ouml;scht';
$TEXT['DELETE_DATE'] = 'Datum l&ouml;schen';
$TEXT['DELETE_ZIP'] = 'Zip-Archiv nach dem entpacken l&ouml;schen';
$TEXT['DESCRIPTION'] = 'Beschreibung';
$TEXT['DESIGNED_FOR'] = 'Entworfen f&uuml;r';
$TEXT['DIRECTORIES'] = 'Verzeichnisse';
$TEXT['DIRECTORY_MODE'] = 'Verzeichnismodus';
$TEXT['DISABLED'] = 'Ausgeschaltet';
$TEXT['DISPLAY_NAME'] = 'Angezeigter Name';
$TEXT['EMAIL'] = 'E-Mail';
$TEXT['EMAIL_ADDRESS'] = 'E-Mail Adresse';
$TEXT['EMPTY_TRASH'] = 'M&uuml;lleimer leeren';
$TEXT['ENABLED'] = 'Eingeschaltet';
$TEXT['END'] = 'Ende';
$TEXT['ERROR'] = 'Fehler';
$TEXT['EXACT_MATCH'] = 'Genaue Wortfolge';
$TEXT['EXECUTE'] = 'Ausf&uuml;hren';
$TEXT['EXPAND'] = 'Erweitern';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['FIELD'] = 'Feld';
$TEXT['FILE'] = 'Datei';
$TEXT['FILES'] = 'Dateien';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Zugriffsrechte';
$TEXT['FILE_MODE'] = 'Dateimodus';
$TEXT['FINISH_PUBLISHING'] = 'Ende der Ver&ouml;ffentlichung';
$TEXT['FOLDER'] = 'Ordner';
$TEXT['FOLDERS'] = 'Ordner';
$TEXT['FOOTER'] = 'Fu&szlig;zeile';
$TEXT['FORGOTTEN_DETAILS'] = 'Haben Sie Ihre pers&ouml;nlichen Daten vergessen?';
$TEXT['FORGOT_DETAILS'] = 'Haben Sie Ihre pers&ouml;nlichen Daten vergessen?';
$TEXT['FROM'] = 'von';
$TEXT['FRONTEND'] = 'Frontend';
$TEXT['FULL_NAME'] = 'Voller Name';
$TEXT['FUNCTION'] = 'Funktion';
$TEXT['GROUP'] = 'Gruppe';
$TEXT['HEADER'] = 'Kopfzeile';
$TEXT['HEADING'] = '&Uuml;berschrift';
$TEXT['HEADING_CSS_FILE'] = 'Aktuelle Moduldatei: ';
$TEXT['HEIGHT'] = 'H&ouml;he';
$TEXT['HIDDEN'] = 'Versteckt';
$TEXT['HIDE'] = 'verstecken';
$TEXT['HIDE_ADVANCED'] = 'Erweiterte Optionen verdecken';
$TEXT['HOME'] = 'Home';
$TEXT['HOMEPAGE_REDIRECTION'] = 'URL Umleitung zur Homepage';
$TEXT['HOME_FOLDER'] = 'Pers&ouml;nlicher Ordner';
$TEXT['HOME_FOLDERS'] = 'Pers&ouml;nliche Ordner';
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
$TEXT['INTRO_PAGE'] = 'Eingangsseite';
$TEXT['INVALID_SIGNS'] = 'muss mit einem Buchstaben beginnen oder hat ung&uuml;ltige Zeichen';
$TEXT['KEYWORDS'] = 'Schl&uuml;sselw&ouml;rter';
$TEXT['LANGUAGE'] = 'Sprache';
$TEXT['LAST_UPDATED_BY'] = 'zuletzt ge&auml;ndert von';
$TEXT['LENGTH'] = 'L&auml;nge';
$TEXT['LEVEL'] = 'Ebene';
$TEXT['LINK'] = 'Link';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix basierend';
$TEXT['LIST_OPTIONS'] = 'Auswahlliste';
$TEXT['LOGGED_IN'] = 'Angemeldet';
$TEXT['LOGIN'] = 'Anmeldung';
$TEXT['LONG'] = 'Lang';
$TEXT['LONG_TEXT'] = 'Langtext';
$TEXT['LOOP'] = 'Schleife';
$TEXT['MAIN'] = 'Hauptblock';
$TEXT['MAINTENANCE_OFF'] = 'Wartung aus';
$TEXT['MAINTENANCE_ON'] = 'Wartung an';
$TEXT['MANAGE'] = 'Manage';
$TEXT['MANAGE_GROUPS'] = 'Gruppen verwalten';
$TEXT['MANAGE_USERS'] = 'Benutzer verwalten';
$TEXT['MATCH'] = '&Uuml;bereinstimmung';
$TEXT['MATCHING'] = 'passende';
$TEXT['MAX_EXCERPT'] = 'Max Anzahl Zitate pro Seite';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Max. Eintragungen pro Stunde';
$TEXT['MEDIA_DIRECTORY'] = 'Medienverzeichnis';
$TEXT['MENU'] = 'Men&uuml;';
$TEXT['MENU_ICON_0'] = 'Men&uuml;-Icon normal';
$TEXT['MENU_ICON_1'] = 'Men&uuml;-Icon hover';
$TEXT['MENU_TITLE'] = 'Men&uuml;titel';
$TEXT['MESSAGE'] = 'Nachricht';
$TEXT['MODIFY'] = '&Auml;ndern';
$TEXT['MODIFY_CONTENT'] = 'Inhalt &auml;ndern';
$TEXT['MODIFY_SETTINGS'] = 'Optionen &auml;ndern';
$TEXT['MODULE_ORDER'] = 'Modulreihenfolge f&uuml;r die Suche';
$TEXT['MODULE_PERMISSIONS'] = 'Modulberechtigungen';
$TEXT['MORE'] = 'Mehr';
$TEXT['MOVE_DOWN'] = 'Abw&auml;rts verschieben';
$TEXT['MOVE_UP'] = 'Aufw&auml;rts verschieben';
$TEXT['MULTIPLE_MENUS'] = 'Mehrere Men&uuml;s';
$TEXT['MULTISELECT'] = 'Mehrfachauswahl';
$TEXT['NAME'] = 'Name';
$TEXT['NEED_CURRENT_PASSWORD'] = 'mit aktuellem Passwort best&auml;tigen';
$TEXT['NEED_TO_LOGIN'] = 'M&uuml;ssen Sie sich einloggen?';
$TEXT['NEW_PASSWORD'] = 'Neues Passwort';
$TEXT['NEW_WINDOW'] = 'Neues Fenster';
$TEXT['NEXT'] = 'n&auml;chste';
$TEXT['NEXT_PAGE'] = 'n&auml;chste Seite';
$TEXT['NO'] = 'Nein';
$TEXT['NONE'] = 'Keine';
$TEXT['NONE_FOUND'] = 'Keine gefunden';
$TEXT['NOT_FOUND'] = 'Nicht gefunden';
$TEXT['NOT_INSTALLED'] = 'nicht installiert';
$TEXT['NO_IMAGE_SELECTED'] = 'Kein Bild ausgew&auml;hlt';
$TEXT['NO_RESULTS'] = 'Keine Ergebnisse';
$TEXT['OF'] = 'von';
$TEXT['ON'] = 'Am';
$TEXT['OPEN'] = '&Ouml;ffnen';
$TEXT['OPTION'] = 'Option';
$TEXT['OTHERS'] = 'Alle';
$TEXT['OUT_OF'] = 'von';
$TEXT['OVERWRITE_EXISTING'] = '&Uuml;berschreibe bestehende';
$TEXT['PAGE'] = 'Seite';
$TEXT['PAGES_DIRECTORY'] = 'Seitenverzeichnis';
$TEXT['PAGES_PERMISSION'] = 'Seitenberechtigung';
$TEXT['PAGES_PERMISSIONS'] = 'Seitenerechtigungen';
$TEXT['PAGE_EXTENSION'] = 'Dateiendungen';
$TEXT['PAGE_ICON'] = 'Seitenbild';
$TEXT['PAGE_ICON_DIR'] = 'Verzeichnis f&uuml;r Seiten-/Menuebilder';
$TEXT['PAGE_LANGUAGES'] = 'Seitensprache';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Limit der Seitenebenen';
$TEXT['PAGE_SPACER'] = 'Leerzeichen';
$TEXT['PAGE_TITLE'] = 'Seitentitel';
$TEXT['PAGE_TRASH'] = 'Seiten M&uuml;lleimer';
$TEXT['PARENT'] = '&Uuml;bergeordnete Datei';
$TEXT['PASSWORD'] = 'Passwort';
$TEXT['PATH'] = 'Pfad';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP Fehlerberichte';
$TEXT['PLEASE_LOGIN'] = 'Bitte anmelden';
$TEXT['PLEASE_SELECT'] = 'Bitte ausw&auml;hlen';
$TEXT['POST'] = 'Beitrag';
$TEXT['POSTS_PER_PAGE'] = 'Nachrichten pro Seite';
$TEXT['POST_FOOTER'] = 'Nachricht Fu&szlig;zeile';
$TEXT['POST_HEADER'] = 'Nachricht Kopfzeile';
$TEXT['PREVIOUS'] = 'vorherige';
$TEXT['PREVIOUS_PAGE'] = 'vorherige Seite';
$TEXT['PRIVATE'] = 'Privat';
$TEXT['PRIVATE_VIEWERS'] = 'Private Nutzer';
$TEXT['PROFILES_EDIT'] = 'Profil &auml;ndern';
$TEXT['PUBLIC'] = '&Ouml;ffentlich';
$TEXT['PUBL_END_DATE'] = 'End Datum';
$TEXT['PUBL_START_DATE'] = 'Start Datum';
$TEXT['RADIO_BUTTON_GROUP'] = 'Optionsfeld';
$TEXT['READ'] = 'Lesen';
$TEXT['READ_MORE'] = 'Weiterlesen';
$TEXT['REDIRECT_AFTER'] = 'Weiterleitung nach';
$TEXT['REGISTERED'] = 'registriert';
$TEXT['REGISTERED_VIEWERS'] = 'registrierter Besucher';
$TEXT['RELOAD'] = 'Neu laden';
$TEXT['REMEMBER_ME'] = 'Passwort speichern';
$TEXT['RENAME'] = 'Umbenennen';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'Diese Dateitypen nicht hochladen';
$TEXT['REQUIRED'] = 'Erforderlich';
$TEXT['REQUIREMENT'] = 'Voraussetzung';
$TEXT['RESET'] = 'Zur&uuml;cksetzen';
$TEXT['RESIZE'] = 'Gr&ouml;&szlig;e &auml;ndern';
$TEXT['RESIZE_IMAGE_TO'] = 'Bildgr&ouml;&szlig;e ver&auml;ndern auf';
$TEXT['RESTORE'] = 'Wiederherstellen';
$TEXT['RESTORE_DATABASE'] = 'Datenbank wiederherstellen';
$TEXT['RESTORE_MEDIA'] = 'Dateien wiederherstellen';
$TEXT['RESULTS'] = 'Resultate';
$TEXT['RESULTS_FOOTER'] = 'Ergebnisse Fu&szlig;zeile';
$TEXT['RESULTS_FOR'] = 'Ergebnisse f&uuml;r';
$TEXT['RESULTS_HEADER'] = 'Ergebnisse &Uuml;berschrift';
$TEXT['RESULTS_LOOP'] = 'Ergebnisse Schleife';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Neues Passwort wiederholen';
$TEXT['RETYPE_PASSWORD'] = 'Geben Sie bitte Ihr Passwort nochmal ein';
$TEXT['SAME_WINDOW'] = 'Gleiches Fenster';
$TEXT['SAVE'] = 'Speichern';
$TEXT['SEARCH'] = 'Suche';
$TEXT['SEARCHING'] = 'suchen';
$TEXT['SECTION'] = 'Abschnitt';
$TEXT['SECTION_BLOCKS'] = 'Bl&ouml;cke';
$TEXT['SEC_ANCHOR'] = 'Abschnitts-Anker Text';
$TEXT['SELECT_BOX'] = 'Auswahlliste';
$TEXT['SEND_DETAILS'] = 'Anmeldedaten senden';
$TEXT['SEPARATE'] = 'Separat';
$TEXT['SEPERATOR'] = 'Separator';
$TEXT['SERVER_EMAIL'] = 'Server E-Mail';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Server Betriebssystem';
$TEXT['SESSION_IDENTIFIER'] = 'Sitzungs ID';
$TEXT['SETTINGS'] = 'Optionen';
$TEXT['SHORT'] = 'Kurz';
$TEXT['SHORT_TEXT'] = 'Kurztext';
$TEXT['SHOW'] = 'zeigen';
$TEXT['SHOW_ADVANCED'] = 'Erweiterte Optionen anzeigen';
$TEXT['SIGNUP'] = 'Registrierung';
$TEXT['SIZE'] = 'Gr&ouml;&szlig;e';
$TEXT['SMART_LOGIN'] = 'Intelligente Anmeldung';
$TEXT['START'] = 'Start';
$TEXT['START_PUBLISHING'] = 'Beginn der Ver&ouml;ffentlichung';
$TEXT['SUBJECT'] = 'Betreff';
$TEXT['SUBMISSIONS'] = 'Eintragungen';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Max. gespeicherte Eintragungen';
$TEXT['SUBMISSION_ID'] = 'Eintragungs-ID';
$TEXT['SUBMITTED'] = 'eingetragen';
$TEXT['SUCCESS'] = 'Erfolgreich';
$TEXT['SYSTEM_DEFAULT'] = 'Standardeinstellung';
$TEXT['SYSTEM_PERMISSIONS'] = 'Zugangsberechtigungen';
$TEXT['TABLE_PREFIX'] = 'TabellenPr&auml;fix';
$TEXT['TARGET'] = 'Ziel';
$TEXT['TARGET_FOLDER'] = 'Zielordner';
$TEXT['TEMPLATE'] = 'Template';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Zugriffsrechte f&uuml;r Vorlagen';
$TEXT['TEXT'] = 'Text';
$TEXT['TEXTAREA'] = 'Langtext';
$TEXT['TEXTFIELD'] = 'Kurztext';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['THEME_COPY_CURRENT'] = 'Backend-Theme kopieren.';
$TEXT['THEME_CURRENT'] = 'aktuelles Theme';
$TEXT['THEME_IMPORT_HTT'] = 'Templatefiles importieren';
$TEXT['THEME_NEW_NAME'] = 'Name des neuen Themes';
$TEXT['THEME_NOMORE_HTT'] = 'keine weiteren vorhanden';
$TEXT['THEME_SELECT_HTT'] = 'Templatefiles ausw&auml;hlen';
$TEXT['THEME_START_COPY'] = 'kopieren';
$TEXT['THEME_START_IMPORT'] = 'importieren';
$TEXT['TIME'] = 'Zeit';
$TEXT['TIMEZONE'] = 'Zeitzone';
$TEXT['TIME_FORMAT'] = 'Zeitformat';
$TEXT['TIME_LIMIT'] = 'Zeitlimit zur Erstellung der Zitate pro Modul';
$TEXT['TITLE'] = 'Titel';
$TEXT['TO'] = 'an';
$TEXT['TOP_FRAME'] = 'Frameset sprengen';
$TEXT['TRASH_EMPTIED'] = 'M&uuml;lleimer geleert';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Bearbeite die CSS Definitionen im nachfolgenden Textfeld.';
$TEXT['TYPE'] = 'Art';
$TEXT['UNDER_CONSTRUCTION'] = 'In Bearbeitung';
$TEXT['UNINSTALL'] = 'Deinstallieren';
$TEXT['UNKNOWN'] = 'Unbekannt';
$TEXT['UNLIMITED'] = 'Unbegrenzt';
$TEXT['UNZIP_FILE'] = 'Zip-Archiv hochladen und entpacken';
$TEXT['UP'] = 'Aufw&auml;rts';
$TEXT['UPGRADE'] = 'Aktualisieren';
$TEXT['UPLOAD_FILES'] = 'Datei(en) &uuml;bertragen';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Benutzer';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'Benutzer ist aktiv';
$TEXT['USERS_CAN_SELFDELETE'] = 'Selbstl&ouml;schung m&ouml;glich';
$TEXT['USERS_CHANGE_SETTINGS'] = 'Benutzer kann eigene Einstellungen &auml;ndern';
$TEXT['USERS_DELETED'] = 'Benutzer ist als gel&ouml;scht markiert';
$TEXT['USERS_FLAGS'] = 'Benutzerflags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'Benutzer kann erweitertes Profil anlegen';
$TEXT['VERIFICATION'] = 'Pr&uuml;fziffer';
$TEXT['VERSION'] = 'Version';
$TEXT['VIEW'] = 'Ansicht';
$TEXT['VIEW_DELETED_PAGES'] = 'gel&ouml;schte Seiten anschauen';
$TEXT['VIEW_DETAILS'] = 'Details';
$TEXT['VISIBILITY'] = 'Sichtbarkeit';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Standard "VON" Adresse';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Standard Absender Name';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Bitte geben Sie eine Standard "VON" Adresse und einen Sendernamen an. Als Absender Adresse empfiehlt sich ein Format wie: <strong>admin@IhreWebseite.de</strong>. Manche E-Mail Provider (z.B. <em>mail.de</em>) stellen keine E-Mails zu, die nicht &uuml;ber den Provider selbst verschickt wurden, in der Absender Adresse aber den Namen des E-Mail Providers <em>name@mail.de</em> enthalten. Die Standard Werte werden nur verwendet, wenn keine anderen Werte von WebsiteBaker gesetzt wurden. Wenn Ihr Service Provider <acronym title="Simple Mail Transfer Protocol">SMTP</acronym> anbietet, sollten Sie diese Option f&uuml;r ausgehende E-Mails verwenden.';
$TEXT['WBMAILER_FUNCTION'] = 'E-Mail Routine';
$TEXT['WBMAILER_NOTICE'] = '<strong>SMTP Maileinstellungen:</strong><br />Die nachfolgenden Einstellungen m&uuml;ssen nur angepasst werden, wenn Sie E-Mail &uuml;ber <acronym title="Simple Mail Transfer Protocol">SMTP</acronym> verschicken wollen. Wenn Sie Ihren SMTP Server nicht kennen, oder Sie sich unsicher bei den Einstellungen sind, verwenden Sie einfach die Standard E-Mail Routine: PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP Authentifikation';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'nur aktivieren, wenn SMTP Authentifikation ben&ouml;tigt wird';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP Host';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP Passwort';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'Webseite';
$TEXT['WEBSITE_DESCRIPTION'] = 'Webseitenbeschreibung';
$TEXT['WEBSITE_FOOTER'] = 'Fu&szlig;zeile';
$TEXT['WEBSITE_HEADER'] = 'Kopfzeile';
$TEXT['WEBSITE_KEYWORDS'] = 'Schl&uuml;sselw&ouml;rter';
$TEXT['WEBSITE_TITLE'] = 'Webseitentitel';
$TEXT['WELCOME_BACK'] = 'Willkommen zur&uuml;ck';
$TEXT['WIDTH'] = 'Breite';
$TEXT['WINDOW'] = 'Fenster';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Einstellungen f&uuml;r Schreibrechte';
$TEXT['WRITE'] = 'Schreiben';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG Editor';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG Stil';
$TEXT['YES'] = 'Ja';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On Voraussetzungen nicht erf&uuml;llt';
$HEADING['ADD_CHILD_PAGE'] = 'Unterseite hinzuf&uuml;gen';
$HEADING['ADD_GROUP'] = 'Gruppe hinzuf&uuml;gen';
$HEADING['ADD_GROUPS'] = 'Gruppen hinzuf&uuml;gen';
$HEADING['ADD_HEADING'] = 'Kopf hinzuf&uuml;gen';
$HEADING['ADD_PAGE'] = 'Seite hinzuf&uuml;gen';
$HEADING['ADD_USER'] = 'Benutzer hinzuf&uuml;gen';
$HEADING['ADMINISTRATION_TOOLS'] = 'Verwaltungsprogramme';
$HEADING['BROWSE_MEDIA'] = 'Medien durchsuchen';
$HEADING['CREATE_FOLDER'] = 'Ordner erstellen';
$HEADING['DEFAULT_SETTINGS'] = 'Standardeinstellungen';
$HEADING['DELETED_PAGES'] = 'gel&ouml;schte Seiten';
$HEADING['FILESYSTEM_SETTINGS'] = 'Dateisystemoptionen';
$HEADING['GENERAL_SETTINGS'] = 'Allgemeine Optionen';
$HEADING['INSTALL_LANGUAGE'] = 'Sprache hinzuf&uuml;gen';
$HEADING['INSTALL_MODULE'] = 'Modul installieren';
$HEADING['INSTALL_TEMPLATE'] = 'Designvorlage installieren';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Sprachdateien manuell ausf&uuml;hren';
$HEADING['INVOKE_MODULE_FILES'] = 'Moduldateien manuell ausf&uuml;hren';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Templatedateien manuell ausf&uuml;hren';
$HEADING['LANGUAGE_DETAILS'] = 'Details zur Sprache';
$HEADING['MANAGE_SECTIONS'] = 'Abschnitte verwalten';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Erweiterte Seitenoptionen &auml;ndern';
$HEADING['MODIFY_DELETE_GROUP'] = '&Auml;ndern/L&ouml;schen von Gruppen';
$HEADING['MODIFY_DELETE_PAGE'] = 'Seite &auml;ndern/Seite l&ouml;schen';
$HEADING['MODIFY_DELETE_USER'] = '&Auml;ndern/L&ouml;schen von Benutzern';
$HEADING['MODIFY_GROUP'] = 'Gruppe &auml;ndern';
$HEADING['MODIFY_GROUPS'] = 'Gruppen &auml;ndern';
$HEADING['MODIFY_INTRO_PAGE'] = 'Eingangsseite &auml;ndern';
$HEADING['MODIFY_PAGE'] = 'Seite &auml;ndern';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Seitenoptionen &auml;ndern';
$HEADING['MODIFY_USER'] = 'Benutzer &auml;ndern';
$HEADING['MODULE_DETAILS'] = 'Details zum Modul';
$HEADING['MY_EMAIL'] = 'E-Mail Adresse';
$HEADING['MY_PASSWORD'] = 'Passwort';
$HEADING['MY_SETTINGS'] = 'Einstellungen';
$HEADING['SEARCH_SETTINGS'] = 'Suchoptionen';
$HEADING['SERVER_SETTINGS'] = 'Servereinstellungen';
$HEADING['TEMPLATE_DETAILS'] = 'Details zur Designvorlage';
$HEADING['UNINSTALL_LANGUAGE'] = 'Sprache l&ouml;schen';
$HEADING['UNINSTALL_MODULE'] = 'Modul deinstallieren';
$HEADING['UNINSTALL_TEMPLATE'] = 'Designvorlage deinstallieren';
$HEADING['UPGRADE_LANGUAGE'] = 'Sprache registrieren/aktualisieren (Upgrade)';
$HEADING['UPLOAD_FILES'] = 'Datei(en) &uuml;bertragen';
$HEADING['WBMAILER_SETTINGS'] = 'Maileinstellungen';
$MESSAGE['ADDON_ERROR_RELOAD'] = 'Fehler beim Abgleich der Addon Informationen.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Sprachen erfolgreich geladen';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>ACHTUNG!</strong> &Uuml;berspielen Sie Sprachdateien aus Sicherheitsgr&uuml;nden nur &uuml;ber FTP in den Ordner /languages/ und benutzen die Upgrade Funktion zum Registrieren oder Aktualisieren.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Warnung: Eventuell vorhandene Datenbankeintr&auml;ge eines Moduls gehen verloren. ';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'Beim Hochladen oder L&ouml;schen von Modulen per FTP (nicht empfohlen), werden eventuell vorhandene Modulfunktionen <tt>install</tt>, <tt>upgrade</tt> oder <tt>uninstall</tt> nicht automatisch ausgef&uuml;hrt. Solche Module funktionieren daher meist nicht richtig, oder hinterlassen Datenbankeintr&auml;ge beim L&ouml;schen per FTP.<br /><br /> Nachfolgend k&ouml;nnen die Modulfunktionen von per FTP hochgeladenen Modulen manuell ausgef&uuml;hrt werden.';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Warnung: Eventuell vorhandene Datenbankeintr&auml;ge eines Moduls gehen verloren. Bitte nur bei Problemen mit per FTP hochgeladenen Modulen verwenden. ';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Warnung: Eventuell vorhandene Datenbankeintr&auml;ge eines Moduls gehen verloren. ';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Module erfolgreich geladen';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = '&Uuml;berschreibe neuere Dateien';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Installation fehlgeschlagen. Ihr System erf&uuml;llt nicht alle Voraussetzungen die f&uuml;r diese Erweiterung ben&ouml;tigt werden. Um diese Erweiterung nutzen zu k&ouml;nnen, m&uuml;ssen nachfolgende Updates durchgef&uuml;hrt werden.';
$MESSAGE['ADDON_RELOAD'] = 'Abgleich der Datenbank mit den Informationen aus den Addon-Dateien (z.B. nach FTP Upload).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Designvorlagen erfolgreich geladen';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Ungen&uuml;gende Zugangsberechtigungen';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Das Passwort kann nur einmal pro Stunde zur&uuml;ckgesetzt werden';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Das Passwort konnte nicht versendet werden, bitte kontaktieren Sie den Systemadministrator';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Die angegebene E-Mail Adresse wurde nicht in der Datenbank gefunden';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Bitte geben Sie nachfolgend Ihre E-Mail Adresse an';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Ihr Loginname und Ihr Passwort wurden an Ihre E-Mail Adresse gesendet';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Kein aktiver Inhalt auf dieser Seite vorhanden';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Sie sind nicht berechtigt, diese Seite zu sehen';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Bereits installiert';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Kann im Zielverzeichnis nicht schreiben';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Bitte haben Sie etwas Geduld.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Deinstallation fehlgeschlagen';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Deinstallation nicht m&ouml;glich: Datei wird benutzt';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = 'Das {{type}} <strong>{{type_name}}</strong> kann nicht deinstalliert werden, weil es auf {{pages}} benutzt wird:';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'folgender Seite;folgenden Seiten';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Das Template <strong>{{name}}</strong> kann nicht deinstalliert werden, weil es das Standardtemplate ist!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Das Template <strong>{{name}}</strong> kann nicht deinstalliert werden, weil es das Standardbackendtheme ist!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Fehler beim Entpacken';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Die Datei kann nicht &uuml;bertragen werden';
$MESSAGE['GENERIC_COMPARE'] = ' erfolgreich';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Fehler beim &Ouml;ffnen der Datei.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' fehlgeschlagen';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Bitte beachten Sie, dass Sie nur den folgenden Dateityp ausw&auml;hlen k&ouml;nnen:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Bitte beachten Sie, dass Sie nur folgende Dateitypen ausw&auml;hlen k&ouml;nnen:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Bitte alle Felder ausf&uuml;llen';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'Sie haben keine Auswahl getroffen!';
$MESSAGE['GENERIC_INSTALLED'] = 'Erfolgreich installiert';
$MESSAGE['GENERIC_INVALID'] = 'Die &uuml;bertragene Datei ist ung&uuml;ltig';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Ung&uuml;ltige WebsiteBaker Installationsdatei. Bitte *.zip Format pr&uuml;fen.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Ung&uuml;ltige WebsiteBaker Sprachdatei. Bitte Textdatei pr&uuml;fen.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Ung&uuml;ltige WebsiteBaker Moduledatei. Bitte Textdatei pr&uuml;fen.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Ung&uuml;ltige WebsiteBaker Templatedatei. Bitte Textdatei pr&uuml;fen.';
$MESSAGE['GENERIC_IN_USE'] = ' aber benutzt in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Fehlende Archivdatei!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'Das Modul ist nicht ordnungsgem&auml;ss installiert!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' nicht m&ouml;glich';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Nicht installiert';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Aktualisierung nicht m&ouml;glich';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Die Datenbanksicherung kann je nach Gr&ouml;&szlig;e der Datenbank einige Zeit dauern.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Bitte versuchen Sie es sp&auml;ter noch einmal ...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Sicherheitsverletzung!! Zugriff wurde verweigert!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Sicherheitsverletzung!! Das Speichern der Daten wurde verweigert!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Erfolgreich deinstalliert';
$MESSAGE['GENERIC_UPGRADED'] = 'Erfolgreich aktualisiert';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Versionsabgleich';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade erforderlich!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'Diese Seite ist f&uuml;r Wartungsarbeiten vor&uuml;bergehend geschlossen';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Momentan in Bearbeitung';
$MESSAGE['GROUPS_ADDED'] = 'Die Gruppe wurde erfolgreich hinzugef&uuml;gt';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Sind Sie sicher, dass Sie die ausgew&auml;hlte Gruppe l&ouml;schen m&ouml;chten (und alle Benutzer, die dazugeh&ouml;ren)?';
$MESSAGE['GROUPS_DELETED'] = 'Die Gruppe wurde erfolgreich gel&ouml;scht';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Der Gruppenname wurde nicht angegeben';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Der Gruppenname existiert bereits';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Keine Gruppen gefunden';
$MESSAGE['GROUPS_SAVED'] = 'Die Gruppe wurde erfolgreich gespeichert';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Der Loginname oder das Passwort ist nicht korrekt';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Bitte geben Sie unten Ihren Loginnamen und Passwort ein';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Bitte geben Sie Ihr Passwort ein';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Das angegebene Passwort ist zu lang';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Das angegebene Passwort ist zu kurz';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Bitte geben Sie Ihren Loginnamen ein';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Der angegebene Loginname ist zu lang';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Der angegebene Loginname ist zu kurz';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Sie haben keine Dateiendung angegeben';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Sie haben keinen neuen Namen angegeben';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Das ausgew&auml;hlte Verzeichnis konnte nicht gel&ouml;scht werden';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Die ausgew&auml;hlte Datei konnte nicht gel&ouml;scht werden';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Das Umbenennen war nicht erfolgreich';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Sind Sie sicher, dass Sie die folgende Datei oder Verzeichnis l&ouml;schen m&ouml;chten?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Das Verzeichnis wurde gel&ouml;scht';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Die Datei wurde gel&ouml;scht';
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
$MESSAGE['MEDIA_NONE_FOUND'] = 'Im aktuellen Verzeichnis konnten keine Dateien (z.B. Bilder) gefunden werden';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'Es wurde keine Datei empfangen';
$MESSAGE['MEDIA_RENAMED'] = 'Das Umbenennen war erfolgreich';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = 'Datei wurde erfolgreich &uuml;bertragen';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Der Name des Zielverzeichnisses darf nicht ../ enthalten';
$MESSAGE['MEDIA_UPLOADED'] = 'Dateien wurden erfolgreich &uuml;bertragen';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Dieses Formular wurde zu oft aufgerufen. Bitte versuchen Sie es in einer Stunde noch einmal.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Die eingegebene Pr&uuml;fziffer stimmt nicht &uuml;berein. Wenn Sie Probleme mit dem Lesen der Pr&uuml;fziffer haben, bitte schreiben Sie eine E-Mail an uns: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Bitte folgende Angaben erg&auml;nzen';
$MESSAGE['PAGES_ADDED'] = 'Die Seite wurde erfolgreich hinzugef&uuml;gt';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Seitenkopf erfolgreich hinzugef&uuml;gt';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Bitte geben Sie einen Men&uuml;titel ein';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Bitte geben Sie einen Titel f&uuml;r die Seite ein';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Beim Anlegen der Zugangsdatei im Verzeichnis /pages ist ein Fehler aufgetreten (Ungen&uuml;gende Zugangsrechte)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Beim L&ouml;schen der Zugangsdatei im Verzeichnis /pages ist ein Fehler aufgetreten (Ungen&uuml;gende Zugangsrechte)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Bei der Zusammenstellung der Seite ist ein Fehler aufgetreten';
$MESSAGE['PAGES_DELETED'] = 'Die Seite wurde erfolgreich gel&ouml;scht';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Sind Sie sicher, dass Sie die ausgew&auml;hlte Seite l&ouml;schen m&ouml;chten ( und deren Unterseiten )';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Sie haben keine Berechtigung, diese Seite zu &auml;ndern';
$MESSAGE['PAGES_INTRO_LINK'] = 'Bitte klicken Sie HIER um die Eingangsseite zu &auml;ndern';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Es konnte nicht in die Datei /pages/intro.php geschrieben werden (ungen&uuml;gende Zugangsrechte)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Eingangsseite wurde erfolgreich gespeichert';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Die letzte &Auml;nderung wurde durchgef&uuml;hrt von';
$MESSAGE['PAGES_NOT_FOUND'] = 'Die Seite konnte nicht gefunden werden';
$MESSAGE['PAGES_NOT_SAVED'] = 'Beim Speichern der Seite ist ein Fehler aufgetreten';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Eine Seite mit einem &auml;hnlichen oder demselben Titel existiert bereits';
$MESSAGE['PAGES_REORDERED'] = 'Die Seite wurde erfolgreich neu zusammengestellt';
$MESSAGE['PAGES_RESTORED'] = 'Die Seite wurde erfolgreich wiederhergestellt';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Zur&uuml;ck zum Seitenmen&uuml;';
$MESSAGE['PAGES_SAVED'] = 'Die Seite wurde erfolgreich gespeichert';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Die Seiteneinstellungen wurden erfolgreich gespeichert';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Einstellungen f&uuml;r diesen Abschnitt erfolgreich gespeichert';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Das Passwort, das Sie angegeben haben, ist ung&uuml;ltig';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Pers&ouml;nliche Daten wurden erfolgreich gespeichert';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'E-Mail Einstellung ge&auml;ndert';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Es wurden ung&uuml;ltige Zeichen f&uuml;r des Passwort verwendet';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Das Passwort wurde erfolgreich ge&auml;ndert';
$MESSAGE['RECORD_MODIFIED_FAILED'] = '&Auml;nderung des Datensatzes ist fehlgeschlagen.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'Ge&auml;nderter Datensatz wurde erfolgreich aktualisiert.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Hinzuf&uuml;gen eines neuen Datensatzes ist fehlgeschlagen.';
$MESSAGE['RECORD_NEW_SAVED'] = 'Neuer Datensatz wurde erfolgreich hinzugef&uuml;gt.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Bitte beachten Sie: Wenn Sie dieses Feld anklicken, werden alle ungespeicherten &Auml;nderungen zur&uuml;ckgesetzt';
$MESSAGE['SETTINGS_SAVED'] = 'Die Optionen wurden erfolgreich gespeichert';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Konfigurationsdatei konnte nicht ge&ouml;ffnet werden';
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
Diese E-Mail wurde automatisch erstellt!

';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Hallo {LOGIN_DISPLAY_NAME},

Sie erhalten diese E-Mail, weil sie ein neues Passwort angefordert haben.

Ihre neuen Logindaten f&uuml;r {LOGIN_WEBSITE_TITLE} lauten:

Loginname: {LOGIN_NAME}
Passwort: {LOGIN_PASSWORD}

Das bisherige Passwort wurde durch das neue Passwort oben ersetzt.

Sollten Sie kein neues Kennwort angefordert haben, l&ouml;schen Sie bitte diese E-Mail.

Mit freundlichen Gr&uuml;ssen
----------------------------------------
Diese E-Mail wurde automatisch erstellt!
';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Hallo {LOGIN_DISPLAY_NAME},

Herzlich willkommen bei \'{LOGIN_WEBSITE_TITLE}\'

Ihre Logindaten f&uuml;r \'{LOGIN_WEBSITE_TITLE}\' lauten:
Loginname: {LOGIN_NAME}
Passwort: {LOGIN_PASSWORD}

Vielen Dank f&uuml;r Ihre Registrierung

Wenn Sie dieses E-Mail versehentlich erhalten haben, l&ouml;schen Sie bitte diese E-Mail.
----------------------------------------
Diese E-Mail wurde automatisch erstellt!
';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Deine WB Logindaten ...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Bitte geben Sie Ihre E-Mail Adresse an';
$MESSAGE['START_CURRENT_USER'] = 'Sie sind momentan angemeldet als:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Das Installations-Verzeichnis "/install" existiert noch! Dies stellt ein Sicherheitsrisiko dar. Bitte l&ouml;schen.';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Bitte die Datei "upgrade-script.php" vom Webserver l&ouml;schen.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Willkommen in der WebsiteBaker Verwaltung';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Bitte beachten Sie: Um eine andere Designvorlage auszuw&auml;hlen, benutzen Sie den Bereich "Optionen"';
$MESSAGE['THEME_ALREADY_EXISTS'] = 'Neuer Theme-Name existiert bereits.';
$MESSAGE['THEME_COPY_CURRENT'] = 'Das aktuell aktive Backend-Theme kopieren und unter einem neuem Namen abspeichern.';
$MESSAGE['THEME_DESTINATION_READONLY'] = 'Ungen&uuml;gende Rechte um das Zielverzeichnis zu erstellen!';
$MESSAGE['THEME_IMPORT_HTT'] = 'Zus&auml;tzliche Templatefile(s) in das aktuelle Theme importieren.<br />Mit diesen Templates k&ouml;nnen die Default-Templates &uuml;berschrieben werden.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Ung&uuml;ltigen Theme-Name angegeben!';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Unbekannter Upload Fehler';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Konnte Datei nicht schreiben. Fehlende Schreibrechte.';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'Erweiterungsfehler';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'Die hochgeladene Datei &uum;berschreitet die in dem HTML Formular mittels der Anweisung MAX_FILE_SIZE angegebene maximale Dateigr&oum;sse. ';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'Die hochgeladene Datei &uum;berschreitet die in der Anweisung upload_max_filesize in php.ini festgelegte Gr&oum;sse';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'Es wurde keine Datei hochgeladen';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Fehlender tempor&auml;rer Ordner';
$MESSAGE['UPLOAD_ERR_OK'] = 'Die Datei wurde erfolgreich hochgeladen';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'Die Datei wurde nur teilweise hochgeladen';
$MESSAGE['USERS_ADDED'] = 'Der Benutzer wurde erfolgreich hinzugef&uuml;gt';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Funktion abgelehnt, Sie k&ouml;nnen sich nicht selbst l&ouml;schen!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Bitte beachten Sie: Sie sollten in die obigen Felder nur Werte eingeben, wenn Sie das Passwort dieses Benutzers &auml;ndern m&ouml;chten';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Sind Sie sicher, dass Sie den ausgew&auml;hlten Benutzer l&ouml;schen m&ouml;chten?';
$MESSAGE['USERS_DELETED'] = 'Der Benutzer wurde erfolgreich gel&ouml;scht';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Die angegebene E-Mail Adresse wird bereits verwendet';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Die angegebene E-Mail Adresse ist ung&uuml;ltig';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Es wurden ung&uuml;ltige Zeichen f&uuml;r den Loginnamen verwendet';
$MESSAGE['USERS_NO_GROUP'] = 'Es wurde keine Gruppe ausgew&auml;hlt';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Das angegebene Passwort ist ung&uuml;ltig';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Das eingegebene Passwort war zu kurz';
$MESSAGE['USERS_SAVED'] = 'Der Benutzer wurde erfolgreich gespeichert';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'Der angegebene Loginname wird bereits verwendet';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'Der eingegebene Loginname war zu kurz';
$OVERVIEW['ADMINTOOLS'] = 'Zugriff auf die WebsiteBaker Verwaltungsprogramme...';
$OVERVIEW['GROUPS'] = 'Verwaltung von Gruppen und Ihrer Zugangsberechtigungen...';
$OVERVIEW['HELP'] = 'Noch Fragen? Hier finden Sie Antworten';
$OVERVIEW['LANGUAGES'] = 'Sprachen verwalten...';
$OVERVIEW['MEDIA'] = 'Verwaltung der im Medienordner gespeicherten Dateien...';
$OVERVIEW['MODULES'] = 'Verwaltung der WebsiteBaker Module...';
$OVERVIEW['PAGES'] = 'Verwaltung Ihrer Webseiten...';
$OVERVIEW['PREFERENCES'] = '&Auml;ndern pers&ouml;nlicher Einstellungen wie E-Mail Adresse, Passwort, usw.... ';
$OVERVIEW['SETTINGS'] = '&Auml;ndern der Optionen f&uuml;r WebsiteBaker...';
$OVERVIEW['START'] = '&Uuml;berblick &uuml;ber die Verwaltung';
$OVERVIEW['TEMPLATES'] = '&Auml;ndern des Designs Ihrer Webseite mit Vorlagen...';
$OVERVIEW['USERS'] = 'Verwaltung von Benutzern, die sich in WebsiteBaker einloggen d&uuml;rfen...';
$OVERVIEW['VIEW'] = 'Ansicht Ihrer Webseite in einem neuen Fenster...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
