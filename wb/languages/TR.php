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
 * @version         $Id: TR.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/TR.php $
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
$language_code = 'TR';
$language_name = 'T&uuml;rk';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Atakan KO&Ccedil;';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Giri&thorn;';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Eklentiler';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Giri&thorn; Bilgilerini Gerial';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Gruplar';
$MENU['HELP'] = 'Yard&yacute;m';
$MENU['LANGUAGES'] = 'Diller';
$MENU['LOGIN'] = 'Giri&thorn;';
$MENU['LOGOUT'] = '&Ccedil;&yacute;k&yacute;&thorn;';
$MENU['MEDIA'] = 'Resimler';
$MENU['MODULES'] = 'Mod&uuml;ller';
$MENU['PAGES'] = 'Sayfalar';
$MENU['PREFERENCES'] = 'Tercihler';
$MENU['SETTINGS'] = 'Ayarlar';
$MENU['START'] = 'Ba&thorn;lat';
$MENU['TEMPLATES'] = 'Kal&yacute;plar';
$MENU['USERS'] = 'Kullan&yacute;c&yacute;lar';
$MENU['VIEW'] = 'G&ouml;r&uuml;nt&uuml;le';
$TEXT['ACCOUNT_SIGNUP'] = 'Kull&yacute;c&yacute; Kay&yacute;t Ol';
$TEXT['ACTIONS'] = 'Hareketler';
$TEXT['ACTIVE'] = 'Aktif';
$TEXT['ADD'] = 'Ekle';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'K&yacute;s&yacute;m Ekle';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Y&ouml;netici';
$TEXT['ADMINISTRATION_TOOL'] = 'Administration tool';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Y&ouml;nerici';
$TEXT['ADVANCED'] = '&Yacute;leri';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Allowed Viewers';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = '&Ccedil;oklu se&ccedil;imlere izin ver';
$TEXT['ALL_WORDS'] = 'B&uuml;t&uuml;n Kelimeler';
$TEXT['ANCHOR'] = 'Anchor';
$TEXT['ANONYMOUS'] = 'Bilinmeyen';
$TEXT['ANY_WORDS'] = 'Herhangi bir s&ouml;zc&uuml;k';
$TEXT['APP_NAME'] = 'Application Ad&yacute;';
$TEXT['ARE_YOU_SURE'] = 'eminmisin?';
$TEXT['AUTHOR'] = 'Yazar';
$TEXT['BACK'] = 'Geri';
$TEXT['BACKUP'] = 'Yedek Al';
$TEXT['BACKUP_ALL_TABLES'] = 'Backup all tables in database';
$TEXT['BACKUP_DATABASE'] = 'Database Yedekle';
$TEXT['BACKUP_MEDIA'] = 'Media Yedekle';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Backup only WB-specific tables';
$TEXT['BASIC'] = 'Ba&thorn;lang&yacute;&ccedil;';
$TEXT['BLOCK'] = 'Blok';
$TEXT['CALENDAR'] = 'Calender';
$TEXT['CANCEL'] = '&Yacute;ptal';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha Verification';
$TEXT['CAP_EDIT_CSS'] = 'Edit CSS';
$TEXT['CHANGE'] = 'De&eth;i&thorn;tir';
$TEXT['CHANGES'] = 'De&eth;i&thorn;iklikler';
$TEXT['CHANGE_SETTINGS'] = 'Ayarlar&yacute; De&eth;i&thorn;tir';
$TEXT['CHARSET'] = 'Charset';
$TEXT['CHECKBOX_GROUP'] = 'T&yacute;klamal&yacute; Se&ccedil;im';
$TEXT['CLOSE'] = 'Kapat';
$TEXT['CODE'] = 'Kod';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'Daralt';
$TEXT['COMMENT'] = 'Yorum';
$TEXT['COMMENTING'] = 'Yorum yapan';
$TEXT['COMMENTS'] = 'Yorumlar';
$TEXT['CREATE_FOLDER'] = 'Dizin Yarat';
$TEXT['CURRENT'] = 'Kullan&yacute;lan';
$TEXT['CURRENT_FOLDER'] = 'Kullan&yacute;lan Dizin';
$TEXT['CURRENT_PAGE'] = 'Kullan&yacute;lan Sayfa';
$TEXT['CURRENT_PASSWORD'] = 'Kullan&yacute;lan &THORN;ifre';
$TEXT['CUSTOM'] = 'Custom';
$TEXT['DATABASE'] = 'Database';
$TEXT['DATE'] = 'Tarih';
$TEXT['DATE_FORMAT'] = 'Tarih Format&yacute;';
$TEXT['DEFAULT'] = 'Varsay&yacute;lan';
$TEXT['DEFAULT_CHARSET'] = 'Default Charset';
$TEXT['DEFAULT_TEXT'] = 'Varsay&yacute;lan Yaz&yacute;';
$TEXT['DELETE'] = 'Sil';
$TEXT['DELETED'] = 'Silindi';
$TEXT['DELETE_DATE'] = 'Delete date';
$TEXT['DELETE_ZIP'] = 'Delete zip archive after unpacking';
$TEXT['DESCRIPTION'] = 'A&ccedil;&yacute;klama';
$TEXT['DESIGNED_FOR'] = 'D&uuml;zenleyen';
$TEXT['DIRECTORIES'] = 'Dizinler';
$TEXT['DIRECTORY_MODE'] = 'Dizin Modu';
$TEXT['DISABLED'] = '&Yacute;ptal';
$TEXT['DISPLAY_NAME'] = 'G&ouml;r&uuml;n&uuml;m Ad&yacute;';
$TEXT['EMAIL'] = 'Email';
$TEXT['EMAIL_ADDRESS'] = 'Email Adresi';
$TEXT['EMPTY_TRASH'] = '&Ccedil;&ouml;p kutusu bo&thorn;';
$TEXT['ENABLED'] = '&Yacute;zin Ver';
$TEXT['END'] = 'Son';
$TEXT['ERROR'] = 'Hata';
$TEXT['EXACT_MATCH'] = 'Tam Bul';
$TEXT['EXECUTE'] = '&Ccedil;al&yacute;&thorn;t&yacute;r';
$TEXT['EXPAND'] = 'Geni&thorn;let';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['FIELD'] = 'Alan';
$TEXT['FILE'] = 'Dosya';
$TEXT['FILES'] = 'Dosyalar';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Dosya Sistemi &Yacute;zinleri';
$TEXT['FILE_MODE'] = 'Dosya &Yacute;zini';
$TEXT['FINISH_PUBLISHING'] = 'Yay&yacute;n&yacute; Bitir';
$TEXT['FOLDER'] = 'Dizin';
$TEXT['FOLDERS'] = 'Dizinler';
$TEXT['FOOTER'] = 'Alt K&yacute;s&yacute;m';
$TEXT['FORGOTTEN_DETAILS'] = 'Sizin Ayr&yacute;nt&yacute;l&yacute; Detaylar&yacute;n&yacute;z?';
$TEXT['FORGOT_DETAILS'] = 'Detay Hat&yacute;rlat?';
$TEXT['FROM'] = 'From';
$TEXT['FRONTEND'] = 'Son Kullan&yacute;c&yacute;';
$TEXT['FULL_NAME'] = 'Tam Ad&yacute;n&yacute;z';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Grup';
$TEXT['HEADER'] = '&Uuml;st K&yacute;s&yacute;m';
$TEXT['HEADING'] = 'Ba&thorn;l&yacute;k';
$TEXT['HEADING_CSS_FILE'] = 'Actual module file: ';
$TEXT['HEIGHT'] = 'Y&uuml;kseklik';
$TEXT['HIDDEN'] = 'Gizli';
$TEXT['HIDE'] = 'Gizle';
$TEXT['HIDE_ADVANCED'] = '&Yacute;leri Se&ccedil;enekleri Gizle';
$TEXT['HOME'] = 'Ana Sayfa';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Ana sayfa y&ouml;nlendir';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Ikon';
$TEXT['IMAGE'] = 'Resim';
$TEXT['INLINE'] = 'In-line';
$TEXT['INSTALL'] = 'Y&uuml;kle';
$TEXT['INSTALLATION'] = 'Y&uuml;kle';
$TEXT['INSTALLATION_PATH'] = 'Y&uuml;kleme Yolu';
$TEXT['INSTALLATION_URL'] = 'Y&uuml;keleme URL';
$TEXT['INSTALLED'] = 'installed';
$TEXT['INTRO'] = 'Demo';
$TEXT['INTRO_PAGE'] = 'Demo Sayfas&yacute;';
$TEXT['INVALID_SIGNS'] = 'must begin with a letter or has invalid signs';
$TEXT['KEYWORDS'] = 'Keywords';
$TEXT['LANGUAGE'] = 'Dil';
$TEXT['LAST_UPDATED_BY'] = 'Son G&uuml;ncelleyen';
$TEXT['LENGTH'] = 'Uzunluk';
$TEXT['LEVEL'] = 'Limit';
$TEXT['LINK'] = 'Link';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix tabanl&yacute;';
$TEXT['LIST_OPTIONS'] = 'Liste se&ccedil;enekleri';
$TEXT['LOGGED_IN'] = 'Giri&thorn; Kaydet';
$TEXT['LOGIN'] = 'Giri&thorn;';
$TEXT['LONG'] = 'Uzun';
$TEXT['LONG_TEXT'] = 'Uzun Yaz&yacute;';
$TEXT['LOOP'] = 'S&uuml;rekli';
$TEXT['MAIN'] = 'Ana';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'Y&ouml;net';
$TEXT['MANAGE_GROUPS'] = 'Grup Y&ouml;netimi';
$TEXT['MANAGE_USERS'] = 'Kullan&yacute;c&yacute; Y&ouml;netimi';
$TEXT['MATCH'] = 'Bul';
$TEXT['MATCHING'] = 'Bulunanlar';
$TEXT['MAX_EXCERPT'] = 'Max lines of excerpt';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Maksimum Saat Ba&thorn;&yacute; Sunum';
$TEXT['MEDIA_DIRECTORY'] = 'Resim Dizini';
$TEXT['MENU'] = 'Menu';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Menu Ba&thorn;l&yacute;&eth;&yacute;';
$TEXT['MESSAGE'] = 'Mesaj';
$TEXT['MODIFY'] = 'D&uuml;zenle';
$TEXT['MODIFY_CONTENT'] = 'D&uuml;zeni De&eth;i&thorn;tir';
$TEXT['MODIFY_SETTINGS'] = 'Ayarlar&yacute; De&eth;i&thorn;tir';
$TEXT['MODULE_ORDER'] = 'Module-order for searching';
$TEXT['MODULE_PERMISSIONS'] = 'Mod&uuml;l &Yacute;zinleri';
$TEXT['MORE'] = 'Daha &Ccedil;ok';
$TEXT['MOVE_DOWN'] = 'A&thorn;a&eth;&yacute; Ta&thorn;&yacute;';
$TEXT['MOVE_UP'] = 'Yukar&yacute; Ta&thorn;&yacute;';
$TEXT['MULTIPLE_MENUS'] = '&Ccedil;oklu men&uuml;ler';
$TEXT['MULTISELECT'] = '&Ccedil;oklu Se&ccedil;im';
$TEXT['NAME'] = '&Yacute;sim';
$TEXT['NEED_CURRENT_PASSWORD'] = 'confirm with current password';
$TEXT['NEED_TO_LOGIN'] = 'Need to log-in?';
$TEXT['NEW_PASSWORD'] = 'Yeni &THORN;ifre';
$TEXT['NEW_WINDOW'] = 'Yeni Pencere';
$TEXT['NEXT'] = 'Sonraki';
$TEXT['NEXT_PAGE'] = 'Sonraki Sayfa';
$TEXT['NO'] = 'Hay&yacute;r';
$TEXT['NONE'] = 'Yok';
$TEXT['NONE_FOUND'] = 'Hi&ccedil;biri bulmad&yacute;';
$TEXT['NOT_FOUND'] = 'Bulunamad&yacute;';
$TEXT['NOT_INSTALLED'] = 'not installed';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Bulunamad&yacute;';
$TEXT['OF'] = 'Of';
$TEXT['ON'] = 'On';
$TEXT['OPEN'] = 'Open';
$TEXT['OPTION'] = 'Se&ccedil;enekler';
$TEXT['OTHERS'] = 'Di&eth;erleri';
$TEXT['OUT_OF'] = 'D&yacute;&thorn;ar&yacute;';
$TEXT['OVERWRITE_EXISTING'] = '&Uuml;st&uuml;ne Yaz';
$TEXT['PAGE'] = 'Sayfa';
$TEXT['PAGES_DIRECTORY'] = 'Sayfa Dizini';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Sayfa Uzant&yacute;s&yacute;';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Sayfa Dili';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Sayfa Alt Limiti';
$TEXT['PAGE_SPACER'] = 'Sayfa Bo&thorn;lu&eth;u';
$TEXT['PAGE_TITLE'] = 'Sayfa Ba&thorn;l&yacute;&eth;&yacute;';
$TEXT['PAGE_TRASH'] = 'Sayfay&yacute; Sil';
$TEXT['PARENT'] = 'Ana Grup';
$TEXT['PASSWORD'] = '&THORN;ifre';
$TEXT['PATH'] = 'Yol';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP Hata Rapor D&uuml;zeyi';
$TEXT['PLEASE_LOGIN'] = 'Please login';
$TEXT['PLEASE_SELECT'] = 'L&uuml;tfen Se&ccedil;in';
$TEXT['POST'] = 'Mesaj';
$TEXT['POSTS_PER_PAGE'] = 'Sayfa ba&thorn;&yacute;na mesajlar';
$TEXT['POST_FOOTER'] = 'Alt Mesaj';
$TEXT['POST_HEADER'] = '&Uuml;st Mesaj&yacute;';
$TEXT['PREVIOUS'] = '&Ouml;nceki';
$TEXT['PREVIOUS_PAGE'] = '&Ouml;nceki Sayfa';
$TEXT['PRIVATE'] = '&Ouml;zel';
$TEXT['PRIVATE_VIEWERS'] = '&Ouml;zel izleyiciler';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'Herkez';
$TEXT['PUBL_END_DATE'] = 'End date';
$TEXT['PUBL_START_DATE'] = 'Start date';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radyo D&uuml;&eth;meleri';
$TEXT['READ'] = 'Oku';
$TEXT['READ_MORE'] = 'Oku';
$TEXT['REDIRECT_AFTER'] = 'Redirect after';
$TEXT['REGISTERED'] = 'Kay&yacute;tl&yacute; Kullan&yacute;c&yacute;';
$TEXT['REGISTERED_VIEWERS'] = '&Yacute;zleyiciler kaydetti';
$TEXT['RELOAD'] = 'Tekrarla';
$TEXT['REMEMBER_ME'] = 'Haz&yacute;rla';
$TEXT['RENAME'] = 'Yeni isim ver';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Gerekli';
$TEXT['REQUIREMENT'] = 'Requirement';
$TEXT['RESET'] = 'S&yacute;f&yacute;rla';
$TEXT['RESIZE'] = 'Tekrar Boyutland&yacute;r';
$TEXT['RESIZE_IMAGE_TO'] = 'Boyutland&yacute;r resimi';
$TEXT['RESTORE'] = 'Yede&eth;i y&uuml;kle';
$TEXT['RESTORE_DATABASE'] = 'Database Geri Y&uuml;kle';
$TEXT['RESTORE_MEDIA'] = 'Media Geri Y&uuml;kle';
$TEXT['RESULTS'] = 'Sonu&ccedil;lar';
$TEXT['RESULTS_FOOTER'] = 'Bulundu&eth;unda Alt K&yacute;s&yacute;m';
$TEXT['RESULTS_FOR'] = 'Sonu&ccedil;lar';
$TEXT['RESULTS_HEADER'] = 'Bulundu&eth;unda &Uuml;st K&yacute;s&yacute;m';
$TEXT['RESULTS_LOOP'] = 'Bulundu&eth;unda D&ouml;ng&uuml;';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Tekrarla Yeni &THORN;ifre';
$TEXT['RETYPE_PASSWORD'] = '&THORN;ifreyi Tekrarla';
$TEXT['SAME_WINDOW'] = 'Ayn&yacute; Pencere';
$TEXT['SAVE'] = 'Kay&yacute;t et';
$TEXT['SEARCH'] = 'Ara';
$TEXT['SEARCHING'] = 'Arama';
$TEXT['SECTION'] = 'K&yacute;s&yacute;m';
$TEXT['SECTION_BLOCKS'] = 'K&yacute;s&yacute;m bloklar&yacute;';
$TEXT['SEC_ANCHOR'] = 'Section-Anchor text';
$TEXT['SELECT_BOX'] = 'Se&ccedil;meli Men&uuml;';
$TEXT['SEND_DETAILS'] = 'Detaylar&yacute; G&ouml;nder';
$TEXT['SEPARATE'] = 'Ay&yacute;r&yacute;c&yacute;';
$TEXT['SEPERATOR'] = 'B&ouml;l&uuml;c&uuml;';
$TEXT['SERVER_EMAIL'] = 'Server Email';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Server &Yacute;&thorn;letim Sistemi';
$TEXT['SESSION_IDENTIFIER'] = 'Session Identifier';
$TEXT['SETTINGS'] = 'Ayarlar';
$TEXT['SHORT'] = 'K&yacute;sa';
$TEXT['SHORT_TEXT'] = 'K&yacute;sa Yaz&yacute;';
$TEXT['SHOW'] = 'G&ouml;ster';
$TEXT['SHOW_ADVANCED'] = '&Yacute;leri Se&ccedil;enekleri G&ouml;ster';
$TEXT['SIGNUP'] = 'Kay&yacute;t Ol';
$TEXT['SIZE'] = 'Boyut';
$TEXT['SMART_LOGIN'] = 'G&uuml;venli Giri&thorn;';
$TEXT['START'] = 'Ba&thorn;lat';
$TEXT['START_PUBLISHING'] = 'Yay&yacute;na Ba&thorn;la';
$TEXT['SUBJECT'] = 'Konu';
$TEXT['SUBMISSIONS'] = 'Sunu&thorn;lar';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Sunu&thorn;lar, veritaban&yacute;nda depolad&yacute;';
$TEXT['SUBMISSION_ID'] = 'Sunu&thorn;lar ID';
$TEXT['SUBMITTED'] = 'G&ouml;nderildi';
$TEXT['SUCCESS'] = '&Yacute;&thorn;lem Ba&thorn;ar&yacute;ld&yacute;';
$TEXT['SYSTEM_DEFAULT'] = 'Sistem Varsay&yacute;lan&yacute;';
$TEXT['SYSTEM_PERMISSIONS'] = 'Sistem &Yacute;zinleri';
$TEXT['TABLE_PREFIX'] = 'Table D&uuml;zen Ad&yacute;';
$TEXT['TARGET'] = 'Hedef';
$TEXT['TARGET_FOLDER'] = 'Hedef Dizin';
$TEXT['TEMPLATE'] = 'Kal&yacute;p';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Kal&yacute;p &Yacute;zinleri';
$TEXT['TEXT'] = 'Yaz&yacute;';
$TEXT['TEXTAREA'] = 'Textarea';
$TEXT['TEXTFIELD'] = 'Textfield';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Saat';
$TEXT['TIMEZONE'] = 'Zaman Dilimi';
$TEXT['TIME_FORMAT'] = 'Saat Format&yacute;';
$TEXT['TIME_LIMIT'] = 'Max time to gather excerpts per module';
$TEXT['TITLE'] = 'Ba&thorn;l&yacute;k';
$TEXT['TO'] = 'To';
$TEXT['TOP_FRAME'] = 'Top Frame';
$TEXT['TRASH_EMPTIED'] = '&Ccedil;&ouml;p kutusu bo&thorn;alt&yacute;ld&yacute;';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Edit the CSS definitions in the textarea below.';
$TEXT['TYPE'] = 'Tip';
$TEXT['UNDER_CONSTRUCTION'] = 'Yap&yacute;m A&thorn;amas&yacute;nda';
$TEXT['UNINSTALL'] = 'Kald&yacute;r';
$TEXT['UNKNOWN'] = 'Bilinmeyen';
$TEXT['UNLIMITED'] = 'S&yacute;n&yacute;rs&yacute;z';
$TEXT['UNZIP_FILE'] = 'Upload and unpack a zip archive';
$TEXT['UP'] = 'Yukar&yacute;';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Dosya Y&uuml;kle';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Kullan&yacute;c&yacute;';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Verification';
$TEXT['VERSION'] = 'Versiyon';
$TEXT['VIEW'] = 'G&ouml;r&uuml;n&uuml;&thorn;';
$TEXT['VIEW_DELETED_PAGES'] = 'Silinen Sayfay&yacute; G&ouml;ster';
$TEXT['VIEW_DETAILS'] = 'Detaylar';
$TEXT['VISIBILITY'] = 'G&ouml;r&uuml;n&uuml;rl&uuml;k';
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
$TEXT['WEBSITE'] = 'Website';
$TEXT['WEBSITE_DESCRIPTION'] = 'Website A&ccedil;&yacute;klamas&yacute;';
$TEXT['WEBSITE_FOOTER'] = 'Website Alt K&yacute;s&yacute;m';
$TEXT['WEBSITE_HEADER'] = 'Website &Uuml;st K&yacute;s&yacute;m';
$TEXT['WEBSITE_KEYWORDS'] = 'Website Keywords';
$TEXT['WEBSITE_TITLE'] = 'Website Ba&thorn;l&yacute;&eth;&yacute;';
$TEXT['WELCOME_BACK'] = 'Ho&thorn;geldiniz';
$TEXT['WIDTH'] = 'Geni&thorn;lik';
$TEXT['WINDOW'] = 'Pencere';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Yaz&yacute;labilir dosya izinleri';
$TEXT['WRITE'] = 'Yaz';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG Editor';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG Style';
$TEXT['YES'] = 'Evet';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On requirements not met';
$HEADING['ADD_CHILD_PAGE'] = 'Add child page';
$HEADING['ADD_GROUP'] = 'Grup Ekle';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Ba&thorn;l&yacute;k Ekle';
$HEADING['ADD_PAGE'] = 'Sayfa Ekle';
$HEADING['ADD_USER'] = 'Kullan&yacute;c&yacute; Ekle';
$HEADING['ADMINISTRATION_TOOLS'] = 'Administration Ara&ccedil;lar&yacute;';
$HEADING['BROWSE_MEDIA'] = 'Resimleri Y&ouml;netme';
$HEADING['CREATE_FOLDER'] = 'Dizin Yarat';
$HEADING['DEFAULT_SETTINGS'] = 'Varsay&yacute;lan Ayarlar';
$HEADING['DELETED_PAGES'] = 'Sayfay&yacute; Sil';
$HEADING['FILESYSTEM_SETTINGS'] = 'Dosya Sistemi Ayarlar&yacute;';
$HEADING['GENERAL_SETTINGS'] = 'Genel Ayarlar';
$HEADING['INSTALL_LANGUAGE'] = 'Dil Y&uuml;kle';
$HEADING['INSTALL_MODULE'] = 'Mod&uuml;l Y&uuml;kle';
$HEADING['INSTALL_TEMPLATE'] = 'Kal&yacute;p Y&uuml;kle';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Execute module files manually';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Dil A&ccedil;&yacute;klamas&yacute;';
$HEADING['MANAGE_SECTIONS'] = 'K&yacute;s&yacute;mlar&yacute; Y&ouml;net';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Geli&thorn;tirilmi&thorn; Sayfa Ayarlar&yacute;n&yacute; De&eth;i&thorn;tir';
$HEADING['MODIFY_DELETE_GROUP'] = 'De&eth;i&thorn;tir/Sil Grup';
$HEADING['MODIFY_DELETE_PAGE'] = 'De&eth;i&thorn;tir/Sil Sayfa';
$HEADING['MODIFY_DELETE_USER'] = 'De&eth;i&thorn;tir/Sil kullan&yacute;c&yacute;';
$HEADING['MODIFY_GROUP'] = 'Grup D&uuml;zenle';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Ba&thorn;lang&yacute;&ccedil; Sayfas&yacute;n&yacute; D&uuml;zenle';
$HEADING['MODIFY_PAGE'] = 'Sayfay&yacute; De&eth;i&thorn;tir';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Sayfa Ayarlar&yacute;n&yacute; De&eth;i&thorn;tir';
$HEADING['MODIFY_USER'] = 'Kullan&yacute;c&yacute; D&uuml;zenle';
$HEADING['MODULE_DETAILS'] = 'Mod&uuml;l A&ccedil;&yacute;klamas&yacute;';
$HEADING['MY_EMAIL'] = 'Emailim';
$HEADING['MY_PASSWORD'] = '&THORN;ifrem';
$HEADING['MY_SETTINGS'] = 'Ayarlar&yacute;m';
$HEADING['SEARCH_SETTINGS'] = 'Arama Ayarlar&yacute;';
$HEADING['SERVER_SETTINGS'] = 'Server Ayarlar&yacute;';
$HEADING['TEMPLATE_DETAILS'] = 'Kal&yacute;p A&ccedil;&yacute;klamas&yacute;';
$HEADING['UNINSTALL_LANGUAGE'] = 'Dil Kald&yacute;r';
$HEADING['UNINSTALL_MODULE'] = 'Mod&uuml;l Kald&yacute;r';
$HEADING['UNINSTALL_TEMPLATE'] = 'Kal&yacute;p Kald&yacute;r';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Dosya Y&uuml;kle';
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
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Insufficient privelliges to be here';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Paralonuzu 1 saat aral&yacute;klarla de&eth;i&thorn;tirebilirsiniz.';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Uygunsuz email &thorn;ifresi, L&uuml;tfen Y&ouml;netici ile Kontak kurun';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'Bu email adresi veritaban&yacute;nda bulunamad&yacute;';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'L&uuml;tfen email adresini girin';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sorry, no active content to display';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = '&Uuml;zg&uuml;n&uuml;m, bu sayfay&yacute; g&ouml;r&uuml;nt&uuml;lemeye yetkiniz yok';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Daha &ouml;nce y&uuml;klenmi&thorn;ti';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Uygunsuz, hedef dizin yaz&yacute;lam&yacute;yor';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Kald&yacute;r&yacute;lam&yacute;yor';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Kald&yacute;rama: Se&ccedil;ilen dosya, kullan&yacute;mdad&yacute;r';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> could not be uninstalled, because it is still in use on {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'this page;these pages';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default template!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Dosya unzip edilemiyor';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Dosya Y&uuml;klenemiyor';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Dosya a&ccedil;arken hata.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Y&uuml;kledi&eth;in dosyan&yacute;n izin verilen dosya olmas&yacute;na dikkat edin:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Y&uuml;kledi&eth;in dosyalar&yacute;n izin verilen dosya olmas&yacute;na dikkat edin:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'L&uuml;tfen geri d&ouml;n&uuml;p b&uuml;t&uuml;n alanlar&yacute; doldurunuz';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde yerle&thorn;tirildi';
$MESSAGE['GENERIC_INVALID'] = 'Senin y&uuml;kledi&eth;in dosya, ge&ccedil;ersizdir';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Invalid WebsiteBaker installation file. Please check the *.zip format.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Invalid WebsiteBaker language file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Y&uuml;klenemiyor';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Ol hasta memnun et, bu, bir anda alabilirdi.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'L&uuml;tfen daha sonra kontrol edin...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Security offense!! data storing was refused!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kald&yacute;r&yacute;ld&yacute;';
$MESSAGE['GENERIC_UPGRADED'] = 'G&uuml;ncelleme ba&thorn;ar&yacute;l&yacute; bir&thorn;ekilde yap&yacute;ld&yacute;';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Website Under Construction';
$MESSAGE['GROUPS_ADDED'] = 'Grup, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde ekledi';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Se&ccedil;ti&eth;iniz grubu silmek istedi&eth;inizden eminmisiniz? (ve bu gruba ekli kullan&yacute;c&yacute;lar&yacute;)?';
$MESSAGE['GROUPS_DELETED'] = 'Grup, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde silindi';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Grup ad&yacute; bo&thorn;';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Bu grup ad&yacute; zaten var';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Hi&ccedil;bir grup bulmad&yacute;';
$MESSAGE['GROUPS_SAVED'] = 'Grup, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kay&yacute;t edildi';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'L&uuml;tfen &thorn;ifre girin';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = '&THORN;ifreniz &ccedil;ok uzun';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = '&THORN;ifreniz &ccedil;ok k&yacute;sa';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Mutlaka bir uzant&yacute; girmelisinz';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Mutlaka bir isim girmelisiniz';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Se&ccedil;ti&eth;iniz dizin silinemiyor';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Se&ccedil;ti&eth;iniz dosya silinemiyor';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Yeni isim ver ba&thorn;ar&yacute;s&yacute;z';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Dosya ve dizinleri silmek istedi&eth;inizden eminmisiniz?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Dizin, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde silindi';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Dosya, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde silindi';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Directory does not exist';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Giremezsiniz ../ i&ccedil;indeki dizin ad&yacute;';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Girmi&thorn; oldu&eth;unuz dizin zaten var';
$MESSAGE['MEDIA_DIR_MADE'] = 'Dizin, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde yaratt&yacute;';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Dizin yaratma ba&thorn;ar&yacute;s&yacute;z';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Girmi&thorn; oldu&eth;unuz dosya zaten var';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Dosya Bulunamad&yacute;';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Giremezsiniz ../ ismine';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'index.php isimini kullanamazs&yacute;n&yacute;z';
$MESSAGE['MEDIA_NONE_FOUND'] = 'No media found in the current folder';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was recieved';
$MESSAGE['MEDIA_RENAMED'] = 'Yeni isim ver ba&thorn;ar&yacute;l&yacute;.';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' Dosya ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde y&uuml;klendi';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Cannot have ../ in the folder target';
$MESSAGE['MEDIA_UPLOADED'] = ' Dosyalar ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde y&uuml;klendi';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Sorry, this form has been submitted too many times so far this hour. Please retry in the next hour.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'The verification number (also known as Captcha) that you entered is incorrect. If you are having problems reading the Captcha, please email: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'You must enter details for the following fields';
$MESSAGE['PAGES_ADDED'] = 'Sayfa, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde ekledi';
$MESSAGE['PAGES_ADDED_HEADING'] = '&Uuml;st sayfa, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde ekledi';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'L&uuml;tfen men&uuml; ba&thorn;l&yacute;&eth;&yacute;n&yacute; girin';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'L&uuml;tfen sayfa ba&thorn;l&yacute;&eth;&yacute;n&yacute; girin';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Yarat&yacute;rken hatal&yacute; giri&thorn; /pages dizini i&ccedil;in (Yetersiz yetki)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Silinirken hatal&yacute; giri&thorn; /pages dizini i&ccedil;in (Yetersiz yetki)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Yenilenen sayfada hata var';
$MESSAGE['PAGES_DELETED'] = 'Sayfa, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde silindi';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Se&ccedil;ti&eth;iniz sayfay&yacute; silmek istedi&eth;inizden eminmisiniz (B&uuml;t&uuml;n alt sayfalar silinecektir)';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Sizin bu sayfay&yacute; de&eth;i&thorn;tirme izininiz yok';
$MESSAGE['PAGES_INTRO_LINK'] = 'Buraya t&yacute;klayarak Giri&thorn; Sayfas&yacute;n&yacute; D&uuml;zenleye Bilirsiniz.';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Dosyaya yaz&yacute;lam&yacute;yor /pages/intro.php (Yetersiz yetki)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Giri&thorn; sayfas&yacute; ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kay&yacute;t edildi';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Son de&eth;i&thorn;iklik yapan';
$MESSAGE['PAGES_NOT_FOUND'] = 'Sayfa bulunamad&yacute;';
$MESSAGE['PAGES_NOT_SAVED'] = 'Kay&yacute;t edilen sayfa hatal&yacute;';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Bu sayfa veya dosya zaten var.';
$MESSAGE['PAGES_REORDERED'] = 'Ba&thorn;ar&yacute;l&yacute; bi&ccedil;imde yenilendi';
$MESSAGE['PAGES_RESTORED'] = 'Sayfa, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kurtar&yacute;ld&yacute;';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Sayfaya Git';
$MESSAGE['PAGES_SAVED'] = 'Sayfa, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kay&yacute;t edildi';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Sayfa ayarlar&yacute; ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kay&yacute;t edildi';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Section properties saved successfully';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Girdi&eth;iniz &thorn;ifre yanl&yacute;&thorn;';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Ayr&yacute;nt&yacute;lar, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kay&yacute;t edildi';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'Email, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde g&uuml;ncelle&thorn;tirdi';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Invalid password chars used';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Parola, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde de&eth;i&thorn;tirdi';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'The change of the record has missed.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'The changed record was updated successfully.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Adding a new record has missed.';
$MESSAGE['RECORD_NEW_SAVED'] = 'New record was added successfully.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Not Edin: Bu buton b&uuml;t&uuml;n de&eth;i&thorn;iklikleri ilk haline geri getirir';
$MESSAGE['SETTINGS_SAVED'] = 'Ayarlar ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kay&yacute;t edildi';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Unable to open the configuration file';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Cannot write to configuration file';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Please note: this is only recommended for testing environments';
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
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Bir email adresi girmelisiniz.';
$MESSAGE['START_CURRENT_USER'] = 'Sizin kulland&yacute;&eth;&yacute;n&yacute;z giri&thorn; ismi:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Uyar&yacute;! Y&uuml;kleme dizini halen duruyor!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Ho&thorn;geldiniz WebsiteBaker Y&ouml;netimine';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Please note: to change the template you must go to the Settings section';
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
$MESSAGE['USERS_ADDED'] = 'Kullan&yacute;c&yacute;, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde ekledi';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Function rejected, You can not delete yourself!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Not Edin: Sen sadece yukar&yacute;daki alanlara de&eth;erleri gir. E&eth;er bu kullan&yacute;c&yacute;lar&yacute; dile de&eth;i&thorn;tirseydin.';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Se&ccedil;ti&eth;iniz kullan&yacute;c&yacute;lar&yacute; silmek istedi&eth;inizden eminmisiniz?';
$MESSAGE['USERS_DELETED'] = 'Kullan&yacute;c&yacute;, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde silindi';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'Girdi&eth;iniz email ba&thorn;kas&yacute; taraf&yacute;ndan kullan&yacute;l&yacute;yor';
$MESSAGE['USERS_INVALID_EMAIL'] = 'Girdi&eth;iniz email adresi ge&ccedil;ersiz';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Hi&ccedil;bir grup se&ccedil;ilmedi';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'Girdi&eth;iniz &thorn;ifre bulunamad&yacute;';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'Girdi&eth;iniz &thorn;ifre k&yacute;sa';
$MESSAGE['USERS_SAVED'] = 'Kullan&yacute;c&yacute;, ba&thorn;ar&yacute;l&yacute; bir &thorn;ekilde kay&yacute;t edildi';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'Access the WebsiteBaker administration tools...';
$OVERVIEW['GROUPS'] = 'Kullan&yacute;c&yacute; Gruplar&yacute;n&yacute;n &Yacute;zinlerini D&uuml;zenleme...';
$OVERVIEW['HELP'] = 'Sorular&yacute;n&yacute;z? Cevaplar&yacute;...';
$OVERVIEW['LANGUAGES'] = 'WebsiteBaker Dilleri D&uuml;zenleme...';
$OVERVIEW['MEDIA'] = 'Resim Deposundaki Dosyalar&yacute; Y&ouml;netme...';
$OVERVIEW['MODULES'] = 'WebsiteBaker Mod&uuml;llerini Y&ouml;netme...';
$OVERVIEW['PAGES'] = 'Website Sayfalar&yacute;n&yacute; Y&ouml;netme...';
$OVERVIEW['PREFERENCES'] = 'Email, &THORN;ifre gibi ayarlar&yacute; d&uuml;zenleme... ';
$OVERVIEW['SETTINGS'] = 'WebsiteBaker i&ccedil;in ayarlar&yacute; d&uuml;zenleme...';
$OVERVIEW['START'] = 'Y&ouml;netici G&ouml;r&uuml;n&uuml;m&uuml;';
$OVERVIEW['TEMPLATES'] = 'Websitenizdeki Kal&yacute;plar&yacute; De&eth;i&thorn;tirme Ve D&uuml;zenleme...';
$OVERVIEW['USERS'] = 'WebsiteBaker kullan&yacute;c&yacute;lar&yacute;n&yacute; d&uuml;zenleme...';
$OVERVIEW['VIEW'] = 'Yeni bir pencerede sitenizin &ouml;ng&ouml;r&uuml;n&uuml;m&uuml;...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
