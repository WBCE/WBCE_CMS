<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 *
 * Made whith help of Automated Language File tool Copyright heimsath.org
 */

//no direct file access
if (count(get_included_files()) ==1) {
    $z="HTTP/1.0 404 Not Found";
    header($z);
    die($z);
}

// Set the language information
$language_code = 'SV';
$language_name = 'Swedish'; // Svenska
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Markus Eriksson, Peppe Bergqvist';
$language_license = 'GNU General Public License';


$MENU['ACCESS'] = 'R&#228;ttigheter';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Till&#228;gg';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Skicka inloggningsuppgifter';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Grupper';
$MENU['HELP'] = 'Hj&#228;lp';
$MENU['LANGUAGES'] = 'Spr&#229;k';
$MENU['LOGIN'] = 'Logga in';
$MENU['LOGOUT'] = 'Logga ut';
$MENU['MEDIA'] = 'Mediabibliotek';
$MENU['MODULES'] = 'Moduler';
$MENU['PAGES'] = 'Sidor';
$MENU['PREFERENCES'] = 'Mina uppgifter';
$MENU['SETTINGS'] = 'Inst&#228;llningar';
$MENU['START'] = 'Hem';
$MENU['TEMPLATES'] = 'Mallar';
$MENU['USERS'] = 'Anv&#228;ndare';
$MENU['VIEW'] = 'Visa sida';


