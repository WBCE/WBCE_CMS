<?php
/**
 *
 * @category        backend
 * @package         language
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: EN.php 1636 2012-03-09 14:30:29Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/languages/EN.php $
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
$language_code = 'EN';
$language_name = 'English';
$language_version = '2.8';
$language_platform = '2.8.x';
$language_author = 'Ryan Djurovich, Christian Sommer';
$language_license = 'GNU General Public License';

/* MENU */
$MENU['ACCESS'] = 'Access';
$MENU['ADDON'] = 'Add-on';
$MENU['ADDONS'] = 'Add-ons';
$MENU['ADMINTOOLS'] = 'Admin-Tools';
$MENU['BREADCRUMB'] = 'You are here: ';
$MENU['FORGOT'] = 'Retrieve Login Details';
$MENU['GROUP'] = 'Group';
$MENU['GROUPS'] = 'Groups';
$MENU['HELP'] = 'Help';
$MENU['LANGUAGES'] = 'Languages';
$MENU['LOGIN'] = 'Login';
$MENU['LOGOUT'] = 'Log-out';
$MENU['MEDIA'] = 'Media';
$MENU['MODULES'] = 'Modules';
$MENU['PAGES'] = 'Pages';
$MENU['PREFERENCES'] = 'Preferences';
$MENU['SETTINGS'] = 'Settings';
$MENU['START'] = 'Start';
$MENU['TEMPLATES'] = 'Templates';
$MENU['USERS'] = 'Users';
$MENU['VIEW'] = 'View';

