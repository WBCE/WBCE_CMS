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
 * @version         $Id: FI.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/FI.php $
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
$language_code = 'FI';
$language_name = 'Suomi';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Jontse';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'K&auml;ytt&auml;j&auml;t';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Lis&auml;osat';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Salasana unohtunut';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Ryhm&auml;';
$MENU['HELP'] = 'Apu';
$MENU['LANGUAGES'] = 'Kielet';
$MENU['LOGIN'] = 'Kirjaudu';
$MENU['LOGOUT'] = 'Kirjaudu ulos';
$MENU['MEDIA'] = 'Tiedostot';
$MENU['MODULES'] = 'Moduulit';
$MENU['PAGES'] = 'Sivut';
$MENU['PREFERENCES'] = 'Omat tiedot';
$MENU['SETTINGS'] = 'Asetukset';
$MENU['START'] = 'Alku';
$MENU['TEMPLATES'] = 'Sivupohjat';
$MENU['USERS'] = 'K&auml;ytt&auml;j&auml;t';
$MENU['VIEW'] = 'Katsele';
$TEXT['ACCOUNT_SIGNUP'] = 'Kirjaunut';
$TEXT['ACTIONS'] = 'Tila';
$TEXT['ACTIVE'] = 'K&auml;yt&ouml;ss&auml;';
$TEXT['ADD'] = 'Lis&auml;&auml;';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'Asenna osa';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Administration';
$TEXT['ADMINISTRATION_TOOL'] = 'Ty&ouml;kalu';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'P&auml;&auml;k&auml;ytt&auml;j&auml;t';
$TEXT['ADVANCED'] = 'Lis&auml;asetukset';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Allowed Viewers';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Salli monivalinta';
$TEXT['ALL_WORDS'] = 'Joka sana';
$TEXT['ANCHOR'] = 'Anchor';
$TEXT['ANONYMOUS'] = 'Anonyymi';
$TEXT['ANY_WORDS'] = 'Jokin sanoista';
$TEXT['APP_NAME'] = 'Sovelluksen nimi';
$TEXT['ARE_YOU_SURE'] = 'Oletko varma?';
$TEXT['AUTHOR'] = 'Luonut';
$TEXT['BACK'] = 'Paluu';
$TEXT['BACKUP'] = 'Varmuuskopioi';
$TEXT['BACKUP_ALL_TABLES'] = 'Backup all tables in database';
$TEXT['BACKUP_DATABASE'] = 'Varmista tietokanta';
$TEXT['BACKUP_MEDIA'] = 'Varmista...';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Backup only WB-specific tables';
$TEXT['BASIC'] = 'Perus';
$TEXT['BLOCK'] = 'Tekstialue';
$TEXT['CALENDAR'] = 'Calender';
$TEXT['CANCEL'] = 'Peruuta';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha varmistus';
$TEXT['CAP_EDIT_CSS'] = 'Edit CSS';
$TEXT['CHANGE'] = 'Muuta';
$TEXT['CHANGES'] = 'Muutokset';
$TEXT['CHANGE_SETTINGS'] = 'Muuta asetuksia';
$TEXT['CHARSET'] = 'Merkist&ouml;';
$TEXT['CHECKBOX_GROUP'] = 'Valintaryhm&auml;';
$TEXT['CLOSE'] = 'Sulje';
$TEXT['CODE'] = 'Koodi';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'Kutista';
$TEXT['COMMENT'] = 'Kommentti';
$TEXT['COMMENTING'] = 'Kommentoi';
$TEXT['COMMENTS'] = 'Kommentit';
$TEXT['CREATE_FOLDER'] = 'Luo kansio';
$TEXT['CURRENT'] = 'Nykyinen';
$TEXT['CURRENT_FOLDER'] = 'Nykyinen kansio';
$TEXT['CURRENT_PAGE'] = 'Nykyinen sivu';
$TEXT['CURRENT_PASSWORD'] = 'Vanha salasana';
$TEXT['CUSTOM'] = 'Asiakas';
$TEXT['DATABASE'] = 'Tietokanta';
$TEXT['DATE'] = 'P&auml;iv&auml;ys';
$TEXT['DATE_FORMAT'] = 'P&auml;iv&auml;yksen muoto';
$TEXT['DEFAULT'] = 'Nykyinen';
$TEXT['DEFAULT_CHARSET'] = 'Oletusmerkrkist&ouml;';
$TEXT['DEFAULT_TEXT'] = 'Oletusteksti';
$TEXT['DELETE'] = 'Poista';
$TEXT['DELETED'] = 'Poistettu';
$TEXT['DELETE_DATE'] = 'Delete date';
$TEXT['DELETE_ZIP'] = 'Delete zip archive after unpacking';
$TEXT['DESCRIPTION'] = 'Kuvaus';
$TEXT['DESIGNED_FOR'] = 'Suunniteltu';
$TEXT['DIRECTORIES'] = 'Kansiot';
$TEXT['DIRECTORY_MODE'] = 'Kansion muoto';
$TEXT['DISABLED'] = 'Poistettu k&auml;yt&ouml;st&auml;';
$TEXT['DISPLAY_NAME'] = 'Nimi';
$TEXT['EMAIL'] = 'S&auml;hk&ouml;posti';
$TEXT['EMAIL_ADDRESS'] = 'S&auml;hk&ouml;postiosoite';
$TEXT['EMPTY_TRASH'] = 'Tyhjenn&auml; roskakori';
$TEXT['ENABLED'] = 'Salli';
$TEXT['END'] = 'Loppu';
$TEXT['ERROR'] = 'Virhe';
$TEXT['EXACT_MATCH'] = 'Tarkalleen';
$TEXT['EXECUTE'] = 'Suorita';
$TEXT['EXPAND'] = 'Laajenna';
$TEXT['EXTENSION'] = 'Lis&auml;osa';
$TEXT['FIELD'] = 'Kentt&auml;';
$TEXT['FILE'] = 'Tiedosto';
$TEXT['FILES'] = 'Tiedostot';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Tiedosto-oikeudet';
$TEXT['FILE_MODE'] = 'Tiedostomuoto';
$TEXT['FINISH_PUBLISHING'] = 'Lopeta julkaisu';
$TEXT['FOLDER'] = 'Kansio';
$TEXT['FOLDERS'] = 'Kansiot';
$TEXT['FOOTER'] = 'Alatunniste';
$TEXT['FORGOTTEN_DETAILS'] = 'Salasana unohtunut?';
$TEXT['FORGOT_DETAILS'] = 'Peruuta tiedot?';
$TEXT['FROM'] = 'Mist&auml;';
$TEXT['FRONTEND'] = 'Johdanto';
$TEXT['FULL_NAME'] = 'Nimi';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Ryhm&auml;';
$TEXT['HEADER'] = 'Yl&auml;tunniste';
$TEXT['HEADING'] = 'Otsikko';
$TEXT['HEADING_CSS_FILE'] = 'Actual module file: ';
$TEXT['HEIGHT'] = 'Korkeus';
$TEXT['HIDDEN'] = 'Piilotettu';
$TEXT['HIDE'] = 'Piilota';
$TEXT['HIDE_ADVANCED'] = 'Piilota lis&auml;asetukset';
$TEXT['HOME'] = 'Koti';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Kotisivun uudelleen ohjaus';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Palvelin';
$TEXT['ICON'] = 'Kuvake';
$TEXT['IMAGE'] = 'Kuva';
$TEXT['INLINE'] = 'Per&auml;kk&auml;in';
$TEXT['INSTALL'] = 'Asenna';
$TEXT['INSTALLATION'] = 'Asentaminen';
$TEXT['INSTALLATION_PATH'] = 'Asennuspolku';
$TEXT['INSTALLATION_URL'] = 'Asennus URL';
$TEXT['INSTALLED'] = 'Asennettu';
$TEXT['INTRO'] = 'Esisivu';
$TEXT['INTRO_PAGE'] = 'Esisivu';
$TEXT['INVALID_SIGNS'] = 'must begin with a letter or has invalid signs';
$TEXT['KEYWORDS'] = 'Avainsanat';
$TEXT['LANGUAGE'] = 'Kieli';
$TEXT['LAST_UPDATED_BY'] = 'P&auml;ivitetty';
$TEXT['LENGTH'] = 'Pituus';
$TEXT['LEVEL'] = 'Taso';
$TEXT['LINK'] = 'Linkki';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix';
$TEXT['LIST_OPTIONS'] = 'Listan tyyppi';
$TEXT['LOGGED_IN'] = 'Kirjautunut';
$TEXT['LOGIN'] = 'Kirjaudu';
$TEXT['LONG'] = 'Lis&auml;&auml;';
$TEXT['LONG_TEXT'] = 'Tarkemmin';
$TEXT['LOOP'] = 'Silmukka';
$TEXT['MAIN'] = 'P&auml;&auml;';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'Hallinnoi';
$TEXT['MANAGE_GROUPS'] = 'Ryhmien hallinta';
$TEXT['MANAGE_USERS'] = 'K&auml;ytt&auml;j&auml;hallinta';
$TEXT['MATCH'] = 'Vastaavuus';
$TEXT['MATCHING'] = 'Etsii';
$TEXT['MAX_EXCERPT'] = 'Max lines of excerpt';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Salasana l&auml;hetet&auml;&auml;n vain kerran tunnissa';
$TEXT['MEDIA_DIRECTORY'] = 'Tiedostokansio';
$TEXT['MENU'] = 'Valikko';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Valikkoteksti';
$TEXT['MESSAGE'] = 'Viesti';
$TEXT['MODIFY'] = 'Muokkaa';
$TEXT['MODIFY_CONTENT'] = 'Muokkaa sis&auml;lt&ouml;&auml;t';
$TEXT['MODIFY_SETTINGS'] = 'Muuta asetuksia';
$TEXT['MODULE_ORDER'] = 'Module-order for searching';
$TEXT['MODULE_PERMISSIONS'] = 'Moduulien hallinta';
$TEXT['MORE'] = 'Lis&auml;&auml;';
$TEXT['MOVE_DOWN'] = 'Aiirr&auml; alas';
$TEXT['MOVE_UP'] = 'Siirr&auml; yl&ouml;s';
$TEXT['MULTIPLE_MENUS'] = 'Monivalikko';
$TEXT['MULTISELECT'] = 'Monivalinta';
$TEXT['NAME'] = 'Nimi';
$TEXT['NEED_CURRENT_PASSWORD'] = 'confirm with current password';
$TEXT['NEED_TO_LOGIN'] = 'Kirjautuminen vaadittu';
$TEXT['NEW_PASSWORD'] = 'Uusi salasana';
$TEXT['NEW_WINDOW'] = 'Uuteen ikkunaan';
$TEXT['NEXT'] = 'Seuraava';
$TEXT['NEXT_PAGE'] = 'Seuraava sivu';
$TEXT['NO'] = 'Ei';
$TEXT['NONE'] = 'Ei mik&auml;&auml;n';
$TEXT['NONE_FOUND'] = 'Ei l&ouml;ytynyt';
$TEXT['NOT_FOUND'] = 'Ei l&ouml;ytynyt';
$TEXT['NOT_INSTALLED'] = 'not installed';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Ei l&ouml;ytynyt';
$TEXT['OF'] = 'Of';
$TEXT['ON'] = 'On';
$TEXT['OPEN'] = 'Open';
$TEXT['OPTION'] = 'Lis&auml;asteus';
$TEXT['OTHERS'] = 'Muut';
$TEXT['OUT_OF'] = 'Out Of';
$TEXT['OVERWRITE_EXISTING'] = 'Korvaa';
$TEXT['PAGE'] = 'Sivu';
$TEXT['PAGES_DIRECTORY'] = 'Sivukansio';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Sivun tarkennin';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Sivun kieli';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Tasoja enint&auml;&auml;n';
$TEXT['PAGE_SPACER'] = 'Sivujen erotin ';
$TEXT['PAGE_TITLE'] = 'Sivun otsikko';
$TEXT['PAGE_TRASH'] = 'Roskakori';
$TEXT['PARENT'] = 'Is&auml;nt&auml;';
$TEXT['PASSWORD'] = 'Salasana';
$TEXT['PATH'] = 'Polku';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP:n virheraportointitapa';
$TEXT['PLEASE_LOGIN'] = 'Please login';
$TEXT['PLEASE_SELECT'] = 'Valitset';
$TEXT['POST'] = 'Liite';
$TEXT['POSTS_PER_PAGE'] = 'Osaa sivulla';
$TEXT['POST_FOOTER'] = 'Lis&auml;alaviite';
$TEXT['POST_HEADER'] = 'Alaotsikko';
$TEXT['PREVIOUS'] = 'Edellinen';
$TEXT['PREVIOUS_PAGE'] = 'Edellinen sivu';
$TEXT['PRIVATE'] = 'Yksityinen';
$TEXT['PRIVATE_VIEWERS'] = 'Yksityiset';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'Julkinen';
$TEXT['PUBL_END_DATE'] = 'Julkaisun poistop&auml;iv&auml;';
$TEXT['PUBL_START_DATE'] = 'Julkaisup&auml;iv&auml;';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radiopainikkeet';
$TEXT['READ'] = 'Lue';
$TEXT['READ_MORE'] = 'Lue lis&auml;&auml;..';
$TEXT['REDIRECT_AFTER'] = 'Redirect after';
$TEXT['REGISTERED'] = 'Rekister&ouml;itynyt';
$TEXT['REGISTERED_VIEWERS'] = 'Rekister&ouml;ity';
$TEXT['RELOAD'] = 'Lataa uudelleen';
$TEXT['REMEMBER_ME'] = 'Palauta ';
$TEXT['RENAME'] = 'Nime&auml; uudelleen';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Pakollinen';
$TEXT['REQUIREMENT'] = 'Requirement';
$TEXT['RESET'] = 'Peruuta';
$TEXT['RESIZE'] = 'Muuta kokoa';
$TEXT['RESIZE_IMAGE_TO'] = 'Muuta kuvan koko';
$TEXT['RESTORE'] = 'Palauta';
$TEXT['RESTORE_DATABASE'] = 'Palauta tietokanta';
$TEXT['RESTORE_MEDIA'] = 'Palauta...';
$TEXT['RESULTS'] = 'Tulokset';
$TEXT['RESULTS_FOOTER'] = 'Alatuniste';
$TEXT['RESULTS_FOR'] = 'Tulokset';
$TEXT['RESULTS_HEADER'] = 'Tulokset';
$TEXT['RESULTS_LOOP'] = 'Tulossilmukka';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Kirjoita uudelleen';
$TEXT['RETYPE_PASSWORD'] = 'Kirjoita uudeleen';
$TEXT['SAME_WINDOW'] = 'Nykyiseen ikkunaan';
$TEXT['SAVE'] = 'Talleta';
$TEXT['SEARCH'] = 'Etsi';
$TEXT['SEARCHING'] = 'Etsii..';
$TEXT['SECTION'] = 'Osa';
$TEXT['SECTION_BLOCKS'] = 'Osa';
$TEXT['SEC_ANCHOR'] = 'Section-Anchor text';
$TEXT['SELECT_BOX'] = 'Valinta';
$TEXT['SEND_DETAILS'] = 'L&auml;het&auml; tiedot';
$TEXT['SEPARATE'] = 'Erill&auml;&auml;n';
$TEXT['SEPERATOR'] = 'Erotin';
$TEXT['SERVER_EMAIL'] = 'Palvelimen s&auml;hk&ouml;posti';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Palvelimen k&auml;ytt&ouml;j&auml;rjestelm&auml;';
$TEXT['SESSION_IDENTIFIER'] = 'Tunniste';
$TEXT['SETTINGS'] = 'Asetukset';
$TEXT['SHORT'] = 'Lyhyesti';
$TEXT['SHORT_TEXT'] = 'Lyhyesti';
$TEXT['SHOW'] = 'N&auml;yt&auml;';
$TEXT['SHOW_ADVANCED'] = 'N&auml;yt&auml; lis&auml;asetukset';
$TEXT['SIGNUP'] = 'Rekister&ouml;ityminen';
$TEXT['SIZE'] = 'Koko';
$TEXT['SMART_LOGIN'] = 'Kirjautuminen';
$TEXT['START'] = 'Alku';
$TEXT['START_PUBLISHING'] = 'Julkaise';
$TEXT['SUBJECT'] = 'Aihe';
$TEXT['SUBMISSIONS'] = 'Alasivu';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Salasana talletettu tietokantaan';
$TEXT['SUBMISSION_ID'] = 'Alasivun ID';
$TEXT['SUBMITTED'] = 'Siirretty alisivuksi';
$TEXT['SUCCESS'] = 'Onnistui';
$TEXT['SYSTEM_DEFAULT'] = 'Oletus';
$TEXT['SYSTEM_PERMISSIONS'] = 'Oikeudet';
$TEXT['TABLE_PREFIX'] = 'Taulukon ominaisuudet';
$TEXT['TARGET'] = 'Kohde';
$TEXT['TARGET_FOLDER'] = 'Kohdekansio';
$TEXT['TEMPLATE'] = 'Sivupohja';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Sivupohjat';
$TEXT['TEXT'] = 'Teksti';
$TEXT['TEXTAREA'] = 'Tekstialue';
$TEXT['TEXTFIELD'] = 'Tekstikentt&auml;';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Aika';
$TEXT['TIMEZONE'] = 'Aikavy&ouml;hyke';
$TEXT['TIME_FORMAT'] = 'Ajan muoto ';
$TEXT['TIME_LIMIT'] = 'Max time to gather excerpts per module';
$TEXT['TITLE'] = 'Otsikko';
$TEXT['TO'] = 'Minne';
$TEXT['TOP_FRAME'] = 'Frameset sprengen';
$TEXT['TRASH_EMPTIED'] = 'Tyhjennetty';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Edit the CSS definitions in the textarea below.';
$TEXT['TYPE'] = 'Tyyppi';
$TEXT['UNDER_CONSTRUCTION'] = 'Ty&ouml;n alla';
$TEXT['UNINSTALL'] = 'Poista';
$TEXT['UNKNOWN'] = 'Tuntematon';
$TEXT['UNLIMITED'] = 'Rajaton';
$TEXT['UNZIP_FILE'] = 'Upload and unpack a zip archive';
$TEXT['UP'] = 'Yl&ouml;s';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Lataa palvelimelle';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'K&auml;ytt&auml;j&auml;';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Varmistus';
$TEXT['VERSION'] = 'Versio';
$TEXT['VIEW'] = 'Katsele';
$TEXT['VIEW_DELETED_PAGES'] = 'N&auml;yt&auml; poistetut';
$TEXT['VIEW_DETAILS'] = 'N&auml;yt&auml; tiedot';
$TEXT['VISIBILITY'] = 'N&auml;kyvyys';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Default From Mail';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Default Sender Name';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Please specify a default "FROM" address and "SENDER" name below. It is recommended to use a FROM address like: <strong>admin@yourdomain.com</strong>. Some mail provider (e.g. <em>mail.com</em>) may reject mails with a FROM: address like <em>name@mail.com</em> sent via a foreign relay to avoid spam.<br /><br />The default values are only used if no other values are specified by WebsiteBaker. If your server supports <acronym title="Simple mail transfer protocol">SMTP</acronym>, you may want use this option for outgoing mails.';
$TEXT['WBMAILER_FUNCTION'] = 'Mail Routine';
$TEXT['WBMAILER_NOTICE'] = '<strong>SMTP Mailer Settings:</strong><br />The settings below are only required if you want to send mails via <acronym title="Simple mail transfer protocol">SMTP</acronym>. If you do not know your SMTP host or you are not sure about the required settings, simply stay with the default mail routine: PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP Authentification';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'only activate if your SMTP host requires authentification';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP Host';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP Password';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'www-sivu';
$TEXT['WEBSITE_DESCRIPTION'] = 'Sivuston kuvaus';
$TEXT['WEBSITE_FOOTER'] = 'Alatunniste';
$TEXT['WEBSITE_HEADER'] = 'Johdanto';
$TEXT['WEBSITE_KEYWORDS'] = 'Sivusaton avainsanat';
$TEXT['WEBSITE_TITLE'] = 'Sivuston otsikko';
$TEXT['WELCOME_BACK'] = 'N&auml;kemiin';
$TEXT['WIDTH'] = 'Leveys';
$TEXT['WINDOW'] = 'Ikkuna';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Kirjoitusoikeudet';
$TEXT['WRITE'] = 'Kirjoita';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG muokkain';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG tyyli';
$TEXT['YES'] = 'Kyll&auml;';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On requirements not met';
$HEADING['ADD_CHILD_PAGE'] = 'Add child page';
$HEADING['ADD_GROUP'] = 'Lis&auml;&auml; ryhm&auml;';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Lis&auml;&auml; otsikko';
$HEADING['ADD_PAGE'] = 'Lis&auml;&auml; sivu';
$HEADING['ADD_USER'] = 'Lis&auml;&auml; k&auml;ytt&auml;j&auml;';
$HEADING['ADMINISTRATION_TOOLS'] = 'Ty&ouml;kalut';
$HEADING['BROWSE_MEDIA'] = 'Selaa tiedostoja';
$HEADING['CREATE_FOLDER'] = 'Luo kansio';
$HEADING['DEFAULT_SETTINGS'] = 'Oletusasetukset';
$HEADING['DELETED_PAGES'] = 'Poistetut sivut';
$HEADING['FILESYSTEM_SETTINGS'] = 'Tiedostoj&auml;rjestelm&auml;';
$HEADING['GENERAL_SETTINGS'] = 'Asetukset';
$HEADING['INSTALL_LANGUAGE'] = 'Asenna kieli';
$HEADING['INSTALL_MODULE'] = 'Asenna moduuli';
$HEADING['INSTALL_TEMPLATE'] = 'Asenna sivupohja';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Execute module files manually';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Kielen tiedot';
$HEADING['MANAGE_SECTIONS'] = 'Muokkaa osia';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Muuta sivun lis&auml;asetuksia';
$HEADING['MODIFY_DELETE_GROUP'] = 'Muokkaa/poista ryhm&auml;';
$HEADING['MODIFY_DELETE_PAGE'] = 'Muokkaa/poista sivu';
$HEADING['MODIFY_DELETE_USER'] = 'Muokkaa/poista k&auml;ytt&auml;j&auml;';
$HEADING['MODIFY_GROUP'] = 'Muokkaa ryhm&auml;&auml;';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Muokkaa esisivua';
$HEADING['MODIFY_PAGE'] = 'Muokkaa sivua';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Muuta sivun asetuksia';
$HEADING['MODIFY_USER'] = 'Muokkaa k&auml;ytt&auml;j&auml;&auml;';
$HEADING['MODULE_DETAILS'] = 'Moduulin tietoja';
$HEADING['MY_EMAIL'] = 'S&auml;hk&ouml;postiosoite';
$HEADING['MY_PASSWORD'] = 'Salasana';
$HEADING['MY_SETTINGS'] = 'Omat tiedot';
$HEADING['SEARCH_SETTINGS'] = 'Etsinn&auml;n asetukset';
$HEADING['SERVER_SETTINGS'] = 'Palvelimen asetukset';
$HEADING['TEMPLATE_DETAILS'] = 'Sivupohjan info';
$HEADING['UNINSTALL_LANGUAGE'] = 'Poista kieli';
$HEADING['UNINSTALL_MODULE'] = 'Poista moduuli';
$HEADING['UNINSTALL_TEMPLATE'] = 'Poista sivupohja';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Lataa palvelimelle';
$HEADING['WBMAILER_SETTINGS'] = 'Mailer Settings';
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
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Oikeutesi eiv&auml;t riit&auml;...';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Salasanan vaiho vain kerran tunnissa!';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Salasanan postitus ei onnistu, ota yhteytt&auml; p&auml;&auml;k&auml;ytt&auml;j&auml;&auml;n';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Virheellinen s&auml;hk&ouml;postiosoite';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Anna s&auml;hk&ouml;postiosoite';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sorry, no active content to display';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Oikeutesi eiv&auml;t riit&auml;...';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Asennettu, uudelleen asennus ei onnistu';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Kohdekansioon ei voi kirjoittaa';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Ei voi poistaa';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Tiedosto k&auml;yt&ouml;ss&auml;, tiedostoa ei voi poistaa';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> could not be uninstalled, because it is still in use on {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'this page;these pages';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default template!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Zip-tiedostoa ei voi purkaa';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Lataus ei onnistu';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Virhe tiedostoa avattaessa.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Tiedostotyypin tulee olla jokin seuraavista:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Tiedostotyypin tulee olla jjokin seuraavista:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Palaa ja t&auml;yt&auml; kaikki kent&auml;t';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Asennettu';
$MESSAGE['GENERIC_INVALID'] = 'Ladatussa tiedostossa virhe';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Invalid WebsiteBaker installation file. Please check the *.zip format.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Invalid WebsiteBaker language file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Ei ole asennettu';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Hetkinen...';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Tervetuloa my&ouml;hemmin...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Security offense!! data storing was refused!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Poistettu';
$MESSAGE['GENERIC_UPGRADED'] = 'P&auml;ivitetty';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Verkkosivusto on ty&ouml;n alla';
$MESSAGE['GROUPS_ADDED'] = 'Ryhm&auml;n lis&auml;ys onnistui';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Haluatko poistaa ryhm&auml;n ja kakki sen k&auml;ytt&auml;j&auml;t?';
$MESSAGE['GROUPS_DELETED'] = 'Ryhm&auml; poistettu';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Ryhm&auml;n nimi puuttuu';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Ryhm&auml;n nimi varattu';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Ryhm&auml;&auml; ei l&ouml;ydy';
$MESSAGE['GROUPS_SAVED'] = 'Rym&auml; talletettu';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Salasana';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Liian pitk&auml; salasana';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Salasana liian lyhyt';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Et kirjoittanut tiedoston tarkennetta';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Et antanut nime&auml;';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Kansion poistamienen ei onnistu';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Tiedostoa ei voi poistaa';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Ei voinut uudelleen nimet&auml;';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Halutko poistaa tiedoston/kansion?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Kansio poistettu';
$MESSAGE['MEDIA_DELETED_FILE'] = ' Tiedosto pistettu';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Kansion nimi varattu';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Merkki ../ ei kelpaa ';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Saman niminen kansio olemassa';
$MESSAGE['MEDIA_DIR_MADE'] = 'Kansio luotu';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Kansiota ei voi luoda';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Saman niminen tiedosto olemassa';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Tiedostoa ei l&ouml;ydy';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Merkki&auml; ../ ei voi k&auml;ytt&auml;&auml;';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Nimi index.php ei kelpaa';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Tiedostokansio tyhj&auml;';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was recieved';
$MESSAGE['MEDIA_RENAMED'] = 'Udelleen nimetty';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' tiedosto ladattu';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Merkki&auml; ../ ei voi k&auml;ytt&auml;&auml; ';
$MESSAGE['MEDIA_UPLOADED'] = ' tiedostot ladattu';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Rajoitus voimassa, yrit&auml; tunnin kuluttua uudelleen';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'The verification number (also known as Captcha) that you entered is incorrect. If you are having problems reading the Captcha, please email: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'T&auml;yt&auml; kent&auml;t';
$MESSAGE['PAGES_ADDED'] = 'Sivu lis&auml;tty';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Otsikko lis&auml;tty';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Kirjoita valikkon tule teksti';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Kirjoita sivun nimi';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'K&auml;ytt&ouml;oiketesi eiv&auml;t riit&auml;';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Oikeutesi eiv&auml;t riit&auml;';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Sivujen j&auml;rjestely ei onnistu';
$MESSAGE['PAGES_DELETED'] = 'Sivu poistettu';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Poistetaanko sivu ja sen alisivut?';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Oikeutesi eiv&auml;t riit&auml;';
$MESSAGE['PAGES_INTRO_LINK'] = 'Muuta esisivua';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Ei voi kirjoittaa /pages/intro.php (oikeutesi eiv&auml;t riit&auml;)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Esisivu tallennettu';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'P&auml;ivitt&auml;nyt';
$MESSAGE['PAGES_NOT_FOUND'] = 'Sivua ei l&ouml;ydy';
$MESSAGE['PAGES_NOT_SAVED'] = 'Tannennusvirhe!';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Otsikko k&auml;yt&ouml;ss&auml;';
$MESSAGE['PAGES_REORDERED'] = 'Sivut j&auml;rjestelty uudelleen';
$MESSAGE['PAGES_RESTORED'] = 'Sivusto tallennettu';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Palaa sivuille...';
$MESSAGE['PAGES_SAVED'] = 'Sivu tallennettu';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Sivun asetukset tallennettu';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Asetukset tallennettu';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Nykyinen salasana v&auml;&auml;r&auml;';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Tiedot tallennettu';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'S&auml;hp&ouml;stiosoite p&auml;ivitetty';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Invalid password chars used';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Salasanan vaiho onnistui';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'The change of the record has missed.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'The changed record was updated successfully.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Adding a new record has missed.';
$MESSAGE['RECORD_NEW_SAVED'] = 'New record was added successfully.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Klikattaessa h&auml;vi&auml;v&auml;t kaikki tallettamattomat muutokset';
$MESSAGE['SETTINGS_SAVED'] = 'Asetusten talletus onnitui';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Konfigurointitiedostoa ei voi vavata';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Konfiguraation kirjoitus ei onnistu';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Susitellaan ainoastaan testitarkoituksiin';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
A new user was registered.

