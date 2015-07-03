<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file is contains the functions of the backend
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

// include module configuration and function file
require_once ('code/config.php');
require_once ('code/functions.php');

// load module language file
$lang = $module_path . '/languages/' . LANGUAGE . '.php';
require_once (! file_exists($lang) ? $module_path . '/languages/EN.php' : $lang);

/**
 * Create Twig template object and configure it
 */
// register Twig shipped with AFE if not already done by the WB core (included since WB 2.8.3 #1688)  
if (! class_exists('Twig_Autoloader')) {
	require_once ('thirdparty/Twig/Twig/Autoloader.php');
	Twig_Autoloader::register();
}
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
$twig = new Twig_Environment($loader, array(
	'autoescape'       => false,
	'cache'            => false,
	'strict_variables' => false,
	'debug'            => false,
));
        
/**
 * Show outputs depending on selected display mode
 */
// get valid addon- and file id from $_GET parameter
cleanGetParameters($aid, $fid);

if ($aid == '') {
	#################################################################################
	# CREATE OVERVIEW OF ADDONS WHICH ARE READABLE (MODULES, TEMPLATES, LANGUAGES)
	#################################################################################
	// fetch addon infos to $_SESSION['addon_list'] (installed; PHP readable; not in $hidden_addons)
	getAddons(isset($_GET['reload']));

	// load template file depending on $aid 
	$tpl = $twig->loadTemplate('addons_overview.htt');
	
	/**
	 * Make AFE language data accessible in template {{ lang.KEY }} 
 	 */
	$data = array();
	$data['afe'] = array(
		'URL_HELP_FILE'       => $url_help, 
		'URL_ADMIN_TOOL'      => $url_admintools, 
		'CLASS_SHOW_FTP_INFO' => 'hidden',
		'VERSION'             => $afe_version,
	);

	$LANG['ADDON_FILE_EDITOR'][1]['TXT_FTP_NOTICE'] = str_replace('{URL_ASSISTANT}', $url_ftp_assistant, $LANG['ADDON_FILE_EDITOR'][1]['TXT_FTP_NOTICE']);
	foreach ($LANG['ADDON_FILE_EDITOR'][1] as $key => $value) {
		$data['lang'][$key] = $value;
	}

	/**
	 * Create an overview list of add-ons readable by PHP and not listed in $hidden_addons
	 */
	$data['afe']['SHOW_FTP_INFO'] = false;
	$data['afe']['MODULES'] = array();
	$data['afe']['TEMPLATES'] = array();
	$data['afe']['LANGUAGES'] = array();
	
	foreach ($_SESSION['addon_list'] as $addon_id => $addon) {
		$addon_type = strtoupper($addon['type']);

		// create addon specific variable ($module_counter, $template_counter, $language_counter)
		$addon_var = "{$addon['type']}_counter";
		$$addon_var = (isset($$addon_var)) ? $$addon_var : 0;

		// only show ftp info box if at least one addon folder is not writeable by PHP
		if (! is_writeable($addon['file'])) $data['afe']['SHOW_FTP_INFO'] = true;

		// replace addon specific placeholder
		$data['afe']["{$addon_type}S"][] = array(
			'ADDON_NAME'          => $addon['name'], 
			'CLASS_ODD_EVEN'      => ($$addon_var % 2) ? 'odd ' : '', 
			'CLASS_PERMISSION'    => is_writeable($addon['file']) ? '' : 'permission', 
			'URL_EDIT_ADDON'      => $url_admintools . '&amp;aid=' . $addon_id, 
			'URL_ZIP_ADDON'       => $url_mod_path . '/code/download.php?aid=' . $addon_id, 
			'TXT_ZIP_ADDON_FILES' => $LANG['ADDON_FILE_EDITOR'][1]['TXT_ZIP_' . "{$addon_type}" . '_FILES'], 'URL_ICON_FOLDER' => $url_icon_folder,
			'TXT_EDIT_ADDON_FILE' => $LANG['ADDON_FILE_EDITOR'][1]['TXT_EDIT_' . "{$addon_type}" . '_FILES'] . 
				((is_writeable($addon['file'])) ? '' : $LANG['ADDON_FILE_EDITOR'][1]['TXT_FTP_SUPPORT']), 
		);

		$$addon_var++;
	}

	// ouput the final template
	$tpl->display($data);

} elseif (is_numeric($aid) && isset($_SESSION['addon_list'][$aid])) {
	#################################################################################
	# SHOW FILEMANAGER WITH FILES AND FOLDERS OF THE SPECIFIED ADDON
	#################################################################################
	// load template file depending on $aid 
	$tpl = $twig->loadTemplate('addons_filemanager.htt');
	
	/**
	 * Make AFE language data accessible in template {{ lang.KEY }} 
 	 */
	$data = array();
	foreach ($LANG['ADDON_FILE_EDITOR'][2] as $key => $value) {
		$data['lang'][$key] = $value;
	}

	// include additional language variables used in filemanager
	$data['lang'] = array_merge($data['lang'], array(
		'TXT_HELP'       => $LANG['ADDON_FILE_EDITOR'][1]['TXT_HELP'],
		'TXT_CANCEL'     => $TEXT['CANCEL'], 
		'TXT_FTP_NOTICE' => (str_replace('{URL_ASSISTANT}', $url_ftp_assistant, $LANG['ADDON_FILE_EDITOR'][1]['TXT_FTP_NOTICE'])),
	));
	
	// extract addon main path (e.g. /modules/addon_file_editor)
	$addon_main_path = str_replace(WB_PATH, '', $_SESSION['addon_list'][$aid]['path']);

	// replace additional template placeholder
	$data['afe'] = array(
		'ADDON_NAME'             => $_SESSION['addon_list'][$aid]['name'], 
		'ADDON_PATH'             => $addon_main_path, 
		'URL_HELP_FILE'          => $url_help, 
		'TXT_ADDON_TYPE'         => $LANG['ADDON_FILE_EDITOR'][2]['TXT_' . strtoupper($_SESSION['addon_list'][$aid]['type'])], 
		'URL_ICON_FOLDER'        => $url_icon_folder, 
		'CLASS_HIDDEN'           => ($_SESSION['addon_list'][$aid]['type'] == 'language' && ! isset($_GET['list_all'])) ? 'hidden' : '', 
		'CLASS_SHOW_FTP_INFO'    => 'hidden', 
		'URL_EDIT_ADDON'         => $url_admintools, 'URL_RELOAD' => $url_admintools . '&amp;aid=' . $aid . '&amp;reload', 
		'URL_CREATE_FILE_FOLDER' => $url_action_handler . '?aid=' . $aid . '&amp;action=4', 
		'URL_UPLOAD_FILE'        => $url_action_handler . '?aid=' . $aid . '&amp;action=5', 
		'VERSION'                => $afe_version,
	);

	// fetch file infos of actual add-on to $_SESSION['addon_file_infos']
	getAddonFileInfos($_SESSION['addon_list'][$aid]['path'], $aid, isset($_GET['reload']));

	// output current addon file infos
	$data['afe']['SHOW_FTP_INFO'] = false;
	$data['afe']['FILES'] = array();
	foreach ($_SESSION['addon_file_infos'] as $index => $file_info) {
		// skip the very first entry which contains current addon root folder
		if ($index == 0) continue;

		// if we process the WB language folder, only show the required language file
		if ($_SESSION['addon_list'][$aid]['type'] == 'language' && ! isset($_GET['list_all'])) {
			if ($file_info['path'] != $_SESSION['addon_list'][$aid]['file']) continue;
		}

		// work out displayed file or folder name part
		if ($file_info['type'] == 'folder') {
			// extract sub path to current folder (e.g. /modules/anynews/htt/icons/ --> /htt/icons)
			$file_name = str_replace($_SESSION['addon_list'][$aid]['path'], '', $file_info['path']);
		} else {
			// extract file name (e.g. test.php from full path)
			$file_name = basename($file_info['path']);
		}

		// only show ftp info box if at least one addon folder is not writeable by PHP
		if (! is_writeable($file_info['path'])) $data['afe']['SHOW_FTP_INFO'] = true;

		// create a link for all textfiles and images
		$icon_edit_url = '-';
		switch ($file_info['icon']) {
			case 'textfile':
				$url = $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $index . '&amp;action=1';
				// make file name clickable (edit text file)
				$file_name = '<a href="' . $url . '" title="' . $LANG['ADDON_FILE_EDITOR'][2]['TXT_EDIT'] . '">' . $file_name . '</a>';
				$icon_edit_url = $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $index . '&amp;action=1';
				break;

			case 'image':
				// build URL to the image file
				$url = str_replace(WB_PATH, '', $file_info['path']);
				$url = WB_URL . str_replace($path_sep, '/', $url);

				// create link to open image in new browser window
				$file_name = '<a href="' . $url . '" target="_blank" title="' . $LANG['ADDON_FILE_EDITOR'][2]['TXT_VIEW'] . '">' . $file_name . '</a>';

				// check if PIXLR Support is enabled
				if ($pixlr_support == true && (strpos(WB_URL, '/localhost/') == false)) {
					// open image with the online Flash image editor http://pixlr.com/
					$icon_edit_url = createPixlrURL($url, $file_info['path'], true);
				}
				break;
		}

		// replace placeholders with dynamic file information
		$data['afe']['FILES'][] = array(
			'FILE_NAME'        => $file_name, 
			'FILE_SIZE'        => ($file_info['size'] == '') ? '&nbsp;' : $file_info['size'], 
			'FILE_MAKE_TIME'   => $file_info['maketime'], 
			'CLASS_ODD_EVEN'   => ($index % 2 == 0) ? 'odd ' : '', 
			'CLASS_FOLDER'     => ($file_info['type'] == 'folder') ? 'folder ' : '', 
			'CLASS_PERMISSION' => is_writeable($file_info['path']) ? '' : 'permission', 
			'URL_ICON_FOLDER'  => $url_icon_folder, 
			'FILE_ICON'        => $file_info['icon'], 
			'TXT_FILE_TYPE'    => $LANG['ADDON_FILE_EDITOR'][2]['TXT_FILE_TYPE_' . strtoupper($file_info['icon'])], 
			'HIDE_EDIT_ICON'   => ($icon_edit_url == '-') ? 'hidden' : '', 
			'HIDE_UNZIP_ICON'  => ($unzip_archive_support && substr(strtolower($file_name), -4) == '.zip') ? '' : 'hidden',  
			'TARGET_BLANK'     => ($icon_edit_url <> '-' && $file_info['icon'] == 'image') ? ' target="_blank"' : '', 
			'URL_EDIT_FILE'    => $icon_edit_url, 
			'URL_RENAME_FILE'  => $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $index .'&amp;action=2', 
			'URL_DELETE_FILE'  => $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $index . '&amp;action=3',
			'URL_UNZIP_FILE'   => ($unzip_archive_support) ? $url_action_handler . '?aid=' . $aid . '&amp;fid=' . $index .'&amp;action=6' : '', 
			'TXT_EDIT'         => ($icon_edit_url <> '-' && $file_info['icon'] == 'image') 
                                  ? ($LANG['ADDON_FILE_EDITOR'][2]['TXT_EDIT'] . ' (Online: PIXLR)')
                                  : $LANG['ADDON_FILE_EDITOR'][2]['TXT_EDIT'], 
		);

		$index++;
	}

	// ouput the final template
	$tpl->display($data);

} else {
	#################################################################################
	# FILEMANAGER NOT PROPERLY INITIALIZED - REDIRECT TO ADDON OVERVIEW PAGE
	#################################################################################
	$admin->print_error($LANG['ADDON_FILE_EDITOR'][3]['ERR_WRONG_PARAMETER'], $url_admintools);
}