/* TEXT */
$TEXT['ACCOUNT_SIGNUP'] = 'Account Sign-Up';
$TEXT['ACTIONS'] = 'Actions';
$TEXT['ACTIVE'] = 'Active';
$TEXT['ADD'] = 'Add';
$TEXT['ADDON'] = 'Add-On';
$TEXT['ADD_SECTION'] = 'Add Section';
$TEXT['ADMIN'] = 'Admin';
$TEXT['ADMINISTRATION'] = 'Administration';
$TEXT['ADMINISTRATION_TOOL'] = 'Administration tool';
$TEXT['ADMINISTRATOR'] = 'Administrator';
$TEXT['ADMINISTRATORS'] = 'Administrators';
$TEXT['ADVANCED'] = 'Advanced';
$TEXT['ALLOWED_FILETYPES_ON_UPLOAD'] = 'Allowed filetypes on upload';
$TEXT['ALLOWED_VIEWERS'] = 'Allowed Viewers';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Allow Multiple Selections';
$TEXT['ALL_WORDS'] = 'All Words';
$TEXT['ANCHOR'] = 'Anchor';
$TEXT['ANONYMOUS'] = 'Anonymous';
$TEXT['ANY_WORDS'] = 'Any Words';
$TEXT['APP_NAME'] = 'Application Name';
$TEXT['ARE_YOU_SURE'] = 'Are you sure?';
$TEXT['AUTHOR'] = 'Author';
$TEXT['BACK'] = 'Back';
$TEXT['BACKUP'] = 'Backup';
$TEXT['BACKUP_ALL_TABLES'] = 'Backup all tables in database';
$TEXT['BACKUP_DATABASE'] = 'Backup Database';
$TEXT['BACKUP_MEDIA'] = 'Backup Media';
$TEXT['BACKUP_WB_SPECIFIC'] = 'Backup only WB-specific tables';
$TEXT['BASIC'] = 'Basic';
$TEXT['BLOCK'] = 'Block';
$TEXT['CALENDAR'] = 'Calendar';
$TEXT['CANCEL'] = 'Cancel';
$TEXT['CAN_DELETE_HIMSELF'] = 'Can delete himself';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha Verification';
$TEXT['CAP_EDIT_CSS'] = 'Edit CSS';
$TEXT['CHANGE'] = 'Change';
$TEXT['CHANGES'] = 'Changes';
$TEXT['CHANGE_SETTINGS'] = 'Change Settings';
$TEXT['CHARSET'] = 'Charset';
$TEXT['CHECKBOX_GROUP'] = 'Checkbox Group';
$TEXT['CLOSE'] = 'Close';
$TEXT['CODE'] = 'Code';
$TEXT['CODE_SNIPPET'] = 'Code-snippet';
$TEXT['COLLAPSE'] = 'Collapse';
$TEXT['COMMENT'] = 'Comment';
$TEXT['COMMENTING'] = 'Commenting';
$TEXT['COMMENTS'] = 'Comments';
$TEXT['CREATE_FOLDER'] = 'Create Folder';
$TEXT['CURRENT'] = 'Current';
$TEXT['CURRENT_FOLDER'] = 'Current Folder';
$TEXT['CURRENT_PAGE'] = 'Current Page';
$TEXT['CURRENT_PASSWORD'] = 'Current Password';
$TEXT['CUSTOM'] = 'Custom';
$TEXT['DATABASE'] = 'Database';
$TEXT['DATE'] = 'Date';
$TEXT['DATE_FORMAT'] = 'Date Format';
$TEXT['DEFAULT'] = 'Default';
$TEXT['DEFAULT_CHARSET'] = 'Default Charset';
$TEXT['DEFAULT_TEXT'] = 'Default Text';
$TEXT['DELETE'] = 'Delete';
$TEXT['DELETED'] = 'Deleted';
$TEXT['DELETE_DATE'] = 'Delete date';
$TEXT['DELETE_ZIP'] = 'Delete zip archive after unpacking';
$TEXT['DESCRIPTION'] = 'Description';
$TEXT['DESIGNED_FOR'] = 'Designed For';
$TEXT['DIRECTORIES'] = 'Directories';
$TEXT['DIRECTORY_MODE'] = 'Directory Mode';
$TEXT['DISABLED'] = 'Disabled';
$TEXT['DISPLAY_NAME'] = 'Display Name';
$TEXT['EMAIL'] = 'Email';
$TEXT['EMAIL_ADDRESS'] = 'Email Address';
$TEXT['EMPTY_TRASH'] = 'Empty Trash';
$TEXT['ENABLED'] = 'Enabled';
$TEXT['END'] = 'End';
$TEXT['ERROR'] = 'Error';
$TEXT['EXACT_MATCH'] = 'Exact Match';
$TEXT['EXECUTE'] = 'Execute';
$TEXT['EXPAND'] = 'Expand';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['FIELD'] = 'Field';
$TEXT['FILE'] = 'File';
$TEXT['FILES'] = 'Files';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Filesystem Permissions';
$TEXT['FILE_MODE'] = 'File Mode';
$TEXT['FINISH_PUBLISHING'] = 'Finish Publishing';
$TEXT['FOLDER'] = 'Folder';
$TEXT['FOLDERS'] = 'Folders';
$TEXT['FOOTER'] = 'Footer';
$TEXT['FORGOTTEN_DETAILS'] = 'Forgotten your details?';
$TEXT['FORGOT_DETAILS'] = 'Forgot Details?';
$TEXT['FROM'] = 'From';
$TEXT['FRONTEND'] = 'Front-end';
$TEXT['FULL_NAME'] = 'Full Name';
$TEXT['FUNCTION'] = 'Function';
$TEXT['GROUP'] = 'Group';
$TEXT['HEADER'] = 'Header';
$TEXT['HEADING'] = 'Heading';
$TEXT['HEADING_CSS_FILE'] = 'Actual module file: ';
$TEXT['HEIGHT'] = 'Height';
$TEXT['HIDDEN'] = 'Hidden';
$TEXT['HIDE'] = 'Hide';
$TEXT['HIDE_ADVANCED'] = 'Hide Advanced Options';
$TEXT['HOME'] = 'Home';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Homepage Redirection';
$TEXT['HOME_FOLDER'] = 'Personal Folder';
$TEXT['HOME_FOLDERS'] = 'Personal Folders';
$TEXT['HOST'] = 'Host';
$TEXT['ICON'] = 'Icon';
$TEXT['IMAGE'] = 'Image';
$TEXT['INLINE'] = 'In-line';
$TEXT['INSTALL'] = 'Install';
$TEXT['INSTALLATION'] = 'Installation';
$TEXT['INSTALLATION_PATH'] = 'Installation Path';
$TEXT['INSTALLATION_URL'] = 'Installation URL';
$TEXT['INSTALLED'] = 'installed';
$TEXT['INTRO'] = 'Intro';
$TEXT['INTRO_PAGE'] = 'Intro Page';
$TEXT['INVALID_SIGNS'] = 'must begin with a letter or has invalid signs';
$TEXT['KEYWORDS'] = 'Keywords';
$TEXT['LANGUAGE'] = 'Language';
$TEXT['LAST_UPDATED_BY'] = 'Last Updated By';
$TEXT['LENGTH'] = 'Length';
$TEXT['LEVEL'] = 'Level';
$TEXT['LINK'] = 'Link';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix based';
$TEXT['LIST_OPTIONS'] = 'List Options';
$TEXT['LOGGED_IN'] = 'Logged-In';
$TEXT['LOGIN'] = 'Login';
$TEXT['LONG'] = 'Long';
$TEXT['LONG_TEXT'] = 'Long Text';
$TEXT['LOOP'] = 'Loop';
$TEXT['MAIN'] = 'Main';
$TEXT['MAINTENANCE_ON'] = 'Maintenance on';
$TEXT['MAINTENANCE_OFF'] = 'Maintenance off';
$TEXT['MANAGE'] = 'Manage';
$TEXT['MANAGE_GROUPS'] = 'Manage Groups';
$TEXT['MANAGE_USERS'] = 'Manage Users';
$TEXT['MATCH'] = 'Match';
$TEXT['MATCHING'] = 'Matching';
$TEXT['MAX_EXCERPT'] = 'Max lines of excerpt';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Max. Submissions Per Hour';
$TEXT['MEDIA_DIRECTORY'] = 'Media Directory';
$TEXT['MENU'] = 'Menu';
$TEXT['MENU_ICON_0'] = 'Menu-Icon normal';
$TEXT['MENU_ICON_1'] = 'Menu-Icon hover';
$TEXT['MENU_TITLE'] = 'Menu Title';
$TEXT['MESSAGE'] = 'Message';
$TEXT['MODIFY'] = 'Modify';
$TEXT['MODIFY_CONTENT'] = 'Modify Content';
$TEXT['MODIFY_SETTINGS'] = 'Modify Settings';
$TEXT['MODULE_ORDER'] = 'Module-order for searching';
$TEXT['MODULE_PERMISSIONS'] = 'Module Permissions';
$TEXT['MORE'] = 'More';
$TEXT['MOVE_DOWN'] = 'Move Down';
$TEXT['MOVE_UP'] = 'Move Up';
$TEXT['MULTIPLE_MENUS'] = 'Multiple Menus';
$TEXT['MULTISELECT'] = 'Multi-select';
$TEXT['NAME'] = 'Name';
$TEXT['NEED_CURRENT_PASSWORD'] = 'confirm with current password';
$TEXT['NEED_TO_LOGIN'] = 'Need to log-in?';
$TEXT['NEW_PASSWORD'] = 'New Password';
$TEXT['NEW_WINDOW'] = 'New Window';
$TEXT['NEXT'] = 'Next';
$TEXT['NEXT_PAGE'] = 'Next Page';
$TEXT['NO'] = 'No';
$TEXT['NONE'] = 'None';
$TEXT['NONE_FOUND'] = 'None Found';
$TEXT['NOT_FOUND'] = 'Not Found';
$TEXT['NOT_INSTALLED'] = 'not installed';
$TEXT['NO_IMAGE_SELECTED'] = 'no image selected';
$TEXT['NO_RESULTS'] = 'No Results';
$TEXT['OF'] = 'Of';
$TEXT['ON'] = 'On';
$TEXT['OPEN'] = 'Open';
$TEXT['OPTION'] = 'Option';
$TEXT['OTHERS'] = 'Others';
$TEXT['OUT_OF'] = 'Out Of';
$TEXT['OVERWRITE_EXISTING'] = 'Overwrite existing';
$TEXT['PAGE'] = 'Page';
$TEXT['PAGES_DIRECTORY'] = 'Pages Directory';
$TEXT['PAGES_PERMISSION'] = 'Pages Permission';
$TEXT['PAGES_PERMISSIONS'] = 'Pages Permissions';
$TEXT['PAGE_EXTENSION'] = 'Page Extension';
$TEXT['PAGE_ICON'] = 'Page Image';
$TEXT['PAGE_ICON_DIR'] = 'Path pages/menu images';
$TEXT['PAGE_LANGUAGES'] = 'Page Languages';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Page Level Limit';
$TEXT['PAGE_SPACER'] = 'Page Spacer';
$TEXT['PAGE_TITLE'] = 'Page Title';
$TEXT['PAGE_TRASH'] = 'Page Trash';
$TEXT['PARENT'] = 'Parent';
$TEXT['PASSWORD'] = 'Password';
$TEXT['PATH'] = 'Path';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP Error Reporting Level';
$TEXT['PLEASE_LOGIN'] = 'Please login';
$TEXT['PLEASE_SELECT'] = 'Please select';
$TEXT['POST'] = 'Post';
$TEXT['POSTS_PER_PAGE'] = 'Posts Per Page';
$TEXT['POST_FOOTER'] = 'Post Footer';
$TEXT['POST_HEADER'] = 'Post Header';
$TEXT['PREVIOUS'] = 'Previous';
$TEXT['PREVIOUS_PAGE'] = 'Previous Page';
$TEXT['PRIVATE'] = 'Private';
$TEXT['PRIVATE_VIEWERS'] = 'Private Viewers';
$TEXT['PROFILES_EDIT'] = 'Change the profile';
$TEXT['PUBLIC'] = 'Public';
$TEXT['PUBL_END_DATE'] = 'End date';
$TEXT['PUBL_START_DATE'] = 'Start date';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radio Button Group';
$TEXT['READ'] = 'Read';
$TEXT['READ_MORE'] = 'Read More';
$TEXT['REDIRECT_AFTER'] = 'Redirect after';
$TEXT['REGISTERED'] = 'Registered';
$TEXT['REGISTERED_VIEWERS'] = 'Registered Viewers';
$TEXT['RELOAD'] = 'Reload';
$TEXT['REMEMBER_ME'] = 'Remember Me';
$TEXT['RENAME'] = 'Rename';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'No upload for this filetypes';
$TEXT['REQUIRED'] = 'Required';
$TEXT['REQUIREMENT'] = 'Requirement';
$TEXT['RESET'] = 'Reset';
$TEXT['RESIZE'] = 'Re-size';
$TEXT['RESIZE_IMAGE_TO'] = 'Resize Image To';
$TEXT['RESTORE'] = 'Restore';
$TEXT['RESTORE_DATABASE'] = 'Restore Database';
$TEXT['RESTORE_MEDIA'] = 'Restore Media';
$TEXT['RESULTS'] = 'Results';
$TEXT['RESULTS_FOOTER'] = 'Results Footer';
$TEXT['RESULTS_FOR'] = 'Results For';
$TEXT['RESULTS_HEADER'] = 'Results Header';
$TEXT['RESULTS_LOOP'] = 'Results Loop';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Re-type New Password';
$TEXT['RETYPE_PASSWORD'] = 'Re-type Password';
$TEXT['SAME_WINDOW'] = 'Same Window';
$TEXT['SAVE'] = 'Save';
$TEXT['SEARCH'] = 'Search';
$TEXT['SEARCHING'] = 'Searching';
$TEXT['SECTION'] = 'Section';
$TEXT['SECTION_BLOCKS'] = 'Section Blocks';
$TEXT['SEC_ANCHOR'] = 'Section-Anchor text';
$TEXT['SELECT_BOX'] = 'Select Box';
$TEXT['SEND_DETAILS'] = 'Send Details';
$TEXT['SEPARATE'] = 'Separate';
$TEXT['SEPERATOR'] = 'Separator';
$TEXT['SERVER_EMAIL'] = 'Server Email';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Server Operating System';
$TEXT['SESSION_IDENTIFIER'] = 'Session Identifier';
$TEXT['SETTINGS'] = 'Settings';
$TEXT['SHORT'] = 'Short';
$TEXT['SHORT_TEXT'] = 'Short Text';
$TEXT['SHOW'] = 'Show';
$TEXT['SHOW_ADVANCED'] = 'Show Advanced Options';
$TEXT['SIGNUP'] = 'Sign-up';
$TEXT['SIZE'] = 'Size';
$TEXT['SMART_LOGIN'] = 'Smart Login';
$TEXT['START'] = 'Start';
$TEXT['START_PUBLISHING'] = 'Start Publishing';
$TEXT['SUBJECT'] = 'Subject';
$TEXT['SUBMISSIONS'] = 'Submissions';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Submissions Stored In Database';
$TEXT['SUBMISSION_ID'] = 'Submission ID';
$TEXT['SUBMITTED'] = 'Submitted';
$TEXT['SUCCESS'] = 'Success';
$TEXT['SYSTEM_DEFAULT'] = 'System Default';
$TEXT['SYSTEM_PERMISSIONS'] = 'System Permissions';
$TEXT['TABLE_PREFIX'] = 'Table Prefix';
$TEXT['TARGET'] = 'Target';
$TEXT['TARGET_FOLDER'] = 'Target folder';
$TEXT['TEMPLATE'] = 'Template';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Template Permissions';
$TEXT['TEXT'] = 'Text';
$TEXT['TEXTAREA'] = 'Textarea';
$TEXT['TEXTFIELD'] = 'Textfield';
$TEXT['THEME'] = 'Backend-Theme';
$TEXT['TIME'] = 'Time';
$TEXT['TIMEZONE'] = 'Timezone';
$TEXT['TIME_FORMAT'] = 'Time Format';
$TEXT['TIME_LIMIT'] = 'Max time to gather excerpts per module';
$TEXT['TITLE'] = 'Title';
$TEXT['TO'] = 'To';
$TEXT['TOP_FRAME'] = 'Top Frame';
$TEXT['TRASH_EMPTIED'] = 'Trash Emptied';
$TEXT['TXT_EDIT_CSS_FILE'] = 'Edit the CSS definitions in the textarea below.';
$TEXT['TYPE'] = 'Type';
$TEXT['UNDER_CONSTRUCTION'] = 'Under Construction';
$TEXT['UNINSTALL'] = 'Uninstall';
$TEXT['UNKNOWN'] = 'Unknown';
$TEXT['UNLIMITED'] = 'Unlimited';
$TEXT['UNZIP_FILE'] = 'Upload and unpack a zip archive';
$TEXT['UP'] = 'Up';
$TEXT['UPGRADE'] = 'Upgrade';
$TEXT['UPLOAD_FILES'] = 'Upload File(s)';
$TEXT['URL'] = 'URL';
$TEXT['USER'] = 'User';
$TEXT['USERNAME'] = 'Loginname';
$TEXT['USERS_ACTIVE'] = 'User is set active';
$TEXT['USERS_CAN_SELFDELETE'] = 'User can delete himself';
$TEXT['USERS_CHANGE_SETTINGS'] = 'User can change his own settings';
$TEXT['USERS_DELETED'] = 'User is marked as deleted';
$TEXT['USERS_FLAGS'] = 'User-Flags';
$TEXT['USERS_PROFILE_ALLOWED'] = 'User can create extended profile';
$TEXT['VERIFICATION'] = 'Verification';
$TEXT['VERSION'] = 'Version';
$TEXT['VIEW'] = 'View';
$TEXT['VIEW_DELETED_PAGES'] = 'View Deleted Pages';
$TEXT['VIEW_DETAILS'] = 'View Details';
$TEXT['VISIBILITY'] = 'Visibility';
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
$TEXT['WEBSITE_DESCRIPTION'] = 'Website Description';
$TEXT['WEBSITE_FOOTER'] = 'Website Footer';
$TEXT['WEBSITE_HEADER'] = 'Website Header';
$TEXT['WEBSITE_KEYWORDS'] = 'Website Keywords';
$TEXT['WEBSITE_TITLE'] = 'Website Title';
$TEXT['WELCOME_BACK'] = 'Welcome back';
$TEXT['WIDTH'] = 'Width';
$TEXT['WINDOW'] = 'Window';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'World-writeable file permissions';
$TEXT['WRITE'] = 'Write';
$TEXT['WYSIWYG_EDITOR'] = 'WYSIWYG Editor';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG Style';
$TEXT['YES'] = 'Yes';