Loginname: {LOGIN_NAME}
UserId: {LOGIN_ID}
E-Mail: {LOGIN_EMAIL}
IP-Adress: {LOGIN_IP}
Registration date: {SIGNUP_DATE}
----------------------------------------
This message was automatic generated!

';
$MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'] = '
Hello {LOGIN_DISPLAY_NAME},

This mail was sent because the \'forgot password\' function has been applied to your account.

Your new \'{LOGIN_WEBSITE_TITLE}\' login details are:

Loginname: {LOGIN_NAME}
Password: {LOGIN_PASSWORD}

Your password has been reset to the one above.
This means that your old password will no longer work anymore!
If you\'ve got any questions or problems within the new login-data
you should contact the website-team or the admin of \'{LOGIN_WEBSITE_TITLE}\'.
Please remember to clean you browser-cache before using the new one to avoid unexpected fails.

Regards
------------------------------------
This message was automatic generated

';
$MESSAGE['SIGNUP2_BODY_LOGIN_INFO'] = '
Hello {LOGIN_DISPLAY_NAME},

Welcome to our \'{LOGIN_WEBSITE_TITLE}\'.

Your \'{LOGIN_WEBSITE_TITLE}\' login details are:
Loginname: {LOGIN_NAME}
Password: {LOGIN_PASSWORD}