$TEXT['ACCOUNT_SIGNUP'] = 'Kontoregistrering';
$TEXT['ACTIONS'] = '&#197;tg&#228;rder';
$TEXT['ACTIVE'] = 'Aktiv';
$TEXT['ADD'] = 'L&#228;gg till';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'L&#228;gg till sektion';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Administration';
$TEXT['ADMINISTRATION_TOOL'] = 'Administrationsverktyg';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administratorer';
$TEXT['ADVANCED'] = 'Avancerat';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Till&#229;tna att se';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Till&#229;t flera val';
$TEXT['ALL_WORDS'] = 'Alla ord';
$TEXT['ANCHOR'] = 'Anchor';
$TEXT['ANONYMOUS'] = 'Anonym';
$TEXT['ANY_WORDS'] = 'N&#229;got ord';
$TEXT['APP_NAME'] = 'Namn p&#229; applikation';
$TEXT['ARE_YOU_SURE'] = '&#196;r du s&#228;ker?';
$TEXT['AUTHOR'] = 'F&#246;rfattare';
$TEXT['BACK'] = 'Tillbaka';
$TEXT['BACKUP'] = 'Backup';
$TEXT['BACKUP_ALL_TABLES'] = 'Backup av samtliga tabeller i databasen';
$TEXT['BACKUP_DATABASE'] = 'Backup av databas';
$TEXT['BACKUP_MEDIA'] = 'Backup av media';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Backup av endast tabeller f&#246;r WB';
$TEXT['BASIC'] = 'Standard';
$TEXT['BLOCK'] = 'Block';
$TEXT['CALENDAR'] = 'Calender';
$TEXT['CANCEL'] = 'Avbryt';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captchaverifiering (&#228;ven kallat verifierings nummer) ';
$TEXT['CAP_EDIT_CSS'] = 'Edit CSS';
$TEXT['CHANGE'] = '&#196;ndra';
$TEXT['CHANGES'] = '&#196;ndringar';
$TEXT['CHANGE_SETTINGS'] = '&#196;ndra inst&#228;llningar';
$TEXT['CHARSET'] = 'Typsnitt';
$TEXT['CHECKBOX_GROUP'] = 'Valruta flera';
$TEXT['CLOSE'] = 'St&#228;ng';
$TEXT['CODE'] = 'Kod';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'St&#228;ng';
$TEXT['COMMENT'] = 'Kommentar';
$TEXT['COMMENTING'] = 'Kommenterar';
$TEXT['COMMENTS'] = 'Kommentarer';
$TEXT['CREATE_FOLDER'] = 'Skapa mapp';
$TEXT['CURRENT'] = 'Nuvarande';
$TEXT['CURRENT_FOLDER'] = 'Nuvarande mapp';
$TEXT['CURRENT_PAGE'] = 'Nuvarande sida';
$TEXT['CURRENT_PASSWORD'] = 'Nuvarande l&#246;senord';
$TEXT['CUSTOM'] = 'Sedvanlig';
$TEXT['DATABASE'] = 'Databas';
$TEXT['DATE'] = 'Datum';
$TEXT['DATE_FORMAT'] = 'Datumformat';
$TEXT['DEFAULT'] = 'Standard';
$TEXT['DEFAULT_CHARSET'] = 'Standardtypsnitt';
$TEXT['DEFAULT_TEXT'] = 'Standardtext';
$TEXT['DELETE'] = 'Radera';
$TEXT['DELETED'] = 'Raderat';
$TEXT['DELETE_DATE'] = 'Delete date';
$TEXT['DELETE_ZIP'] = 'Delete zip archive after unpacking';
$TEXT['DESCRIPTION'] = 'Beskrivning';
$TEXT['DESIGNED_FOR'] = 'Skapad f&#246;r';
$TEXT['DIRECTORIES'] = 'Mappar';
$TEXT['DIRECTORY_MODE'] = 'Mappar s&#228;tt';
$TEXT['DISABLED'] = 'Inaktiverad';
$TEXT['DISPLAY_NAME'] = 'Visa namn';
$TEXT['EMAIL'] = 'E-post';
$TEXT['EMAIL_ADDRESS'] = 'E-postadress';
$TEXT['EMPTY_TRASH'] = 'T&#246;m papperskorgen';
$TEXT['ENABLED'] = 'Aktiverad';
$TEXT['END'] = 'Stopp';
$TEXT['ERROR'] = 'FEL';
$TEXT['EXACT_MATCH'] = 'Exakt matchning';
$TEXT['EXECUTE'] = 'K&#246;ra script';
$TEXT['EXPAND'] = '&#214;ppna';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['FIELD'] = 'F&#228;lt';
$TEXT['FILE'] = 'Fil';
$TEXT['FILES'] = 'Filer';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'R&#228;ttigheter filsystem';
$TEXT['FILE_MODE'] = 'Fil s&#228;tt';
$TEXT['FINISH_PUBLISHING'] = 'Avsluta publicering';
$TEXT['FOLDER'] = 'Mapp';
$TEXT['FOLDERS'] = 'Mappar';
$TEXT['FOOTER'] = 'Fot';
$TEXT['FORGOTTEN_DETAILS'] = 'Gl&#246;mt dina uppgifter?';
$TEXT['FORGOT_DETAILS'] = 'Gl&#246;mt dina uppgifter?';
$TEXT['FROM'] = 'Fr&#229;n';
$TEXT['FRONTEND'] = 'Front-end';
$TEXT['FULL_NAME'] = 'Ditt hela namn';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Grupp';
$TEXT['HEADER'] = 'Huvud';
$TEXT['HEADING'] = 'Rubrik';
$TEXT['HEADING_CSS_FILE'] = 'Actual module file: ';
$TEXT['HEIGHT'] = 'H&#246;jd';
$TEXT['HIDDEN'] = 'G&#246;md';
$TEXT['HIDE'] = 'G&#246;m';
$TEXT['HIDE_ADVANCED'] = 'G&#246;m avancerade val';
$TEXT['HOME'] = 'Hem';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Omstyrning hemsida';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Icon';
$TEXT['IMAGE'] = 'Bild';
$TEXT['INLINE'] = 'Aktiverad';
$TEXT['INSTALL'] = 'Installera';
$TEXT['INSTALLATION'] = 'Installation';
$TEXT['INSTALLATION_PATH'] = 'Installation s&#246;kv&#228;g';
$TEXT['INSTALLATION_URL'] = 'Installation URL';
$TEXT['INSTALLED'] = 'installed';
$TEXT['INTRO'] = 'Inledning';
$TEXT['INTRO_PAGE'] = 'F&#246;rstasida';
$TEXT['INVALID_SIGNS'] = 'must begin with a letter or has invalid signs';
$TEXT['KEYWORDS'] = 'Nyckelord';
$TEXT['LANGUAGE'] = 'Spr&#229;k';
$TEXT['LAST_UPDATED_BY'] = 'Senast uppdaterad av';
$TEXT['LENGTH'] = 'L&#228;ngd';
$TEXT['LEVEL'] = 'Niv&#229;';
$TEXT['LINK'] = 'Link';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix';
$TEXT['LIST_OPTIONS'] = 'Visa val';
$TEXT['LOGGED_IN'] = 'Inloggad';
$TEXT['LOGIN'] = 'Logga In';
$TEXT['LONG'] = 'Br&#246;dtext';
$TEXT['LONG_TEXT'] = 'L&#229;ng text';
$TEXT['LOOP'] = 'Loop';
$TEXT['MAIN'] = 'Huvudmenyn';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MANAGE'] = 'Behandla';
$TEXT['MANAGE_GROUPS'] = 'Behandla grupper';
$TEXT['MANAGE_USERS'] = 'Behandla anv&#228;ndare';
$TEXT['MATCH'] = 'Matcha';
$TEXT['MATCHING'] = 'Matching';
$TEXT['MAX_EXCERPT'] = 'Max lines of excerpt';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Max poster per timme';
$TEXT['MEDIA_DIRECTORY'] = 'Media mapp';
$TEXT['MENU'] = 'Meny';
$TEXT['FILENAME'] = 'Filename';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Menyns titel';
$TEXT['MESSAGE'] = 'Meddelande';
$TEXT['MODIFY'] = '&#196;ndra';
$TEXT['MODIFY_CONTENT'] = 'Redigera inneh&#229;ll';
$TEXT['MODIFY_SETTINGS'] = 'Redigera inst&#228;llningar';
$TEXT['MODULE_ORDER'] = 'Module-order for searching';
$TEXT['MODULE_PERMISSIONS'] = 'Modultill&#229;telse';
$TEXT['MORE'] = 'Mer';
$TEXT['MOVE_DOWN'] = 'Flytta Ner';
$TEXT['MOVE_UP'] = 'Flytta Upp';
$TEXT['MULTIPLE_MENUS'] = 'Flera menyer';
$TEXT['MULTISELECT'] = 'Flerval';
$TEXT['NAME'] = 'Namn';
$TEXT['NEED_CURRENT_PASSWORD'] = 'confirm with current password';
$TEXT['NEED_TO_LOGIN'] = 'Logga in?';
$TEXT['NEW_PASSWORD'] = 'Nytt l&#246;senord';
$TEXT['NEW_WINDOW'] = 'Nytt f&#246;nster';
$TEXT['NEXT'] = 'N&#228;sta';
$TEXT['NEXT_PAGE'] = 'N&#228;sta sida';
$TEXT['NO'] = 'Nej';
$TEXT['NONE'] = 'Ingen';
$TEXT['NONE_FOUND'] = 'Inget hittades';
$TEXT['NOT_FOUND'] = 'Hittades inte';
$TEXT['NOT_INSTALLED'] = 'not installed';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'Inget resultat';
$TEXT['OF'] = 'Av';
$TEXT['ON'] = 'Den';
$TEXT['OPEN'] = 'Open';
$TEXT['OPTION'] = 'Val';
$TEXT['OTHERS'] = 'Andra';
$TEXT['OUT_OF'] = 'Utav';
$TEXT['OVERWRITE_EXISTING'] = 'Skriv &#246;ver nuvarande';
$TEXT['PAGE'] = 'Sida';
$TEXT['PAGES_DIRECTORY'] = 'Sidors mapp';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Sidors fil&#228;ndelse';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Sidors spr&#229;k';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Sidniv&#229; gr&#228;ns';
$TEXT['PAGE_SPACER'] = 'Mellanrum sida';
$TEXT['PAGE_TITLE'] = 'Sidans titel';
$TEXT['PAGE_TRASH'] = 'Papperskorg';
$TEXT['PARENT'] = 'Underliggande till';
$TEXT['PASSWORD'] = 'L&#246;senord';
$TEXT['PATH'] = 'S&#246;kv&#228;g';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP felrapport niv&#229;';
$TEXT['PLEASE_LOGIN'] = 'Please login';
$TEXT['PLEASE_SELECT'] = 'V&#228;nligen v&#228;lj';
$TEXT['POST'] = 'Nyhet';
$TEXT['POSTS_PER_PAGE'] = 'Inl&#228;gg per sida';
$TEXT['POST_FOOTER'] = 'Nyhet fot';
$TEXT['POST_HEADER'] = 'Nyhet huvud';
$TEXT['PREVIOUS'] = 'F&#246;reg&#229;ende';
$TEXT['PREVIOUS_PAGE'] = 'F&#246;reg&#229;ende sida';
$TEXT['PRIVATE'] = 'Privat';
$TEXT['PRIVATE_VIEWERS'] = 'Privata anv&#228;ndare';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'Offentligt';
$TEXT['PUBL_END_DATE'] = 'End date';
$TEXT['PUBL_START_DATE'] = 'Start date';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radioknapp flera';
$TEXT['READ'] = 'L&#228;s';
$TEXT['READ_MORE'] = 'L&#228;s mer';
$TEXT['REDIRECT_AFTER'] = 'Redirect after';
$TEXT['REGISTERED'] = 'Registrerad';
$TEXT['REGISTERED_VIEWERS'] = 'Registrerade anv&#228;ndare';
$TEXT['RELOAD'] = 'Ladda Om';
$TEXT['REMEMBER_ME'] = 'Kom ih&#229;g mig';
$TEXT['RENAME'] = 'D&#246;p om';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Obligatoriskt';
$TEXT['REQUIREMENT'] = 'Requirement';
$TEXT['RESET'] = '&#197;ngra';
$TEXT['RESIZE'] = 'Storleks&#228;ndra';
$TEXT['RESIZE_IMAGE_TO'] = 'Storleks&#228;ndra bilden till';
$TEXT['RESTORE'] = '&#197;terst&#228;ll';
$TEXT['RESTORE_DATABASE'] = '&#197;terst&#228;ll databas';
$TEXT['RESTORE_MEDIA'] = '&#197;terst&#228;ll media';
$TEXT['RESULTS'] = 'Resultat';
$TEXT['RESULTS_FOOTER'] = 'Resultat fot';
$TEXT['RESULTS_FOR'] = 'Resultat f&#246;r';
$TEXT['RESULTS_HEADER'] = 'Resultat huvud';
$TEXT['RESULTS_LOOP'] = 'Resultat loop';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Nytt l&#246;senord igen';
$TEXT['RETYPE_PASSWORD'] = 'Skriv l&#246;senordet igen';
$TEXT['SAME_WINDOW'] = 'Samma f&#246;nster';
$TEXT['SAVE'] = 'Spara';
$TEXT['SEARCH'] = 'S&#246;k';
$TEXT['SEARCHING'] = 'S&#246;ker';
$TEXT['SECTION'] = 'Sektion';
$TEXT['SECTION_BLOCKS'] = 'Sektioner block';
$TEXT['SEC_ANCHOR'] = 'Section-Anchor text';
$TEXT['SELECT_BOX'] = 'Valruta';
$TEXT['SEND_DETAILS'] = 'Skicka uppgifter';
$TEXT['SEPARATE'] = 'Separerat';
$TEXT['SEPERATOR'] = 'Separator';
$TEXT['SERVER_EMAIL'] = 'Server e-post';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Serveroperativsystem';
$TEXT['SESSION_IDENTIFIER'] = 'Sessionsidentifierare';
$TEXT['SETTINGS'] = 'Inst&#228;llningar';
$TEXT['SHORT'] = 'Ingress';
$TEXT['SHORT_TEXT'] = 'Kort text';
$TEXT['SHOW'] = 'Visa';
$TEXT['SHOW_ADVANCED'] = 'Visa avancerade val';
$TEXT['SIGNUP'] = 'Registrera';
$TEXT['SIZE'] = 'Storlek';
$TEXT['SMART_LOGIN'] = 'Smart inloggning';
$TEXT['START'] = 'Starta';
$TEXT['START_PUBLISHING'] = 'Starta publicering';
$TEXT['SUBJECT'] = '&#196;mne';
$TEXT['SUBMISSIONS'] = 'Inskickningar';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Poster som sparas i databasen';
$TEXT['SUBMISSION_ID'] = 'Inskickning ID';
$TEXT['SUBMITTED'] = 'Inskickat';
$TEXT['SUCCESS'] = 'Lyckades';
$TEXT['SYSTEM_DEFAULT'] = 'Systemets standard';
$TEXT['SYSTEM_PERMISSIONS'] = 'Systemtill&#229;telse';
$TEXT['TABLE_PREFIX'] = 'Tabell prefix';
$TEXT['TARGET'] = 'M&#229;lf&#246;nster';
$TEXT['TARGET_FOLDER'] = 'M&#229;lmapp';
$TEXT['TEMPLATE'] = 'Mall';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Till&#229;telse mallar';
$TEXT['TEXT'] = 'Text';
$TEXT['TEXTAREA'] = 'Textruta';
$TEXT['TEXTFIELD'] = 'Textrad';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['TIME'] = 'Tid';
$TEXT['TIMEZONE'] = 'Tidzon';
$TEXT['TIME_FORMAT'] = 'Tidsformat';
$TEXT['TIME_LIMIT'] = 'Max time to gather excerpts per module';
$TEXT['TITLE'] = 'Titel';
$TEXT['TO'] = 'Till';
$TEXT['TOP_FRAME'] = 'Top frame';
$TEXT['TRASH_EMPTIED'] = 'Papperskorgen t&#246;md';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Edit the CSS definitions in the textarea below.';
$TEXT['TYPE'] = 'Typ';
$TEXT['UNDER_CONSTRUCTION'] = 'Under uppbygnad.... Kommer snart';
$TEXT['UNINSTALL'] = 'Avinstallera';
$TEXT['UNKNOWN'] = 'Ok&#228;nd';
$TEXT['UNLIMITED'] = 'Obegr&#228;nsat';
$TEXT['UNZIP_FILE'] = 'Upload and unpack a zip archive';
$TEXT['UP'] = 'Upp';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Ladda upp filer';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'Anv&#228;ndare';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Verifikation';
$TEXT['VERSION'] = 'Version';
$TEXT['VIEW'] = 'Visa';
$TEXT['VIEW_DELETED_PAGES'] = 'Visa raderade sidor';
$TEXT['VIEW_DETAILS'] = 'Visa detaljer';
$TEXT['VISIBILITY'] = 'Synlighetsgrad';
$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] = 'Default From Mail';
$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] = 'Default Sender Name';
$TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'] = 'Please specify a default "FROM" address and "SENDER" name below. It is recommended to use a FROM address like: <strong>admin@yourdomain.com</strong>. Some mail provider (e.g. <em>mail.com</em>) may reject mails with a FROM: address like <em>name@mail.com</em> sent via a foreign relay to avoid spam.<br /><br />The default values are only used if no other values are specified by WBCE CMS.';
$TEXT['WBMAILER_FUNCTION'] = 'Mail Routine';
$TEXT['WBMAILER_NOTICE'] = '<strong>SMTP Mailer Settings:</strong><br />The settings below are only required if you want to send mails via <abbr title="Simple mail transfer protocol">SMTP</abbr>. If you do not know your SMTP host or you are not sure about the required settings, simply stay with the default mail routine: PHP MAIL.';
$TEXT['WBMAILER_PHP'] = 'PHP MAIL';
$TEXT['WBMAILER_SMTP'] = 'SMTP';
$TEXT['WBMAILER_SMTP_AUTH'] = 'SMTP Authentification';
$TEXT['WBMAILER_SMTP_AUTH_NOTICE'] = 'only activate if your SMTP host requires authentification';
$TEXT['WBMAILER_SMTP_HOST'] = 'SMTP Host';
$TEXT['WBMAILER_SMTP_PASSWORD'] = 'SMTP Password';
$TEXT['WBMAILER_SMTP_USERNAME'] = 'SMTP Loginname';
$TEXT['WEBSITE'] = 'Websida';
$TEXT['WEBSITE_DESCRIPTION'] = 'Websitens beskrivning';
$TEXT['WEBSITE_FOOTER'] = 'Websitens fot';
$TEXT['WEBSITE_HEADER'] = 'Websitens huvud';
$TEXT['WEBSITE_KEYWORDS'] = 'Websitens nyckelord';
$TEXT['WEBSITE_TITLE'] = 'Websitens titel';
$TEXT['WELCOME_BACK'] = 'V&#228;lkommen tillbaka';
$TEXT['WIDTH'] = 'Bredd';
$TEXT['WINDOW'] = 'F&#246;nster';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'Skrivr&#228;ttigheter filer';
$TEXT['WRITE'] = 'Skriv';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG redigerare';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG stil';
$TEXT['YES'] = 'Ja';
$TEXT['ADVANCED_SEARCH'] = 'Advanced Search';
$TEXT['QUICK_SEARCH_STRG_F'] = 'Press <b>Strg + f</b> for Quick search or use';
$TEXT['THEME_COPY_CURRENT'] = 'Copy backend theme.';
$TEXT['THEME_NEW_NAME'] = 'Name of the new Theme';
$TEXT['THEME_CURRENT'] = 'current active theme';
$TEXT['THEME_START_COPY'] = 'copy';
$TEXT['THEME_IMPORT_HTT'] = 'Import additional templates';
$TEXT['THEME_SELECT_HTT'] = 'select templates';
$TEXT['THEME_NOMORE_HTT'] = 'no more available';
$TEXT['THEME_START_IMPORT'] = 'import';
// PHP error levels (since WBCE 1.3.0)
$TEXT['ERR_USE_SYSTEM_DEFAULT'] = 'Use system default (php.ini)';
$TEXT['ERR_HIDE_ERRORS_NOTICES'] = 'Hide all errors and notices (WWW)';
$TEXT['ERR_SHOW_ERRORS_NOTICES'] = 'Show all errors and notices (development)';
$TEXT['ERR_SHOW_ERRORS_HIDE_NOTICES'] = 'Show errors, hide notices';


