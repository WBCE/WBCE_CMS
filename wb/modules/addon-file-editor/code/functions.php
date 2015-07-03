<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file is contains the module specific routines
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     cwsoft-addon-file-editor
 * @author      cwsoft (http://cwsoft.de)
 * @copyright   cwsoft
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */

// prevent this file from being accessed directly
if (defined('WB_PATH') == false) {
	exit("Cannot access this file directly");
}

/**
 * FTP LAYER ROUTINES
 */
function readFtpSettings()
{
	global $admin, $database;

	// fetch FTP settings from database
	$table = TABLE_PREFIX . 'mod_cwsoft-addon-file-editor';
	$sql = "SELECT * FROM `$table`";
	$results = $database->query($sql);
	return ($results && $row = $results->fetchRow()) ? $row : false;
}

function updateFtpSettings($post_array)
{
	global $admin, $database;

	// define POST keys to be cleaned
	$keys = array('ftp_enabled' => 0, 'ftp_server' => '-', 'ftp_user' => '-', 'ftp_password' => '', 'ftp_port' => '21', 'ftp_start_dir' => '/');

	// clean POST values before writting into database
	foreach ($keys as $key => $default) {
		${$key} = (isset($post_array[$key])) ? $admin->add_slashes(trim(strip_tags($post_array[$key]))) : $default;
		${$key} = (${$key} == '') ? $default : ${$key};
	}

	// now loop over update values and create the SQL query string (this way we do not forget values)
	$sql_key_values = '';
	foreach ($keys as $key => $default) {
		$sql_key_values .= (($sql_key_values) ? ', ' : '') . "`$key` = '" . ${$key} . "'";
	}

	// write page settings to the module table
	$table = TABLE_PREFIX . 'mod_cwsoft-addon-file-editor';
	$sql = "UPDATE `$table` SET $sql_key_values WHERE `id` = '1'";
	return $database->query($sql);
}

function ftpLogin()
{
	// fetch FTP settings from database
	$ftp_settings = readFtpSettings();

	// do nothing, if FTP support is disabled
	if (! (isset($ftp_settings['ftp_enabled']) && $ftp_settings['ftp_enabled'] == 1)) return false;

	// check if ftp settings are defined and not empty
	$keys = array('ftp_server', 'ftp_user', 'ftp_password', 'ftp_port', 'ftp_start_dir');
	foreach ($keys as $key) {
		if (! isset($ftp_settings[$key]) || (isset($ftp_settings[$key]) && $ftp_settings[$key] == '-')) return false;
	}

	// connect with FTP server
	$ftp_stream = @ftp_connect($ftp_settings['ftp_server'], $ftp_settings['ftp_port'], 15);

	// login to FTP server
	$status = @ftp_login($ftp_stream, $ftp_settings['ftp_user'], $ftp_settings['ftp_password']);

	// check if login was successfull
	$ftp = ($ftp_stream !== false && $status) ? $ftp_stream : false;

	// set passive mode
	$status = ($ftp) ? @ftp_pasv($ftp_stream, true) : false;

	// change to specified start folder
	$status = ($ftp) ? @ftp_chdir($ftp, $ftp_settings['ftp_start_dir']) : false;

	return ($status) ? $ftp : false;
}

function ftpReadStringFromFile($ftp_stream, $remote_file)
{
	global $path_sep;

	// check if ftp stream is specified and WB temporary folder is writeable
	$temp_folder = WB_PATH . $path_sep . 'temp' . $path_sep;
	if (! is_resource($ftp_stream) || ! is_writeable($temp_folder)) return false;

	// create unique temporary file in WB temporary folder
	$temp_file = $temp_folder . md5(uniqid(rand(), true)) . '.txt';
	$handle = fopen($temp_file, 'w');
	if ($handle) fclose($handle);

	// download remote file with FTP and extract file contents
	$status = @ftp_get($ftp_stream, $temp_file, $remote_file, FTP_ASCII);

	// fetch content of downloaded file
	$content = ($status && is_readable($temp_file)) ? file_get_contents($temp_file) : false;

	// delete the temporary file
	unlink($temp_file);

	return $content;
}

function ftpWriteStringToFile($ftp_stream, $content, $remote_file)
{
	global $path_sep;

	// check if ftp stream is specified and WB temporary folder is writeable
	$temp_folder = WB_PATH . $path_sep . 'temp' . $path_sep;
	if (! is_resource($ftp_stream) || ! is_writeable($temp_folder)) return false;

	// save content to a temporary file (our local ftp file)
	$temp_file = $temp_folder . md5(uniqid(rand(), true)) . '.txt';
	$handle = fopen($temp_file, 'w');
	$status = ($handle) ? fwrite($handle, $content) : false;
	if ($handle) fclose($handle);

	// upload local file by the use of FTP
	$status = ($status) ? @ftp_put($ftp_stream, $remote_file, $temp_file, FTP_ASCII) : false;

	// delete the temporary file
	if (file_exists($temp_file)) unlink($temp_file);

	return $status;
}

function ftpRenameFile($ftp_stream, $old_file, $new_file)
{
	if (! is_resource($ftp_stream)) return false;
	return @ftp_rename($ftp_stream, $old_file, $new_file);
}

function ftpDeleteFile($ftp_stream, $remote_file)
{
	if (! is_resource($ftp_stream)) return false;
	return @ftp_delete($ftp_stream, $remote_file);
}

function ftpDeleteFolder($ftp_stream, $remote_folder)
{
	if (! is_resource($ftp_stream)) return false;
	return @ftp_rmdir($ftp_stream, $remote_folder);
}

function ftpCreateFolder($ftp_stream, $remote_folder)
{
	if (! is_resource($ftp_stream)) return false;
	return @ftp_mkdir($ftp_stream, $remote_folder);
}

function ftpMoveFile($ftp_stream, $local_file, $remote_file, $mode = 'ASCII')
{
	if (! is_resource($ftp_stream)) return false;
	$mode = ($mode == 'ASCII') ? FTP_ASCII : FTP_BINARY;
	return ftp_put($ftp_stream, $remote_file, $local_file, $mode);
}

/**
 * FILEMANAGER ROUTINES
 */
function getAddons($force_reload = false)
{
	global $table, $database, $path_sep, $hidden_addons;

	if (! $force_reload && isset($_SESSION['addon_list']) && is_array($_SESSION['addon_list'])) return;

	// fetch arrays from database
	$sql = "SELECT * FROM `$table` ORDER BY `name` ASC";
	$results = $database->query($sql);

	$_SESSION['addon_list'] = array();
	while ($results && $row = $results->fetchRow()) {
		// set addon type depending variables
		$addon_folder = WB_PATH . $path_sep . $row['type'] . 's' . $path_sep . $row['directory'];

		// only show addons which are readable by PHP and not protected
		$addon_file = $addon_folder . (($row['type'] == 'language') ? '.php' : $path_sep . 'index.php');
		if (! (is_readable($addon_file) && ! in_array(strtolower($row['directory']), $hidden_addons))) continue;

		// add add-on information to SESSION variable
		$aid = (int)$row['addon_id'];
		$_SESSION['addon_list'][$aid] = array('name' => ucfirst($row['name']), 'type' => $row['type'], 'path' => dirname($addon_file), 'file' => $addon_file);
	}
}

function getAddonSubFolders($addon_path)
{
	global $path_sep;

	$subfolder_list = array();
	if (is_dir($addon_path) && $handle = opendir($addon_path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				if (is_dir($addon_path . $path_sep . $file)) {
					// add actual folder to array
					$subfolder_list[] = $addon_path . $path_sep . $file;

					// check if actual folder contains subfolders
					$subfolder_list = array_merge($subfolder_list, getAddonSubFolders($addon_path . $path_sep . $file));
				}
			}
		}
		closedir($handle);
	}
	return $subfolder_list;
}

