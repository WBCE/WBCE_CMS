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
 * @version         $Id: HU.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/HU.php $
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
$language_code = 'HU';
$language_name = 'Magyar';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Zsolt + Robert';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Jogosults&aacute;gok';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Kieg&eacute;sz&iacute;t-?-?';
$MENU['ADMINTOOLS'] = 'Admin-Eszk&ouml;z&ouml;k';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Elfelejtett jelsz&oacute;';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Csoportok';
$MENU['HELP'] = 'S&uacute;g&oacute;';
$MENU['LANGUAGES'] = 'Nyelvek';
$MENU['LOGIN'] = 'Bel&eacute;p&eacute;s';
$MENU['LOGOUT'] = 'Kil&eacute;p&eacute;s';
$MENU['MEDIA'] = 'M&eacute;dia';
$MENU['MODULES'] = 'Modulok';
$MENU['PAGES'] = 'Weblapok';
$MENU['PREFERENCES'] = 'Saj&aacute;t adatok';
$MENU['SETTINGS'] = 'Param&eacute;terek';
$MENU['START'] = 'Kezd-?-?ap';
$MENU['TEMPLATES'] = 'Sablonok';
$MENU['USERS'] = 'Felhaszn&aacute;l&oacute;k';
$MENU['VIEW'] = 'Port&aacute;l n&eacute;zet';
$TEXT['ACCOUNT_SIGNUP'] = 'Fi&oacute;k L&eacute;trehoz&aacute;s';
$TEXT['ACTIONS'] = 'Tev&eacute;kenys&eacute;gek';
$TEXT['ACTIVE'] = 'Akt&iacute;v';
$TEXT['ADD'] = 'Hozz&aacute;ad';
$TEXT['ADDON'] = 'Kig&eacute;sz&iacute;t&ccedil;';
$TEXT['ADD_SECTION'] = 'Szakasz hozz&aacute;ad&aacute;sa';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Adminisztr&aacute;l&aacute;s';
$TEXT['ADMINISTRATION_TOOL'] = 'Adminisztr&aacute;ci&oacute;s Eszk&ouml;z';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Adminisztr&aacute;torok';
$TEXT['ADVANCED'] = 'B-?-¦&iacute;tett';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Enged&eacute;lyezett l&aacute;togat&oacute;k';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'T&ouml;bbet is kiv&aacute;laszthat';
$TEXT['ALL_WORDS'] = 'Minden sz&oacute;';
$TEXT['ANCHOR'] = 'Horgony';
$TEXT['ANONYMOUS'] = 'N&eacute;vtelen';
$TEXT['ANY_WORDS'] = 'B&aacute;rmely sz&oacute;';
$TEXT['APP_NAME'] = 'Alkalmaz&aacute;s Neve';
$TEXT['ARE_YOU_SURE'] = 'Biztos hogy ezt akarja?';
$TEXT['AUTHOR'] = 'Szerz&ccedil;';
$TEXT['BACK'] = 'Vissza';
$TEXT['BACKUP'] = 'Biztons&aacute;gi Ment&eacute;s';
$TEXT['BACKUP_ALL_TABLES'] = 'Minden adatb&aacute;zis t&aacute;bla ment&eacute;se';
$TEXT['BACKUP_DATABASE'] = 'Adatb&aacute;zis Ment&eacute;se';
$TEXT['BACKUP_MEDIA'] = 'Biztons&aacute;gi ment&eacute;s M&eacute;dia';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Csak WB specifikus adatb&aacute;zis t&aacute;bla ment&eacute;se';
$TEXT['BASIC'] = 'Alap';
$TEXT['BLOCK'] = 'Blokk';
$TEXT['CALENDAR'] = 'Napt&aacute;r';
$TEXT['CANCEL'] = 'M&eacute;gse';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha Ellen&ouml;rz&eacute;s';
$TEXT['CAP_EDIT_CSS'] = 'CSS Szerkeszt&eacute;se';
$TEXT['CHANGE'] = 'M&oacute;dos&iacute;t';
$TEXT['CHANGES'] = 'V&aacute;toz&aacute;sok';
$TEXT['CHANGE_SETTINGS'] = 'Be&aacute;ll&iacute;t&aacute;sok megv&aacute;ltoztat&aacute;sa';
$TEXT['CHARSET'] = 'Karakterk&eacute;szlet';
$TEXT['CHECKBOX_GROUP'] = 'Jel&ouml;l&agrave;n&eacute;gyzet csoport';
$TEXT['CLOSE'] = 'Bez&aacute;r';
$TEXT['CODE'] = 'K&oacute;d';
$TEXT['CODE_SNIPPET'] = 'Code-r&eacute;szlet';
$TEXT['COLLAPSE'] = '&sup3;szecsuk';
$TEXT['COMMENT'] = 'Megjegyz&eacute;s';
$TEXT['COMMENTING'] = 'Komment&aacute;l&aacute;s';
$TEXT['COMMENTS'] = 'Megjegyz&eacute;sek';
$TEXT['CREATE_FOLDER'] = 'K&ouml;nyvt&aacute;r l&eacute;trehoz&aacute;sa';
$TEXT['CURRENT'] = 'Aktu&aacute;lis';
$TEXT['CURRENT_FOLDER'] = 'Aktu&aacute;lis k&ouml;nyvt&aacute;r';
$TEXT['CURRENT_PAGE'] = 'Aktu&aacute;lis Lap';
$TEXT['CURRENT_PASSWORD'] = 'Aktu&aacute;lis Jelsz&oacute;';
$TEXT['CUSTOM'] = 'Egy&eacute;ni';
$TEXT['DATABASE'] = 'Adatb&aacute;zis';
$TEXT['DATE'] = 'D&aacute;tum';
$TEXT['DATE_FORMAT'] = 'D&aacute;tum form&aacute;tum';
$TEXT['DEFAULT'] = 'Alap&eacute;rtelmezett';
$TEXT['DEFAULT_CHARSET'] = 'Alap&eacute;rtelmezett Karakterrk&eacute;szlet';
$TEXT['DEFAULT_TEXT'] = 'Alap&eacute;rtelmezett sz&ouml;veg';
$TEXT['DELETE'] = 'T&ouml;rl&eacute;s';
$TEXT['DELETED'] = 'T&ouml;r&ouml;lve';
$TEXT['DELETE_DATE'] = 'D&aacute;tum t&ouml;rl&eacute;se';
$TEXT['DELETE_ZIP'] = 'ZIP arch&iacute;vum t&ouml;rl&eacute;se kicsomagol&aacute;s ut&aacute;n&amp;';
$TEXT['DESCRIPTION'] = 'Le&iacute;r&aacute;s';
$TEXT['DESIGNED_FOR'] = 'Tervezve';
$TEXT['DIRECTORIES'] = 'K&ouml;nyvt&aacute;rak';
$TEXT['DIRECTORY_MODE'] = 'K&ouml;nyvt&aacute;r m&oacute;d';
$TEXT['DISABLED'] = 'Letiltva';
$TEXT['DISPLAY_NAME'] = 'Megjelen&agrave;N&eacute;v';
$TEXT['EMAIL'] = 'E-mail';
$TEXT['EMAIL_ADDRESS'] = 'E-mail C&iacute;m';
$TEXT['EMPTY_TRASH'] = 'Kuka &uuml;r&iacute;t&eacute;s';
$TEXT['ENABLED'] = 'Enged&eacute;lyezve';
$TEXT['END'] = 'V&eacute;ge';
$TEXT['ERROR'] = 'Hiba';
$TEXT['EXACT_MATCH'] = 'Pontos egyez&eacute;s';
$TEXT['EXECUTE'] = 'V&eacute;grehajt&aacute;s';
$TEXT['EXPAND'] = 'Kibont';
$TEXT['EXTENSION'] = 'B-?-¦&iacute;tm&eacute;ny';
$TEXT['FIELD'] = 'Mez&ccedil;';
$TEXT['FILE'] = 'F&aacute;jl';
$TEXT['FILES'] = 'F&aacute;jlok';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'File rendszer jogosults&aacute;gok';
$TEXT['FILE_MODE'] = 'File M&oacute;d';
$TEXT['FINISH_PUBLISHING'] = 'Publik&aacute;l&aacute;s v&eacute;ge';
$TEXT['FOLDER'] = 'K&ouml;nyvt&aacute;r';
$TEXT['FOLDERS'] = 'K&ouml;nyvt&aacute;rak';
$TEXT['FOOTER'] = 'L&aacute;bl&eacute;c';
$TEXT['FORGOTTEN_DETAILS'] = 'Mi is a jelsz&oacute;?';
$TEXT['FORGOT_DETAILS'] = 'Elfelejtettem a jelsz&oacute;t.';
$TEXT['FROM'] = 'Felad&oacute;';
$TEXT['FRONTEND'] = 'Megjelen&agrave;fel&uuml;let';
$TEXT['FULL_NAME'] = 'Teljes n&eacute;v';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Csoport';
$TEXT['HEADER'] = 'Fejl&eacute;c';
$TEXT['HEADING'] = 'C&iacute;msor';
$TEXT['HEADING_CSS_FILE'] = 'Aktu&aacute;lis Modul F&aacute;jl: ';
$TEXT['HEIGHT'] = 'Magass&aacute;g';
$TEXT['HIDDEN'] = 'Rejtett';
$TEXT['HIDE'] = 'Elrejt';
$TEXT['HIDE_ADVANCED'] = 'Speci&aacute;lis be&aacute;ll&iacute;t&aacute;sok elrejt&eacute;se';
$TEXT['HOME'] = 'Kezd-?-?ap';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Honlap &aacute;tir&aacute;ny&iacute;t&aacute;s';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Ikon';
$TEXT['IMAGE'] = 'K&eacute;p';
$TEXT['INLINE'] = 'Sorban';
$TEXT['INSTALL'] = 'Telep&iacute;t';
$TEXT['INSTALLATION'] = 'Telep&iacute;t&eacute;s';
$TEXT['INSTALLATION_PATH'] = 'Telep&iacute;t&eacute;si &uacute;tvonal';
$TEXT['INSTALLATION_URL'] = 'Telep&iacute;t&eacute;si URL';
$TEXT['INSTALLED'] = 'telep&iacute;tve';
$TEXT['INTRO'] = 'Bevezet&ccedil;';
$TEXT['INTRO_PAGE'] = 'Bevezet&agrave;Lap';
$TEXT['INVALID_SIGNS'] = 'must begin with a letter or has invalid signs';
$TEXT['KEYWORDS'] = 'Kulcsszavak';
$TEXT['LANGUAGE'] = 'Nyelv';
$TEXT['LAST_UPDATED_BY'] = 'M&oacute;dos&iacute;totta';
$TEXT['LENGTH'] = 'Hossz';
$TEXT['LEVEL'] = 'Szint';
$TEXT['LINK'] = 'Hivatkoz&aacute;s';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix';
$TEXT['LIST_OPTIONS'] = 'Lista opci&oacute;k';
$TEXT['LOGGED_IN'] = 'Bejelentkezve';
$TEXT['LOGIN'] = 'Bel&eacute;p&eacute;s';
$TEXT['LONG'] = 'Hossz-?-¦';
$TEXT['LONG_TEXT'] = 'Hossz&uacute; sz&ouml;veg';
$TEXT['LOOP'] = 'ism&eacute;tl-?-?&uuml;/br&gt; t&ouml;rzs szakasz';
$TEXT['MAIN'] = 'F&ccedil;';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'Kezel';
$TEXT['MANAGE_GROUPS'] = 'Csoportok kezel&eacute;se';
$TEXT['MANAGE_USERS'] = 'Felhaszn&aacute;l&oacute;k kezel&eacute;se';
$TEXT['MATCH'] = 'Egyezik';
$TEXT['MATCHING'] = 'Egyez&eacute;s';
$TEXT['MAX_EXCERPT'] = 'Maximum tal&aacute;lat';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Max. bek&uuml;ld&eacute;s &oacute;r&aacute;nk&eacute;nt';
$TEXT['MEDIA_DIRECTORY'] = 'M&eacute;dia k&ouml;nyvt&aacute;r';
$TEXT['MENU'] = 'Men&uuml;';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Menu C&iacute;m';
$TEXT['MESSAGE'] = '&ordm;enet';
$TEXT['MODIFY'] = 'M&oacute;dos&iacute;t&aacute;s';
$TEXT['MODIFY_CONTENT'] = 'Tartalom m&oacute;dos&iacute;t&aacute;sa';
$TEXT['MODIFY_SETTINGS'] = 'Be&aacute;ll&iacute;t&aacute;sok m&oacute;dos&iacute;t&aacute;sa';
$TEXT['MODULE_ORDER'] = 'Modul sorrend keres&eacute;sn&eacute;l';
$TEXT['MODULE_PERMISSIONS'] = 'Modul enged&eacute;lyek';
$TEXT['MORE'] = 'B-?-¦ebben';
$TEXT['MOVE_DOWN'] = 'Mozgat Le';
$TEXT['MOVE_UP'] = 'Mozgat Fel';
$TEXT['MULTIPLE_MENUS'] = 'T&ouml;bbszint-?-¦ men&uuml;';
$TEXT['MULTISELECT'] = 'T&ouml;bb v&aacute;laszt&aacute;sos';
$TEXT['NAME'] = 'N&eacute;v';
$TEXT['NEED_CURRENT_PASSWORD'] = 'confirm with current password';
$TEXT['NEED_TO_LOGIN'] = 'Vissza a bel&eacute;p&eacute;shez';
$TEXT['NEW_PASSWORD'] = '&ordf; Jelsz&oacute;';
$TEXT['NEW_WINDOW'] = '&ordf; ablak';
$TEXT['NEXT'] = 'K&ouml;vetke&ccedil;';
$TEXT['NEXT_PAGE'] = 'K&ouml;vetke&agrave;oldal';
$TEXT['NO'] = 'Nem';
$TEXT['NONE'] = 'Egyik sem';
$TEXT['NONE_FOUND'] = 'Nem tal&aacute;lhat&oacute;';
$TEXT['NOT_FOUND'] = 'Nem tal&aacute;lhat&oacute;';
$TEXT['NOT_INSTALLED'] = 'nincs telep&iacute;tve';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Nincs eredm&eacute;ny';
$TEXT['OF'] = '&ouml;sszesen:';
$TEXT['ON'] = 'Be';
$TEXT['OPEN'] = 'Megnyit&aacute;s';
$TEXT['OPTION'] = 'Opci&oacute;k';
$TEXT['OTHERS'] = 'Egyebek';
$TEXT['OUT_OF'] = 'T&uacute;l';
$TEXT['OVERWRITE_EXISTING'] = 'Megl&eacute;v&agrave;fel&uuml;l&iacute;r&aacute;sa';
$TEXT['PAGE'] = 'Lap';
$TEXT['PAGES_DIRECTORY'] = 'Lap k&ouml;nyvt&aacute;r';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Lap kiterjeszt&eacute;s';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Lap nyelv';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Lap szint limit';
$TEXT['PAGE_SPACER'] = 'Lap filen&eacute;v elv&aacute;laszt&oacute;';
$TEXT['PAGE_TITLE'] = 'Lap c&iacute;m';
$TEXT['PAGE_TRASH'] = 'Lap kuka';
$TEXT['PARENT'] = 'Almen&uuml;je ennek';
$TEXT['PASSWORD'] = 'Jelsz&oacute;';
$TEXT['PATH'] = '&acute;vonal';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP hibajelent&eacute;si szint';
$TEXT['PLEASE_LOGIN'] = 'K&eacute;rem l&eacute;pjen be';
$TEXT['PLEASE_SELECT'] = 'K&eacute;rem v&aacute;lasszon';
$TEXT['POST'] = 'Cikk';
$TEXT['POSTS_PER_PAGE'] = '&ordm;enetek laponk&eacute;nt';
$TEXT['POST_FOOTER'] = '&ordm;enet l&aacute;bl&eacute;c';
$TEXT['POST_HEADER'] = '&ordm;enet fejbl&eacute;c';
$TEXT['PREVIOUS'] = 'El-?-?&ccedil;';
$TEXT['PREVIOUS_PAGE'] = 'El-?-?&agrave;oldal';
$TEXT['PRIVATE'] = 'Priv&aacute;t';
$TEXT['PRIVATE_VIEWERS'] = 'Priv&aacute;t jogosultak';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'Publikus';
$TEXT['PUBL_END_DATE'] = 'Z&aacute;r&oacute; d&aacute;tum';
$TEXT['PUBL_START_DATE'] = 'Kezd&agrave;d&aacute;tum';
$TEXT['RADIO_BUTTON_GROUP'] = 'V&aacute;laszt&oacute; gomb csoport';
$TEXT['READ'] = 'Olr&aacute;s';
$TEXT['READ_MORE'] = '&lt;/br&gt;Tov&aacute;bb...&lt;/br&gt;';
$TEXT['REDIRECT_AFTER'] = '&acute;ir&aacute;ny&iacute;t&aacute;s';
$TEXT['REGISTERED'] = 'Regisztr&aacute;lva';
$TEXT['REGISTERED_VIEWERS'] = 'Regisztr&aacute;lt l&aacute;togat&oacute;k';
$TEXT['RELOAD'] = '&ordf;rat&ouml;lt&eacute;s';
$TEXT['REMEMBER_ME'] = 'Eml&eacute;kezzen';
$TEXT['RENAME'] = '&acute;nevez';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'K&ouml;telez&ccedil;';
$TEXT['REQUIREMENT'] = 'K&ouml;vetelem&eacute;ny';
$TEXT['RESET'] = 'Visszavon';
$TEXT['RESIZE'] = '&ordf;ra m&eacute;retez';
$TEXT['RESIZE_IMAGE_TO'] = 'K&eacute;p &aacute;tm&eacute;retez&eacute;se';
$TEXT['RESTORE'] = 'Vissza&aacute;ll&iacute;t&aacute;s';
$TEXT['RESTORE_DATABASE'] = 'Adatb&aacute;zis Vissza&aacute;ll&iacute;t&aacute;sa';
$TEXT['RESTORE_MEDIA'] = 'Vissza&aacute;ll&iacute;t&aacute;si M&eacute;dia';
$TEXT['RESULTS'] = 'Eredm&eacute;nyek';
$TEXT['RESULTS_FOOTER'] = 'Eredm&eacute;nyek l&aacute;bl&eacute;c';
$TEXT['RESULTS_FOR'] = 'Keresett';
$TEXT['RESULTS_HEADER'] = 'Eredm&eacute;nyek fejl&eacute;c';
$TEXT['RESULTS_LOOP'] = 'Eredm&eacute;nyek ciklus';
$TEXT['RETYPE_NEW_PASSWORD'] = '&ordf; Jelsz&oacute; m&eacute;gegyszer';
$TEXT['RETYPE_PASSWORD'] = 'Jelsz&oacute; m&eacute;gegyszer';
$TEXT['SAME_WINDOW'] = 'Azonos Ablak';
$TEXT['SAVE'] = 'Ment&eacute;s';
$TEXT['SEARCH'] = 'Keres&eacute;s';
$TEXT['SEARCHING'] = 'Keres&eacute;s...';
$TEXT['SECTION'] = 'Szakasz';
$TEXT['SECTION_BLOCKS'] = 'Szakaszok';
$TEXT['SEC_ANCHOR'] = 'Szekci&oacute;-Horgony sz&ouml;veg';
$TEXT['SELECT_BOX'] = 'Jel&ouml;l&agrave;n&eacute;gyzet';
$TEXT['SEND_DETAILS'] = 'Jelsz&oacute; elk&uuml;ld&eacute;se';
$TEXT['SEPARATE'] = 'K&uuml;l&ouml;n&aacute;ll&oacute;';
$TEXT['SEPERATOR'] = 'Elv&aacute;laszt&oacute;';
$TEXT['SERVER_EMAIL'] = 'Port&aacute;l E-mail c&iacute;me';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Szerver Oper&aacute;ci&oacute;s Rendszer';
$TEXT['SESSION_IDENTIFIER'] = 'Session Azonos&iacute;t&oacute;';
$TEXT['SETTINGS'] = 'Be&aacute;ll&iacute;t&aacute;s';
$TEXT['SHORT'] = 'R&ouml;vid';
$TEXT['SHORT_TEXT'] = 'R&ouml;vid sz&ouml;veg';
$TEXT['SHOW'] = 'Mutat';
$TEXT['SHOW_ADVANCED'] = 'Speci&aacute;lis be&aacute;ll&iacute;t&aacute;sok mutat&aacute;sa';
$TEXT['SIGNUP'] = 'Regisztr&aacute;l&aacute;s...';
$TEXT['SIZE'] = 'M&eacute;ret';
$TEXT['SMART_LOGIN'] = 'Okos bejelentkez&eacute;s';
$TEXT['START'] = 'Kezd&eacute;s';
$TEXT['START_PUBLISHING'] = 'Publik&aacute;l&aacute;s kezdete';
$TEXT['SUBJECT'] = 'T&aacute;rgy';
$TEXT['SUBMISSIONS'] = 'Bek&uuml;ld&eacute;sek';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'T&aacute;rolva az adatb&aacute;zisban';
$TEXT['SUBMISSION_ID'] = 'Bek&uuml;ld&eacute;s azonos&iacute;t&oacute;';
$TEXT['SUBMITTED'] = 'Elk&uuml;ldve';
$TEXT['SUCCESS'] = 'Sikeres';
$TEXT['SYSTEM_DEFAULT'] = 'Rendszer alap&eacute;rtelmezett';
$TEXT['SYSTEM_PERMISSIONS'] = 'Rendszer enged&eacute;lyek';
$TEXT['TABLE_PREFIX'] = 'T&aacute;bla el-?-?ag';
$TEXT['TARGET'] = 'C&eacute;l';
$TEXT['TARGET_FOLDER'] = 'C&eacute;l k&ouml;nyvt&aacute;r';
$TEXT['TEMPLATE'] = 'Weboldal Sablon';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Sablon jogosults&aacute;gok';
$TEXT['TEXT'] = 'Sz&ouml;veg';
$TEXT['TEXTAREA'] = 'Sz&ouml;vegter&uuml;let';
$TEXT['TEXTFIELD'] = 'Sz&ouml;vegmez&ccedil;';
$TEXT['THEME'] = 'Admin T&eacute;ma';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Id&ccedil;';
$TEXT['TIMEZONE'] = 'Id-?-?&oacute;na';
$TEXT['TIME_FORMAT'] = 'Id&agrave;form&aacute;tum';
$TEXT['TIME_LIMIT'] = 'Maxim&aacute;lis id&agrave;a modulonk&eacute;nti tal&aacute;latra';
$TEXT['TITLE'] = 'C&iacute;m';
$TEXT['TO'] = 'C&iacute;mzett';
$TEXT['TOP_FRAME'] = 'Fels&agrave;Keret';
$TEXT['TRASH_EMPTIED'] = 'Kuka ki&uuml;r&iacute;tve';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Itt szerkesztheted a CSS defin&iacute;ci&oacute;kat.';
$TEXT['TYPE'] = 'T&iacute;pus';
$TEXT['UNDER_CONSTRUCTION'] = 'Fejleszt&eacute;s alatt';
$TEXT['UNINSTALL'] = 'Elt&aacute;vol&iacute;t';
$TEXT['UNKNOWN'] = 'Ismeretlen';
$TEXT['UNLIMITED'] = 'V&eacute;gtelen';
$TEXT['UNZIP_FILE'] = 'ZIP arch&iacute;vum felt&ouml;lt&eacute;se &eacute;s kicsomagol&aacute;sa';
$TEXT['UP'] = 'Fel';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'F&aacute;jl felt&ouml;lt&eacute;s';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Felhaszn&aacute;l&oacute;';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Ellen&ouml;rz&eacute;s';
$TEXT['VERSION'] = 'Verzi&oacute;';
$TEXT['VIEW'] = 'N&eacute;zet';
$TEXT['VIEW_DELETED_PAGES'] = 'T&ouml;r&ouml;lt Lapok megtekint&eacute;se';
$TEXT['VIEW_DETAILS'] = 'Inf&oacute;t megn&eacute;z';
$TEXT['VISIBILITY'] = 'Megjelen&eacute;s';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'K&uuml;ld&agrave;email';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'K&uuml;ld&agrave;szem&eacute;ly';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'K&eacute;rlek add meg az alap&eacute;rtelmezett "K&uuml;ld&agrave;email" c&iacute;met &eacute;s a "K&uuml;ld&agrave;szem&eacute;ly" mez-?-?. Aj&aacute;nlott az al&aacute;bbi foszn&aacute;lata: &lt;strong&gt;admin@tedomained.hu&lt;/strong&gt;. N&eacute;mely szolg&aacute;ltat&oacute; (e.g. &lt;em&gt;mail.com&lt;/em&gt;) Visszautas&iacute;thatja a leveleket az olyan k&uuml;ld&agrave;c&iacute;mt-?-? mint &lt;@mail.com&lt;/em&gt; ez az&eacute;rt van hogy megakad&aacute;lyozz&aacute;k a SPAM k&uuml;ld&eacute;st.&lt;br /&gt;&lt;br /&gt;Az alap&eacute;rtelmezett &eacute;rt&eacute;kek csak akkor &eacute;rv&eacute;nyesek,ha nincs m&aacute;s megadva aker-ben. Ha a szervered t&aacute;mogatja &lt;acronym title="Simple mail transfer protocol"&gt;SMTP&lt;/acronym&gt;protokolt, akkor haszn&aacute;lhatod ezt az opci&oacute;t lev&eacute;l k&uuml;ld&eacute;;hez.';
$TEXT['WBMAILER_FUNCTION'] = 'Mail Rutin';
$TEXT['WBMAILER_NOTICE'] = '&lt;strong&gt;SMTP Mailer Be&aacute;ll&iacute;t&aacute;sok:&lt;/strong&gt;&lt;br /&gt;Ezek a be&aacute;ll&iacute;t&aacute;sok csak akkor sz&uuml;ks&eacute;gesek, ha emailt akarsz k&uuml;ldeni &lt;acro="Simple mail transfer protocol"&gt;SMTP&lt;/acronym&gt; protokollon kereszt&uuml;l. Ha nem tudod az SMTP kiszolg&aacute;l&oacute;dat, vagy nem vagy biztos a k&ouml;vetlem&eacute;nyekben, akkoszer-?-¦en maradj az alap be&aacute;ll&iacute;t&aacute;sn&aacute;l: PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP Azonos&iacute;t&aacute;s';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'csak akkor aktiv&aacute;ld ha az SMTP host azonos&iacute;t&aacute;st k&eacute;r';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP Host';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP Jelsz&oacute;';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'Weblap';
$TEXT['WEBSITE_DESCRIPTION'] = 'Weblap le&iacute;r&aacute;s';
$TEXT['WEBSITE_FOOTER'] = 'Weblap l&aacute;bl&eacute;c';
$TEXT['WEBSITE_HEADER'] = 'Weblap fejl&eacute;c';
$TEXT['WEBSITE_KEYWORDS'] = 'Weblap kulcsszavak';
$TEXT['WEBSITE_TITLE'] = 'Weblap C&iacute;m';
$TEXT['WELCOME_BACK'] = '&curren;v';
$TEXT['WIDTH'] = 'Sz&eacute;less&eacute;g';
$TEXT['WINDOW'] = 'Ablak';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Mindenki &aacute;ltal &iacute;rhat&oacute; file jogok';
$TEXT['WRITE'] = 'g';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG Szerkeszt&ccedil;';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG St&iacute;lus';
$TEXT['YES'] = 'Igen';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Kieg&eacute;sz&iacute;t&agrave;k&ouml;vetelm&eacute;nyek nem megfelel-?-?k';
$HEADING['ADD_CHILD_PAGE'] = 'Add child page';
$HEADING['ADD_GROUP'] = 'Csoport m&oacute;dos&iacute;t&aacute;sa';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Fejl&eacute;c hozz&aacute;ad&aacute;sa';
$HEADING['ADD_PAGE'] = 'Lap hozz&aacute;ad&aacute;sa';
$HEADING['ADD_USER'] = 'Felhaszn&aacute;l&oacute; hozz&aacute;ad&aacute;sa';
$HEADING['ADMINISTRATION_TOOLS'] = 'Adminisztr&aacute;ci&oacute;s eszk&ouml;z&ouml;k';
$HEADING['BROWSE_MEDIA'] = 'M&eacute;dia b&ouml;ng&eacute;sz&eacute;se';
$HEADING['CREATE_FOLDER'] = '&ordf; k&ouml;nyvt&aacute;r';
$HEADING['DEFAULT_SETTINGS'] = 'Alap&eacute;rtelmezett Be&aacute;ll&iacute;t&aacute;sok';
$HEADING['DELETED_PAGES'] = 'T&ouml;r&ouml;lt Lapok';
$HEADING['FILESYSTEM_SETTINGS'] = 'F&aacute;jl Rendszer';
$HEADING['GENERAL_SETTINGS'] = '&not;tal&aacute;nos be&aacute;ll&iacute;t&aacute;sok';
$HEADING['INSTALL_LANGUAGE'] = 'Nyelv telep&iacute;t&eacute;s';
$HEADING['INSTALL_MODULE'] = 'Modul telep&iacute;t&eacute;s';
$HEADING['INSTALL_TEMPLATE'] = 'Sablon telep&iacute;t&eacute;s';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Modul f&aacute;jlok v&eacute;grehajt&aacute;sa manu&aacute;lisan';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Nyelv Inf&oacute;';
$HEADING['MANAGE_SECTIONS'] = 'Szakaszok kezel&eacute;se';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Speci&aacute;lis be&aacute;ll&iacute;t&aacute;sok';
$HEADING['MODIFY_DELETE_GROUP'] = 'Csoport m&oacute;dos&iacute;t&aacute;sa/t&ouml;rl&eacute;se';
$HEADING['MODIFY_DELETE_PAGE'] = 'Lap m&oacute;dos&iacute;t&aacute;sa/T&ouml;rl&eacute;se';
$HEADING['MODIFY_DELETE_USER'] = 'Felhaszn&aacute;l&oacute; m&oacute;dos&iacute;t&aacute;sa/t&ouml;rl&eacute;se';
$HEADING['MODIFY_GROUP'] = 'Csoport m&oacute;dos&iacute;t&aacute;sa';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Bevezet&agrave;lap m&oacute;dos&iacute;t&aacute;sa';
$HEADING['MODIFY_PAGE'] = 'Lap m&oacute;dos&iacute;t&aacute;sa';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Lap be&aacute;ll&iacute;t&aacute;sainak m&oacute;dos&iacute;t&aacute;sa';
$HEADING['MODIFY_USER'] = 'Felhaszn&aacute;l&oacute; m&oacute;dos&iacute;t&aacute;sa';
$HEADING['MODULE_DETAILS'] = 'Modul inf&oacute;';
$HEADING['MY_EMAIL'] = 'E-mail';
$HEADING['MY_PASSWORD'] = 'Jelsz&oacute;';
$HEADING['MY_SETTINGS'] = 'Be&aacute;ll&iacute;t&aacute;sok';
$HEADING['SEARCH_SETTINGS'] = 'Keres&eacute;si be&aacute;ll&iacute;t&aacute;sok';
$HEADING['SERVER_SETTINGS'] = 'Szerver be&aacute;ll&iacute;t&aacute;sok';
$HEADING['TEMPLATE_DETAILS'] = 'Sablon inf&oacute;';
$HEADING['UNINSTALL_LANGUAGE'] = 'Nyelv elt&aacute;vol&iacute;t&aacute;s';
$HEADING['UNINSTALL_MODULE'] = 'Modul elt&aacute;vol&iacute;t&aacute;s';
$HEADING['UNINSTALL_TEMPLATE'] = 'Sablon elt&aacute;vol&iacute;t&aacute;s';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'F&aacute;jl(ok) felt&ouml;lt&eacute;se';
$HEADING['WBMAILER_SETTINGS'] = 'Levelez&agrave;Be&aacute;ll&iacute;t&aacute;sok';
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
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Itt nincs elegend&agrave;jogosults&aacute;god!';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Sajn&aacute;ljuk, de a jelsz&oacute;t nem lehet egy &oacute;r&aacute;n bel&uuml;l t&ouml;bbsz&ouml;r &uacute;jrak&eacute;rni';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Az E-mail k&uuml;ld&eacute;s probl&eacute;m&aacute;ba &uuml;tk&ouml;z&ouml;tt, k&eacute;rem vegye fel a kapcsolatot az adminisztr&aacute;torral';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Az &reg; &aacute;ltal megadott E-mail c&iacute;m nem talalhat&oacute; adatb&aacute;zisunkban';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'K&eacute;rem &iacute;rja be az E-mail c&iacute;m&eacute;t';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sajnos nincs megjelen&iacute;thet&agrave;tartalom';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Sajn&aacute;ljuk, de a megjelen&iacute;t&eacute;shez nincs jogosults&aacute;ga!';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'M&aacute;r telep&iacute;tve';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'A c&eacute;l k&ouml;nyvt&aacute;r nem &iacute;rhat&oacute;';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Nem lehet elt&aacute;vol&iacute;tani';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Nem lehet elt&aacute;volt&iacute;tani! A file haszn&aacute;latban van.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '&lt;br /&gt;&lt;br /&gt;{{type}} &lt;b&gt;{{type_name}}&lt;/b&gt; nem lehet elt&aacute;vol&iacute;tani, mert m&eacute;g haszn&aacute;latban van a k&ouml;v&agrave;oldalon: {{pages}}.&lt;br /&gt;&lt;br /&gt;';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'ez az oldal;ezek az oldalak';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Nem lehet elt&aacute;vol&iacute;tani ezt a sablont: &lt;b&gt;{{name}}&lt;/b&gt;, mert ez az alap&eacute;rtelmezett!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Kicsomagol&aacute;s nem lehets&eacute;ges';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'File felt&ouml;lt&eacute;s nem lehets&eacute;ges';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'F&aacute;jl megnyit&aacute;s hiba.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'A felt&ouml;lt&ouml;tt file csak ilyen form&aacute;tum&uacute; lehet:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'A felt&ouml;lt&ouml;tt file-ok csak a k&ouml;vetkez&agrave;form&aacute;tumuak lehetnek:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'K&eacute;rem t&eacute;rjen vissza &eacute;s t&ouml;lts&ouml;n ki minden mez-?-?';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Telep&iacute;t&eacute;s sikeres';
$MESSAGE['GENERIC_INVALID'] = 'A felt&ouml;lt&ouml;tt file nem megfelel&ccedil;';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = '&sup2;v&eacute;nytelen WebsiteBaker telep&iacute;t&agrave;f&aacute;jl. K&eacute;rlek ellen-?-?izd a *.zip form&aacute;tumot.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = '&sup2;v&eacute;nytelen WebsiteBaker nyelvi f&aacute;jl. K&eacute;rlek ellen-?-?izd a sz&ouml;veges f&aacute;jlt.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Nincs telp&iacute;tve';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'K&eacute;rem v&aacute;rjon, ez eltarthat egy ideig.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'K&eacute;rem t&eacute;rjen vissza k&eacute;s-?-?b!';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Security offense!! data storing was refused!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Elt&aacute;vol&iacute;t&aacute;s sikeres';
$MESSAGE['GENERIC_UPGRADED'] = 'Upgraded successfully';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'A Weboldal Karbantart&aacute;s Alatt';
$MESSAGE['GROUPS_ADDED'] = 'Csoport sikeresen hozz&aacute;adva';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Biztos, hogy t&ouml;r&ouml;lni akarja a kijel&ouml;lt csoportot? (Minden felhaszn&aacute;l&oacute;ja t&ouml;rl-?-?ik)';
$MESSAGE['GROUPS_DELETED'] = 'Csoport t&ouml;r&ouml;lve';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = '&sup2;es a csoportn&eacute;v';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Csoport n&eacute;v m&aacute;r l&eacute;tezik';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Nincs csoport';
$MESSAGE['GROUPS_SAVED'] = 'Csoport elmentve';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Please enter a password';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'T&uacute;l hossz&uacute; jelsz&oacute;';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'T&uacute;l r&ouml;vid jelsz&oacute;';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Nem adott meg file kiterjeszt&eacute;st';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Nem adott meg &uacute;j nevet';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Nem lehet t&ouml;r&ouml;lni a kiv&aacute;lasztott k&ouml;nyvt&aacute;rat';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Nem lehet t&ouml;r&ouml;lni a kiv&aacute;lasztott file-t';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Nem siker&uuml;lt &aacute;tnevezni';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Biztos hogy t&ouml;rli a k&ouml;vetkez&agrave;file-t vagy k&ouml;nyvt&aacute;rat?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'K&ouml;nyvt&aacute;r t&ouml;r&ouml;lve';
$MESSAGE['MEDIA_DELETED_FILE'] = 'File t&ouml;r&ouml;lve';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Nem lehet ../ a c&eacute;l mez-?-?en';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Nem tartalmazhat ../ -t a k&ouml;nyvt&aacute;r n&eacute;v';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Ilyen nev-?-¦ k&ouml;nyvt&aacute;r m&aacute;r l&eacute;tezik';
$MESSAGE['MEDIA_DIR_MADE'] = 'A k&ouml;nyvt&aacute;r sikeresen l&eacute;trehozva';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'nem siker&uuml;lt l&eacute;trehozni a k&ouml;nyvt&aacute;rat';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Ilyen nev-?-¦ file m&aacute;r l&eacute;tezik';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'File nem tal&aacute;lhat&oacute;';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Nem lehet ../ a n&eacute;vben';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Nem lehet index.php a n&eacute;v';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Nem tal&aacute;lhat&oacute; semmilyen m&eacute;dia file az aktu&aacute;lis k&ouml;nyvt&aacute;rban';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was recieved';
$MESSAGE['MEDIA_RENAMED'] = '&acute;nevez&eacute;s sikeres';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' file sikeresen felt&ouml;ltve';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Nem lehet ../ in k&ouml;nyvt&aacute;r n&eacute;vben';
$MESSAGE['MEDIA_UPLOADED'] = ' file sikeresen felt&ouml;ltve';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Sajn&aacute;ljuk, de ez az -?-¦rlap t&uacute;l sokszor lett kit&ouml;ltve egy &oacute;ran bel&uuml;l! K&eacute;rem pr&oacute;b&aacute;lja meg egy &oacute;ra m&uacute;lva';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'A megadott ellen&ouml;rz&agrave;k&oacute;d (vagy m&aacute;s n&eacute;ven Captcha) hib&aacute;s. Ha probl&eacute;m&aacute;d van elolvasni a Captcha k&oacute;dot, k&uuml;mailt ide: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'A k&ouml;vetkez&agrave;mez-?-?et k&ouml;telez&agrave;kit&ouml;ltenie';
$MESSAGE['PAGES_ADDED'] = 'Lap sikeresen hozz&aacute;adva';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Lap c&iacute;msor sikeresen hozz&aacute;adva';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'K&eacute;rem adjon meg men&uuml; nevet';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'K&eacute;rem adjon meg Lap c&iacute;met';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Nem lehet l&eacute;trehozni az access filet a /pages k&ouml;nyvt&aacute;rban (nem megfelel&agrave;jogosults&aacute;gok)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Nem lehet t&ouml;r&ouml;lni az access filet a /pages k&ouml;nyvt&aacute;rban (nem megfelel&agrave;jogosults&aacute;gok)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Hiba a Lap &aacute;trendez&eacute;s k&ouml;zben';
$MESSAGE['PAGES_DELETED'] = 'Lap t&ouml;r&ouml;lve';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Biztos, hogy t&ouml;r&ouml;lni akarja az adott lapot? (&eacute;s az &ouml;sszes al lapj&aacute;t)';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Nincs joga m&oacute;dos&iacute;tani ezt a lapot';
$MESSAGE['PAGES_INTRO_LINK'] = 'Kattintson ide az Bevezet&agrave;Lap m&oacute;dos&iacute;t&aacute;s&aacute;hoz';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Nem lehet l&eacute;trehozni /pages/intro.php file-t (nincs megfelel&agrave;jogosults&aacute;g)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Bevezet&agrave;lap sikeresen elmentve';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Utolj&aacute;ra m&oacute;dos&iacute;totta:';
$MESSAGE['PAGES_NOT_FOUND'] = 'Lap nem tal&aacute;lhat&oacute;';
$MESSAGE['PAGES_NOT_SAVED'] = 'Hiba a lap ment&eacute;se k&ouml;zben';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Ilyen lap m&aacute;r l&eacute;tezik';
$MESSAGE['PAGES_REORDERED'] = 'Lap sikeresen &aacute;trendezve';
$MESSAGE['PAGES_RESTORED'] = 'lap vissza&aacute;ll&iacute;tva';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Visszat&eacute;r&eacute;s a lapokhoz';
$MESSAGE['PAGES_SAVED'] = 'Lap sikeresen elmentve';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Lap be&aacute;ll&iacute;t&aacute;sok elmentve';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Szakasz tulajdons&aacute;gok elmentve';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'A jelenlegi jelsz&oacute; hib&aacute;s';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Sikeres ment&eacute;s';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'E-mail friss&iacute;tve';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Invalid password chars used';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'A jelsz&oacute; sikeresen megv&aacute;ltozott';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'The change of the record has missed.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'The changed record was updated successfully.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Adding a new record has missed.';
$MESSAGE['RECORD_NEW_SAVED'] = 'New record was added successfully.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Figyelmeztet&eacute;s: A nem mentett v&aacute;ltoz&aacute;sok elvesznek';
$MESSAGE['SETTINGS_SAVED'] = 'Be&aacute;ll&iacute;t&aacute;sok sikeresen elmentve';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'A konfigur&aacute;ci&oacute;s file nem nyithat&oacute; meg';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Nem lehets&eacute;ges a konfigur&aacute;ci&oacute;s file &iacute;r&aacute;sa';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Figyelmeztet&eacute;s: Ez csak tesztk&ouml;rnyezetben javasolt';
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
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Bejelentkez&eacute;si r&eacute;szletek...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'E-mail c&iacute;met meg kell adnia';
$MESSAGE['START_CURRENT_USER'] = 'Bejelentkezve mint:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Figyelmeztet&eacute;s! A telep&iacute;t&eacute;si k&ouml;nyvt&aacute;r m&eacute;g nem lett t&ouml;r&ouml;lve!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = '&curren;v a WebsiteBaker Admin fel&uuml;let&eacute;n';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Figyelem: A sablon megv&aacute;ltoztat&aacute;s&aacute;t a be&aacute;ll&iacute;t&aacute;sokban teheti meg';
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
$MESSAGE['USERS_ADDED'] = 'Felhaszn&aacute;l&oacute; sikeresen hozz&aacute;adva';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Function rejected, You can not delete yourself!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Figyelem: A jelsz&oacute;t itt csak annak megv&aacute;ltoztat&aacute;sakor kell megadni';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Biztos, hogy t&ouml;r&ouml;lni a kiv&aacute;lasztott felhaszn&aacute;l&oacute;t?';
$MESSAGE['USERS_DELETED'] = 'Felhaszn&aacute;l&oacute; t&ouml;r&ouml;lve';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Sajnos a megadott E-mail c&iacute;met m&aacute;r haszn&aacute;latban van';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Az E-mail c&iacute;m nem val&oacute;s';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Csoport nincs kiv&aacute;lasztva';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'A be&iacute;rt jelsz&oacute; nem eggyezik';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'A be&iacute;rt jelsz&oacute; t&uacute;l r&ouml;vid';
$MESSAGE['USERS_SAVED'] = 'Felhaszn&aacute;l&oacute; sikeresen mentve';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'WebsiteBaker adminisztr&aacute;ci&oacute;s eszk&ouml;z&ouml;k...';
$OVERVIEW['GROUPS'] = 'Csoportok &eacute;s azok rendszer jogainak kezel&eacute;se...';
$OVERVIEW['HELP'] = 'K&eacute;rd&eacute;sed van? itt tal&aacute;lsz v&aacute;laszt...  (Angol)';
$OVERVIEW['LANGUAGES'] = 'WebsiteBaker nyelvi be&aacute;ll&iacute;t&aacute;sok...';
$OVERVIEW['MEDIA'] = 'A "media" k&ouml;nyvt&aacute;rban t&aacute;rolt fileok kezel&eacute;se...';
$OVERVIEW['MODULES'] = 'WebsiteBaker modulok kezel&eacute;se...';
$OVERVIEW['PAGES'] = 'A Port&aacute;l Weblapjainak kezel&eacute;se...';
$OVERVIEW['PREFERENCES'] = 'Be&aacute;ll&iacute;t&aacute;sok megv&aacute;ltoztat&aacute;sa mint: email, jelsz&oacute;, stb... ';
$OVERVIEW['SETTINGS'] = 'A rendszer glob&aacute;lis be&aacute;ll&iacute;t&aacute;sa...';
$OVERVIEW['START'] = 'Admin &aacute;ttekint&eacute;s';
$OVERVIEW['TEMPLATES'] = 'A Honlap megjelen&eacute;s&eacute;nek v&aacute;ltoztat&aacute;sa Sablonokkal...';
$OVERVIEW['USERS'] = 'Felhaszn&aacute;l&oacute;k bejelentkez&eacute;si enged&eacute;lyei...';
$OVERVIEW['VIEW'] = 'A k&eacute;sz Port&aacute;l megtekint&eacute;se &uacute;j ablakban...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