$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On requirements not met';
$HEADING['ADD_CHILD_PAGE'] = 'Add child page';
$HEADING['ADD_GROUP'] = 'Skapa ny grupp';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Rubrik';
$HEADING['ADD_PAGE'] = 'Skapa ny sida';
$HEADING['ADD_USER'] = 'Skapa ny anv&#228;ndare';
$HEADING['ADMINISTRATION_TOOLS'] = 'Administrationsverktyg';
$HEADING['BROWSE_MEDIA'] = 'Mediabibliotek';
$HEADING['CREATE_FOLDER'] = 'Skapa ny mapp';
$HEADING['DEFAULT_SETTINGS'] = 'Standardinst&#228;llningar';
$HEADING['DELETED_PAGES'] = 'Raderade sidor';
$HEADING['FILESYSTEM_SETTINGS'] = 'Inst&#228;llningar f&#246;r Filsystem';
$HEADING['GENERAL_SETTINGS'] = 'Generella inst&#228;llningar';
$HEADING['INSTALL_LANGUAGE'] = 'Installera spr&#229;k';
$HEADING['INSTALL_MODULE'] = 'Installera modul';
$HEADING['INSTALL_TEMPLATE'] = 'Installera mall';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Execute module files manually';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Spr&#229;kdetaljer';
$HEADING['MANAGE_SECTIONS'] = 'Redigera sektioner';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = '&#196;ndra avancerade inst&#228;llningar f&#246;r sidan';
$HEADING['MODIFY_DELETE_GROUP'] = '&#196;ndra/radera grupp';
$HEADING['MODIFY_DELETE_PAGE'] = '&#196;ndra/radera sida';
$HEADING['MODIFY_DELETE_USER'] = '&#196;ndra/radera anv&#228;ndare';
$HEADING['MODIFY_GROUP'] = '&#196;ndra grupp';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = '&#196;ndra F&#246;rstasidan';
$HEADING['MODIFY_PAGE'] = '&#196;ndra sida';
$HEADING['MODIFY_PAGE_SETTINGS'] = '&#196;ndra sidans inst&#228;llningar';
$HEADING['MODIFY_USER'] = '&#196;ndra anv&#228;ndare';
$HEADING['MODULE_DETAILS'] = 'Moduldetaljer';
$HEADING['MY_EMAIL'] = 'Min e-post';
$HEADING['MY_PASSWORD'] = 'Mitt l&#246;senord';
$HEADING['MY_SETTINGS'] = 'Mina uppgifter';
$HEADING['SEARCH_SETTINGS'] = 'S&#246;kinst&#228;llningar';
$HEADING['SERVER_SETTINGS'] = 'Server Inst&#228;llningar';
$HEADING['TEMPLATE_DETAILS'] = 'Malldetaljer';
$HEADING['UNINSTALL_LANGUAGE'] = 'Avinstallera spr&#229;k';
$HEADING['UNINSTALL_MODULE'] = 'Avinstallera modul';
$HEADING['UNINSTALL_TEMPLATE'] = 'Avinstallera mall';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Ladda Upp fil(er)';
$HEADING['WBMAILER_SETTINGS'] = 'Mailer Settings';