/* HEADING */
$HEADING['ADDON_PRECHECK_FAILED'] = 'Add-On requirements not met';
$HEADING['ADD_CHILD_PAGE'] = 'Add child page';
$HEADING['ADD_GROUP'] = 'Add Group';
$HEADING['ADD_GROUPS'] = 'Add Groups';
$HEADING['ADD_HEADING'] = 'Add Heading';
$HEADING['ADD_PAGE'] = 'Add Page';
$HEADING['ADD_USER'] = 'Add User';
$HEADING['ADMINISTRATION_TOOLS'] = 'Administration Tools';
$HEADING['BROWSE_MEDIA'] = 'Browse Media';
$HEADING['CREATE_FOLDER'] = 'Create Folder';
$HEADING['DEFAULT_SETTINGS'] = 'Default Settings';
$HEADING['DELETED_PAGES'] = 'Deleted Pages';
$HEADING['FILESYSTEM_SETTINGS'] = 'Filesystem Settings';
$HEADING['GENERAL_SETTINGS'] = 'General Settings';
$HEADING['INSTALL_LANGUAGE'] = 'Install Language';
$HEADING['INSTALL_MODULE'] = 'Install Module';
$HEADING['INSTALL_TEMPLATE'] = 'Install Template';
$HEADING['INVOKE_LANGUAGE_FILES'] = 'Execute language files manually';
$HEADING['INVOKE_MODULE_FILES'] = 'Execute module files manually';
$HEADING['INVOKE_TEMPLATE_FILES'] = 'Execute template files manually';
$HEADING['LANGUAGE_DETAILS'] = 'Language Details';
$HEADING['MANAGE_SECTIONS'] = 'Manage Sections';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Modify Advanced Page Settings';
$HEADING['MODIFY_DELETE_GROUP'] = 'Modify/Delete Group';
$HEADING['MODIFY_DELETE_PAGE'] = 'Modify/Delete Page';
$HEADING['MODIFY_DELETE_USER'] = 'Modify/Delete User';
$HEADING['MODIFY_GROUP'] = 'Modify Group';
$HEADING['MODIFY_GROUPS'] = 'Modify Groups';
$HEADING['MODIFY_INTRO_PAGE'] = 'Modify Intro Page';
$HEADING['MODIFY_PAGE'] = 'Modify Page';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Modify Page Settings';
$HEADING['MODIFY_USER'] = 'Modify User';
$HEADING['MODULE_DETAILS'] = 'Module Details';
$HEADING['MY_EMAIL'] = 'My Email';
$HEADING['MY_PASSWORD'] = 'My Password';
$HEADING['MY_SETTINGS'] = 'My Settings';
$HEADING['SEARCH_SETTINGS'] = 'Search Settings';
$HEADING['SERVER_SETTINGS'] = 'Server Settings';
$HEADING['TEMPLATE_DETAILS'] = 'Template Details';
$HEADING['UNINSTALL_LANGUAGE'] = 'Uninstall Language';
$HEADING['UNINSTALL_MODULE'] = 'Uninstall Module';
$HEADING['UNINSTALL_TEMPLATE'] = 'Uninstall Template';
$HEADING['UPGRADE_LANGUAGE'] = 'Language register/upgrading';
$HEADING['UPLOAD_FILES'] = 'Upload File(s)';
$HEADING['WBMAILER_SETTINGS'] = 'Mailer Settings';

