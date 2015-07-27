<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker CE backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file contains the English text outputs of the module.
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @platform    CMS WebsiteBaker Community Edition (WBCE)
 * @package     cwsoft-addon-file-editor
 * @author      cwsoft (http://cwsoft.de)
 * @translation cwsoft
 * @copyright   cwsoft
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
*/

// German module description
$module_description = 'The cwsoft-addon-file-editor module allows you to edit text- and image files of installed Add-ons via the backend. For details see <a href="https://github.com/cwsoft/wbce-addon-file-editor#readme">GitHub</a>.';

// initialize global $LANG variable as array if needed
global $LANG;
if (! isset($LANG) || (isset($LANG) && ! is_array($LANG))) {
    $LANG = array();
}

// Text outputs overview page (templates/addons_overview.htt)
$LANG['ADDON_FILE_EDITOR'][1] = array(
    'TXT_DESCRIPTION'               => 'The list below shows all Add-ons, which are readable by PHP. You can edit Add-on files by ' . 
                                       'clicking on the Add-on name. The download icon allows you to create a installable backup ' .
                                       'of your Add-on on the fly.',
    'TXT_FTP_NOTICE'                => 'Add-ons/files highlighted red are not writeable by PHP. This may be the case, if you install ' .
                                       'Add-ons by the use of FTP. You need to <a class="link" target="_blank" href="{URL_ASSISTANT}">' .
                                       'enable FTP support </a> in order to edit those Add-on files.',
    'TXT_HEADING'                   => 'Installed Add-ons (Modules, Templates, Language Files)',
    'TXT_HELP'                      => 'Help',

    'TXT_RELOAD'                    => 'Reload',
    'TXT_ACTION_EDIT'               => 'Edit',
    'TXT_ACTION_DELETE'             => 'Delete',
    'TXT_FTP_SUPPORT'               => ' (requires FTP write access to modify)',

    'TXT_MODULES'                   => 'Module',
    'TXT_LIST_OF_MODULES'           => 'List of installed Modules',
    'TXT_EDIT_MODULE_FILES'         => 'Edit module files',
    'TXT_ZIP_MODULE_FILES'          => 'Backup and download module files',

    'TXT_TEMPLATES'                 => 'Templates',
    'TXT_LIST_OF_TEMPLATES'         => 'List of installed templates',
    'TXT_EDIT_TEMPLATE_FILES'       => 'Edit template files',
    'TXT_ZIP_TEMPLATE_FILES'        => 'Backup and download template files',

    'TXT_LANGUAGES'                 => 'Language files',
    'TXT_LIST_OF_LANGUAGES'         => 'List of installed WB language files',
    'TXT_EDIT_LANGUAGE_FILES'       => 'Edit language file',
    'TXT_ZIP_LANGUAGE_FILES'        => 'Download language file',
);

// Text outputs filemanager page (templates/filemanager.htt)
$LANG['ADDON_FILE_EDITOR'][2] = array(
    'TXT_EDIT_DESCRIPTION'          => 'The filemanager allows you to edit, rename, create, delete and upload files. A click on text- ' .
                                       'or image file names opens the files for editing or viewing.',
    'TXT_BACK_TO_OVERVIEW'          => 'back to Add-on overview',

    'TXT_MODULE'                    => 'Module',
    'TXT_TEMPLATE'                  => 'Template',
    'TXT_LANGUAGE'                  => 'WB Language File',
    'TXT_FTP_SUPPORT'               => ' (requires FTP write access to modify)',

    'TXT_RELOAD'                    => 'Reload',
    'TXT_CREATE_FILE_FOLDER'        => 'Create File/Folder',
    'TXT_UPLOAD_FILE'               => 'Upload File',
    'TXT_VIEW'                      => 'View',
    'TXT_EDIT'                      => 'Edit',
    'TXT_UNZIP'                     => 'Unzip',
    'TXT_RENAME'                    => 'Rename',
    'TXT_DELETE'                    => 'Delete',

    'TXT_FILE_INFOS'                => 'File information',
    'TXT_FILE_ACTIONS'              => 'Actions',
    'TXT_FILE_TYPE_TEXTFILE'        => 'Text file',
    'TXT_FILE_TYPE_FOLDER'          => 'Folder',
    'TXT_FILE_TYPE_IMAGE'           => 'Image file',
    'TXT_FILE_TYPE_ARCHIVE'         => 'Archive file',
    'TXT_FILE_TYPE_OTHER'           => 'Unknown',

    'DATE_FORMAT'                   => 'm/d/y / h:i a',
);

// General text outputs for the file handler templates
$LANG['ADDON_FILE_EDITOR'][3] = array(
    'ERR_WRONG_PARAMETER'           => 'The specified parameters are wrong or incomplete.',
    'TXT_MODULE'                    => 'Module',
    'TXT_TEMPLATE'                  => 'Template',
    'TXT_LANGUAGE'                  => 'WB Language File',
    'TXT_ACTION'                    => 'Action',
    'TXT_ACTUAL_FILE'               => 'Current File',
    'TXT_SUBMIT_CANCEL'             => 'Cancel',
);    

// Text outputs file handler (templates/action_handler_edit_textfile.htt)
$LANG['ADDON_FILE_EDITOR'][4] = array(
    'TXT_ACTION_EDIT_TEXTFILE'      => 'Edit text file',
    'TXT_SUBMIT_SAVE'               => 'Save',
    'TXT_SUBMIT_SAVE_BACK'          => 'Save &amp; back',
    'TXT_ACTUAL_FILE'               => 'Current file',
    'TXT_SAVE_SUCCESS'              => 'File modifications sucessfully saved.',
    'TXT_SAVE_ERROR'                => 'Unable to save the file. Please check permissions.',
);

// Text outputs file handler (templates/action_handler_rename_file_folder.htt)
$LANG['ADDON_FILE_EDITOR'][5] = array(
    'TXT_ACTION_RENAME_FILE'        => 'Rename file/folder',
    'TXT_OLD_FILE_NAME'             => 'File/folder (old)',
    'TXT_NEW_FILE_NAME'             => 'File/folder (new)',
    'TXT_SUBMIT_RENAME'             => 'Rename',
    'TXT_RENAME_SUCCESS'            => 'File/folder sucessfully renamed.',
    'TXT_RENAME_ERROR'              => 'Unable to rename file/folder. Please check permissions.',
    'TXT_ALLOWED_FILE_CHARS'        => '[a-zA-Z0-9.-_]',
);

// Text outputs file handler (templates/action_handler_delete_file_folder.htt)
$LANG['ADDON_FILE_EDITOR'][6] = array(
    'TXT_ACTION_DELETE_FILE'        => 'Delete file/folder',
    'TXT_SUBMIT_DELETE'             => 'Delete',
    'TXT_ACTUAL_FOLDER'             => 'Current folder',
    'TXT_DELETE_WARNING'            => '<strong>Note: </strong>Deletion of files and folders can not be revised. Keep in mind ' .
                                       'that when deleting a folder, all files and sub folders contained will be deleted too.',
    'TXT_DELETE_SUCCESS'            => 'File/folder sucessfully deleted.',
    'TXT_DELETE_ERROR'              => 'Unable to delete file/folder. Please check permissions.<br /><em>Note: To delete a folder ' .
                                       'by FTP, make sure the folder does not contain other folders or files.</em>'
);

// Text outputs file handler (templates/action_handler_create_file_folder.htt)
$LANG['ADDON_FILE_EDITOR'][7] = array(
    'TXT_ACTION_CREATE_FILE'        => 'Create file/folder',
    'TXT_CREATE'                    => 'Create',
    'TXT_FILE'                      => 'File',
    'TXT_FOLDER'                    => 'Folder',
    'TXT_FILE_NAME'                 => 'File name',
    'TXT_ALLOWED_FILE_CHARS'        => '[a-zA-Z0-9.-_]',
    'TXT_TARGET_FOLDER'             => 'Target folder',
    'TXT_SUBMIT_CREATE'             => 'Create',
    'TXT_CREATE_SUCCESS'            => 'File/folder sucessfully created.',
    'TXT_CREATE_ERROR'              => 'Unable to create file/folder. Please check permissions and specified file name.',
    'TXT_INVALID_FILENAME'          => 'The specified file or folder name is invalid.',
);

// Text outputs file handler (templates/action_handler_upload_file.htt)
$LANG['ADDON_FILE_EDITOR'][8] = array(
    'TXT_ACTION_UPLOAD_FILE'        => 'Upload file',
    'TXT_SUBMIT_UPLOAD'             => 'Upload',

    'TXT_FILE'                      => 'File',
    'TXT_TARGET_FOLDER'             => 'Target folder',

    'TXT_UPLOAD_SUCCESS'            => 'File sucessfully uploaded.',
    'TXT_UPLOAD_ERROR'              => 'Unable to upload file. Please check permissions and file size.',
);

// Text outputs for the download handler
$LANG['ADDON_FILE_EDITOR'][9] = array(
    'ERR_TEMP_PERMISSION'           => 'PHP has no write permission for the temporary WB folder (/temp).',
    'ERR_ZIP_CREATION'              => 'Unable to create the archive.',
    'ERR_ZIP_DOWNLOAD'              => 'Error while downloading backup file.<br /><a href="{URL}">Download</a> manually.',
);

// Text outputs for the FTP checking (templates/ftp_connection_check.htt)
$LANG['ADDON_FILE_EDITOR'][10] = array(
    'TXT_FTP_HEADING'               => 'FTP Configuration-Assistant',
    'TXT_FTP_DESCRIPTION'           => 'The FTP assistant helps you to set-up and test the FTP support for the Addon File Editor.',

    'TXT_FTP_SETTINGS'              => 'Actual FTP Settings',
    'TXT_FTP_SUPPORT'               => 'FTP Support',
    'TXT_ENABLE'                    => 'Enabled',
    'TXT_DISABLE'                   => 'Disabled',
    'TXT_FTP_SERVER'                => 'Server',
    'TXT_FTP_USER'                  => 'User',
    'TXT_FTP_PASSWORD'              => 'Password',
    'TXT_FTP_PORT'                  => 'Port',
    'TXT_FTP_START_DIR'             => 'Start directory',

    'TXT_FTP_CONNECTION_TEST'       => 'Check FTP Connection',
    'TXT_CHECK_FTP_CONNECTION'      => 'Press the button below to check the connection status to your FTP server.',
    'TXT_FTP_CHECK_NOTE'            => '<strong>Note: </strong>The connection test can take up to 15 seconds.',
    'TXT_SUBMIT_CHECK'              => 'Connect',
    'TXT_FTP_LOGIN_OK'              => 'Connection to FTP server sucessfull. FTP support is enabled.',
    'TXT_FTP_LOGIN_FAILED'          => 'Connection to FTP server failed. Please check your FTP settings. ' .
                                       '<br /><strong>Note: </strong>The start directory must point to your WB installation.',
);

// Text outputs file handler (templates/action_handler_unzip_file.htt)
$LANG['ADDON_FILE_EDITOR'][11] = array(
    'TXT_ACTION_UNZIP_ARCHIVE'      => 'Unzip archive file',
    'TXT_FILE_TO_UNZIP'             => 'File to unzip',
    'TXT_TARGET_FOLDER'             => 'Target folder',
    'TXT_SUBMIT_UNZIP'              => 'Unzip',
    'TXT_UNZIP_WARNING'             => '<strong>Note: </strong>Unzipping an archive may overwrite possible existing files in the target folder and can not be revised.',
    'TXT_UNZIP_SUCCESS'             => 'Archive sucessfully unzipped.',
    'TXT_UNZIP_ERROR'               => 'Failed to to unzip given archive file. Please check that the archive is a valid ZIP-archive and the target folder has PHP write permission.',
);