function getAddonFileInfos($addon_path, $addon_id, $force_reload = false)
{
	global $path_sep, $show_all_files, $text_extensions, $image_extensions, $archive_extensions;

	// check if addon files were already extracted and are still valid
	if (! $force_reload && isset($_SESSION['addon_id']) && ($_SESSION['addon_id'] === $addon_id) && isset($_SESSION['addon_file_infos']) && is_array($_SESSION['addon_file_infos'])) return;

	// unset addon file infos if exists
	unset($_SESSION['addon_id']);
	unset($_SESSION['addon_file_infos']);
	unset($_SESSION['addon_folders']);

	// create array with supported file extensions
	$registered_extensions = (! $show_all_files) ? array_merge($text_extensions, $image_extensions, $archive_extensions) : '';

	// fetch all subfolders within the specified addon path
	$addon_folders = getAddonSubFolders($addon_path);

	// add the addon root path to the beginning of the array
	array_unshift($addon_folders, $addon_path);

	// sort addon folders in human order
	natsort($addon_folders);

	// loop over the specified folders and extract the files contained
	$file_infos = array();
	foreach ($addon_folders as $folder) {
		// add folder information to file info list
		$file_infos[] = array('type' => 'folder', 'path' => $folder, 'extension' => '', 'icon' => 'folder', 'size' => '', 'maketime' => getHumanReadableFileMakeTime($folder), );

		// extract all files in the actual directory
		$files = array();
		
		// skip empty folders
		$files_in_folder = glob($folder . $path_sep . '*.*', GLOB_ERR);
		if (! is_array($files_in_folder)) continue;
		
		// loop over all files contained in actual folder
		foreach ($files_in_folder as $file) {
			$file_extension = getFileExtension($file);
			// skip files which are not supported if option is enabled
			if (! $show_all_files && ! in_array($file_extension, $registered_extensions)) continue;
			$file_infos[] = array('type' => 'file', 'path' => $file, 'extension' => $file_extension, 'icon' => getFileIconByExtension($file, $file_extension), 'size' => getHumanReadableFileSize($file), 'maketime' => getHumanReadableFileMakeTime($file), );
		}
	
	}

	// store current addon file infos in session variable
	$_SESSION['addon_id'] = $addon_id;
	$_SESSION['addon_file_infos'] = $file_infos;
	$_SESSION['addon_folders'] = $addon_folders;

	return true;
}

