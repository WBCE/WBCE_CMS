<?php
/**
 *
 * @category        framework
 * @package         language
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: NO.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/NO.php $
 * @lastmodified    $Date: 2012-03-09 15:30:29 +0100 (Fr, 09. Mrz 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Define that this file is loaded
if(!defined('LANGUAGE_LOADED')) {
define('LANGUAGE_LOADED', true);
}

// Set the language information
$language_code = 'NO';
$language_name = 'Norsk';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Odd Egil Hansen (oeh)';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Tilgang';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Tillegg';
$MENU['ADMINTOOLS'] = 'Admin-verkt&oslash;y';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Hent innloggings informasjon';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Grupper';
$MENU['HELP'] = 'Hjelp';
$MENU['LANGUAGES'] = 'Spr&aring;k';
$MENU['LOGIN'] = 'Logg inn';
$MENU['LOGOUT'] = 'Logg ut';
$MENU['MEDIA'] = 'Media';
$MENU['MODULES'] = 'Moduler';
$MENU['PAGES'] = 'Sider';
$MENU['PREFERENCES'] = 'Bruker innstillinger';
$MENU['SETTINGS'] = 'Innstillinger';
$MENU['START'] = 'Start';
$MENU['TEMPLATES'] = 'Maler';
$MENU['USERS'] = 'Brukere';
$MENU['VIEW'] = 'Forh&aring;ndsvis';
$TEXT['ACCOUNT_SIGNUP'] = 'Konto Registrering';
$TEXT['ACTIONS'] = 'Valg';
$TEXT['ACTIVE'] = 'Aktivt';
$TEXT['ADD'] = 'Tilf&oslash;y';
$TEXT['ADDON'] = 'Tillegg';
$TEXT['ADD_SECTION'] = 'Legg Til Seksjon';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Administrasjon';
$TEXT['ADMINISTRATION_TOOL'] = 'Administrasjonsverkt&oslash;y';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administratorer';
$TEXT['ADVANCED'] = 'Avansert';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Tillatte filtyper ved opplasting';
$TEXT['ALLOWED_VIEWERS'] = 'Tillatte lesere';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Tillat Flere Valg';
$TEXT['ALL_WORDS'] = 'Alle Ord';
$TEXT['ANCHOR'] = 'Anker';
$TEXT['ANONYMOUS'] = 'Anonym';
$TEXT['ANY_WORDS'] = 'Samme Hvilke Ord';
$TEXT['APP_NAME'] = 'Applikasjonsnavn';
$TEXT['ARE_YOU_SURE'] = 'Er du sikker?';
$TEXT['AUTHOR'] = 'Skribent';
$TEXT['BACK'] = 'Tilbake';
$TEXT['BACKUP'] = 'Sikkerhetskopiere';
$TEXT['BACKUP_ALL_TABLES'] = 'Sikkerhetskopiere alle tabeller i databasen';
$TEXT['BACKUP_DATABASE'] = 'Sikkerhetskopiere database';
$TEXT['BACKUP_MEDIA'] = 'Sikkerhetskopi Medie';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Sikkerhetskopiere bare WB- spesifikke tabeller';
$TEXT['BASIC'] = 'Grunnleggende';
$TEXT['BLOCK'] = 'Blokker';
$TEXT['CALENDAR'] = 'Kalender';
$TEXT['CANCEL'] = 'Avbryt';
$TEXT['CAN_DELETE_HIMSELF'] = 'Kan slette egen bruker';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha bekreftelse';
$TEXT['CAP_EDIT_CSS'] = 'Rediger CSS koden';
$TEXT['CHANGE'] = 'Forandre';
$TEXT['CHANGES'] = 'Endringer';
$TEXT['CHANGE_SETTINGS'] = 'Endre Innstillinger';
$TEXT['CHARSET'] = 'Tegnsett';
$TEXT['CHECKBOX_GROUP'] = 'Valgboks Gruppe';
$TEXT['CLOSE'] = 'Lukk';
$TEXT['CODE'] = 'Kode';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'Skjul';
$TEXT['COMMENT'] = 'Kommentar';
$TEXT['COMMENTING'] = 'Kommenterer';
$TEXT['COMMENTS'] = 'Kommentarer';
$TEXT['CREATE_FOLDER'] = 'Opprett Katalog';
$TEXT['CURRENT'] = 'Aktuell';
$TEXT['CURRENT_FOLDER'] = 'Gjelende Katalog';
$TEXT['CURRENT_PAGE'] = 'Aktuell Side';
$TEXT['CURRENT_PASSWORD'] = 'Gjeldende Passord';
$TEXT['CUSTOM'] = 'Egendefinert';
$TEXT['DATABASE'] = 'Database';
$TEXT['DATE'] = 'Dato';
$TEXT['DATE_FORMAT'] = 'Dato format';
$TEXT['DEFAULT'] = 'Standard';
$TEXT['DEFAULT_CHARSET'] = 'Standard Charset';
$TEXT['DEFAULT_TEXT'] = 'Standard Tekst';
$TEXT['DELETE'] = 'Slett';
$TEXT['DELETED'] = 'Slettet';
$TEXT['DELETE_DATE'] = 'Slette dato';
$TEXT['DELETE_ZIP'] = 'Slett .zip arkivet etter at den er pakket ut';
$TEXT['DESCRIPTION'] = 'Beskrivelse';
$TEXT['DESIGNED_FOR'] = 'Laget For';
$TEXT['DIRECTORIES'] = 'Kataloger';
$TEXT['DIRECTORY_MODE'] = 'Katalog Modus';
$TEXT['DISABLED'] = 'Deaktivert';
$TEXT['DISPLAY_NAME'] = 'Vis Navn';
$TEXT['EMAIL'] = 'E-post';
$TEXT['EMAIL_ADDRESS'] = 'E-post Adresse';
$TEXT['EMPTY_TRASH'] = 'T&oslash;m S&oslash;ppel';
$TEXT['ENABLED'] = 'Aktivert';
$TEXT['END'] = 'Slutt';
$TEXT['ERROR'] = 'Feil';
$TEXT['EXACT_MATCH'] = 'Eksakt Treff';
$TEXT['EXECUTE'] = 'Utf&oslash;re';
$TEXT['EXPAND'] = 'Utvid';
$TEXT['EXTENSION'] = 'Utvidelse';
$TEXT['FIELD'] = 'Felt';
$TEXT['FILE'] = 'Fil';
$TEXT['FILES'] = 'Filer';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Filsystem Tillgang';
$TEXT['FILE_MODE'] = 'Fil Modus';
$TEXT['FINISH_PUBLISHING'] = 'Avslutt Publisering';
$TEXT['FOLDER'] = 'Katalog';
$TEXT['FOLDERS'] = 'Kataloger';
$TEXT['FOOTER'] = 'Bunntekst ';
$TEXT['FORGOTTEN_DETAILS'] = 'Glemt dine detaljer?';
$TEXT['FORGOT_DETAILS'] = 'Glemt detaljer?';
$TEXT['FROM'] = 'Fra';
$TEXT['FRONTEND'] = 'Forside';
$TEXT['FULL_NAME'] = 'Fult Navn';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Gruppe';
$TEXT['HEADER'] = 'Topptekst';
$TEXT['HEADING'] = 'Overskrift';
$TEXT['HEADING_CSS_FILE'] = 'Faktisk modul fil: ';
$TEXT['HEIGHT'] = 'H&oslash;yde';
$TEXT['HIDDEN'] = 'Skjult';
$TEXT['HIDE'] = 'Skjul';
$TEXT['HIDE_ADVANCED'] = 'Skjul Avanserte Valg';
$TEXT['HOME'] = 'Hjem';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Hjemmeside Omadressering';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Vert';
$TEXT['ICON'] = 'Ikon';
$TEXT['IMAGE'] = 'Bilde';
$TEXT['INLINE'] = 'In-line';
$TEXT['INSTALL'] = 'Innstaller';
$TEXT['INSTALLATION'] = 'Installsjon';
$TEXT['INSTALLATION_PATH'] = 'Innstallasjons Sti';
$TEXT['INSTALLATION_URL'] = 'Installasjons URL';
$TEXT['INSTALLED'] = 'installert';
$TEXT['INTRO'] = 'Introduksjon';
$TEXT['INTRO_PAGE'] = 'Intro Side';
$TEXT['INVALID_SIGNS'] = 'm&aring; begynne med en bokstav eller har ugyldige tegn';
$TEXT['KEYWORDS'] = 'N&oslash;kkelord';
$TEXT['LANGUAGE'] = 'Spr&aring;k';
$TEXT['LAST_UPDATED_BY'] = 'Sist Endret Av';
$TEXT['LENGTH'] = 'Lengde';
$TEXT['LEVEL'] = 'Niv&aring;';
$TEXT['LINK'] = 'Lenke';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix bassert';
$TEXT['LIST_OPTIONS'] = 'Vis Valg';
$TEXT['LOGGED_IN'] = 'Innlogget';
$TEXT['LOGIN'] = 'Logg inn';
$TEXT['LONG'] = 'Langt';
$TEXT['LONG_TEXT'] = 'Lang Tekst';
$TEXT['LOOP'] = 'L&oslash;kke';
$TEXT['MAIN'] = 'Hoved';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'Administrer';
$TEXT['MANAGE_GROUPS'] = 'Administrer Grupper';
$TEXT['MANAGE_USERS'] = 'Administrer Brukere';
$TEXT['MATCH'] = 'Treff';
$TEXT['MATCHING'] = 'Finner Motstykke';
$TEXT['MAX_EXCERPT'] = 'Maksimalt antall linjer for utdrag';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Maks Avgivelser Per Time';
$TEXT['MEDIA_DIRECTORY'] = 'Media Katalog';
$TEXT['MENU'] = 'Meny';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Meny Tittel';
$TEXT['MESSAGE'] = 'Melding';
$TEXT['MODIFY'] = 'Endre';
$TEXT['MODIFY_CONTENT'] = 'Endre innhold';
$TEXT['MODIFY_SETTINGS'] = 'Endre innstillinger';
$TEXT['MODULE_ORDER'] = 'Modul-rekkef&oslash;lge for s&oslash;king';
$TEXT['MODULE_PERMISSIONS'] = 'Modul Adgang';
$TEXT['MORE'] = 'Mer';
$TEXT['MOVE_DOWN'] = 'Flytt ned';
$TEXT['MOVE_UP'] = 'Flytt opp';
$TEXT['MULTIPLE_MENUS'] = 'Flere Menyer';
$TEXT['MULTISELECT'] = 'Flere Valg';
$TEXT['NAME'] = 'Navn';
$TEXT['NEED_CURRENT_PASSWORD'] = 'Bekreft med gjeldende passord';
$TEXT['NEED_TO_LOGIN'] = 'Trenger du &aring; logge inn?';
$TEXT['NEW_PASSWORD'] = 'Nytt Passord';
$TEXT['NEW_WINDOW'] = 'Nytt Vindu';
$TEXT['NEXT'] = 'Neste';
$TEXT['NEXT_PAGE'] = 'Neste Side';
$TEXT['NO'] = 'Nei';
$TEXT['NONE'] = 'Ingen';
$TEXT['NONE_FOUND'] = 'Ingen funnet';
$TEXT['NOT_FOUND'] = 'Ikke Funnet';
$TEXT['NOT_INSTALLED'] = 'ikke installert';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Ingen Resultater';
$TEXT['OF'] = 'Av';
$TEXT['ON'] = 'P&aring;';
$TEXT['OPEN'] = '&Aring;pne';
$TEXT['OPTION'] = 'Valg';
$TEXT['OTHERS'] = 'Andre';
$TEXT['OUT_OF'] = 'Av antall';
$TEXT['OVERWRITE_EXISTING'] = 'Overskriv eksisterende';
$TEXT['PAGE'] = 'Side';
$TEXT['PAGES_DIRECTORY'] = 'Side Katalog';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Side Tillegg';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Side Spr&aring;k';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Side Niv&aring; Begrensning';
$TEXT['PAGE_SPACER'] = 'Side Mellomrom';
$TEXT['PAGE_TITLE'] = 'Side Tittel';
$TEXT['PAGE_TRASH'] = 'Sides&oslash;ppel';
$TEXT['PARENT'] = 'Hovedkategori';
$TEXT['PASSWORD'] = 'Passord';
$TEXT['PATH'] = 'Sti';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP Feil rapporteringsniv&aring;';
$TEXT['PLEASE_LOGIN'] = 'Venligst log p&aring;';
$TEXT['PLEASE_SELECT'] = 'Vennligst velg';
$TEXT['POST'] = 'Innlegg';
$TEXT['POSTS_PER_PAGE'] = 'Innlegg Per Side';
$TEXT['POST_FOOTER'] = 'Legg Til Bunntekst';
$TEXT['POST_HEADER'] = 'Legg Til Topptekst';
$TEXT['PREVIOUS'] = 'Forrige';
$TEXT['PREVIOUS_PAGE'] = 'Forrige Side';
$TEXT['PRIVATE'] = 'Privat';
$TEXT['PRIVATE_VIEWERS'] = 'Private Seere';
$TEXT['PROFILES_EDIT'] = 'Endre profilen';
$TEXT['PUBLIC'] = 'Offentlig';
$TEXT['PUBL_END_DATE'] = 'Slutt dato';
$TEXT['PUBL_START_DATE'] = 'Start dato';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radioknapp Gruppe';
$TEXT['READ'] = 'Les';
$TEXT['READ_MORE'] = 'Les Mer';
$TEXT['REDIRECT_AFTER'] = 'Videresend etter';
$TEXT['REGISTERED'] = 'Registrert';
$TEXT['REGISTERED_VIEWERS'] = 'Registrerte Seere';
$TEXT['RELOAD'] = 'Oppdater';
$TEXT['REMEMBER_ME'] = 'Husk Meg';
$TEXT['RENAME'] = 'Endre navn';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'P&aring;budt';
$TEXT['REQUIREMENT'] = 'Krav';
$TEXT['RESET'] = 'Tilbakestill';
$TEXT['RESIZE'] = 'Endre St&oslash;rrelse';
$TEXT['RESIZE_IMAGE_TO'] = 'Endre Bilde St&oslash;rrelse Til';
$TEXT['RESTORE'] = 'Gjenopprett';
$TEXT['RESTORE_DATABASE'] = 'Gjenopprett Database';
$TEXT['RESTORE_MEDIA'] = 'Gjenopprett Media';
$TEXT['RESULTS'] = 'Resultat';
$TEXT['RESULTS_FOOTER'] = 'Resultat Bunntekst ';
$TEXT['RESULTS_FOR'] = 'Resultat For';
$TEXT['RESULTS_HEADER'] = 'Resultat Topptekst';
$TEXT['RESULTS_LOOP'] = 'Resultat L&oslash;kke';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Skriv Passordet P&aring; Nytt';
$TEXT['RETYPE_PASSWORD'] = 'Skriv Passord P&aring; Nytt';
$TEXT['SAME_WINDOW'] = 'Samme Vindu';
$TEXT['SAVE'] = 'Lagre';
$TEXT['SEARCH'] = 'S&oslash;k';
$TEXT['SEARCHING'] = 'S&oslash;ker';
$TEXT['SECTION'] = 'Seksjon';
$TEXT['SECTION_BLOCKS'] = 'Sekjsons Blokker';
$TEXT['SEC_ANCHOR'] = 'Seksjonsanker tekst';
$TEXT['SELECT_BOX'] = 'Velg Boks';
$TEXT['SEND_DETAILS'] = 'Send detaljer';
$TEXT['SEPARATE'] = 'Separat';
$TEXT['SEPERATOR'] = 'Mellomrom';
$TEXT['SERVER_EMAIL'] = 'Server E-post';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Serveren Operativ System';
$TEXT['SESSION_IDENTIFIER'] = 'Sesjons id-navn';
$TEXT['SETTINGS'] = 'Innstillinger';
$TEXT['SHORT'] = 'Kort';
$TEXT['SHORT_TEXT'] = 'Kort tekst';
$TEXT['SHOW'] = 'Vis';
$TEXT['SHOW_ADVANCED'] = 'Vis Avanserte Valg';
$TEXT['SIGNUP'] = 'Registrer';
$TEXT['SIZE'] = 'St&oslash;rrelse';
$TEXT['SMART_LOGIN'] = 'Smart Innlogging';
$TEXT['START'] = 'Start';
$TEXT['START_PUBLISHING'] = 'Start Publisering';
$TEXT['SUBJECT'] = 'Emne';
$TEXT['SUBMISSIONS'] = 'Avgivelser';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Avgivelser Lagret i Database';
$TEXT['SUBMISSION_ID'] = 'Avgitt ID';
$TEXT['SUBMITTED'] = 'Avgitt';
$TEXT['SUCCESS'] = 'Suksess';
$TEXT['SYSTEM_DEFAULT'] = 'System Standard';
$TEXT['SYSTEM_PERMISSIONS'] = 'System Adgang';
$TEXT['TABLE_PREFIX'] = 'Tabell Prefiks';
$TEXT['TARGET'] = 'M&aring;l';
$TEXT['TARGET_FOLDER'] = 'Gjelende katalog';
$TEXT['TEMPLATE'] = 'Mal';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Mal Tillgang';
$TEXT['TEXT'] = 'Tekst';
$TEXT['TEXTAREA'] = 'Tekstomr&aring;de';
$TEXT['TEXTFIELD'] = 'Tekstfelt';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Tid';
$TEXT['TIMEZONE'] = 'Tidssone';
$TEXT['TIME_FORMAT'] = 'Tids format';
$TEXT['TIME_LIMIT'] = 'Maksimal tid for &aring; samle utrag per modul';
$TEXT['TITLE'] = 'Tittel';
$TEXT['TO'] = 'Til';
$TEXT['TOP_FRAME'] = 'Topp ramme';
$TEXT['TRASH_EMPTIED'] = 'S&oslash;ppelet er T&oslash;mt';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Rediger  CSS koden i tekst viduet nedenfor.';
$TEXT['TYPE'] = 'Type';
$TEXT['UNDER_CONSTRUCTION'] = 'Under Konstruksjon';
$TEXT['UNINSTALL'] = 'Avinstaller';
$TEXT['UNKNOWN'] = 'Ukjent';
$TEXT['UNLIMITED'] = 'Ubegrenset';
$TEXT['UNZIP_FILE'] = 'Last opp og pakk ut et .zip arkiv';
$TEXT['UP'] = 'Opp';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Last opp fil(er)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Bruker';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Bekreftelse';
$TEXT['VERSION'] = 'Versjon';
$TEXT['VIEW'] = 'Se';
$TEXT['VIEW_DELETED_PAGES'] = 'Vis Slettete Sider';
$TEXT['VIEW_DETAILS'] = 'Se Detaljer';
$TEXT['VISIBILITY'] = 'Synlighet';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Standard Fra e-post';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Standard Avsender Navn';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Spesifiser en standard "FRA" addresse og "AVSENDER" navn under. Det er annbefalt &aring; bruke en FRA adresse som: <strong>admin@yourdomain.com</strong>. Noen e-post leverand&oslash;rer(f.eks. <em>mail.com</em>) kan muligens avvise e-poster med en FRA: addresse som <em>name@mail.com</em> sendt igjennom en frmmed sent via en fremmed "relay" for &aring; unng&aring; spam.<br /><br />Standard verdiene brukes kun hvis det ikke er spessifisert andre verdier av WebsiteBaker. Hvis serveren din st&oslash;tter <acronym title="Simple mail transfer protocol">SMTP</acronym>, b&oslash;r du muligens benytte denne muligheten for utg&aring;ende e-post.';
$TEXT['WBMAILER_FUNCTION'] = 'e-post rutine';
$TEXT['WBMAILER_NOTICE'] = '<strong>SMTP e-post innstillinger:</strong><br />Innstillingene under er kun p&aring;krevet hvis du vil sende e-post via <acronym title="Simple mail transfer protocol">SMTP</acronym>. Hvis du ikke vet hvem som er din "SMTP" leverand&oslash;r, eller du ikke er sikker p&aring; innstillingene som kreves, b&oslash;r du bruke standard e-post rutinen: PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP Autentifisering';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'aktiveres kun hvis din SMTP v&aelig;rt krever autentifisering';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP V&aelig;rt';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP Passord';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'Internett Side';
$TEXT['WEBSITE_DESCRIPTION'] = 'Nettstedets Beskrivelse';
$TEXT['WEBSITE_FOOTER'] = 'Nettsted Bunntekst ';
$TEXT['WEBSITE_HEADER'] = 'Nettsted Topptekst';
$TEXT['WEBSITE_KEYWORDS'] = 'Nettsted N&oslash;kkelord';
$TEXT['WEBSITE_TITLE'] = 'Nettstedets Tittel';
$TEXT['WELCOME_BACK'] = 'Velkommen tilbake';
$TEXT['WIDTH'] = 'Bredde';
$TEXT['WINDOW'] = 'Vindu';
$TEXT['WINDOWS'] = 'WINDOWS';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Global skrivetilgang til filer';
$TEXT['WRITE'] = 'Skriv';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG Editor';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG Stil';
$TEXT['YES'] = 'Ja';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Kravene for installering av denne modulen er ikke oppfylt';
$HEADING['ADD_CHILD_PAGE'] = 'Legg til ny underside';
$HEADING['ADD_GROUP'] = 'Legg til Gruppe';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Tilf&oslash;y overskrift';
$HEADING['ADD_PAGE'] = 'Legg til side';
$HEADING['ADD_USER'] = 'Legg til Bruker';
$HEADING['ADMINISTRATION_TOOLS'] = 'Administrasjonsverkt&oslash;y';
$HEADING['BROWSE_MEDIA'] = 'Utforsk Media';
$HEADING['CREATE_FOLDER'] = 'Opprett Katalog';
$HEADING['DEFAULT_SETTINGS'] = 'Standard Innstillinger';
$HEADING['DELETED_PAGES'] = 'Slettede sider';
$HEADING['FILESYSTEM_SETTINGS'] = 'Filsystem Innstillinger';
$HEADING['GENERAL_SETTINGS'] = 'Generelle Instillinger';
$HEADING['INSTALL_LANGUAGE'] = 'Installer Spr&aring;k';
$HEADING['INSTALL_MODULE'] = 'Innstaller Modul';
$HEADING['INSTALL_TEMPLATE'] = 'Installer Mal';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Start modul filene mauelt';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Spr&aring;k Detaljer';
$HEADING['MANAGE_SECTIONS'] = 'Administrer seksjoner';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Endre avansert sideinnstillinger';
$HEADING['MODIFY_DELETE_GROUP'] = 'Endre/Slette Gruppe';
$HEADING['MODIFY_DELETE_PAGE'] = 'Endre/Slett side';
$HEADING['MODIFY_DELETE_USER'] = 'Endre/Slette Bruker';
$HEADING['MODIFY_GROUP'] = 'Endre Gruppe';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Endre introduksjons side';
$HEADING['MODIFY_PAGE'] = 'Endre side';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Endre sideinnstillinger';
$HEADING['MODIFY_USER'] = 'Endre Bruker';
$HEADING['MODULE_DETAILS'] = 'Modul Detaljer';
$HEADING['MY_EMAIL'] = 'Min E-post';
$HEADING['MY_PASSWORD'] = 'Mitt Passord';
$HEADING['MY_SETTINGS'] = 'Mine Innstillinger';
$HEADING['SEARCH_SETTINGS'] = 'S&oslash;ke Innstillinger';
$HEADING['SERVER_SETTINGS'] = 'Server Innstillinger';
$HEADING['TEMPLATE_DETAILS'] = 'Mal Detaljer';
$HEADING['UNINSTALL_LANGUAGE'] = 'Avinstaller Spr&aring;k';
$HEADING['UNINSTALL_MODULE'] = 'Avinstaller Modul';
$HEADING['UNINSTALL_TEMPLATE'] = 'Avinstaller Mal';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Last opp fil(er)';
$HEADING['WBMAILER_SETTINGS'] = 'Innstillinger for e-post senderen';
$MESSAGE['ADDON_ERROR_RELOAD'] = 'Error while updating the Add-On information.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Languages reloaded successfully';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>ATTENTION!</strong> For safety reasons uploading languages files in the folder/languages/ only by FTP and use the Upgrade function for registering or updating.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Warning: Existing module database entries will get lost. ';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'When modules are uploaded via FTP (not recommended), the module installation functions <tt>install</tt>, <tt>upgrade</tt> or <tt>uninstall</tt> will not be executed automatically. Those modules may not work correct or do not uninstall properly.<br /><br />You can execute the module functions manually for modules uploaded via FTP below.';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Warning: Existing module database entries will get lost. Only use this option if you experience problems with modules uploaded via FTP.';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Warning: Existing module database entries will get lost. ';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Modules reloaded successfully';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = 'Overwrite newer Files';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Add-on installation failed. Your system does not fulfill the requirements of this Add-on. To make this Add-on working on your system, please fix the issues summarized below.';
$MESSAGE['ADDON_RELOAD'] = 'Update database with information from Add-on files (e.g. after FTP upload).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Templates reloaded successfully';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Mangelfull tillgangs rettigheter';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Beklager, men passord kan ikke tilbakestilles mer enn en gang i timen.';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Kunne ikke sende passord. Kontakt system administrator';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'E-post adressen ble ikke funnet i databasen';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Vennligst skriv e-post adressen nedenfor';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Beklager, ikke noe aktivt innhold &aring; vise.';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Beklager, du har ikke tillgang til &aring; se denne siden';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Allerede installert';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Kunne ikke skrive til m&aring;l katalogen';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Kan ikke avinstallere';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Kan ikke avinstallere: Valgte fil er i bruk';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> kunne ikke avinstalleres, da den fortsatt benyttes p&aring; siden {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'denne siden;disse sidene';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Kan ikke avinstallere designmalen <b>{{name}}</b>, da den benyttes som standard designmal!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Kan ikke pakke ut .zip filen';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Kan ikke laste opp fil';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Feil ved &aring;pningen av filen.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Vennligst merk at filen du vil laste opp m&aring; v&aelig;re av f&oslash;lgende format:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Vennligst merk at filen du vil laste opp m&aring; v&aelig;re en av f&oslash;lgende formater:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Vennligst g&aring; tilbake og fyll inn alle felter';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Lykkes &aring; installere';
$MESSAGE['GENERIC_INVALID'] = 'Filen du lastet opp er ikke gyldig';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Feil i WebsiteBaker installasjons filen. Vennligst sjekk formatet &aring; *.zip filen.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Feil i WebsiteBaker spr&aring;k filen. Vennligst sjekk tekst filen.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Ikke installert';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Vennligst v&aelig;r t&aring;lmodig, dette kan ta en stund.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Vennligst kom tilbake p&aring; et annet tidspunkt...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Brudd p&aring; sikkerheten!! Lagring av data ble nektet!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Lykkes &aring; avinstallere';
$MESSAGE['GENERIC_UPGRADED'] = 'Lykkes &aring; oppdatere';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Nettstedet er under konstruksjon';
$MESSAGE['GROUPS_ADDED'] = 'Lykkes &aring; legge til gruppe';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Slette valgte gruppe (og dermed ogs&aring; alle tilh&oslash;rende brukere)?';
$MESSAGE['GROUPS_DELETED'] = 'Lykkes &aring; slette gruppe';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Gruppe navn ikke angitt';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Gruppenavn finnes allerede';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Ingen gruppe funnet';
$MESSAGE['GROUPS_SAVED'] = 'Lykkes &aring; lagre gruppe';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Vennligst skriv et passord';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Det angitte passordet er for langt';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Det angitte passordet er for kort';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Du tastet ikke inn en fil utvidelse';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Du anga ikke et nytt navn';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Kan ikke slette valgte katalog';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Kan ikke slette valgte fil';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Mislykkes &aring; endre navn';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Vil du virkelig slette filen eller katalogen?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Lykkes &aring; slette katalog';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Lykkes &aring; slette fil';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Katalogen finnes ikke';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Kan ikke benytte ../ i katalog navnet';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'En katalog med samme navn eksisterer allerede';
$MESSAGE['MEDIA_DIR_MADE'] = 'Lykkes &aring; opprette katalogen';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Mislykkes &aring; opprette katalogen';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'En fil med samme navn eksisterer allerede';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Fant ingen fil';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Kan ikke benytte ../ i navnet';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Kan ikke benytte index.php som navnet';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Det ble ikke funnet noen media i den angitte katalogen';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was recieved';
$MESSAGE['MEDIA_RENAMED'] = 'Lykkes &aring; endre navn';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' Lykkes &aring; laste opp filen';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Kan ikke ha ../ i katalog m&aring;let';
$MESSAGE['MEDIA_UPLOADED'] = ' Lykkes &aring; laste opp filene';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Beklager, dette skjemaet har blitt sendt for mange ganger denne timen. Vennligst pr&oslash;v igjen om en time.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Bekreftelsesnummeret (ogs&aring; kjent som Captcha) som du skrev inn er feil. Hvis du har problemer med &aring; lese Captcha, vennligst kontakt: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Du m&aring; skrive inn detaljer for f&oslash;lgende felt';
$MESSAGE['PAGES_ADDED'] = 'Lykkes &aring; legge til siden';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Lykkes &aring; legge til side overskrift';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Vennligst skriv inn meny tittel';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Vennligst skriv inn side tittel';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Feilet &aring; opprette adgang fil i /pages katalog (manglende rettigheter)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Feilet &aring; slette adgang fil i /pages katalog (manglende rettigheter)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Feilet &aring; endre rekkef&oslash;lge';
$MESSAGE['PAGES_DELETED'] = 'Lykkes &aring; slette side';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Er du sikker p&aring; at du vil slette valgte side (og dermed alle under-sider)?';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Du har ikke rettigheter til &aring; endre denne siden';
$MESSAGE['PAGES_INTRO_LINK'] = 'Klikk HER for &aring; endre intro siden';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Kunne ikke skrive til fil /pages/intro.php (manglende rettigheter)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Lykkes &aring; lagre intro side';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Sist modifisert av';
$MESSAGE['PAGES_NOT_FOUND'] = 'Fant ikke siden';
$MESSAGE['PAGES_NOT_SAVED'] = 'Kunne ikke lagre side';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'En side med lik eller tilsvarende tittel eksisterer';
$MESSAGE['PAGES_REORDERED'] = 'Lykkes &aring; endre side rekkef&oslash;lge';
$MESSAGE['PAGES_RESTORED'] = 'Lykkes &aring; gjenopprette side';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Tilbake til sider';
$MESSAGE['PAGES_SAVED'] = 'Lykkes &aring; lagre side';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Lykkes &aring; lagre side innstillinger';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Lykkes &aring; lagre seksjons innstillinger';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Passordet du skrev inn er ikke korrekt';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Lykkes &aring; lagre detaljer';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'Lykkes &aring; oppdatere e-post';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Ugyldige karakterer er benyttet i passordet';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Lykkes &aring; endre passord';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'Endring av oppf&oslash;ringen feilet.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'Den endrede oppf&oslash;ringen ble lagret.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Innlegging av ny oppf&oslash;ring feilet.';
$MESSAGE['RECORD_NEW_SAVED'] = 'Ny oppf&oslash;ring ble lagt til.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Merk: Ved &aring; klikke denne knappen lagres ikke endringer';
$MESSAGE['SETTINGS_SAVED'] = 'Lykkes &aring; lagre endringer';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Kunne ikke &aring;pne konfigurasjons filen';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Kunne ikke skrive til konfigurasjons filen';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Merk: Dette er kun ment for teste milj&oslash;er';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
En ny bruker ble registrert.

Loginname: {LOGIN_NAME}
BrukerID: {LOGIN_ID}
e-post: {LOGIN_EMAIL}
IP-Adresse: {LOGIN_IP}
Registrasjons dateo: {SIGNUP_DATE}
----------------------------------------
Denne meldingen ble automatisk generert!

';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Hei {LOGIN_DISPLAY_NAME},

Denne e-posten ble sendt til deg fordi \'glemt passord\' funksjonen er blitt benyttet p&aring; din konto.
Dine nye \'{LOGIN_WEBSITE_TITLE}\' p&aring;loggingsdetalejer er:

Loginname: {LOGIN_NAME}
Passord: {LOGIN_PASSWORD}

Passordet dit er endret til det som st&aring;r over.
Dette betyr at det gamle passordet dit ikke virker lenger!
Hvis du har sp&oslash;rsm&aring;le eller problemer med de nye p&aring;loggingsdataene
b&oslash;r du ta kontakt med \'{LOGIN_WEBSITE_TITLE}\' sine administratorer.
Venligst husk &aring; t&oslash;mme nettleserens cache f&oslash;r du benytter deg av de nye p&aring;loggingsdetaljene dine, slik at du unng&aring;r uventede feil.
Med hilsen
------------------------------------
Denne meldingen ble automatisk generert!
';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Hei {LOGIN_DISPLAY_NAME},

Velkommen til v&aring;r hjemmeside \'{LOGIN_WEBSITE_TITLE}\'.

Dine \'{LOGIN_WEBSITE_TITLE}\' p&aring;loggingsdetaljer er:
Loginname: {LOGIN_NAME}
Passord: {LOGIN_PASSWORD}

Med hilsen
OBS!:
Hvis du har mottatt denne e-posten ved en feiltakelse, vennligst slett den med en gang!
-------------------------------------
Denne meldingen ble automatisk generert!
';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Dine p&aring;-loggings detaljer...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Du m&aring; skrive inn en e-post adresse';
$MESSAGE['START_CURRENT_USER'] = 'Du er logget inn som:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Advarsel, installasjonskatalogen eksisterer forsatt!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Velkommen til WebsiteBaker Administrasjon';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Merk: For &aring; endre malen m&aring; man gj&oslash;re dette i Instillinger seksjonen';
$MESSAGE['THEME_ALREADY_EXISTS'] = 'This new theme descriptor already exists.';
$MESSAGE['THEME_COPY_CURRENT'] = 'Copy the current active theme and save it with a new name.';
$MESSAGE['THEME_DESTINATION_READONLY'] = 'No rights to create new theme directory!';
$MESSAGE['THEME_IMPORT_HTT'] = 'Import additional templates into the current active theme.<br />Use these templates to overwrite the corresponding default template.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Invalid descriptor for the new theme given!';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Unknown upload error';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Failed to write file to disk';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'File upload stopped by extension';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'No file was uploaded';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Missing a temporary folder';
$MESSAGE['UPLOAD_ERR_OK'] = 'File were successful uploaded';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'The uploaded file was only partially uploaded';
$MESSAGE['USERS_ADDED'] = 'Lykkes &aring; opprette ny bruker';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Handlingen ble forkastet. Du kan ikke slette deg selv!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Merk: Skriv kun inn verdier i feltene ovenfor hvis du vil endre passordet til denne brukeren';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Vil du virkelig slette den valgte brukeren?';
$MESSAGE['USERS_DELETED'] = 'Lykkes &aring; slette bruker';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'E-post adresse allerede i bruk';
$MESSAGE['USERS_INVALID_EMAIL'] = 'E-post adresse ikke gyldig';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Ingen gruppe ble valgt';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Passordene er ikke like';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Angitt passord for kort';
$MESSAGE['USERS_SAVED'] = 'Lykkes &aring; lagre bruker';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'G&aring; inn p&aring; WebsiteBaker sine administrasjonsverkt&oslash;y...';
$OVERVIEW['GROUPS'] = 'Administrer grupper og deres system adgang...';
$OVERVIEW['HELP'] = 'Har du et sp&oslash;rsm&aring;l? Finn svaret...';
$OVERVIEW['LANGUAGES'] = 'Administrer WebsiteBaker spr&aring;k...';
$OVERVIEW['MEDIA'] = 'Administrer filer lagret i media katalogen...';
$OVERVIEW['MODULES'] = 'Administrer WebsiteBaker moduler...';
$OVERVIEW['PAGES'] = 'Administrer dine internett sider...';
$OVERVIEW['PREFERENCES'] = 'Forandre innstillinger som e-post adresse, passord, o.l....';
$OVERVIEW['SETTINGS'] = 'Forandre instillinger for WebsiteBaker...';
$OVERVIEW['START'] = 'Administrasjons oversikt';
$OVERVIEW['TEMPLATES'] = 'Forandre utseende p&aring; internett siden med maler...';
$OVERVIEW['USERS'] = 'Velg hvilke brukere som kan logge inn i WebsiteBaker...';
$OVERVIEW['VIEW'] = 'Forh&aring;ndsvis internett siden din i et nytt vindu...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
