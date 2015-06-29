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
 * @version         $Id: CA.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/CA.php $
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
$language_code = 'CA';
$language_name = 'Catalan';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Carles Escrig (simkin)';
$language_license = 'GNU General Public License';
$MENU['ACCESS'] = 'Acc&eacute;s';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Afegits';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Demanar Dades del Compte';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Grups';
$MENU['HELP'] = 'Ajuda';
$MENU['LANGUAGES'] = 'Idiomes';
$MENU['LOGIN'] = 'Entrar';
$MENU['LOGOUT'] = 'Eixir';
$MENU['MEDIA'] = 'Fitxers';
$MENU['MODULES'] = 'M&ograve;duls';
$MENU['PAGES'] = 'P&agrave;gines';
$MENU['PREFERENCES'] = 'Perfil';
$MENU['SETTINGS'] = 'Par&agrave;metres';
$MENU['START'] = 'Inici';
$MENU['TEMPLATES'] = 'Plantilles';
$MENU['USERS'] = 'Usuaris';
$MENU['VIEW'] = 'Veure';
$TEXT['ACCOUNT_SIGNUP'] = 'Registre de Compte';
$TEXT['ACTIONS'] = 'Accions';
$TEXT['ACTIVE'] = 'Actiu';
$TEXT['ADD'] = 'Afegeix';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'Afegeix Secci&oacute;';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Administraci&oacute;';
$TEXT['ADMINISTRATION_TOOL'] = 'Administration tool';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administradors';
$TEXT['ADVANCED'] = 'Avan&ccedil;at';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Allowed Viewers';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Permetre Diverses Seleccions';
$TEXT['ALL_WORDS'] = 'Totes les Paraules';
$TEXT['ANCHOR'] = 'Anchor';
$TEXT['ANONYMOUS'] = 'An&ograve;nim';
$TEXT['ANY_WORDS'] = 'Qualsevol Paraula';
$TEXT['APP_NAME'] = 'Application Name';
$TEXT['ARE_YOU_SURE'] = 'Esteu segur?';
$TEXT['AUTHOR'] = 'Autor';
$TEXT['BACK'] = 'Arrere';
$TEXT['BACKUP'] = 'Backup';
$TEXT['BACKUP_ALL_TABLES'] = 'Backup all tables in database';
$TEXT['BACKUP_DATABASE'] = 'Backup Database';
$TEXT['BACKUP_MEDIA'] = 'Backup Media';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Backup only WB-specific tables';
$TEXT['BASIC'] = 'B&agrave;sic';
$TEXT['BLOCK'] = 'Bloc';
$TEXT['CALENDAR'] = 'Calender';
$TEXT['CANCEL'] = 'Cancel&middot;la';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha Verification';
$TEXT['CAP_EDIT_CSS'] = 'Edit CSS';
$TEXT['CHANGE'] = 'Canvia';
$TEXT['CHANGES'] = 'Canvis';
$TEXT['CHANGE_SETTINGS'] = 'Canvia Par&agrave;metres';
$TEXT['CHARSET'] = 'Charset';
$TEXT['CHECKBOX_GROUP'] = 'Grup de quadres de verificaci&oacute;';
$TEXT['CLOSE'] = 'Tanca';
$TEXT['CODE'] = 'Codi';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'Contrau';
$TEXT['COMMENT'] = 'Comentari';
$TEXT['COMMENTING'] = 'Comentaris';
$TEXT['COMMENTS'] = 'Comentaris';
$TEXT['CREATE_FOLDER'] = 'Crea Carpeta';
$TEXT['CURRENT'] = 'Actual';
$TEXT['CURRENT_FOLDER'] = 'Carpeta Actual';
$TEXT['CURRENT_PAGE'] = 'P&agrave;gina Actual';
$TEXT['CURRENT_PASSWORD'] = 'Contrasenya Actual';
$TEXT['CUSTOM'] = 'Personalitzat';
$TEXT['DATABASE'] = 'Base de Dades';
$TEXT['DATE'] = 'Data';
$TEXT['DATE_FORMAT'] = 'Format de Data';
$TEXT['DEFAULT'] = 'Per defecte';
$TEXT['DEFAULT_CHARSET'] = 'Default Charset';
$TEXT['DEFAULT_TEXT'] = 'Text per defecte';
$TEXT['DELETE'] = 'Esborra';
$TEXT['DELETED'] = 'Esborrat';
$TEXT['DELETE_DATE'] = 'Delete date';
$TEXT['DELETE_ZIP'] = 'Delete zip archive after unpacking';
$TEXT['DESCRIPTION'] = 'Descripci&oacute;';
$TEXT['DESIGNED_FOR'] = 'Dissenyat Per';
$TEXT['DIRECTORIES'] = 'Directoris';
$TEXT['DIRECTORY_MODE'] = 'Mode Directori';
$TEXT['DISABLED'] = 'Inhabilitat';
$TEXT['DISPLAY_NAME'] = 'Nom a Mostrar';
$TEXT['EMAIL'] = 'Correu';
$TEXT['EMAIL_ADDRESS'] = 'Adre&ccedil;a de Correu';
$TEXT['EMPTY_TRASH'] = 'Buida la Paperera';
$TEXT['ENABLED'] = 'Habilitat';
$TEXT['END'] = 'Fi';
$TEXT['ERROR'] = 'Error';
$TEXT['EXACT_MATCH'] = 'Coincid&egrave;ncia Exacta';
$TEXT['EXECUTE'] = 'Execuci&oacute;';
$TEXT['EXPAND'] = 'Expandeix';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['FIELD'] = 'Camp';
$TEXT['FILE'] = 'fitxer';
$TEXT['FILES'] = 'fitxers';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Permisos del Sistema de Fitxers';
$TEXT['FILE_MODE'] = 'Mode Fitxer';
$TEXT['FINISH_PUBLISHING'] = 'Fi de Publicaci&oacute;';
$TEXT['FOLDER'] = 'carpeta';
$TEXT['FOLDERS'] = 'carpetes';
$TEXT['FOOTER'] = 'Peu';
$TEXT['FORGOTTEN_DETAILS'] = 'Heu oblidat la contrasenya?';
$TEXT['FORGOT_DETAILS'] = 'Heu oblidat els Detalls?';
$TEXT['FROM'] = 'des de';
$TEXT['FRONTEND'] = 'Frontal';
$TEXT['FULL_NAME'] = 'Nom Complet';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Grup';
$TEXT['HEADER'] = 'Cap&ccedil;alera';
$TEXT['HEADING'] = 'Encap&ccedil;alament';
$TEXT['HEADING_CSS_FILE'] = 'Actual module file: ';
$TEXT['HEIGHT'] = 'Al&ccedil;ada';
$TEXT['HIDDEN'] = 'Amagat';
$TEXT['HIDE'] = 'Amaga';
$TEXT['HIDE_ADVANCED'] = 'Oculta Opcions Avan&ccedil;ades';
$TEXT['HOME'] = 'Inici';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Redirecci&oacute; de P&agrave;gina Inicial';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Servidor';
$TEXT['ICON'] = 'Icona';
$TEXT['IMAGE'] = 'Imatge';
$TEXT['INLINE'] = 'Inserida';
$TEXT['INSTALL'] = 'Instal&middot;la';
$TEXT['INSTALLATION'] = 'Instal&middot;laci&oacute;';
$TEXT['INSTALLATION_PATH'] = 'Ruta d\'Instal&middot;laci&oacute;';
$TEXT['INSTALLATION_URL'] = 'URL d\'Instal&middot;laci&oacute;';
$TEXT['INSTALLED'] = 'installed';
$TEXT['INTRO'] = 'Entrada';
$TEXT['INTRO_PAGE'] = 'P&agrave;gina d\'Entrada';
$TEXT['INVALID_SIGNS'] = 'must begin with a letter or has invalid signs';
$TEXT['KEYWORDS'] = 'Paraules Clau';
$TEXT['LANGUAGE'] = 'Idioma';
$TEXT['LAST_UPDATED_BY'] = '&Uacute;ltima Actualitzaci&oacute; Per';
$TEXT['LENGTH'] = 'Longitud';
$TEXT['LEVEL'] = 'Nivell';
$TEXT['LINK'] = 'Enlla&ccedil;';
$TEXT['LINUX_UNIX_BASED'] = 'Basat en Linux/Unix';
$TEXT['LIST_OPTIONS'] = 'Llista Opcions';
$TEXT['LOGGED_IN'] = 'Identificat';
$TEXT['LOGIN'] = 'Identificaci&oacute;';
$TEXT['LONG'] = 'Llarg';
$TEXT['LONG_TEXT'] = 'Text Llarg';
$TEXT['LOOP'] = 'Repetici&oacute;';
$TEXT['MAIN'] = 'Principal';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MANAGE'] = 'Administreu';
$TEXT['MANAGE_GROUPS'] = 'Administra els Grups';
$TEXT['MANAGE_USERS'] = 'Administra els Usuaris';
$TEXT['MATCH'] = 'Coincidir';
$TEXT['MATCHING'] = 'Matching';
$TEXT['MAX_EXCERPT'] = 'Max lines of excerpt';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Trameses M&agrave;x. Per Hora';
$TEXT['MEDIA_DIRECTORY'] = 'Directori de Fitxers';
$TEXT['MENU'] = 'Men&uacute;';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'T&iacute;tol del Men&uacute;';
$TEXT['MESSAGE'] = 'Missatge';
$TEXT['MODIFY'] = 'Modifica';
$TEXT['MODIFY_CONTENT'] = 'Modifica Contingut';
$TEXT['MODIFY_SETTINGS'] = 'Modifica Par&agrave;metres';
$TEXT['MODULE_ORDER'] = 'Module-order for searching';
$TEXT['MODULE_PERMISSIONS'] = 'Permisos de M&ograve;dul';
$TEXT['MORE'] = 'M&eacute;s';
$TEXT['MOVE_DOWN'] = 'Mou Avall';
$TEXT['MOVE_UP'] = 'Mou Amunt';
$TEXT['MULTIPLE_MENUS'] = 'Diversos Men&uacute;s';
$TEXT['MULTISELECT'] = 'Multi-selecci&oacute;';
$TEXT['NAME'] = 'Nom';
$TEXT['NEED_CURRENT_PASSWORD'] = 'confirm with current password';
$TEXT['NEED_TO_LOGIN'] = 'Voleu identificar-vos?';
$TEXT['NEW_PASSWORD'] = 'Nova Contrasenya';
$TEXT['NEW_WINDOW'] = 'Nova Finestra';
$TEXT['NEXT'] = 'Seg&uuml;ent';
$TEXT['NEXT_PAGE'] = 'P&agrave;gina Seg&uuml;ent';
$TEXT['NO'] = 'No';
$TEXT['NONE'] = 'Cap';
$TEXT['NONE_FOUND'] = 'No s\'ha trobat cap';
$TEXT['NOT_FOUND'] = 'No Trobat';
$TEXT['NOT_INSTALLED'] = 'not installed';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Cap Resultat';
$TEXT['OF'] = 'De';
$TEXT['ON'] = 'A';
$TEXT['OPEN'] = 'Open';
$TEXT['OPTION'] = 'Opci&oacute;';
$TEXT['OTHERS'] = 'Altres';
$TEXT['OUT_OF'] = 'Fora De';
$TEXT['OVERWRITE_EXISTING'] = 'Sobreescriure';
$TEXT['PAGE'] = 'P&agrave;gina';
$TEXT['PAGES_DIRECTORY'] = 'Directori de P&agrave;gines';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Extensi&oacute; de P&agrave;gina';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Idiomes de la p&agrave;gina';
$TEXT['PAGE_LEVEL_LIMIT'] = 'L&iacute;mit de Nivell de P&agrave;gina';
$TEXT['PAGE_SPACER'] = 'Separador de P&agrave;gina';
$TEXT['PAGE_TITLE'] = 'T&iacute;tol de la P&agrave;gina';
$TEXT['PAGE_TRASH'] = 'Paperera';
$TEXT['PARENT'] = 'Mare';
$TEXT['PASSWORD'] = 'Contrasenya';
$TEXT['PATH'] = 'Ruta';
$TEXT['PHP_ERROR_LEVEL'] = 'Nivell d\'Informe d\'Error de PHP';
$TEXT['PLEASE_LOGIN'] = 'Please login';
$TEXT['PLEASE_SELECT'] = 'Per favor trieu';
$TEXT['POST'] = 'Missatge';
$TEXT['POSTS_PER_PAGE'] = 'Posts Per Page';
$TEXT['POST_FOOTER'] = 'Post Footer';
$TEXT['POST_HEADER'] = 'Post Header';
$TEXT['PREVIOUS'] = 'Anterior';
$TEXT['PREVIOUS_PAGE'] = 'P&agrave;gina Anterior';
$TEXT['PRIVATE'] = 'Privat';
$TEXT['PRIVATE_VIEWERS'] = 'Visualitzadors Privats';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'P&uacute;blic';
$TEXT['PUBL_END_DATE'] = 'End date';
$TEXT['PUBL_START_DATE'] = 'Start date';
$TEXT['RADIO_BUTTON_GROUP'] = 'Grup de Botons';
$TEXT['READ'] = 'Lectura';
$TEXT['READ_MORE'] = 'Llegir M&eacute;s';
$TEXT['REDIRECT_AFTER'] = 'Redirect after';
$TEXT['REGISTERED'] = 'Registrat';
$TEXT['REGISTERED_VIEWERS'] = 'Visualitzadors Registrats';
$TEXT['RELOAD'] = 'Recarrega';
$TEXT['REMEMBER_ME'] = 'Recorda les meues dades';
$TEXT['RENAME'] = 'Reanomena';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Requerit';
$TEXT['REQUIREMENT'] = 'Requirement';
$TEXT['RESET'] = 'Reinicia';
$TEXT['RESIZE'] = 'Redimensiona';
$TEXT['RESIZE_IMAGE_TO'] = 'Redimensiona Imatge A';
$TEXT['RESTORE'] = 'Restore';
$TEXT['RESTORE_DATABASE'] = 'Restore Database';
$TEXT['RESTORE_MEDIA'] = 'Restore Media';
$TEXT['RESULTS'] = 'Resultats';
$TEXT['RESULTS_FOOTER'] = 'Peu de Resultats';
$TEXT['RESULTS_FOR'] = 'Resultats De';
$TEXT['RESULTS_HEADER'] = 'Cap&ccedil;alera de Resultats';
$TEXT['RESULTS_LOOP'] = 'Bucle de Resultats';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Nova Contrasenya (de nou)';
$TEXT['RETYPE_PASSWORD'] = 'Contrasenya (de nou)';
$TEXT['SAME_WINDOW'] = 'La Mateixa Finestra';
$TEXT['SAVE'] = 'Desa';
$TEXT['SEARCH'] = 'Cerca';
$TEXT['SEARCHING'] = 'Recerca';
$TEXT['SECTION'] = 'Secci&oacute;';
$TEXT['SECTION_BLOCKS'] = 'Blocs de la Secci&oacute;';
$TEXT['SEC_ANCHOR'] = 'Section-Anchor text';
$TEXT['SELECT_BOX'] = 'Quadre de Selecci&oacute;';
$TEXT['SEND_DETAILS'] = 'Envia les Dades';
$TEXT['SEPARATE'] = 'Separada';
$TEXT['SEPERATOR'] = 'Separador';
$TEXT['SERVER_EMAIL'] = 'Correu del Servidor';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Sistema Operatiu del Servidor';
$TEXT['SESSION_IDENTIFIER'] = 'Session Identifier';
$TEXT['SETTINGS'] = 'Par&agrave;metres';
$TEXT['SHORT'] = 'Curt';
$TEXT['SHORT_TEXT'] = 'Text Curt';
$TEXT['SHOW'] = 'Mostra';
$TEXT['SHOW_ADVANCED'] = 'Mostra Opcions Avan&ccedil;ades';
$TEXT['SIGNUP'] = 'Registre';
$TEXT['SIZE'] = 'Mida';
$TEXT['SMART_LOGIN'] = 'Identificaci&oacute; R&agrave;pida';
$TEXT['START'] = 'Inici';
$TEXT['START_PUBLISHING'] = 'Inici de Publicaci&oacute;';
$TEXT['SUBJECT'] = 'Assumpte';
$TEXT['SUBMISSIONS'] = 'Trameses';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Trameses Emmagatzemades a la Base de Dades';
$TEXT['SUBMISSION_ID'] = 'ID de Tramesa';
$TEXT['SUBMITTED'] = 'Tram&eacute;s';
$TEXT['SUCCESS'] = '&Egrave;xit';
$TEXT['SYSTEM_DEFAULT'] = 'Per Defecte del Sistema';
$TEXT['SYSTEM_PERMISSIONS'] = 'Permisos de Sistema';
$TEXT['TABLE_PREFIX'] = 'Prefix de Taula';
$TEXT['TARGET'] = 'Dest&iacute;';
$TEXT['TARGET_FOLDER'] = 'Carpeta de dest&iacute;';
$TEXT['TEMPLATE'] = 'Plantilla';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Permisos de Plantilla';
$TEXT['TEXT'] = 'Text';
$TEXT['TEXTAREA'] = '&Agrave;rea de text';
$TEXT['TEXTFIELD'] = 'Camp de text';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_START_IMPORT'] = 'import';
$TEXT['TIME'] = 'Temps';
$TEXT['TIMEZONE'] = 'Fus Horari';
$TEXT['TIME_FORMAT'] = 'Format de Temps';
$TEXT['TIME_LIMIT'] = 'Max time to gather excerpts per module';
$TEXT['TITLE'] = 'T&iacute;tol';
$TEXT['TO'] = 'a';
$TEXT['TOP_FRAME'] = 'Top Frame';
$TEXT['TRASH_EMPTIED'] = 'Paperera Buidada';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Edit the CSS definitions in the textarea below.';
$TEXT['TYPE'] = 'Tipus';
$TEXT['UNDER_CONSTRUCTION'] = 'En Construcci&oacute;';
$TEXT['UNINSTALL'] = 'Desinstal&middot;la';
$TEXT['UNKNOWN'] = 'Desconegut';
$TEXT['UNLIMITED'] = 'Il&middot;limitat';
$TEXT['UNZIP_FILE'] = 'Upload and unpack a zip archive';
$TEXT['UP'] = 'Amunt';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Penja Fitxer(s)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Usuari';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Verification';
$TEXT['VERSION'] = 'Versi&oacute;';
$TEXT['VIEW'] = 'Veure';
$TEXT['VIEW_DELETED_PAGES'] = 'Mostra P&agrave;gines Esborrades';
$TEXT['VIEW_DETAILS'] = 'Veure Detalls';
$TEXT['VISIBILITY'] = 'Visibilitat';
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
$TEXT['WEBSITE'] = 'P&agrave;gina Web';
$TEXT['WEBSITE_DESCRIPTION'] = 'Descripci&oacute; del Lloc Web';
$TEXT['WEBSITE_FOOTER'] = 'Peu del Lloc Web';
$TEXT['WEBSITE_HEADER'] = 'Cap&ccedil;alera del Lloc Web';
$TEXT['WEBSITE_KEYWORDS'] = 'Paraules clau del Lloc Web';
$TEXT['WEBSITE_TITLE'] = 'T&iacute;tol del Lloc Web';
$TEXT['WELCOME_BACK'] = 'Benvingut de nou';
$TEXT['WIDTH'] = 'Amplada';
$TEXT['WINDOW'] = 'Finestra';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Permisos d\'escriptura de fitxer per a tothom';
$TEXT['WRITE'] = 'Escriptura';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG Editor';
$TEXT['WYSIWYG_STYLE'] = 'Estil WYSIWYG';
$TEXT['YES'] = 'S&iacute;';
$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On requirements not met';
$HEADING['ADD_CHILD_PAGE'] = 'Add child page';
$HEADING['ADD_GROUP'] = 'Afegeix Grup';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Afegeix Encap&ccedil;alament';
$HEADING['ADD_PAGE'] = 'Afegeix P&agrave;gina';
$HEADING['ADD_USER'] = 'Afegeix Usuari';
$HEADING['ADMINISTRATION_TOOLS'] = 'Administration Tools';
$HEADING['BROWSE_MEDIA'] = 'Explorar Fitxers';
$HEADING['CREATE_FOLDER'] = 'Crea Carpeta';
$HEADING['DEFAULT_SETTINGS'] = 'Par&agrave;metres per Defecte';
$HEADING['DELETED_PAGES'] = 'P&agrave;gines Esborrades';
$HEADING['FILESYSTEM_SETTINGS'] = 'Par&agrave;metres del Sistema de Fitxers';
$HEADING['GENERAL_SETTINGS'] = 'Par&agrave;metres Generals';
$HEADING['INSTALL_LANGUAGE'] = 'Instal&middot;la Idioma';
$HEADING['INSTALL_MODULE'] = 'Instal&middot;la M&ograve;dul';
$HEADING['INSTALL_TEMPLATE'] = 'Instal&middot;la Plantilla';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Execute module files manually';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Detalls de l\'Idioma';
$HEADING['MANAGE_SECTIONS'] = 'Administra les Seccions';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Modifica els Par&agrave;metres Avan&ccedil;ats de la P&agrave;gina';
$HEADING['MODIFY_DELETE_GROUP'] = 'Modifica/Esborra Grup';
$HEADING['MODIFY_DELETE_PAGE'] = 'Modifica/Esborra P&agrave;gina';
$HEADING['MODIFY_DELETE_USER'] = 'Modifica/Esborra Usuari';
$HEADING['MODIFY_GROUP'] = 'Modifica Grup';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Modifica P&agrave;gina Introduct&ograve;ria';
$HEADING['MODIFY_PAGE'] = 'Modifica P&agrave;gina';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Modifica els Par&agrave;metres de la P&agrave;gina';
$HEADING['MODIFY_USER'] = 'Modifica Usuari';
$HEADING['MODULE_DETAILS'] = 'Detalls del M&ograve;dul';
$HEADING['MY_EMAIL'] = 'El meu Correu';
$HEADING['MY_PASSWORD'] = 'La meua Contrasenya';
$HEADING['MY_SETTINGS'] = 'Els meus Par&agrave;metres';
$HEADING['SEARCH_SETTINGS'] = 'Par&agrave;metres de Cerca';
$HEADING['SERVER_SETTINGS'] = 'Server Settings';
$HEADING['TEMPLATE_DETAILS'] = 'Detalls de la Plantilla';
$HEADING['UNINSTALL_LANGUAGE'] = 'Desinstal&middot;la Idioma';
$HEADING['UNINSTALL_MODULE'] = 'Desinstal&middot;la M&ograve;dul';
$HEADING['UNINSTALL_TEMPLATE'] = 'Desinstal&middot;la Plantilla';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Penja Fitxer(s)';
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
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'No teniu privilegis suficients per estar ac&iacute;';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'La contrasenya no es pot reiniciar m&eacute;s d\'un cop per hora, disculpeu';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'No ha estat possible enviar la contrasenya, per favor contacteu amb l\'administrador del sistema';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'El correu que heu introdu&iuml;t no s\'ha trobat a la base de dades';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Per favor introdu&iuml;u la vostra adre&ccedil;a de correu a baix';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sorry, no active content to display';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Ho sentim, no teniu permisos per a veure aquesta p&agrave;gina';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Ja est&agrave; instal&middot;lat';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'No s\'ha pogut escriure al directori de dest&iacute;';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'No s\'ha pogut desinstal&middot;lar';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'No s\'ha pogut desinstal&middot;lar: s\'est&agrave; usant el fitxer seleccionat';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> could not be uninstalled, because it is still in use on {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'this page;these pages';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default template!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'No s\'ha pogut descomprimir el fitxer';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'No s\'ha pogut penjar el fitxer';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Error opening file.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Per favor recordeu que el fitxer que pengeu ha d\'estar en un dels seg&uuml;ents formats:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Per favor recordeu que els fitxers que pengeu han d\'estar en un dels seg&uuml;ents formats:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Per favor torneu arrere i completeu tots els camps';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Instal&middot;lat amb &egrave;xit';
$MESSAGE['GENERIC_INVALID'] = 'El fitxer que heu penjat no &eacute;s v&agrave;lid';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Invalid WebsiteBaker installation file. Please check the *.zip format.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Invalid WebsiteBaker language file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'No est&agrave; instal&middot;lat';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Please be patient, this might take a while.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Per favor torneu-ho a intentar prompte...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Security offense!! data storing was refused!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Desinstal&middot;lat amb &egrave;xit';
$MESSAGE['GENERIC_UPGRADED'] = 'Upgraded successfully';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Lloc Web en Construcci&oacute;';
$MESSAGE['GROUPS_ADDED'] = 'Grup afegit amb &egrave;xit';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Esteu segur de voler esborrar el grup seleccionat (i qualsevol usuari que pertanyi a aquest)?';
$MESSAGE['GROUPS_DELETED'] = 'Grup esborrat amb &egrave;xit';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'El nom del grup &eacute;s buit';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Group name already exists';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'No s\'han trobat grups';
$MESSAGE['GROUPS_SAVED'] = 'Grup desat amb &egrave;xit';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Per favor introdu&iuml;u una contrasenya';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'La contrasenya &eacute;s massa llarga';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'La contrasenya &eacute;s massa curta';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'No heu introdu&iuml;t una extensi&oacute; de fitxer';
$MESSAGE['MEDIA_BLANK_NAME'] = 'No heu introdu&iuml;t un nou nom';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'No es pot esborrar la carpeta seleccionada';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'No es pot esborrar el fitxer seleccionat';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'No s\'ha pogut canviar el nom';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Esteu segur que voleu esborrar el seg&uuml;ent fitxer o carpeta?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Carpeta esborrada amb &egrave;xit';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Fitxer esborrat amb &egrave;xit';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Directory does not exist';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'No es pot incloure ../ al nom de la carpeta';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Ja existeix una carpeta amb el nom que heu introdu&iuml;t';
$MESSAGE['MEDIA_DIR_MADE'] = 'Carpeta creada amb &egrave;xit';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'No s\'ha pogut crear la carpeta';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Ja existeix un fitxer amb el nom que heu introdu&iuml;t';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Fitxer no trobat';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'No es pot incloure ../ al nom';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'No es pot usar index.php com a nom';
$MESSAGE['MEDIA_NONE_FOUND'] = 'No s\'han trobat fitxers a la carpeta actual';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was recieved';
$MESSAGE['MEDIA_RENAMED'] = 'S\'ha canviat el nom amb &egrave;xit';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' fitxer s\'ha penjat amb &egrave;xit';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'No es pot tenir ../ a la carpeta de dest&iacute;';
$MESSAGE['MEDIA_UPLOADED'] = ' fitxers han estat penjats amb &egrave;xit';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Ho sentim, aquest formulari ha estat enviat massa vegades durant l\'&uacute;ltima hora. Per favor torneu-ho a intentar d\'ac&iacute; una hora.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'The verification number (also known as Captcha) that you entered is incorrect. If you are having problems reading the Captcha, please email: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Heu d\'introduir les dades per als seg&uuml;ents camps';
$MESSAGE['PAGES_ADDED'] = 'P&agrave;gina afegida amb &egrave;xit';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Cap&ccedil;alera de p&agrave;gina afegida amb &egrave;xit';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Per favor introdu&iuml;u un t&iacute;tol per al men&uacute;';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Per favor introdu&iuml;u un t&iacute;tol de p&agrave;gina';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Error creant el fitxer d\'acc&eacute;s al directori /pages (privilegis insuficients)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Error esborrant el fitxer d\'acc&eacute;s al directori /pages (privilegis insuficients)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Error re-ordenant p&agrave;gina';
$MESSAGE['PAGES_DELETED'] = 'P&agrave;gina esborrada amb &egrave;xit';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Esteu segur de voler esborrar la p&agrave;gina seleccionada';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'No teniu permisos per a modificar aquesta p&agrave;gina';
$MESSAGE['PAGES_INTRO_LINK'] = 'Premeu AC&Iacute; per a modificar la p&agrave;gina d\'entrada';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'No s\'ha pogut escriure al fitxer /pages/intro.php (privilegis insuficients)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'P&agrave;gina d\'entrada desada amb &egrave;xit';
$MESSAGE['PAGES_LAST_MODIFIED'] = '&Uacute;ltima modificaci&oacute; per';
$MESSAGE['PAGES_NOT_FOUND'] = 'No s\'ha trobat la p&agrave;gina';
$MESSAGE['PAGES_NOT_SAVED'] = 'Error desant la p&agrave;gina';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'Existeix una p&agrave;gina amb el mateix t&iacute;tol o similar';
$MESSAGE['PAGES_REORDERED'] = 'P&agrave;gina re-ordenada amb &egrave;xit';
$MESSAGE['PAGES_RESTORED'] = 'P&agrave;gina restaurada amb &egrave;xit';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Torna a les p&agrave;gines';
$MESSAGE['PAGES_SAVED'] = 'P&agrave;gina desada amb &egrave;xit';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Par&agrave;metres de p&agrave;gina desats amb &egrave;xit';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Propietats de la secci&oacute; desades amb &egrave;xit';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'La contrasenya (actual) que heu introdu&iuml;t &eacute;s incorrecta';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Dades desades amb &egrave;xit';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'Correu actualitzat amb &egrave;xit';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Invalid password chars used';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Contrasenya canviada amb &egrave;xit';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'The change of the record has missed.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'The changed record was updated successfully.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Adding a new record has missed.';
$MESSAGE['RECORD_NEW_SAVED'] = 'New record was added successfully.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Av&iacute;s: Pr&eacute;mer aquest bot&oacute; reinicia tots els canvis no desats';
$MESSAGE['SETTINGS_SAVED'] = 'Par&agrave;metres desats amb &egrave;xit';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'No ha estat possible obrir el fitxer de configuraci&oacute;';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'No es pot escriure al fitxer de configuraci&oacute;';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Av&iacute;s: a&ccedil;&ograve; nom&eacute;s &eacute;s recomana per a entorns de proves';
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
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Heu d\'Introduir una adre&ccedil;a de correu';
$MESSAGE['START_CURRENT_USER'] = 'Actualment esteu identificat com a:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Atenci&oacute;, el Directori d\'Instal&middot;laci&oacute; Encara Existeix!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Benvingut/da al Panell de Control de WebsiteBaker';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Av&iacute;s: per a canviar la plantilla heu d\'anar a la secci&oacute; Par&agrave;metres';
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
$MESSAGE['USERS_ADDED'] = 'Usuari afegit amb &egrave;xit';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Function rejected, You can not delete yourself!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Av&iacute;s: Nom&eacute;s haur&iacute;eu d\'introduir valors als camps superiors si voleu canviar aquestes contrasenyes d\'usuari';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Esteu segur de voler esborrar l\'usuari seleccionat?';
$MESSAGE['USERS_DELETED'] = 'Usuari esborrat amb &egrave;xit';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'L\'adre&ccedil;a de correu que heu introdu&iuml;t ja est&agrave; en &uacute;s';
$MESSAGE['USERS_INVALID_EMAIL'] = 'L\'adre&ccedil;a de correu introdu&iuml;da &eacute;s inv&agrave;lida';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'No s\'ha seleccionat cap grup';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'La contrasenya introdu&iuml;da no coincideix';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'La contrasenya introdu&iuml;da &eacute;s massa curta';
$MESSAGE['USERS_SAVED'] = 'Usuari desat amb &egrave;xit';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$OVERVIEW['ADMINTOOLS'] = 'Access the WebsiteBaker administration tools...';
$OVERVIEW['GROUPS'] = 'Administreu els grups d\'usuaris i els seus permisos de sistema...';
$OVERVIEW['HELP'] = 'Teniu una pregunta? Trobeu la vostra resposta...';
$OVERVIEW['LANGUAGES'] = 'Administreu els idiomes de WebsiteBaker...';
$OVERVIEW['MEDIA'] = 'Administreu la carpeta de fitxers...';
$OVERVIEW['MODULES'] = 'Administreu els m&ograve;duls de WebsiteBaker...';
$OVERVIEW['PAGES'] = 'Administreu les p&agrave;gines de la vostra web...';
$OVERVIEW['PREFERENCES'] = 'Canvieu les prefer&egrave;ncies com l\'adre&ccedil;a de correu electr&ograve;nic, contrasenya, etc... ';
$OVERVIEW['SETTINGS'] = 'Canvieu els par&agrave;metres de WebsiteBaker...';
$OVERVIEW['START'] = '&Iacute;ndex d\'Administraci&oacute;';
$OVERVIEW['TEMPLATES'] = 'Canvieu l\'aspecte i estil de la vostra p&agrave;gina amb plantilles...';
$OVERVIEW['USERS'] = 'Administreu els usuaris que poden identificar-se a WebsiteBaker...';
$OVERVIEW['VIEW'] = 'Veure i navegar r&agrave;pidament la vostra p&agrave;gina web en una nova finestra...';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