function getHumanReadableFileSize($file)
{
	// extract file size and convert into human readable format (KB, or MB no digits)
	$file_size = ceil(filesize($file) / 1024);
	$file_size = ($file_size > 1024) ? ceil($file_size / 1024) . ' MB' : $file_size . ' KB';
	return $file_size;
}

function getHumanReadableFileMakeTime($file)
{
	global $LANG;

	// extract file change time and convert into human readable format
	// used @date to suppress PHP warnings (PHP 5.x if no default time zone is set in php.ini)
	return @date($LANG['ADDON_FILE_EDITOR'][2]['DATE_FORMAT'], filemtime($file));
}

function getFileExtension($file)
{
	if (is_dir($file)) return '';

	// extract file extension and return
	$file_info = pathinfo($file);
	return $file_info['extension'];

}

function getFileIconByExtension($file, $extension)
{
	global $text_extensions, $image_extensions, $archive_extensions;

	if (in_array($extension, $text_extensions)) return 'textfile';
	if (in_array($extension, $image_extensions)) return 'image';
	if (in_array($extension, $archive_extensions)) return 'archive';
	return 'other';
}

function removeFileOrFolder($path)
{
	global $path_sep;
	if (is_dir($path) && is_readable($path)) {

		$entries = array();
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) $entries[] = $file;
			closedir($handle);
		}

		foreach ($entries as $entry) {
			if ($entry != '.' && $entry != '..') {
				removeFileOrFolder($path . $path_sep . $entry);
			}
		}
		return @rmdir($path);
	} else {
		return @unlink($path);
	}
}

