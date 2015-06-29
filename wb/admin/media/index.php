<?php
/**
 *
 * @category        admin
 * @package         admintools
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: index.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource		$HeadURL:  $
 * @lastmodified    $Date:  $
 *
 */

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Media', 'media');

$starttime = explode(" ", microtime());
$starttime = $starttime[0]+$starttime[1];
include ('parameters.php');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('media.htt')));
$template->set_file('page', 'media.htt');
$template->set_block('page', 'main_block', 'main');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Get home folder not to show
$home_folders = get_home_folders();

// Insert values
$template->set_block('main_block', 'dir_list_block', 'dir_list');
$dirs = directory_list(WB_PATH.MEDIA_DIRECTORY);
$currentHome = $admin->get_home_folder();

if ($currentHome){
	$dirs = directory_list(WB_PATH.MEDIA_DIRECTORY.$currentHome);
}
else
{
	$dirs = directory_list(WB_PATH.MEDIA_DIRECTORY);
}
$array_lowercase = array_map('strtolower', $dirs);
array_multisort($array_lowercase, SORT_ASC, SORT_STRING, $dirs);
foreach($dirs AS $name) {
	if(!isset($home_folders[str_replace(WB_PATH.MEDIA_DIRECTORY, '', $name)])) {
		$template->set_var('NAME', str_replace(WB_PATH, '', $name));
		$template->parse('dir_list', 'dir_list_block', true);
	}
}

// Insert permissions values
if($admin->get_permission('media_create') != true) {
	$template->set_var('DISPLAY_CREATE', 'hide');
}
if($admin->get_permission('media_upload') != true) {
	$template->set_var('DISPLAY_UPLOAD', 'hide');
}
if ($_SESSION['GROUP_ID'] != 1 && $pathsettings['global']['admin_only']) { // Only show admin the settings link
	$template->set_var('DISPLAY_SETTINGS', 'hide');
}
// Workout if the up arrow should be shown
if(($dirs == '') or ($dirs==$currentHome) or (!array_key_exists('dir', $_GET))) {
	$display_up_arrow = 'hide';
} else {
	$display_up_arrow = '';
}

// Insert language headings
$template->set_var(array(
					'HEADING_BROWSE_MEDIA' => $HEADING['BROWSE_MEDIA'],
					'HOME_DIRECTORY' => $currentHome,
					'DISPLAY_UP_ARROW' => $display_up_arrow, // **!
					'HEADING_CREATE_FOLDER' => $HEADING['CREATE_FOLDER'],
					'HEADING_UPLOAD_FILES' => $HEADING['UPLOAD_FILES']
				)
			);
// insert urls
$template->set_var(array(
					'ADMIN_URL' => ADMIN_URL,
					'WB_URL' => WB_URL,
					'WB_PATH' => WB_PATH,
					'THEME_URL' => THEME_URL
				)
			);
// Insert language text and messages
$template->set_var(array(
					'MEDIA_DIRECTORY' => MEDIA_DIRECTORY,
					'TEXT_NAME' => $TEXT['TITLE'],
					'TEXT_RELOAD' => $TEXT['RELOAD'],
					'TEXT_TARGET_FOLDER' => $TEXT['TARGET_FOLDER'],
					'TEXT_OVERWRITE_EXISTING' => $TEXT['OVERWRITE_EXISTING'],
					'TEXT_FILES' => $TEXT['FILES'],
					'TEXT_CREATE_FOLDER' => $TEXT['CREATE_FOLDER'],
					'TEXT_UPLOAD_FILES' => $TEXT['UPLOAD_FILES'],
					'CHANGE_SETTINGS' => $TEXT['MODIFY_SETTINGS'],
					'OPTIONS' => $TEXT['OPTION'],
					'TEXT_UNZIP_FILE' => $TEXT['UNZIP_FILE'],
					'TEXT_DELETE_ZIP' => $TEXT['DELETE_ZIP'],
					'FTAN' => $admin->getFTAN()
				)
			);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');
/*
$endtime=explode(" ", microtime());
$endtime=$endtime[0]+$endtime[1];
$debugVMsg = '';
if($admin->ami_group_member('1') ) {
	$debugVMsg  = "<p>Mask loaded in ".round($endtime - $starttime,6)." Sec,&nbsp;&nbsp;";
	$debugVMsg .= "Memory in use ".number_format(memory_get_usage(true), 0, ',', '.')."&nbsp;Byte,&nbsp;&nbsp;";
	$debugVMsg .= sizeof(get_included_files())."&nbsp;included files</p>";
	// $debugVMsg = print_message($debugVMsg,'#','debug',-1,false);
	print $debugVMsg.'<br />';
 }
*/
// Print admin
$admin->print_footer();