$MESSAGE['ADDON_ERROR_RELOAD'] = 'Error while updating the Add-On information.';
$MESSAGE['ADDON_LANGUAGES_RELOADED'] = 'Languages reloaded successfully';
$MESSAGE['ADDON_MANUAL_FTP_LANGUAGE'] = '<strong>ATTENTION!</strong> For safety reasons uploading languages files in the folder/languages/ only by FTP and use the Upgrade function for registering or updating.';
$MESSAGE['ADDON_MANUAL_FTP_WARNING'] = 'Warning: Existing module database entries will get lost. ';
$MESSAGE['ADDON_MANUAL_INSTALLATION'] = 'When modules are uploaded via FTP (not recommended), the module installation functions <code>install</code>, <code>upgrade</code> or <code>uninstall</code> will not be executed automatically. Those modules may not work correct or do not uninstall properly.<br /><br />You can execute the module functions manually for modules uploaded via FTP below.';
$MESSAGE['ADDON_MANUAL_INSTALLATION_WARNING'] = 'Warning: Existing module database entries will get lost. Only use this option if you experience problems with modules uploaded via FTP.';
$MESSAGE['ADDON_MANUAL_RELOAD_WARNING'] = 'Warning: Existing module database entries will get lost. ';
$MESSAGE['ADDON_MODULES_RELOADED'] = 'Modules reloaded successfully';
$MESSAGE['ADDON_OVERWRITE_NEWER_FILES'] = 'Overwrite newer Files';
$MESSAGE['ADDON_PRECHECK_FAILED'] = 'Add-on installation failed. Your system does not fulfill the requirements of this Add-on. To make this Add-on working on your system, please fix the issues summarized below.';
$MESSAGE['ADDON_RELOAD'] = 'Update database with information from Add-on files (e.g. after FTP upload).';
$MESSAGE['ADDON_TEMPLATES_RELOADED'] = 'Templates reloaded successfully';
$MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'] = 'Du har inte till&#229;telse att vara h&#228;r';
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'L&#246;senordet kan bara &#228;ndras max en g&#229;ng per timme';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Kunde inte skicka l&#246;senordet, v&#228;nligen kontakta administratat&#246;ren';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'E-postadressen som du skrev in kan inte hittas i v&#229;r databas';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Skriv din e-postadress';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sorry, no active content to display';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Tyv&#228;rr, du har inte till&#229;telse att titta p&#229; denna sida';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Redan installerat';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Kan inte skriva i m&#229;lmappen';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Kan inte avinstallera';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Kan inte avinstallera: filen anv&#228;nds just nu';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> could not be uninstalled, because it is still in use on {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'this page;these pages';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default template!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Kan inte packa upp filen';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Kan inte ladda upp fil';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Fel vid &#246;ppnande av fil.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Observera att filen du laddar upp m&#229;ste vara i formatet:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Observera att filen du laddar upp m&#229;ste vara i ett av f&#246;ljande format:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'G&#229; tillbaka och fyll i alla f&#228;lt';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Installerat';
$MESSAGE['GENERIC_INVALID'] = 'Filen du laddade upp &#228;r ogilltig';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Invalid WBCE CMS installation file. Please check the *.zip format.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Invalid WBCE CMS language file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WBCE CMS module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WBCE CMS template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Inte installerat';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'V&#228;nligen ha t&#229;lamod, det h&#228;r kan ta en stund.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'V&#228;nligen kom tillbaka snart...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Security offense!! data storing was refused!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Avinstallerat';
$MESSAGE['GENERIC_UPGRADED'] = 'Uppgradering genomf&#246;rd';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Website Under Construction';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GROUP_HAS_MEMBERS'] = 'This group still has members.';
$MESSAGE['GROUPS_ADDED'] = 'Gruppen lades till';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Vill du verkligen radera gruppen med alla dess anv&#228;ndare?';
$MESSAGE['GROUPS_DELETED'] = 'Gruppen raderades';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Gruppen m&#229;ste ha ett namn';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Gruppnamnet finns redan';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'Ingen grupp hittades';
$MESSAGE['GROUPS_SAVED'] = 'Gruppen sparades';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Skriv ditt l&#246;senord';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'L&#246;senordet &#228;r f&#246;r l&#229;ngt';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'L&#246;senordet &#228;r f&#246;r kort';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'Du skrev ingen fil&#228;ndelse';
$MESSAGE['MEDIA_BLANK_NAME'] = 'Du skrev inget nytt namn';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Kan inte radera mappen';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Kan inte radera filen';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Namn&#228;ndring utf&#246;rdes INTE';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Vill du verkligen radera f&#228;ljande fil/mapp?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Mappen raderades';
$MESSAGE['MEDIA_DELETED_FILE'] = 'Filen raderades';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Mappen finns inte';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Kan inte inkludera ../ i mappens namn';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'Det finns redan en mapp med samma namn';
$MESSAGE['MEDIA_DIR_MADE'] = 'Mappen skapades';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Kunde inte skapa mapp';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'Det finns redan en fil med samma namn';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'Filen hittades inte';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Kan inte inkludera ../ i namnet';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Kan inte anv&#228;nda index.php som Namn';
$MESSAGE['MEDIA_NO_FILE_UPLOADED'] = 'No file was received';
$MESSAGE['MEDIA_NONE_FOUND'] = 'Ingen media hittades i mappen';
$MESSAGE['MEDIA_RENAMED'] = 'Namn&#228;ndring utf&#246;rdes';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' filen laddades upp';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Kan inte ha ../ i mappens m&#229;l';
$MESSAGE['MEDIA_UPLOADED'] = ' filerna laddades upp';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Tyv&#228;rr, detta formul&#228;r har skickats f&#246;r m&#229;nga g&#229;nger inom denna timme. F&#246;rs&#246;k igen om ett tag.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'Verifieringsnumret (ocks&#229; k&#228;nt som Captcha) som du angav &#228;r felaktigt. Om du har problem med att l&#228;sa ut Captcha, v&#228;nligen s&#228;nd email till: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'Du m&#229;ste fylla i f&#246;ljande f&#228;lt';
$MESSAGE['PAGES_ADDED'] = 'Sidan lades till';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Sidans huvud lades till';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Skriv en titel p&#229; menyn';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Skriv en titel p&#229; sidan';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Fel vid skapande av fil (otillr&#228;cklig till&#229;telse)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Fel vid radering av fil (otillr&#228;cklig till&#229;telse)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Fel vid omordning av sidorna';
$MESSAGE['PAGES_DELETED'] = 'Sidan raderades';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Vill du verkligen radera sidan med alla dess undersidor?';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'Du har inte til&#229;telse att redigera denna sida';
$MESSAGE['PAGES_INTRO_LINK'] = 'Klicka h&#228;r f&#246;r att redigera F&#246;rstasidan';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Kan inte skriva till filen /pages/intro.php (otillr&#228;klig till&#229;telse)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'F&#246;rstasidan sparades';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Senast redigerad av';
$MESSAGE['PAGES_NOT_FOUND'] = 'Sidan hittades inte';
$MESSAGE['PAGES_NOT_SAVED'] = 'Fel n&#228;r sidan sparades';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'En sida med samma eller liknande titel finns redan';
$MESSAGE['PAGES_REORDERED'] = 'Sidorna omordnades';
$MESSAGE['PAGES_RESTORED'] = 'Sidan &#229;terskapades';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Tillbaka till Sidor';
$MESSAGE['PAGES_SAVED'] = 'Sidan sparades';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Sidans inst&#228;llningar sparades';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Sektionens inst&#228;llningar sparades';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'Det (nuvarande) l&#246;senordet du skrev &#228;r inte r&#228;tt';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Uppgifterna sparades';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'E-postadressen uppdaterades';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Invalid password chars used';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'L&#246;senordet &#228;ndrades';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'The change of the record has missed.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'The changed record was updated successfully.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Adding a new record has missed.';
$MESSAGE['RECORD_NEW_SAVED'] = 'New record was added successfully.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Observera: Om du klickar p&#229; denna knapp s&#229; f&#246;rsvinner all ny information som inte sparats';
$MESSAGE['SETTINGS_SAVED'] = 'Inst&#228;llningarna sparades';
$MESSAGE['SETTINGS_UNABLE_OPEN_CONFIG'] = 'Kunde inte &#246;ppna konfigurationsfilen';
$MESSAGE['SETTINGS_UNABLE_WRITE_CONFIG'] = 'Kan inte skriva till konfigurationsfilen';
$MESSAGE['SETTINGS_WORLD_WRITEABLE_WARNING'] = 'Observera: detta rekomenderas endast f&#246;r tillf&#228;llig pr&#246;vning av sidorna';
$MESSAGE['SIGNUP2_ADMIN_INFO'] = '
A new user was registered.

Loginname: {LOGIN_NAME}
UserId: {LOGIN_ID}
E-Mail: {LOGIN_EMAIL}
IP-Adress: {LOGIN_IP}
Registration date: {SIGNUP_DATE}
----------------------------------------
This message was automatic generated!&#10;&#10;';
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
This message was automatic generated&#10;&#10;';
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
This message was automatic generated&#33;&#10;';
$MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'] = 'Your login details...';
$MESSAGE['SIGNUP_NO_EMAIL'] = 'Du m&#229;ste skriva en e-postadress';
$MESSAGE['START_CURRENT_USER'] = 'Du &#228;r inloggad som:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'VARNING, installationsmappen finns fortfarande kvar!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'V&#228;lkommen till administrationen av WBCE CMS';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Observera: f&#246;r att &#228;ndra Mall, m&#229;ste du g&#229; till Sektionen Inst&#228;llningar';
$MESSAGE['UPLOAD_ERR_OK'] = 'File were successful uploaded';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'The uploaded file was only partially uploaded';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'No file was uploaded';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Missing a temporary folder';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Failed to write file to disk';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'File upload stopped by extension';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Unknown upload error';
$MESSAGE['USERS_ADDED'] = 'Anv&#228;ndaren lades till';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Function rejected, You can not delete yourself!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Observera: Dessa f&#228;lt ska du bara skriva i om du vill &#196;NDRA L&#246;senordet';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Vill du verkligen radera anv&#228;ndaren?';
$MESSAGE['USERS_DELETED'] = 'Anv&#228;ndaren Raderades';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'E-post adressen du skrev finns redan i v&#229;r Databas';
$MESSAGE['USERS_INVALID_EMAIL'] = 'E-post adressen &#228;r felaktig';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for Loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'Ingen grupp valdes';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'L&#246;senorden du skrev var inte lika';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'L&#246;senordet &#228;r for kort';
$MESSAGE['USERS_SAVED'] = 'Anv&#228;ndaren sparades';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';
$MESSAGE['THEME_COPY_CURRENT'] = 'Copy the current active theme and save it with a new name.';
$MESSAGE['THEME_ALREADY_EXISTS'] = 'This new theme descriptor already exists.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Invalid descriptor for the new theme given!';
$MESSAGE['THEME_DESTINATION_READONLY'] = 'No rights to create new theme directory!';
$MESSAGE['THEME_IMPORT_HTT'] = 'Import additional templates into the current active theme.<br />Use these templates to overwrite the corresponding default template.';


$OVERVIEW['ADMINTOOLS'] = 'Access the WBCE CMS administration tools...';
$OVERVIEW['GROUPS'] = 'Behandla anv&#228;ndargrupper och deras system&#229;tkomst...';
$OVERVIEW['HELP'] = 'Hitta svar p&#229; dina fr&#229;gor (p&#229; engelska)...';
$OVERVIEW['LANGUAGES'] = 'Behandla WBCE CMS spr&#229;k...';
$OVERVIEW['MEDIA'] = 'Redigera inneh&#229;ll i mediabiblioteket...';
$OVERVIEW['MODULES'] = 'Behandla WBCE CMS moduler...';
$OVERVIEW['PAGES'] = 'Redigera dina sidor...';
$OVERVIEW['PREFERENCES'] = '&#196;ndra inst&#228;llningar som e-postadress, l&#246;senord, etc... ';
$OVERVIEW['SETTINGS'] = '&#196;ndra inst&#228;llningar f&#246;r WBCE CMS...';
$OVERVIEW['START'] = 'Administration &#246;versyn';
$OVERVIEW['TEMPLATES'] = '&#196;ndra utseendet med mallar...';
$OVERVIEW['USERS'] = 'Behandla anv&#228;ndare som kan logga in till WBCE CMS...';
$OVERVIEW['VIEW'] = 'Titta p&#229; dina sidor i ett nytt f&#246;nster...';