function renameFileOrFolder($path_old, $new_name)
{
	global $path_sep;

	// make sure file/folder is writeable and new name not empty
	if (! is_writeable($path_old) || $new_name == '') return false;

	if (is_dir($path_old)) {
		// build new folder and check if already exists
		$path_new = dirname($path_old) . $path_sep . $new_name;
		if (file_exists($path_new)) return false;

	} else {
		// extract file extension
		$file_info = pathinfo($path_old);
		// build new file and check if already exists
		$path_new = dirname($path_old) . $path_sep . $new_name . '.' . $file_info['extension'];
		if (file_exists($path_new)) return false;
	}

	// rename file or folder
	return rename($path_old, $path_new);
}

function createFileOrFolder($type, $base_path, $file_name)
{
	global $path_sep;
	if (! is_writeable($base_path)) return false;

	if (strtolower($type) == 'file') {
		// create a new file
		$handle = fopen($base_path . $path_sep . $file_name, 'w');
		if ($handle) fclose($handle);
		return $handle;

	} else {
		// create a new folder
		return mkdir($base_path . $path_sep . $file_name);
	}
}

function unzipArchive($archive_file) {
	global $unzip_archive_support;
	
	// only proceed if unzip archiv support is enabled
	if (! $unzip_archive_support) return false;
	
	// ensure archive_file is a valid *.zip file and target folder is writeable
	if (! is_file($archive_file)) return false;
	if (substr(strtolower($archive_file), -4) != '.zip') return false;   
	
	// ensure target folder is writeable
	$target_folder = dirname($archive_file);
	if (! is_writeable($target_folder)) return false;
	
	// unzip archive file to given target folder
	require_once (WB_PATH . '/include/pclzip/pclzip.lib.php');
	$archive = new PclZip($archive_file);
	$result = $archive->extract(PCLZIP_OPT_PATH, $target_folder);	
	return $result;
}

/**
 * OTHER MODULE ROUTINES
 */
function createSelectEntries($entries)
{
	$output = '';
	foreach ($entries as $index => $entry) {
		$output .= '<option value="' . (int)$index . '">' . $entry . '</option>';
	}
	return $output;
}

function createTargetFolderSelectEntries($folders, $strip_path)
{
	global $path_sep;

	// build options for actual select field
	if (! is_array($folders)) return;

	$addon_path = array();
	foreach ($folders as $index => $folder) {
		$addon_path[$index] = str_replace($strip_path, '', $folder);
	}
	return createSelectEntries($addon_path);
}

function cleanGetParameters(&$aid, &$fid)
{
	global $admin, $database, $table, $path_sep, $text_extensions;
	// force defined values for all variables
	$aid = '';
	$fid = '';

	// create valid addon id and file id from $_GET parameters
	$aid = (isset($_GET['aid']) && is_numeric($_GET['aid'])) ? (int)$_GET['aid'] : '';
	$fid = (isset($_GET['fid']) && is_numeric($_GET['fid'])) ? (int)$_GET['fid'] : '';

	// check if addon id is found in the database
	$sql = "SELECT `type`, `directory` FROM `$table` WHERE `addon_id` = '$aid'";
	$results = $database->query($sql);
	if (! ($results && $results->numRows() > 0)) {
		// specified addon_id invalid, exit
		$aid = '';
		$fid = '';
		return;
	}

	// check if file id is in allowed range
	if ($fid == '' || ! (isset($_SESSION['addon_file_infos']) && count($_SESSION['addon_file_infos']) >= $fid)) {
		// addon file infos not yet stored in SESSION variable, exit
		$fid = '';
		return;
	}

	// check if specified file is read- and writeable and
	if (! $_SESSION['addon_file_infos'][$fid]['icon'] == 'textfile' || ! is_readable($_SESSION['addon_file_infos'][$fid]['path']) || ! is_writeable($_SESSION['addon_file_infos'][$fid]['path'])) {
		// file is not a textfile or not read/writeable
		$fid = '';
		return;
	}
}

