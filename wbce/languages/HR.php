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
 * @version         $Id: HR.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/HR.php $
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
$language_code = 'HR';
$language_name = 'Hrvatski';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Vedran Presecki';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Pristup';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Dodaci';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Dobivanje detalja lozinke';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Grupe';
$MENU['HELP'] = 'Pomo&aelig;';
$MENU['LANGUAGES'] = 'Jezici';
$MENU['LOGIN'] = 'Logiranje';
$MENU['LOGOUT'] = 'Odlogiranje';
$MENU['MEDIA'] = 'Media';
$MENU['MODULES'] = 'Moduli';
$MENU['PAGES'] = 'Stranice';
$MENU['PREFERENCES'] = 'Pode&scaron;avanja';
$MENU['SETTINGS'] = 'Postavke';
$MENU['START'] = 'Start';
$MENU['TEMPLATES'] = 'Predlo&scaron;ci';
$MENU['USERS'] = 'Korisnici';
$MENU['VIEW'] = 'Pogled';
$TEXT['ACCOUNT_SIGNUP'] = 'Logiranje na Account';
$TEXT['ACTIONS'] = 'Akcije';
$TEXT['ACTIVE'] = 'Aktivan';
$TEXT['ADD'] = 'Dodaj';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'Dodaj sekciju';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Administracija';
$TEXT['ADMINISTRATION_TOOL'] = 'Administracijski alati';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administratori';
$TEXT['ADVANCED'] = 'Napredno';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Dopu&scaron;teni promatra&egrave;i';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Dopusti vi&scaron;estruki odabir';
$TEXT['ALL_WORDS'] = 'Sve rije&egrave;i';
$TEXT['ANCHOR'] = 'Anchor';
$TEXT['ANONYMOUS'] = 'anoniman';
$TEXT['ANY_WORDS'] = 'Neke rije&egrave;i';
$TEXT['APP_NAME'] = 'Ime aplikacije';
$TEXT['ARE_YOU_SURE'] = 'Jeste li sigurni?';
$TEXT['AUTHOR'] = 'Autor';
$TEXT['BACK'] = 'Nazad';
$TEXT['BACKUP'] = 'Backup';
$TEXT['BACKUP_ALL_TABLES'] = 'Backupiraj sve tablice u bazi podataka';
$TEXT['BACKUP_DATABASE'] = 'Backup baze podataka';
$TEXT['BACKUP_MEDIA'] = 'Backup Media';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Backupiraj samo WB-specificirane tablice';
$TEXT['BASIC'] = 'Osnovno';
$TEXT['BLOCK'] = 'Blokiraj';
$TEXT['CALENDAR'] = 'Calender';
$TEXT['CANCEL'] = 'Otka&#382;i';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha verifikacija';
$TEXT['CAP_EDIT_CSS'] = 'Edit CSS';
$TEXT['CHANGE'] = 'Izmjeni';
$TEXT['CHANGES'] = 'Izmjene';
$TEXT['CHANGE_SETTINGS'] = 'Promjeni postavke';
$TEXT['CHARSET'] = 'Postavka znakova';
$TEXT['CHECKBOX_GROUP'] = 'Ozna&egrave;i kvadrat grupe';
$TEXT['CLOSE'] = 'Zatvori';
$TEXT['CODE'] = 'Kod';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'Kolaps';
$TEXT['COMMENT'] = 'Komentar';
$TEXT['COMMENTING'] = 'Komentiranje';
$TEXT['COMMENTS'] = 'Komentari';
$TEXT['CREATE_FOLDER'] = 'napravi direktorij';
$TEXT['CURRENT'] = 'Postoje&aelig;i';
$TEXT['CURRENT_FOLDER'] = 'Postoje&aelig;i direktorij';
$TEXT['CURRENT_PAGE'] = 'Trenutna stranica';
$TEXT['CURRENT_PASSWORD'] = 'Potoje&aelig;a lozinka';
$TEXT['CUSTOM'] = 'Korisni&egrave;ki';
$TEXT['DATABASE'] = 'Baza podataka';
$TEXT['DATE'] = 'Datum';
$TEXT['DATE_FORMAT'] = 'Format datuma';
$TEXT['DEFAULT'] = 'Postoje&aelig;i';
$TEXT['DEFAULT_CHARSET'] = 'Po&egrave;etna postavka znakova';
$TEXT['DEFAULT_TEXT'] = 'Postoje&aelig;i tekstt';
$TEXT['DELETE'] = 'Obri&scaron;i';
$TEXT['DELETED'] = 'Obrisan';
$TEXT['DELETE_DATE'] = 'Delete date';
$TEXT['DELETE_ZIP'] = 'Delete zip archive after unpacking';
$TEXT['DESCRIPTION'] = 'Opis';
$TEXT['DESIGNED_FOR'] = 'Dizajniran za';
$TEXT['DIRECTORIES'] = 'direktoriji';
$TEXT['DIRECTORY_MODE'] = 'Mod direktorija';
$TEXT['DISABLED'] = 'Onesposobljen';
$TEXT['DISPLAY_NAME'] = 'Prika&#382;i ime';
$TEXT['EMAIL'] = 'Email';
$TEXT['EMAIL_ADDRESS'] = 'Email adresa';
$TEXT['EMPTY_TRASH'] = 'Isprazni sme&aelig;e';
$TEXT['ENABLED'] = 'Omogu&aelig;en';
$TEXT['END'] = 'Kraj';
$TEXT['ERROR'] = 'Gre&scaron;ka';
$TEXT['EXACT_MATCH'] = 'To&egrave;no odgovara';
$TEXT['EXECUTE'] = 'Izvr&scaron;i';
$TEXT['EXPAND'] = 'Pro&scaron;iri';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['FIELD'] = 'Polje';
$TEXT['FILE'] = 'File';
$TEXT['FILES'] = 'Fileovi';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Dopu&scaron;tanja sitema fileova';
$TEXT['FILE_MODE'] = 'File Mod';
$TEXT['FINISH_PUBLISHING'] = 'Zavr&scaron;i objavljivanje';
$TEXT['FOLDER'] = 'Direktorij';
$TEXT['FOLDERS'] = 'Direktoriji';
$TEXT['FOOTER'] = 'Podno&#382;je';
$TEXT['FORGOTTEN_DETAILS'] = 'Zaboravili ste va&scaron;e podatke?';
$TEXT['FORGOT_DETAILS'] = 'Zaboravili ste datelje?';
$TEXT['FROM'] = 'Od';
$TEXT['FRONTEND'] = 'Po&egrave;etak-kraj';
$TEXT['FULL_NAME'] = 'Puno ime';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Grupa';
$TEXT['HEADER'] = 'Zaglavlje';
$TEXT['HEADING'] = 'Zaglavlje';
$TEXT['HEADING_CSS_FILE'] = 'Actual module file: ';
$TEXT['HEIGHT'] = 'Visina';
$TEXT['HIDDEN'] = 'Skriven';
$TEXT['HIDE'] = 'Sakrij';
$TEXT['HIDE_ADVANCED'] = 'Sakrij napredne opcije';
$TEXT['HOME'] = 'Po&egrave;etak';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Redirekcija po&egrave;etne stranice';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Ikona';
$TEXT['IMAGE'] = 'Slika';
$TEXT['INLINE'] = 'U liniji';
$TEXT['INSTALL'] = 'Instaliraj';
$TEXT['INSTALLATION'] = 'Instalacija';
$TEXT['INSTALLATION_PATH'] = 'Instalacijski dio';
$TEXT['INSTALLATION_URL'] = 'Instalacija URL';
$TEXT['INSTALLED'] = 'installed';
$TEXT['INTRO'] = 'Intro';
$TEXT['INTRO_PAGE'] = 'Intro Stranica';
$TEXT['INVALID_SIGNS'] = 'must begin with a letter or has invalid signs';
$TEXT['KEYWORDS'] = 'Klju&egrave;ne rije&egrave;i';
$TEXT['LANGUAGE'] = 'Jezik';
$TEXT['LAST_UPDATED_BY'] = 'Zadnje izmjenjen od';
$TEXT['LENGTH'] = 'Du&#382;ina';
$TEXT['LEVEL'] = 'Nivo';
$TEXT['LINK'] = 'Link';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix baziran';
$TEXT['LIST_OPTIONS'] = 'Lista opcija';
$TEXT['LOGGED_IN'] = 'Logiran';
$TEXT['LOGIN'] = 'Logiranje';
$TEXT['LONG'] = 'Dugo';
$TEXT['LONG_TEXT'] = 'Dugi tekst';
$TEXT['LOOP'] = 'Petlja';
$TEXT['MAIN'] = 'Glevni';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'Upravljaj';
$TEXT['MANAGE_GROUPS'] = 'Upravljanje grupama';
$TEXT['MANAGE_USERS'] = 'Upravljanje korisnicima';
$TEXT['MATCH'] = 'Usporedi';
$TEXT['MATCHING'] = 'Podudaranje';
$TEXT['MAX_EXCERPT'] = 'Max lines of excerpt';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Maximalan podpristup po satu';
$TEXT['MEDIA_DIRECTORY'] = 'Direktorij medije';
$TEXT['MENU'] = 'Meni';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Naslov menia';
$TEXT['MESSAGE'] = 'Poruka';
$TEXT['MODIFY'] = 'Izmjeni';
$TEXT['MODIFY_CONTENT'] = 'Izmjeni sadr&#382;aj';
$TEXT['MODIFY_SETTINGS'] = 'Izmjeni postavke';
$TEXT['MODULE_ORDER'] = 'Module-order for searching';
$TEXT['MODULE_PERMISSIONS'] = 'Modulske dozvole';
$TEXT['MORE'] = 'Vi&scaron;e';
$TEXT['MOVE_DOWN'] = 'Spusti dolje';
$TEXT['MOVE_UP'] = 'Podigni gore';
$TEXT['MULTIPLE_MENUS'] = 'Ve&scaron;estruki menii';
$TEXT['MULTISELECT'] = 'Vi&scaron;estruki odabir';
$TEXT['NAME'] = 'Ime';
$TEXT['NEED_CURRENT_PASSWORD'] = 'confirm with current password';
$TEXT['NEED_TO_LOGIN'] = 'Molimo logirajte se?';
$TEXT['NEW_PASSWORD'] = 'Nova lozinka';
$TEXT['NEW_WINDOW'] = 'Novi prozor';
$TEXT['NEXT'] = 'Slijede&aelig;i';
$TEXT['NEXT_PAGE'] = 'Nova stranica';
$TEXT['NO'] = 'Ne';
$TEXT['NONE'] = 'Nijedan';
$TEXT['NONE_FOUND'] = 'Nijedan na&eth;en';
$TEXT['NOT_FOUND'] = 'Neprona&eth;eno';
$TEXT['NOT_INSTALLED'] = 'not installed';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Nema rezultata';
$TEXT['OF'] = 'Of';
$TEXT['ON'] = 'On';
$TEXT['OPEN'] = 'Open';
$TEXT['OPTION'] = 'Opcija';
$TEXT['OTHERS'] = 'Drugi';
$TEXT['OUT_OF'] = 'Izvan Of';
$TEXT['OVERWRITE_EXISTING'] = 'Napi&scaron;ite preko postoje&aelig;eg';
$TEXT['PAGE'] = 'Strenica';
$TEXT['PAGES_DIRECTORY'] = 'Direktorij stranica';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'EKstenzije stranice';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Jezici stranice';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Nivo limita stranice';
$TEXT['PAGE_SPACER'] = 'Razmaknica stranica';
$TEXT['PAGE_TITLE'] = 'Naslov stranice';
$TEXT['PAGE_TRASH'] = 'Sme&aelig;e stranice';
$TEXT['PARENT'] = 'Vezan';
$TEXT['PASSWORD'] = 'Lozinka';
$TEXT['PATH'] = 'Dio';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP Gre&scaron;ka Izvje&scaron;taj nivoa';
$TEXT['PLEASE_LOGIN'] = 'Please login';
$TEXT['PLEASE_SELECT'] = 'Odaberite';
$TEXT['POST'] = 'Post';
$TEXT['POSTS_PER_PAGE'] = 'Broj objava po stranici';
$TEXT['POST_FOOTER'] = 'Objavi podno&#382;je';
$TEXT['POST_HEADER'] = 'Objavi zaglavlje';
$TEXT['PREVIOUS'] = 'Prethodni';
$TEXT['PREVIOUS_PAGE'] = 'Prethodna stranica';
$TEXT['PRIVATE'] = 'Privatni';
$TEXT['PRIVATE_VIEWERS'] = 'Privatni pregledatelji';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'Javni';
$TEXT['PUBL_END_DATE'] = 'End date';
$TEXT['PUBL_START_DATE'] = 'Start date';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radio gumb grupe';
$TEXT['READ'] = '&Egrave;itaj';
$TEXT['READ_MORE'] = '&Egrave;itaj vi&scaron;e';
$TEXT['REDIRECT_AFTER'] = 'Redirect after';
$TEXT['REGISTERED'] = 'Registriran';
$TEXT['REGISTERED_VIEWERS'] = 'Registrirani promatra&egrave;i';
$TEXT['RELOAD'] = 'Ponovo u&egrave;itavanje';
$TEXT['REMEMBER_ME'] = 'Sjeti me';
$TEXT['RENAME'] = 'Preimenuj';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Tra&#382;eno';
$TEXT['REQUIREMENT'] = 'Requirement';
$TEXT['RESET'] = 'Resetiraj';
$TEXT['RESIZE'] = 'Izmjeni veli&egrave;inu';
$TEXT['RESIZE_IMAGE_TO'] = 'Izmjeni veli&egrave;inu slike na';
$TEXT['RESTORE'] = 'Povrati';
$TEXT['RESTORE_DATABASE'] = 'Povrati bazu podataka';
$TEXT['RESTORE_MEDIA'] = 'Povrati Media';
$TEXT['RESULTS'] = 'Rezultati';
$TEXT['RESULTS_FOOTER'] = 'Rezultati podno&#382;ja';
$TEXT['RESULTS_FOR'] = 'Rezultati za';
$TEXT['RESULTS_HEADER'] = 'Rezultati zaglavlja';
$TEXT['RESULTS_LOOP'] = 'Rezultati petlje';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Ponovo otipkaj novu lozinku';
$TEXT['RETYPE_PASSWORD'] = 'Ponovo otipkaj novu lozinku';
$TEXT['SAME_WINDOW'] = 'Isti prozor';
$TEXT['SAVE'] = 'Snimi';
$TEXT['SEARCH'] = 'Tra&#382;i';
$TEXT['SEARCHING'] = 'Pretra&#382;ivanje';
$TEXT['SECTION'] = 'Dio';
$TEXT['SECTION_BLOCKS'] = 'Kvadrati sekcije';
$TEXT['SEC_ANCHOR'] = 'Section-Anchor text';
$TEXT['SELECT_BOX'] = 'Ozna&egrave;i kvadrat';
$TEXT['SEND_DETAILS'] = '&Scaron;aljite podatke';
$TEXT['SEPARATE'] = 'Odvojen';
$TEXT['SEPERATOR'] = 'Odvajanje';
$TEXT['SERVER_EMAIL'] = 'Server Email';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Serverski operacijski sutav';
$TEXT['SESSION_IDENTIFIER'] = 'Session Identifier';
$TEXT['SETTINGS'] = 'Postavke';
$TEXT['SHORT'] = 'Kratko';
$TEXT['SHORT_TEXT'] = 'Kratki tekst';
$TEXT['SHOW'] = 'Prika&#382;i';
$TEXT['SHOW_ADVANCED'] = 'Prika&#382;i napredne opcije';
$TEXT['SIGNUP'] = 'Upi&scaron;i se';
$TEXT['SIZE'] = 'Veli&egrave;ina';
$TEXT['SMART_LOGIN'] = 'Inteligentno logiranje';
$TEXT['START'] = 'Start';
$TEXT['START_PUBLISHING'] = 'Zapo&egrave;ni objavljivanje';
$TEXT['SUBJECT'] = 'Subjekt';
$TEXT['SUBMISSIONS'] = 'Podpristupe';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Podpristupi pohranjeni u bazi podataka';
$TEXT['SUBMISSION_ID'] = 'Podpristupni ID';
$TEXT['SUBMITTED'] = 'Pristupljen';
$TEXT['SUCCESS'] = 'Uspjeh';
$TEXT['SYSTEM_DEFAULT'] = 'Postoje&aelig;i sistem';
$TEXT['SYSTEM_PERMISSIONS'] = 'Sistemske dozvole';
$TEXT['TABLE_PREFIX'] = 'Prefix tablice';
$TEXT['TARGET'] = 'Cilj';
$TEXT['TARGET_FOLDER'] = 'Ciljani direktorij';
$TEXT['TEMPLATE'] = 'Predlo&#382;ak';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Pristup predlo&scaron;cima';
$TEXT['TEXT'] = 'Tekst';
$TEXT['TEXTAREA'] = 'Podru&egrave;je teksta';
$TEXT['TEXTFIELD'] = 'Pole teksta';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Vrijeme';
$TEXT['TIMEZONE'] = 'Vremenska zona';
$TEXT['TIME_FORMAT'] = 'Format vrmena';
$TEXT['TIME_LIMIT'] = 'Max time to gather excerpts per module';
$TEXT['TITLE'] = 'Naslov';
$TEXT['TO'] = 'Za';
$TEXT['TOP_FRAME'] = 'Gornji okvir';
$TEXT['TRASH_EMPTIED'] = 'Sme&aelig;e ispra&#382;njeno';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Edit the CSS definitions in the textarea below.';
$TEXT['TYPE'] = 'Tip';
$TEXT['UNDER_CONSTRUCTION'] = 'U izradi';
$TEXT['UNINSTALL'] = 'Deinstaliraj';
$TEXT['UNKNOWN'] = 'Nepoznat';
$TEXT['UNLIMITED'] = 'Neograni&egrave;en';
$TEXT['UNZIP_FILE'] = 'Upload and unpack a zip archive';
$TEXT['UP'] = 'Gore';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Nasnimi fajlove)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Korisnik';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Verifikacija';
$TEXT['VERSION'] = 'VVerzija';
$TEXT['VIEW'] = 'Pogled';
$TEXT['VIEW_DELETED_PAGES'] = 'Pogledaj obrisane stranice';
$TEXT['VIEW_DETAILS'] = 'Vidi detalje';
$TEXT['VISIBILITY'] = 'Vidljivost';
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
$TEXT['WEBSITE'] = 'Web stranica';
$TEXT['WEBSITE_DESCRIPTION'] = 'Opis web stranice';
$TEXT['WEBSITE_FOOTER'] = 'Podno&#382;je web stranice';
$TEXT['WEBSITE_HEADER'] = 'Zaglavlje web stranice';
$TEXT['WEBSITE_KEYWORDS'] = 'Klju&egrave;ne rije&egrave;i web stranice';
$TEXT['WEBSITE_TITLE'] = 'Ime web stranice';
$TEXT['WELCOME_BACK'] = 'Dobro do&scaron;li nazad';
$TEXT['WIDTH'] = '&Scaron;irina';
$TEXT['WINDOW'] = 'Prozor';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'World-zapisuju&aelig;i prisup fileovima';
$TEXT['WRITE'] = 'Pi&scaron;i';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG Editor';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG Style';
$TEXT['YES'] = 'Da';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On requirements not met';
$HEADING['ADD_CHILD_PAGE'] = 'Add child page';
$HEADING['ADD_GROUP'] = 'Dodaj grupu';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Dodaj zaglavlje';
$HEADING['ADD_PAGE'] = 'Dodaj stranicu';
$HEADING['ADD_USER'] = 'Dodaj korisnika';
$HEADING['ADMINISTRATION_TOOLS'] = 'Administracijski alati';
$HEADING['BROWSE_MEDIA'] = 'Pogledaj Mediu';
$HEADING['CREATE_FOLDER'] = 'napravi direktorij';
$HEADING['DEFAULT_SETTINGS'] = 'Prija&scaron;nje postavke';
$HEADING['DELETED_PAGES'] = 'Obrisane stranice';
$HEADING['FILESYSTEM_SETTINGS'] = 'Postavke sistema direktorija';
$HEADING['GENERAL_SETTINGS'] = 'Glavne postavke';
$HEADING['INSTALL_LANGUAGE'] = 'Instaliraj jezik';
$HEADING['INSTALL_MODULE'] = 'Instaliraj module';
$HEADING['INSTALL_TEMPLATE'] = 'Instaliraj predlo&#382;ak';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Execute module files manually';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Detalji jezika';
$HEADING['MANAGE_SECTIONS'] = 'Upravljaj dijelovima';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Mijenjaj napredne postavke stranice';
$HEADING['MODIFY_DELETE_GROUP'] = 'Izmjeni/Obri&scaron;i Grupu';
$HEADING['MODIFY_DELETE_PAGE'] = 'Izmenj/Obri&scaron;i stranicu';
$HEADING['MODIFY_DELETE_USER'] = 'Izmjeni/Obri&scaron;i korisnika';
$HEADING['MODIFY_GROUP'] = 'Izmjeni grupu';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Modificiraj intro stranicu';
$HEADING['MODIFY_PAGE'] = 'Izmjeni stranicu';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Mijenjaj postavke stranice';
$HEADING['MODIFY_USER'] = 'Izmjeni korisnika';
$HEADING['MODULE_DETAILS'] = 'Detalji modula';
$HEADING['MY_EMAIL'] = 'Moj Email';
$HEADING['MY_PASSWORD'] = 'Moja Lozinka';
$HEADING['MY_SETTINGS'] = 'Moje postavke';
$HEADING['SEARCH_SETTINGS'] = 'Tra&#382;enje postavki';
$HEADING['SERVER_SETTINGS'] = 'Postavke servera';
$HEADING['TEMPLATE_DETAILS'] = 'Detalji predlo&scaron;ka';
$HEADING['UNINSTALL_LANGUAGE'] = 'Deinstaliraj jezik';
$HEADING['UNINSTALL_MODULE'] = 'Deinstaliraj module';
$HEADING['UNINSTALL_TEMPLATE'] = 'Deinstaliraj predlo&#382;ak';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Nasnimi fileove';
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
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Nedovoljne privilegije tu';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Na&#382;alost lozinka ne mo&#382;e biti resetirana/izmjenjena vi&scaron;e od jednom u jednom satu';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Ne mo&#382;emo vam emailom poslati lozinku, molimo kontakirajte sistemskog administratora';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Email adresu koju ste unjeli nemamo upisanu u bazi';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Unesite svoju email adresu ispod';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sorry, no active content to display';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Nemate dopu&scaron;tenje za gledanje ove stranice';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Ve&aelig; instalirano';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Ne mo&#382;e zapisati u ciljani direktorij';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Ne mo&#382;e deinstalirati';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Ne mo&#382;e deinstalirati: odabrani file je trenutno u upotrebi';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> could not be uninstalled, because it is still in use on {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'this page;these pages';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default template!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Ne mo&#382;e unzipirati file';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Ne mo&#382;e nasnimiti file';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Gre&scaron;ka pri otvaranju filea.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'File koji nasnimavate mora biti slijede&aelig;eg formata:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'File koji nasnimavate mora biti u jednom od slijede&aelig;ih formata:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Molimo, vratite se nazad i popunite sva polja';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Instaliran uspje&scaron;no';
$MESSAGE['GENERIC_INVALID'] = 'Instaliran file je nevaljal';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Invalid WebsiteBaker installation file. Please check the *.zip format.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Invalid WebsiteBaker language file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Nije instalirano';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Budite strpljivo, ovo mo&#382;e potrajati.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Molimo poku&scaron;ajte ponovo za&egrave;as...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Security offense!! data storing was refused!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Deinstaliran uspje&scaron;no';
$MESSAGE['GENERIC_UPGRADED'] = 'Nadogra&eth;en uspje&scaron;no';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Webstranica u izradi';
$MESSAGE['GROUPS_ADDED'] = 'Grupa je uspje&scaron;no dodana';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Jeste li sigurni da &#382;elite obrisati odabranu gurupu i sve korisnike koji joj pripadaju?';
$MESSAGE['GROUPS_DELETED'] = 'Grupa je uspje&scaron;no obrisana';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Ime grupe je prazno';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Ime grupe ve&aelig; postoji';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Grupa nije na&eth;ena';
$MESSAGE['GROUPS_SAVED'] = 'Grupa je uspje&scaron;no snimljena';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Molimo unesite svoju lozinku';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Lozinka je preduga';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Lozinka je prekratka';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Niste unjeli ekstenziju file-a';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Niste unjeli novo ime';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Ne mo&#382;e obrisati odabrani direktorij';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Ne mo&#382;e obrisati odabrani file';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Preimenovanje je neuspje&scaron;no';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Jeste li sigurni da &#382;elite obrisati file ili direktorij?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Folder je uspje&scaron;no obrisan';
$MESSAGE['MEDIA_DELETED_FILE'] = 'File je uspje&scaron;no obrisan';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Direktorij ne postoji';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Ne mo&#382;e uklju&egrave;iti ../ u ime direktorija';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Direktorij se podudara s imenom koje ste unjeli, a koje ve&aelig; postoji';
$MESSAGE['MEDIA_DIR_MADE'] = 'Direktorij je uspje&scaron;no stvoren';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Ne mo&#382;e napraviti direktorij';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'File se podudara s imenom koje ste unjeli, a koje ve&aelig; postoji';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'File nije prona&eth;en';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Ne mo&#382;e uklju&egrave;iti ../ u ime';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Ne mo&#382;e koristiti index.php kao ime';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Ni jedna medija nije na&eth;ena u postoje&aelig;em direktoriju';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was recieved';
$MESSAGE['MEDIA_RENAMED'] = 'Preimenovanje je uspje&scaron;no';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' file je uspje&scaron;no nasnimljen';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Ne mo&#382;e ../ u cilj direktorija';
$MESSAGE['MEDIA_UPLOADED'] = ' fileovi su supje&scaron;no nasnimljeni';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Ova forma je pregledavana previ&scaron;e puta u jednom satu. Molimo poku&scaron;ajte slijede&aelig;i sat.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Broj provjere (poznat kao Captcha) neto&egrave;no je une&scaron;en. Ako imate problema s &egrave;itanjem Captcha, molimo po&scaron;aljite email: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Morate unjeti detaljen podatke u nadoilaze&aelig;a polja';
$MESSAGE['PAGES_ADDED'] = 'Stranica je uspje&scaron;no dodana';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Zaglavlje stranice uspje&scaron;no je dodano';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Unesite naziv menia';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Unesite naslov stranice';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Gre&scaron;ka pri stvaranju pristupnog filea u stranicama direktorija(nedovoljne privilegije)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Gre&scaron;ka pri brisanju pristupnog filea u stranicama direktorija(nedovoljne privilegije)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Gre&scaron;ka pri re-ordering stranice';
$MESSAGE['PAGES_DELETED'] = 'Stranice su supje&scaron;no obrisane';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Jeste li sigurni da &#382;elite obrisati odabranu stranicu i sve njene podstranice';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Nemate dopu&scaron;tenje za izmjenu stranice';
$MESSAGE['PAGES_INTRO_LINK'] = 'Kliknite OVDJE za izmjenu intro stranice';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Ne mo&#382;e pisati file /pages/intro.php (nedovoljne privilegije)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Intro stranica je uspje&scaron;no snimljena';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Zadnje izmjene';
$MESSAGE['PAGES_NOT_FOUND'] = 'Stranica nije na&eth;ena';
$MESSAGE['PAGES_NOT_SAVED'] = 'Gre&scaron;ka pri snimanju stranice';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Stranica s sli&egrave;nim ili istim imenom ve&aelig; postoji';
$MESSAGE['PAGES_REORDERED'] = 'Stranice re-ordered uspje&scaron;no';
$MESSAGE['PAGES_RESTORED'] = 'Stranice su supje&scaron;no obnovljene';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Povratak na stranice';
$MESSAGE['PAGES_SAVED'] = 'Stranica je uspje&scaron;no snimljena';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Postavke stranice uspje&scaron;no su snimljene';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Sekcijske postavke snimljene uspje&scaron;no';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Une&scaron;ena lozinka nije to&egrave;na';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Detalji su uspje&scaron;no snimljeni';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'Email je snimljen uspje&scaron;no';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Invalid password chars used';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Lozinka je uspje&scaron;no izmjenjena';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'The change of the record has missed.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'The changed record was updated successfully.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Adding a new record has missed.';
$MESSAGE['RECORD_NEW_SAVED'] = 'New record was added successfully.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Obavijest: Pritisnite ovaj gumb za reset svih nesnimljenih izmjena';
$MESSAGE['SETTINGS_SAVED'] = 'Postavke su uspje&scaron;no snimljene';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Nemogu&aelig;e je otvoriti konfiguracijski file';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Ne mo&#382;e zapisivati u konfiguracijski file';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Obavijest: ovo je preporu&egrave;ljivo samo za uvijete testiranja';
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
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Unesite email adresu';
$MESSAGE['START_CURRENT_USER'] = 'Trenutno ste logirani kao:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Upozorenje, instalacijski direktoriji nije jo&scaron; obrisan!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Dobro do&scaron;li u WebsiteBaker administraciju';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Obavijest: Za promjenu predlo&scaron;ka idite na dio s Postavkama';
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
$MESSAGE['USERS_ADDED'] = 'Korisnik je dodan supje&scaron;no';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Function rejected, You can not delete yourself!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Obavijest: Trebate samo unjeti vrijednosti u polja ispod ako &#382;elite izmjeniti korisni&egrave;ku lozinku';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Jeste li sigurni da &#382;elite obrisati odabranog korisnika?';
$MESSAGE['USERS_DELETED'] = 'Korisnik je uspje&scaron;no obrisan';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Une&scaron;en email je ve&aelig; u upotrebi';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Une&scaron;ena email adresa je nepotpuna';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Niti jedna grupa nije odabrana';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Une&scaron;ena lozinka ne odgovara';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Predlo&#382;ena lozinka je prekratka';
$MESSAGE['USERS_SAVED'] = 'Korisnik je snimljen uspje&scaron;no';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'Access the WebsiteBaker administration tools...';
$OVERVIEW['GROUPS'] = 'Upravljajte grupama korisnika i njihovim sistemskim dopu&scaron;tenjima.';
$OVERVIEW['HELP'] = 'Imate pitanje? Prona&eth;ite odgovor...';
$OVERVIEW['LANGUAGES'] = 'Uredite WebsiteBaker jezike...';
$OVERVIEW['MEDIA'] = 'Uredite fileove pohranjene u direktoriju "Media"...';
$OVERVIEW['MODULES'] = 'Uredite WebsiteBaker module...';
$OVERVIEW['PAGES'] = 'Uredite va&scaron;e web stranice...';
$OVERVIEW['PREFERENCES'] = 'Izmjenite postavke email adresa, lozinka i sl.... ';
$OVERVIEW['SETTINGS'] = 'Promjenite postavke za WebsiteBaker...';
$OVERVIEW['START'] = 'Pregled administracije';
$OVERVIEW['TEMPLATES'] = 'Promijenite izgled i do&#382;ivljaj va&scaron;eg weba s predlo&scaron;cima...';
$OVERVIEW['USERS'] = 'Upravljajte korisnicima koji se mogu logirati na WebsiteBaker...';
$OVERVIEW['VIEW'] = 'Brzo pogledajte i listajte Va&scaron; web u novom prozoru...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