/* MESSAGE */
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
$MESSAGE['FORGOT_PASS_ALREADY_RESET'] = 'Password cannot be reset more than once per hour, sorry';
$MESSAGE['FORGOT_PASS_CANNOT_EMAIL'] = 'Unable to email password, please contact system administrator';
$MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'] = 'The email that you entered cannot be found in the database';
$MESSAGE['FORGOT_PASS_NO_DATA'] = 'Please enter your email address below';
$MESSAGE['FORGOT_PASS_PASSWORD_RESET'] = 'Your loginname and password have been sent to your email address';
$MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] = 'Sorry, no active content to display';
$MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] = 'Sorry, you do not have permissions to view this page';
$MESSAGE['GENERIC_ALREADY_INSTALLED'] = 'Already installed';
$MESSAGE['GENERIC_BAD_PERMISSIONS'] = 'Unable to write to the target directory';
$MESSAGE['GENERIC_BE_PATIENT'] = 'Please be patient.';
$MESSAGE['GENERIC_CANNOT_UNINSTALL'] = 'Cannot uninstall';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] = 'Cannot Uninstall: the selected file is in use';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'] = '<br /><br />{{type}} <b>{{type_name}}</b> could not be uninstalled, because it is still in use on {{pages}}.<br /><br />';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES'] = 'this page;these pages';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default template!';
$MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'] = 'Can\'t uninstall the template <b>{{name}}</b>, because it is the default backend theme!';
$MESSAGE['GENERIC_CANNOT_UNZIP'] = 'Cannot unzip file';
$MESSAGE['GENERIC_CANNOT_UPLOAD'] = 'Cannot upload file';
$MESSAGE['GENERIC_COMPARE'] = ' successfully';
$MESSAGE['GENERIC_ERROR_OPENING_FILE'] = 'Error opening file.';
$MESSAGE['GENERIC_FAILED_COMPARE'] = ' failed';
$MESSAGE['GENERIC_FILE_TYPE'] = 'Please note that the file you upload must be of the following format:';
$MESSAGE['GENERIC_FILE_TYPES'] = 'Please note that the file you upload must be in one of the following formats:';
$MESSAGE['GENERIC_FILL_IN_ALL'] = 'Please go back and fill-in all fields';
$MESSAGE['GENERIC_FORGOT_OPTIONS'] = 'You have selected no choice!';
$MESSAGE['GENERIC_INSTALLED'] = 'Installed successfully';
$MESSAGE['GENERIC_INVALID'] = 'The file you uploaded is invalid';
$MESSAGE['GENERIC_INVALID_ADDON_FILE'] = 'Invalid WebsiteBaker installation file. Please check the *.zip format.';
$MESSAGE['GENERIC_INVALID_LANGUAGE_FILE'] = 'Invalid WebsiteBaker language file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_MODULE_FILE'] = 'Invalid WebsiteBaker module file. Please check the text file.';
$MESSAGE['GENERIC_INVALID_TEMPLATE_FILE'] = 'Invalid WebsiteBaker template file. Please check the text file.';
$MESSAGE['GENERIC_IN_USE'] = ' but used in ';
$MESSAGE['GENERIC_MISSING_ARCHIVE_FILE'] = 'Missing Archiv file!';
$MESSAGE['GENERIC_MODULE_VERSION_ERROR'] = 'The module is not installed properly!';
$MESSAGE['GENERIC_NOT_COMPARE'] = ' not possibly';
$MESSAGE['GENERIC_NOT_INSTALLED'] = 'Not installed';
$MESSAGE['GENERIC_NOT_UPGRADED'] = 'Actualization not possibly';
$MESSAGE['GENERIC_PLEASE_BE_PATIENT'] = 'Please be patient, this might take a while.';
$MESSAGE['GENERIC_PLEASE_CHECK_BACK_SOON'] = 'Please check back soon...';
$MESSAGE['GENERIC_SECURITY_ACCESS'] = 'Security offense!! Access denied!';
$MESSAGE['GENERIC_SECURITY_OFFENSE'] = 'Security offense!! data storing was refused!!';
$MESSAGE['GENERIC_UNINSTALLED'] = 'Uninstalled successfully';
$MESSAGE['GENERIC_UPGRADED'] = 'Upgraded successfully';
$MESSAGE['GENERIC_VERSION_COMPARE'] = 'Version comparison';
$MESSAGE['GENERIC_VERSION_GT'] = 'Upgrade necessary!';
$MESSAGE['GENERIC_VERSION_LT'] = 'Downgrade';
$MESSAGE['GENERIC_WEBSITE_UNDER_CONSTRUCTION'] = 'Website Under Construction';
$MESSAGE['GENERIC_WEBSITE_LOCKED'] = 'this site is temporarily down for maintenance';
$MESSAGE['GROUPS_ADDED'] = 'Group added successfully';
$MESSAGE['GROUPS_CONFIRM_DELETE'] = 'Are you sure you want to delete the selected group (and any users that belong to it)?';
$MESSAGE['GROUPS_DELETED'] = 'Group deleted successfully';
$MESSAGE['GROUPS_GROUP_NAME_BLANK'] = 'Group name is blank';
$MESSAGE['GROUPS_GROUP_NAME_EXISTS'] = 'Group name already exists';
$MESSAGE['GROUPS_NO_GROUPS_FOUND'] = 'No groups found';
$MESSAGE['GROUPS_SAVED'] = 'Group saved successfully';
$MESSAGE['LOGIN_AUTHENTICATION_FAILED'] = 'Loginname or password incorrect';
$MESSAGE['LOGIN_BOTH_BLANK'] = 'Please enter your loginname and password below';
$MESSAGE['LOGIN_PASSWORD_BLANK'] = 'Please enter a password';
$MESSAGE['LOGIN_PASSWORD_TOO_LONG'] = 'Supplied password to long';
$MESSAGE['LOGIN_PASSWORD_TOO_SHORT'] = 'Supplied password to short';
$MESSAGE['LOGIN_USERNAME_BLANK'] = 'Please enter a loginname';
$MESSAGE['LOGIN_USERNAME_TOO_LONG'] = 'Supplied loginname to long';
$MESSAGE['LOGIN_USERNAME_TOO_SHORT'] = 'Supplied loginname to short';
$MESSAGE['MEDIA_BLANK_EXTENSION'] = 'You did not enter a file extension';
$MESSAGE['MEDIA_BLANK_NAME'] = 'You did not enter a new name';
$MESSAGE['MEDIA_CANNOT_DELETE_DIR'] = 'Cannot delete the selected folder';
$MESSAGE['MEDIA_CANNOT_DELETE_FILE'] = 'Cannot delete the selected file';
$MESSAGE['MEDIA_CANNOT_RENAME'] = 'Rename unsuccessful';
$MESSAGE['MEDIA_CONFIRM_DELETE'] = 'Are you sure you want to delete the following file or folder?';
$MESSAGE['MEDIA_DELETED_DIR'] = 'Folder deleted successfully';
$MESSAGE['MEDIA_DELETED_FILE'] = 'File deleted successfully';
$MESSAGE['MEDIA_DIR_ACCESS_DENIED'] = 'Specified directory does not exist or is not allowed.';
$MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'] = 'Directory does not exist';
$MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'] = 'Cannot include ../ in the folder name';
$MESSAGE['MEDIA_DIR_EXISTS'] = 'A folder matching the name you entered already exists';
$MESSAGE['MEDIA_DIR_MADE'] = 'Folder created successfully';
$MESSAGE['MEDIA_DIR_NOT_MADE'] = 'Unable to create folder';
$MESSAGE['MEDIA_FILE_EXISTS'] = 'A file matching the name you entered already exists';
$MESSAGE['MEDIA_FILE_NOT_FOUND'] = 'File not found';
$MESSAGE['MEDIA_NAME_DOT_DOT_SLASH'] = 'Cannot include ../ in the name';
$MESSAGE['MEDIA_NAME_INDEX_PHP'] = 'Cannot use index.php as the name';
$MESSAGE['MEDIA_NO_FILE_UPLOADED']='No file was recieved';
$MESSAGE['MEDIA_NONE_FOUND'] = 'No media found in the current folder';
$MESSAGE['MEDIA_RENAMED'] = 'Rename successful';
$MESSAGE['MEDIA_SINGLE_UPLOADED'] = ' file was successfully uploaded';
$MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH'] = 'Cannot have ../ in the folder target';
$MESSAGE['MEDIA_UPLOADED'] = ' files were successfully uploaded';
$MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'] = 'Sorry, this form has been submitted too many times so far this hour. Please retry in the next hour.';
$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = 'The verification number (also known as Captcha) that you entered is incorrect. If you are having problems reading the Captcha, please email: <a href="mailto:{SERVER_EMAIL}">{SERVER_EMAIL}</a>';
$MESSAGE['MOD_FORM_REQUIRED_FIELDS'] = 'You must enter details for the following fields';
$MESSAGE['PAGES_ADDED'] = 'Page added successfully';
$MESSAGE['PAGES_ADDED_HEADING'] = 'Page heading added successfully';
$MESSAGE['PAGES_BLANK_MENU_TITLE'] = 'Please enter a menu title';
$MESSAGE['PAGES_BLANK_PAGE_TITLE'] = 'Please enter a page title';
$MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] = 'Error creating access file in the /pages directory (insufficient privileges)';
$MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE'] = 'Error deleting access file in the /pages directory (insufficient privileges)';
$MESSAGE['PAGES_CANNOT_REORDER'] = 'Error re-ordering page';
$MESSAGE['PAGES_DELETED'] = 'Page deleted successfully';
$MESSAGE['PAGES_DELETE_CONFIRM'] = 'Are you sure you want to delete the selected page (and all of its sub-pages)';
$MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'] = 'You do not have permissions to modify this page';
$MESSAGE['PAGES_INTRO_LINK'] = 'Click HERE to modify the intro page';
$MESSAGE['PAGES_INTRO_NOT_WRITABLE'] = 'Cannot write to file /pages/intro.php (insufficient privileges)';
$MESSAGE['PAGES_INTRO_SAVED'] = 'Intro page saved successfully';
$MESSAGE['PAGES_LAST_MODIFIED'] = 'Last modification by';
$MESSAGE['PAGES_NOT_FOUND'] = 'Page not found';
$MESSAGE['PAGES_NOT_SAVED'] = 'Error saving page';
$MESSAGE['PAGES_PAGE_EXISTS'] = 'A page with the same or similar title exists';
$MESSAGE['PAGES_REORDERED'] = 'Page re-ordered successfully';
$MESSAGE['PAGES_RESTORED'] = 'Page restored successfully';
$MESSAGE['PAGES_RETURN_TO_PAGES'] = 'Return to pages';
$MESSAGE['PAGES_SAVED'] = 'Page saved successfully';
$MESSAGE['PAGES_SAVED_SETTINGS'] = 'Page settings saved successfully';
$MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'] = 'Section properties saved successfully';
$MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'] = 'The (current) password you entered is incorrect';
$MESSAGE['PREFERENCES_DETAILS_SAVED'] = 'Details saved successfully';
$MESSAGE['PREFERENCES_EMAIL_UPDATED'] = 'Email updated successfully';
$MESSAGE['PREFERENCES_INVALID_CHARS'] = 'Invalid password chars used';
$MESSAGE['PREFERENCES_PASSWORD_CHANGED'] = 'Password changed successfully';
$MESSAGE['RECORD_MODIFIED_FAILED'] = 'The change of the record has missed.';
$MESSAGE['RECORD_MODIFIED_SAVED'] = 'The changed record was updated successfully.';
$MESSAGE['RECORD_NEW_FAILED'] = 'Adding a new record has missed.';
$MESSAGE['RECORD_NEW_SAVED'] = 'New record was added successfully.';
$MESSAGE['SETTINGS_MODE_SWITCH_WARNING'] = 'Please Note: Pressing this button resets all unsaved changes';
$MESSAGE['SETTINGS_SAVED'] = 'Settings saved successfully';
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
$MESSAGE['SIGNUP_NO_EMAIL'] = 'You must enter an email address';
$MESSAGE['START_CURRENT_USER'] = 'You are currently logged in as:';
$MESSAGE['START_INSTALL_DIR_EXISTS'] = 'Warning, Installation Directory Still Exists!';
$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'] = 'Please delete the file "upgrade-script.php" from your webspace.';
$MESSAGE['START_WELCOME_MESSAGE'] = 'Welcome to WebsiteBaker Administration';
$MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'] = 'Please note: to change the template you must go to the Settings section';