function myAdminHandler($addon_id_dir, $section_name, $section_permission = 'start', $auto_header = true, $auto_auth = true)
{
	// class to include module backend.css or backend.js into <head> section
	global $path_sep;

	// check if the specified addon exists in WB database
	$addon_data = getAddonInfos($addon_id_dir);
	if (! $addon_data) return new admin($section_name, $section_permission, $auto_header, $auto_auth);

	// create full path to specified addon
	$addon_path = WB_PATH . $path_sep . $addon_data['type'] . 's' . $path_sep . $addon_data['directory'] . $path_sep;
	$addon_url = WB_URL . '/' . $addon_data['type'] . 's/' . $addon_data['directory'] . '/';

	// check if specified addon contains a backend.css or backend.js file to include
	$backend_css = (is_readable($addon_path . 'backend.css')) ? $addon_url . 'backend.css' : '';
	$backend_js = (is_readable($addon_path . 'backend.js')) ? $addon_url . 'backend.js' : '';
	if ($backend_css == '' && $backend_js == '') return new admin($section_name, $section_permission, $auto_header, $auto_auth);

	// store output created by admin class in variable
	ob_start();
	$admin = new admin('Admintools', 'admintools', true, false);
	$output = ob_get_contents();
	ob_end_clean();

	// include Edit Area framework manually to fix corrupt wb_wrapper_edit_area.php (WB 2.8 to 2.8.1 RC3)
	$edit_area_js = '';
	if (isset($_GET['action']) && $_GET['action'] == 1 && strpos($output, 'edit_area_full.js') === false && file_exists(WB_PATH . '/include/editarea/edit_area_full.js')) {
		$edit_area_js .= '<script type="text/javascript" src="' . WB_URL . '/include/editarea/edit_area_full.js"></script>' . "\n";
	}

	// check if a </head> tag is available
	$inject_start = strpos($output, '</head>');
	if ($inject_start !== false) {
		$css_link = ($backend_css == '') ? '' : '<link href="' . $backend_css . '" rel="stylesheet" type="text/css" media="screen" />';
		$js_link = ($backend_js == '') ? '' : '<script type="text/javascript" src="' . $backend_js . '"></script>';
		$output_new = substr($output, 0, $inject_start - 1);
		$output_new .= "\n" . $css_link . "\n" . $js_link . $edit_area_js;
		$output_new .= strstr($output, '</head>');
		echo $output_new;
	} else {
		echo $output;
	}
	return $admin;
}

function getAddonInfos($addon_id_dir)
{
	global $table, $database;

	if (is_numeric($addon_id_dir)) {
		$sql = "SELECT * FROM `$table` WHERE `addon_id` = '" . (int)$addon_id_dir . "'";
	} else {
		$sql = "SELECT * FROM `$table` WHERE `directory` = '" . mysql_real_escape_string(strip_tags($addon_id_dir)) . "'";
	}

	$results = $database->query($sql);
	if ($results && $row = $results->fetchRow()) return $row;
	return false;
}

function writeStatusMessage($message, $back_url = '', $success = true, $auto_redirect = true, $redirect_timer = 1500)
{
	global $TEXT;
	$output = '<div class="' . (($success) ? 'success' : 'error') . '">' . $message . '</div>';

	// work out back link and javascript timer for sucess messages
	if ($back_url != '') {
		$back_link = '<a class="backlink" href="' . $back_url . '">' . $TEXT['BACK'] . '</a>';
		$timer = '<script language="javascript" type="text/javascript">setTimeout("location.href=\'';
		$timer .= $back_url . '\'", ' . (int)$redirect_timer . ');</script>';
	}

	if ($auto_redirect && $success) {
		return $timer . $output . $back_link;
	} else {
		return $output . $back_link;
	}
}

function getAFEVersion() {
	// returns the actual installed AFE version (vX.Y.Z)
	require_once(dirname(__FILE__) . '/../info.php');
	return 'v' . $module_version;
}

