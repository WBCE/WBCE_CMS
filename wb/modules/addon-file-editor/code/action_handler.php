<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file contains the action handler for the file actions:
 * edit, rename, delete, create and upload
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     cwsoft-addon-file-editor
 * @author      cwsoft (http://cwsoft.de)
 * @copyright   cwsoft
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */

// include WB configuration file (restarts sessions) and WB admin class
require_once ('../../../config.php');
require_once ('../../../framework/class.admin.php');

// include module configuration and function file
require_once ('config.php');
require_once ('functions.php');

// load module language file
$lang = $module_path . '/languages/' . LANGUAGE . '.php';
require_once (! file_exists($lang) ? $module_path . '/languages/EN.php' : $lang);

/**
 * Ensure that only users with permissions to Admin-Tools section can access this file
 */
// check user permissions for admintools (redirect users with wrong permissions)
$admin = new admin('Admintools', 'admintools', false, false);
if ($admin->get_permission('admintools') == false) exit("Cannot access this file directly");

/**
 * Make sanity check of user specified values
 * $_GET['action'], $_GET['aid'] and $_GET['fid']
 */
// use modified admin handler to inject module backend.css, backend.js files into <head> section
$admin = myAdminHandler($module_folder, 'Admintools', 'admintools', true, false);

// make sanity check of user specified action handler, addon id and file id
$action = (isset($_GET['action']) && is_numeric($_GET['action'])) ? $_GET['action'] : '';
$aid = (isset($_GET['aid']) && isset($_SESSION['addon_id']) && ($_GET['aid'] == $_SESSION['addon_id'])) ? (int)$_GET['aid'] : '';
$fid = (isset($_GET['fid']) && isset($_SESSION['addon_file_infos']) && $_SESSION['addon_file_infos'] >= $_GET['fid']) ? (int)$_GET['fid'] : '';

// check if addon and file id is specified for action id: 1-3
if ($action < 4 && ($aid == '' || $fid == '')) {
	$admin->print_error($LANG['ADDON_FILE_EDITOR'][3]['ERR_WRONG_PARAMETER'], $url_admintools);	
}

/**
 * Create Twig template object and configure it
 */
// register Twig shipped with AFE if not already done by the WB core (included since WB 2.8.3 #1688)  
if (! class_exists('Twig_Autoloader')) {
	require_once ('../thirdparty/Twig/Twig/Autoloader.php');
	Twig_Autoloader::register();
}
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/../templates');
$twig = new Twig_Environment($loader, array(
	'autoescape'       => false,
	'cache'            => false,
	'strict_variables' => true,
	'debug'            => false,
));

// create array to map action handler to the required AFE template and language files
$tpl_files = array(
	'1' => array('action_handler_edit_textfile.htt',      $LANG['ADDON_FILE_EDITOR'][4]), 
	'2' => array('action_handler_rename_file_folder.htt', $LANG['ADDON_FILE_EDITOR'][5]), 
	'3' => array('action_handler_delete_file_folder.htt', $LANG['ADDON_FILE_EDITOR'][6]), 
	'4' => array('action_handler_create_file_folder.htt', $LANG['ADDON_FILE_EDITOR'][7]), 
	'5' => array('action_handler_upload_file.htt',        $LANG['ADDON_FILE_EDITOR'][8]),
	'6' => array('action_handler_unzip_archive.htt',      $LANG['ADDON_FILE_EDITOR'][11]),
);

// load template file depending on action handler
if ($action > 0 && $action < 7) {
	$tpl = $twig->loadTemplate($tpl_files[$action][0]);
}

/**
 * Set template data (general data and data from text files)  
 */
// add general template language data 
$data = array();
$data['lang'] = array(
	'TXT_HEADING_ADMINTOOLS' => $HEADING['ADMINISTRATION_TOOLS'], 
	'TXT_BACK'               => $TEXT['BACK'], 
	'TXT_HELP'               => $LANG['ADDON_FILE_EDITOR'][1]['TXT_HELP'], 
);

// include language data from AFE language files
foreach (array_merge($LANG['ADDON_FILE_EDITOR'][3], $tpl_files[$action][1]) as $key => $value) {
	$data['lang'][$key] = $value;
}

// update template general data 
$editor_info = getAddonInfos($module_folder);
$addon_info = getAddonInfos($aid);

$data['afe'] = array(
	'URL_HELP_FILE'      => $url_help, 
	'STATUS_MESSAGE'     => '', 
	'CLASS_HIDDEN'       => '', 
	'NAME_FILE_EDITOR'   => $editor_info['name'], 
	'ADDON_TYPE'         => $LANG['ADDON_FILE_EDITOR'][3]['TXT_' . strtoupper($addon_info['type'])], 
	'ADDON_NAME'         => $addon_info['name'], 
	'URL_WB_ADMIN_TOOLS' => ADMIN_URL . '/admintools/index.php', 
	'URL_FILEMANAGER'    => $url_admintools . '&amp;aid=' . $aid, 
	'VERSION'            => $afe_version, 
);

/**
 * Evaluate the action handler
 */
switch ($action) {
	case 1:
		#####################################################################################
		# edit text file
		#####################################################################################
		$actual_file = $_SESSION['addon_file_infos'][$fid]['path'];

		// strip path up to ../modules/mod_directory/; for language files keep mod_directory (e.g. EN)
		$strip_path = WB_PATH . $path_sep . $addon_info['type'] . 's' . (($addon_info['type'] != 'language') ? $path_sep . $addon_info['directory'] : '');

		// fetch content of specified file (read from file or take over from textarea)
		if ((isset($_POST['save_modified_textfile']) || isset($_POST['save_modified_textfile_back'])) && isset($_POST['code_area_text'])) {
			// take content from save request
			$file_content = $admin->strip_slashes($_POST['code_area_text']);
		} else {
			// open file and save data in variable
			$file_content = file_get_contents($actual_file);
		}

		$data['afe'] = array_merge($data['afe'], array(
			'REGISTER_EDIT_AREA' => myRegisterEditArea($syntax = myGetEditAreaSyntax($actual_file)), 
			'ADDON_FILE'         => str_replace($strip_path, '', $actual_file), 
			'FILE_CONTENT'       => htmlspecialchars($file_content), 
			'URL_FORM_SUBMIT'    => $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $fid . '&amp;action=1', 
			'URL_FORM_CANCEL'    => $url_admintools . '&amp;aid=' . $aid
		));

		// action save modified text file
		if ((isset($_POST['save_modified_textfile']) || isset($_POST['save_modified_textfile_back'])) && isset($_POST['code_area_text'])) {

			// save changes to text file
			$status = false;
			if (is_writeable($actual_file) && $handle = fopen($actual_file, 'wb')) {
				$status = fwrite($handle, $file_content);
			}

			// try FTP file upload if fwrite method failed (permissions)
			if (! $status) {
				$ftp_remote_file = str_replace(array(WB_PATH . $path_sep, $path_sep), array('', '/'), $actual_file);
				$ftp = ftpLogin();
				$status = ftpWriteStringToFile($ftp, $file_content, $ftp_remote_file);
			}

			$status_message = ($status) ? $LANG['ADDON_FILE_EDITOR'][4]['TXT_SAVE_SUCCESS'] : $LANG['ADDON_FILE_EDITOR'][4]['TXT_SAVE_ERROR'];
			$back_link = $url_admintools . '&aid=' . $aid . '&fid=' . $fid;

			$data['afe'] = array_merge($data['afe'], array(
				'CLASS_HIDDEN'      => '',
				'STATUS_MESSAGE'    => writeStatusMessage(
						$message        = $status_message, 
						$back_url       = $back_link, 
						$sucess         = $status, 
						$auto_redirect  = ($status && isset($_POST['save_modified_textfile_back'])), 
						$redirect_timer = isset($_POST['save_modified_textfile_back']) ? 0 : 1500
						), 
			));
		}

		// ouput the final template
		$tpl->display($data);
		break;

	case 2:
		#####################################################################################
		# rename file or folder
		#####################################################################################
		$actual_file = $_SESSION['addon_file_infos'][$fid]['path'];
		$file_extension = getFileExtension($actual_file);
		$strip_path = WB_PATH . $path_sep . $addon_info['type'] . 's' . $path_sep . $addon_info['directory'];

		$data['afe'] = array_merge($data['afe'], array(
			'ADDON_FILE'      => str_replace($strip_path, '', $actual_file), 
			'FILE_EXT'        => $file_extension, 
			'OLD_FILE_NAME'   => str_replace('.' . $file_extension, '', basename($actual_file)), 
			'CLASS_HIDE'      => ($_SESSION['addon_file_infos'][$fid]['type'] == 'folder') ? 'hidden' : '', 
			'URL_FORM_SUBMIT' => $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $fid . '&amp;action=2', 
			'URL_FORM_CANCEL' => $url_admintools . '&amp;aid=' . $aid
		));

		// action rename file / folder
		if (isset($_POST['rename_file_folder']) && isset($_POST['new_file'])) {
			$new_file_name = strip_tags($_POST['new_file']);

			// rename the file or folder
			$status = renameFileOrFolder($actual_file, $new_file_name);

			// try to rename file/folder via FTP if PHP method failed (permissions)
			if (! $status) {
				// build old and new file name as required for FTP
				$ftp_old = str_replace(array(WB_PATH . $path_sep, $path_sep), array('', '/'), $actual_file);
				$ftp_new = str_replace(basename($actual_file), $new_file_name, $ftp_old);
				$ftp_new .= (($file_extension == '') ? '' : '.' . $file_extension);

				$ftp = ftpLogin();
				$status = ftpRenameFile($ftp, $ftp_old, $ftp_new);
			}

			$status_message = ($status) ? $LANG['ADDON_FILE_EDITOR'][5]['TXT_RENAME_SUCCESS'] : $LANG['ADDON_FILE_EDITOR'][5]['TXT_RENAME_ERROR'];
			$back_link = $url_admintools . '&aid=' . $aid . '&reload';

			$data['afe'] = array_merge($data['afe'], array(
				'STATUS_MESSAGE' => writeStatusMessage($status_message, $back_link, $status), 
				'CLASS_HIDDEN'   => ($status) ? 'hidden' : '',
			));
		}

		// ouput the final template
		$tpl->display($data);
		break;

	case 3:
		#####################################################################################
		# delete file or folder
		#####################################################################################
		$actual_file = $_SESSION['addon_file_infos'][$fid]['path'];
		$strip_path = WB_PATH . $path_sep . $addon_info['type'] . 's' . $path_sep . $addon_info['directory'];

		$data['afe'] = array_merge($data['afe'], array(
			'ADDON_FILE'       => str_replace($strip_path, '', $actual_file), 
			'FILE_FOLDER_NAME' => basename($actual_file), 
			'URL_FORM_SUBMIT'  => $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $fid . '&amp;action=3&amp;reload', 
			'URL_FORM_CANCEL'  => $url_admintools . '&amp;aid=' . $aid . '&amp;fid=' . $fid, 
			'CLASS_HIDDEN'     => '', 
			'TXT_ACTUAL_FILE'  => ($_SESSION['addon_file_infos'][$fid]['type'] == 'folder') 
			                       ? $LANG['ADDON_FILE_EDITOR'][6]['TXT_ACTUAL_FOLDER']
								   : $LANG['ADDON_FILE_EDITOR'][3]['TXT_ACTUAL_FILE'], 
		));

		// action delete file / folder
		if (isset($_POST['delete_file_folder'])) {
			$status = removeFileOrFolder($actual_file);

			// try to delete file/folder via FTP if PHP method failed (permissions)
			if (! $status) {
				$ftp_file = str_replace(array(WB_PATH . $path_sep, $path_sep), array('', '/'), $actual_file);
				$ftp = ftpLogin();
				$status = is_dir($actual_file) ? ftpDeleteFolder($ftp, $ftp_file) : ftpDeleteFile($ftp, $ftp_file);
			}

			$status_message = ($status) ? $LANG['ADDON_FILE_EDITOR'][6]['TXT_DELETE_SUCCESS'] : $LANG['ADDON_FILE_EDITOR'][6]['TXT_DELETE_ERROR'];
			$back_link = $url_admintools . '&aid=' . $aid . '&fid=' . $fid . '&reload';

			$data['afe'] = array_merge($data['afe'], array(
				'STATUS_MESSAGE' => writeStatusMessage($status_message, $back_link, $status), 
				'CLASS_HIDDEN'   => 'hidden', 
			));
		}

		// ouput the final template
		$tpl->display($data);
		break;

	case 4:
		#####################################################################################
		# create new file or folder
		#####################################################################################
		$strip_path = WB_PATH . $path_sep . $addon_info['type'] . 's' . $path_sep;
		$data['afe'] = array_merge($data['afe'], array(
			'SEL_ENTRIES_FILE_EXTENSIONS' => createSelectEntries($text_extensions), 
			'SEL_ENTRIES_TARGET_FOLDER'   => createTargetFolderSelectEntries($_SESSION['addon_folders'], $strip_path), 
			'URL_WB_ADMIN_TOOLS'          => ADMIN_URL . '/admintools/index.php', 
			'URL_FORM_SUBMIT'             => $url_action_handler . '?aid=' . $aid . '&amp;action=4', 
			'URL_FORM_CANCEL'             => $url_admintools . '&amp;aid=' . $aid
		));

		// action create file / folder
		if (isset($_POST['create_file_folder']) && isset($_POST['file_folder']) && isset($_POST['file_name']) && isset($_POST['target_folder'])) {

			// work out backlink
			$back_link = $url_admintools . '&aid=' . $aid . '&reload';
			
			// extract specified file type (file/folder) and file/folder name to create
			$file_type = ($_POST['file_folder'] == 'folder') ? 'folder' : 'file';
			$file_name = trim(strip_tags($admin->strip_slashes($_POST['file_name'])));
			
			if (! $file_name) {
				// file/folder name provided is empty
				$data['afe'] = array_merge($data['afe'], array(
					'STATUS_MESSAGE' => writeStatusMessage($LANG['ADDON_FILE_EDITOR'][7]['TXT_INVALID_FILENAME'], $back_link, false), 
					'CLASS_HIDDEN'   => '',
				));
			
			} else {
				// try to create specified file/folder
					
				if ($file_type == 'file') {
					// extract file extension
					$extension_id = isset($_POST['file_extensions']) ? (int)$_POST['file_extensions'] : 0;
					$extension = (count($text_extensions) >= $extension_id) ? $text_extensions[$extension_id] : 0;
					$file_name = (substr($file_name, -1, 1) == '.') ? ($file_name . $extension) : ($file_name . '.' . $extension);
				}

				$folder_id = (int)$_POST['target_folder'];
				$target_folder = (count($_SESSION['addon_folders']) >= $folder_id) ? $_SESSION['addon_folders'][$folder_id] : '';
				$status = createFileOrFolder($file_type, $target_folder, $file_name);

				// try to create file/folder via FTP if PHP method failed (permissions)
				if (! $status) {
					$ftp_file = $target_folder . $path_sep . $file_name;
					$ftp_file = str_replace(array(WB_PATH . $path_sep, $path_sep), array('', '/'), $ftp_file);
					$ftp = ftpLogin();
					$status = ($file_type == 'file') ? ftpWriteStringToFile($ftp, ' ', $ftp_file) : ftpCreateFolder($ftp, $ftp_file);
				}

				$status_message = ($status) ? $LANG['ADDON_FILE_EDITOR'][7]['TXT_CREATE_SUCCESS'] : $LANG['ADDON_FILE_EDITOR'][7]['TXT_CREATE_ERROR'];
				$data['afe'] = array_merge($data['afe'], array(
					'STATUS_MESSAGE' => writeStatusMessage($status_message, $back_link, $status), 
					'CLASS_HIDDEN'   => ($status) ? 'hidden' : '',
				));
			}
		}

		// ouput the final template
		$tpl->display($data);
		break;

	case 5:
		#####################################################################################
		# upload file
		#####################################################################################
		$strip_path = WB_PATH . $path_sep . $addon_info['type'] . 's' . $path_sep;
		// set template file
		$data['afe'] = array_merge($data['afe'], array(
			'SEL_ENTRIES_TARGET_FOLDER' => createTargetFolderSelectEntries($_SESSION['addon_folders'], $strip_path), 
			'MAX_FILE_SIZE'             => $max_upload_size * 1024 * 1024, 
			'URL_WB_ADMIN_TOOLS'        => ADMIN_URL . '/admintools/index.php', 
			'URL_FORM_SUBMIT'           => $url_action_handler . '?aid=' . $aid . '&amp;action=5', 
			'URL_FORM_CANCEL'           => $url_admintools . '&amp;aid=' . $aid
		));

		// action upload file
		if (isset($_POST['upload_file']) && isset($_POST['target_folder']) && isset($_FILES['file_upload'])) {
			// obtain the target folder
			$folder_id = (int)$_POST['target_folder'];
			$target_folder = (count($_SESSION['addon_folders']) >= $folder_id) ? $_SESSION['addon_folders'][$folder_id] : '';

			$status = false;
			// check if file upload was successfull
			if ($_FILES['file_upload']['error'] == UPLOAD_ERR_OK && $target_folder != '') {
				// extract file information
				$file_infos = pathinfo($_FILES['file_upload']['name']);
				$file_infos['size'] = $_FILES['file_upload']['size'] / (1024 * 1024);

				// only accept file if file size is not exceeded
				if ($file_infos['size'] <= $max_upload_size) {
					// move file to specified target folder
					$new_file = $target_folder . $path_sep . $_FILES['file_upload']['name'];
					$status = @move_uploaded_file($_FILES['file_upload']['tmp_name'], $new_file);

					// move file using FTP if PHP function failed (permissions)
					if (! $status) {
						// move uploaded file to temporary folder
						$temp_file = WB_PATH . $path_sep . 'temp' . $path_sep . md5(uniqid(rand(), true));
						$temp_file .= '_' . $_FILES['file_upload']['name'];
						$status = @move_uploaded_file($_FILES['file_upload']['tmp_name'], $temp_file);
						if ($status) {
							// file moved to temporary folder, use FTP to upload into target folder
							$remote_file = str_replace(array(WB_PATH . $path_sep, $path_sep), array('', '/'), $new_file);
							$mode = in_array(getFileExtension($temp_file), $text_extensions) ? 'ASCII' : 'BIN';
							$ftp = ftpLogin();
							$status = ftpMoveFile($ftp, $temp_file, $remote_file, $mode);
							unlink($temp_file);
						}
					}
				}
			}

			// output a status message
			$back_link = $url_admintools . '&aid=' . $aid . '&reload';
			$status_message = ($status) ? $LANG['ADDON_FILE_EDITOR'][8]['TXT_UPLOAD_SUCCESS'] : $LANG['ADDON_FILE_EDITOR'][8]['TXT_UPLOAD_ERROR'];

			$data['afe'] = array_merge($data['afe'], array(
				'STATUS_MESSAGE' => writeStatusMessage($status_message, $back_link, $status), 
				'CLASS_HIDDEN'   => ($status) ? 'hidden' : '',
			));
		}

		// ouput the final template
		$tpl->display($data);
		break;

	case 6:
		#####################################################################################
		# unzip ZIP file
		#####################################################################################
		$actual_file = $_SESSION['addon_file_infos'][$fid]['path'];
		$strip_path = WB_PATH . $path_sep . $addon_info['type'] . 's' . $path_sep . $addon_info['directory'];
		$addon_file = str_replace(WB_PATH, '', $actual_file);

		$data['afe'] = array_merge($data['afe'], array(
			'ADDON_FILE'       => $addon_file,
			'TARGET_FOLDER'    => dirname($addon_file), 
			'URL_FORM_SUBMIT'  => $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $fid . '&amp;action=6&amp;reload', 
			'URL_FORM_CANCEL'  => $url_admintools . '&amp;aid=' . $aid . '&amp;fid=' . $fid, 
			'CLASS_HIDDEN'     => '', 
		));

		// action unzip archive file to actual folder
		if (isset($_POST['unzip_archive'])) {
			$status = unzipArchive($actual_file);

			$status_message = ($status) ? $LANG['ADDON_FILE_EDITOR'][11]['TXT_UNZIP_SUCCESS'] : $LANG['ADDON_FILE_EDITOR'][11]['TXT_UNZIP_ERROR'];
			$back_link = $url_admintools . '&aid=' . $aid . '&fid=' . $fid . '&reload';

			$data['afe'] = array_merge($data['afe'], array(
				'STATUS_MESSAGE' => writeStatusMessage($status_message, $back_link, $status), 
				'CLASS_HIDDEN'   => 'hidden', 
			));
		}

		// ouput the final template
		$tpl->display($data);
		break;

	default:
		$admin->print_error($LANG['ADDON_FILE_EDITOR'][3]['ERR_WRONG_PARAMETER'], $url_admintools);
		break;
}

// print admin template footer
$admin->print_footer();