$MESSAGE['UPLOAD_ERR_OK'] = 'File were successful uploaded';
$MESSAGE['UPLOAD_ERR_INI_SIZE'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$MESSAGE['UPLOAD_ERR_FORM_SIZE'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$MESSAGE['UPLOAD_ERR_PARTIAL'] = 'The uploaded file was only partially uploaded';
$MESSAGE['UPLOAD_ERR_NO_FILE'] = 'No file was uploaded';
$MESSAGE['UPLOAD_ERR_NO_TMP_DIR'] = 'Missing a temporary folder';
$MESSAGE['UPLOAD_ERR_CANT_WRITE'] = 'Failed to write file to disk';
$MESSAGE['UPLOAD_ERR_EXTENSION'] = 'File upload stopped by extension';
$MESSAGE['UNKNOW_UPLOAD_ERROR'] = 'Unknown upload error';

$MESSAGE['USERS_ADDED'] = 'User added successfully';
$MESSAGE['USERS_CANT_SELFDELETE'] = 'Function rejected, You can not delete yourself!';
$MESSAGE['USERS_CHANGING_PASSWORD'] = 'Please note: You should only enter values in the above fields if you wish to change this users password';
$MESSAGE['USERS_CONFIRM_DELETE'] = 'Are you sure you want to delete the selected user?';
$MESSAGE['USERS_DELETED'] = 'User deleted successfully';
$MESSAGE['USERS_EMAIL_TAKEN'] = 'The email you entered is already in use';
$MESSAGE['USERS_INVALID_EMAIL'] = 'The email address you entered is invalid';
$MESSAGE['USERS_NAME_INVALID_CHARS'] = 'Invalid chars for loginname found';
$MESSAGE['USERS_NO_GROUP'] = 'No group was selected';
$MESSAGE['USERS_PASSWORD_MISMATCH'] = 'The passwords you entered do not match';
$MESSAGE['USERS_PASSWORD_TOO_SHORT'] = 'The password you entered was too short';
$MESSAGE['USERS_SAVED'] = 'User saved successfully';
$MESSAGE['USERS_USERNAME_TAKEN'] = 'The loginname you entered is already taken';
$MESSAGE['USERS_USERNAME_TOO_SHORT'] = 'The loginname you entered was too short';

/* OVERVIEW */
$OVERVIEW['ADMINTOOLS'] = 'Access the WebsiteBaker administration tools...';
$OVERVIEW['GROUPS'] = 'Manage user groups and their system permissions...';
$OVERVIEW['HELP'] = 'Got a questions? Find your answer...';
$OVERVIEW['LANGUAGES'] = 'Manage WebsiteBaker languages...';
$OVERVIEW['MEDIA'] = 'Manage files stored in the media folder...';
$OVERVIEW['MODULES'] = 'Manage WebsiteBaker modules...';
$OVERVIEW['PAGES'] = 'Manage your websites pages...';
$OVERVIEW['PREFERENCES'] = 'Change preferences such as email address, password, etc... ';
$OVERVIEW['SETTINGS'] = 'Changes settings for WebsiteBaker...';
$OVERVIEW['START'] = 'Administration overview';
$OVERVIEW['TEMPLATES'] = 'Change the look and feel of your website with templates...';
$OVERVIEW['USERS'] = 'Manage users who can log-in to WebsiteBaker...';
$OVERVIEW['VIEW'] = 'Quickly view and browse your website in a new window...';

$TEXT['THEME_COPY_CURRENT']  = 'Copy backend theme.';
$TEXT['THEME_NEW_NAME']      = 'Name of the new Theme';
$TEXT['THEME_CURRENT']       = 'current active theme';
$TEXT['THEME_START_COPY']    = 'copy';
$TEXT['THEME_IMPORT_HTT']    = 'Import additional templates';
$TEXT['THEME_SELECT_HTT']    = 'select templates';
$TEXT['THEME_NOMORE_HTT']    = 'no more available';
$TEXT['THEME_START_IMPORT']  = 'import';
$MESSAGE['THEME_COPY_CURRENT']               = 'Copy the current active theme and save it with a new name.';
$MESSAGE['THEME_ALREADY_EXISTS']             = 'This new theme descriptor already exists.';
$MESSAGE['THEME_INVALID_SOURCE_DESTINATION'] = 'Invalid descriptor for the new theme given!';
$MESSAGE['THEME_DESTINATION_READONLY']       = 'No rights to create new theme directory!';
$MESSAGE['THEME_IMPORT_HTT']                 = 'Import additional templates into the current active theme.<br />Use these templates to overwrite the corresponding default template.';

/* include old languages format */
if(file_exists(WB_PATH.'/languages/old.format.inc.php'))
{
	include(WB_PATH.'/languages/old.format.inc.php');
}