function getReadmeUrl($afe_version) {
	// returns the Url to the GitHub README for the installed AFE version
	$url = 'https://github.com/cwsoft/wb-cwsoft-addon-file-editor/#readme';
	
	if (preg_match('#(v\d*\.\d*\.\d*)(.*)#i', $afe_version, $match)) {
		// only stable versions (vX.Y.Z) are tagged at GitHub
		if (! $match[2]) {
			$url = 'https://github.com/cwsoft/wb-cwsoft-addon-file-editor/tree/' . $match[1] . '#readme';
		}
	}
	return $url;
}

/**
 * PIXLR API ROUTINES 
 * Routines for the online image editor service http://www.pixlr.com
 */
function createPixlrURL($img_url, $img_file, $url_only = true)
{
	// create URL for the PIXLR.COM API
	global $url_mod_path;

	// languages supported by pixlr.com
	$pixlr_loc = array(
		'EN' => 'en',
		'DE' => 'de',
		'ES' => 'es',
		'FR' => 'fr',
		'IT' => 'it',
		'PL' => 'pl',
		'PT' => 'pt-br',
		'RU' => 'ru',
		'TR' => 'tr'
	);

	$file_info = pathinfo($img_file);

	# create Pixlr API link
	$pixlr_url  = 'http://www.pixlr.com/editor/' . '?image=' . $img_url;
	$pixlr_url .= '&amp;title=' . str_replace($file_info['extension'], '', $file_info['basename']) . 'pixlr.' . $file_info['extension'];
	$pixlr_url .= '&amp;method=GET' . '&amp;loc=' . (key_exists(LANGUAGE, $pixlr_loc) ? $pixlr_loc[LANGUAGE] : 'en');
	$pixlr_url .= '&amp;exit=' . WB_URL;
	$pixlr_url .= '&amp;referrer=' . WB_URL;
	$pixlr_url .= '&amp;target=' . urlencode($url_mod_path . '/code/get_pixlr_image.php?img_path=' . str_replace(WB_PATH, '', $img_file));
	
	if ($url_only == true) return $pixlr_url;

	return '<a href="' . $pixlr_url . '" target="_blank" title="edit with pixlr.com">' . basename($img_file) . '</a>';
}

/**
 * REPLACEMENT FOR THE WB EDIT AREA WRAPPER (include/editarea/wb_wrapper_edit_area.php)  
 */
function myRegisterEditArea($syntax = 'php')
{
	// make sure edit area framework exists (loaded via myAdminWrapper)
	if (! file_exists(WB_PATH . '/include/editarea/edit_area_full.js')) return;

	// check if highlight syntax is supported by edit_area
	$syntax = in_array($syntax, array('css', 'html', 'js', 'php', 'xml')) ? $syntax : 'php';

	// work out language file to include
	$language = 'en';
	if (defined('LANGUAGE') && file_exists(WB_PATH . '/include/editarea/langs/' . basename(strtolower(LANGUAGE)) . '.js')) {
		$language = strtolower(LANGUAGE);
	}

	// return Javascript code
	$result = <<< EOT
	<script type="text/javascript">
		editAreaLoader.init({
			id:					'code_area',
			start_highlight:	true,
			syntax:				'$syntax',
			min_width:			650,
			min_height:			500,
			allow_resize:		'both',
			allow_toggle:		true,
			toolbar:			'search, fullscreen, |, undo, redo, |, select_font, syntax_selection, |, highlight, reset_highlight, |, help',
			language:			'$language'
		});
	</script>
EOT;
	return $result;
}

function myGetEditAreaSyntax($file)
{
	// returns the highlight scheme for edit_area
	$syntax = 'php';
	if (is_readable($file)) {
		// extract file extension
		$file_info = pathinfo($file);

		switch ($file_info['extension']) {
			case 'htm':
			case 'html':
			case 'htt':
				$syntax = 'html';
				break;

			case 'css':
				$syntax = 'css';
				break;

			case 'js':
				$syntax = 'js';
				break;

			case 'xml':
				$syntax = 'xml';
				break;

			case 'php':
			case 'php3':
			case 'php4':
			case 'php5':
				$syntax = 'php';
				break;

			default:
				$syntax = 'php';
				break;
		}
	}
	return $syntax;
}