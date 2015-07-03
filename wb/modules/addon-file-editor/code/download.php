<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file creates a ZIP archive of a specified Addon folder
 * on the fly and sends the ZIP archive for download to the browser.
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

// create new instance this time showing the admin panel (no headers possible anymore)
ob_start();
$admin = new admin('Admintools', 'admintools', true, false);

// ensure that user specified addon id is valid (if not redirect user)
if (! (isset($_GET['aid']) && is_numeric($_GET['aid']) && getAddonInfos($_GET['aid']))) $admin->print_error($LANG['ADDON_FILE_EDITOR'][3]['ERR_WRONG_PARAMETER'], $url_admintools);

/**
 * Remove old zip files and create new one
 */
removeFileOrFolder($temp_zip_path);
@mkdir($temp_zip_path);

// check permissions of temporary folder
if (! is_writeable($temp_zip_path)) {
	ob_end_flush();
	$admin->print_error($LANG['ADDON_FILE_EDITOR'][9]['ERR_TEMP_PERMISSION'], $url_admintools);
}

/**
 * Create a ZIP archive for the specified add-on
 */
$info = getAddonInfos($_GET['aid']);

if ($info['type'] == 'language') {
	$addon_path = WB_PATH . $path_sep . $info['type'] . 's' . $path_sep . $info['directory'] . '.php';

	$path_to_download_file = WB_PATH . $path_sep . 'languages' . $path_sep . $info['directory'] . '.php';
	$content_type = 'application/text';
	$download_file_name = $info['directory'] . '.txt';

} else {
	$addon_path = WB_PATH . $path_sep . $info['type'] . 's' . $path_sep . $info['directory'] . $path_sep;

	// create a zip archive using the PclZip class shipped with Website Baker
	require_once (WB_PATH . '/include/pclzip/pclzip.lib.php');
	$archive = new PclZip($temp_zip_path . $info['directory'] . '.zip');

	$list = $archive->create($addon_path, PCLZIP_OPT_REMOVE_PATH, $addon_path);
	if ($list == 0) {
		ob_end_flush();
		$admin->print_error($LANG['ADDON_FILE_EDITOR'][9]['ERR_ZIP_CREATION'], $url_admintools);
	}

	$path_to_download_file = $temp_zip_path . $info['directory'] . '.zip';
	$content_type = 'application/zip';
	$download_file_name = $info['directory'] . '.zip';
}

/**
 * Send the add-on backup to the browser using PEAR Download class
 */
ob_end_clean();
require ($module_path . '/thirdparty/PEAR/Download.php');
$dl = new HTTP_Download();
$dl->setContentType($content_type);
$dl->setFile($path_to_download_file);
$dl->setContentDisposition(HTTP_DOWNLOAD_ATTACHMENT, $download_file_name);
$status = $dl->send();
if (PEAR::isError($status)) {
	$url_download_file = str_replace(array(WB_PATH, $path_sep), array(WB_URL, '/'), $path_to_download_file);
	$admin = new admin('Admintools', 'admintools', true, false);
	$admin->print_error(str_replace('{URL}', $url_download_file, $LANG['ADDON_FILE_EDITOR'][9]['ERR_ZIP_DOWNLOAD']), $url_admintools);
}