Regards

Please:
if you have received this message by an error, please delete it immediately!
-------------------------------------
This message was automatic generated!
';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Your login details...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Anna s&auml;hk&ouml;postiosoite';
$MESSAGE['START_CURRENT_USER'] = 'Olet kirjautunut nimell&auml;:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Poista asennuskansio!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Tervetuloa sivuston hallintaan';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Sivupohjan voi vaihtaa asetukset-kohdasta';
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
$MESSAGE['USERS_ADDED'] = 'Lis&auml;tty';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Function rejected, You can not delete yourself!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Jos haluat vaihtaa salasanan, t&auml;yt&auml; vain ko kent&auml;t';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Haluatko poistaa k&auml;ytt&auml;j&auml;n?';
$MESSAGE['USERS_DELETED'] = 'Poistettu';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'S&auml;hk&ouml;postiosoite k&auml;yt&ouml;ss&auml;!';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Virheellinen s&auml;hk&ouml;postiosoite';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Valitse ryhm&auml;!';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Salasanat eiv&auml;t t&auml;sm&auml;&auml;';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Salasana liian lyhyt';
$MESSAGE['USERS_SAVED'] = 'Talletettu';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'Access the WebsiteBaker administration tools...';
$OVERVIEW['GROUPS'] = 'k&auml;ytt&auml;j&auml;ryhm&auml;t...';
$OVERVIEW['HELP'] = 'Kysymykset, vastaukset...';
$OVERVIEW['LANGUAGES'] = 'Muuta kieli...';
$OVERVIEW['MEDIA'] = 'Tiedostojen hallinta...';
$OVERVIEW['MODULES'] = 'Moduulien hallinta...';
$OVERVIEW['PAGES'] = 'Sivujen hallinta...';
$OVERVIEW['PREFERENCES'] = 'S&auml;hk&ouml;postiosoite, salsana... ';
$OVERVIEW['SETTINGS'] = 'WebsiteBakerin asetukset...';
$OVERVIEW['START'] = 'P&auml;&auml;k&auml;ytt&auml;j&auml;tila';
$OVERVIEW['TEMPLATES'] = 'Muuta sivupohjaa...';
$OVERVIEW['USERS'] = 'K&auml;ytt&auml;j&auml;hallinta...';
$OVERVIEW['VIEW'] = 'Tarkastele sivuja...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
