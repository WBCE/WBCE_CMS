<?php
/**
 *
 * @category        admin
 * @package         groups
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: get_permissions.php 1529 2011-11-25 05:03:32Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/groups/get_permissions.php $
 * @lastmodified    $Date: 2011-11-25 06:03:32 +0100 (Fr, 25. Nov 2011) $
 *
 */


if(!defined('WB_PATH')) { exit('Direct access to this file is not allowed'); }

// Get system permissions
if($admin->get_post('advanced') != 'yes') {
	$system_permissions['pages'] = $admin->get_post('pages');
		$system_permissions['pages_view'] = $system_permissions['pages'];
		$system_permissions['pages_add'] = $system_permissions['pages'];
		$system_permissions['pages_add_l0'] = $system_permissions['pages'];
		$system_permissions['pages_settings'] = $system_permissions['pages'];
		$system_permissions['pages_modify'] = $system_permissions['pages'];
		$system_permissions['pages_intro'] = $system_permissions['pages'];
		$system_permissions['pages_delete'] = $system_permissions['pages'];
	$system_permissions['media'] = $admin->get_post('media');
		$system_permissions['media_view'] = $system_permissions['media'];
		$system_permissions['media_upload'] = $system_permissions['media'];
		$system_permissions['media_rename'] = $system_permissions['media'];
		$system_permissions['media_delete'] = $system_permissions['media'];
		$system_permissions['media_create'] = $system_permissions['media'];
	if($admin->get_post('modules') != '' OR $admin->get_post('templates') != '' OR $admin->get_post('languages') != '') {
		$system_permissions['addons'] = 1;
	} else {
		$system_permissions['addons'] = 0;
	}
		$system_permissions['modules'] = $admin->get_post('modules');
			$system_permissions['modules_view'] = $system_permissions['modules'];
			$system_permissions['modules_install'] = $system_permissions['modules'];
			$system_permissions['modules_uninstall'] = $system_permissions['modules'];
		$system_permissions['templates'] = $admin->get_post('templates');
			$system_permissions['templates_view'] = $system_permissions['templates'];
			$system_permissions['templates_install'] = $system_permissions['templates'];
			$system_permissions['templates_uninstall'] = $system_permissions['templates'];
		$system_permissions['languages'] = $admin->get_post('languages');
			$system_permissions['languages_view'] = $system_permissions['languages'];
			$system_permissions['languages_install'] = $system_permissions['languages'];
			$system_permissions['languages_uninstall'] = $system_permissions['languages'];
	$system_permissions['settings'] = $admin->get_post('settings');
		$system_permissions['settings_basic'] = $system_permissions['settings'];
		$system_permissions['settings_advanced'] = $system_permissions['settings'];
	if($admin->get_post('users') != '' OR $admin->get_post('groups') != '') {
		$system_permissions['access'] = 1;
	} else {
		$system_permissions['access'] = 0;
	}
	$system_permissions['users'] = $admin->get_post('users');
	$system_permissions['users_view'] = $system_permissions['users'];
	$system_permissions['users_add'] = $system_permissions['users'];
	$system_permissions['users_modify'] = $system_permissions['users'];
	$system_permissions['users_delete'] = $system_permissions['users'];
	$system_permissions['groups'] = $admin->get_post('groups');
	$system_permissions['groups_view'] = $system_permissions['groups'];
	$system_permissions['groups_add'] = $system_permissions['groups'];
	$system_permissions['groups_modify'] = $system_permissions['groups'];
	$system_permissions['groups_delete'] = $system_permissions['groups'];
	$system_permissions['admintools'] = $admin->get_post('admintools');
	$system_permissions['admintools_settings'] = $system_permissions['admintools'];
} else {
	// Pages
	$system_permissions['pages_view'] = $admin->get_post('pages_view');
		$system_permissions['pages_add'] = $admin->get_post('pages_add');
	if($admin->get_post('pages_add') != 1 AND $admin->get_post('pages_add_l0') == 1) {
		$system_permissions['pages_add'] = $admin->get_post('pages_add_l0');
	}
	$system_permissions['pages_add_l0'] = $admin->get_post('pages_add_l0');
	$system_permissions['pages_settings'] = $admin->get_post('pages_settings');
	$system_permissions['pages_modify'] = $admin->get_post('pages_modify');
	$system_permissions['pages_intro'] = $admin->get_post('pages_intro');
	$system_permissions['pages_delete'] = $admin->get_post('pages_delete');
	if($system_permissions['pages_view'] == 1 OR $system_permissions['pages_add'] == 1 OR $system_permissions['pages_settings'] == 1 OR $system_permissions['pages_modify'] == 1 OR $system_permissions['pages_intro'] == 1 OR $system_permissions['pages_delete'] == 1) {
		$system_permissions['pages'] = 1;
	} else {
		$system_permissions['pages'] = '';
	}
	// Media
	$system_permissions['media_view'] = $admin->get_post('media_view');
	$system_permissions['media_upload'] = $admin->get_post('media_upload');
	$system_permissions['media_rename'] = $admin->get_post('media_rename');
	$system_permissions['media_delete'] = $admin->get_post('media_delete');
	$system_permissions['media_create'] = $admin->get_post('media_create');
	if($system_permissions['media_view'] == 1 OR $system_permissions['media_upload'] == 1 OR $system_permissions['media_rename'] == 1 OR $system_permissions['media_delete'] == 1 OR $system_permissions['media_create'] == 1) {
		$system_permissions['media'] = 1;
	} else {
		$system_permissions['media'] = '';
	}
	// Add-ons
		// Modules
		$system_permissions['modules_view'] = $admin->get_post('modules_view');
		$system_permissions['modules_install'] = $admin->get_post('modules_install');
		$system_permissions['modules_uninstall'] = $admin->get_post('modules_uninstall');
		if($system_permissions['modules_view'] == 1 OR $system_permissions['modules_install'] == 1 OR $system_permissions['modules_uninstall'] == 1) {
			$system_permissions['modules'] = 1;
		} else {
			$system_permissions['modules'] = '';
		}
		// Templates
		$system_permissions['templates_view'] = $admin->get_post('templates_view');
		$system_permissions['templates_install'] = $admin->get_post('templates_install');
		$system_permissions['templates_uninstall'] = $admin->get_post('templates_uninstall');
		if($system_permissions['templates_view'] == 1 OR $system_permissions['templates_install'] == 1 OR $system_permissions['templates_uninstall'] == 1) {
			$system_permissions['templates'] = 1;
		} else {
			$system_permissions['templates'] = '';
		}
		// Languages
		$system_permissions['languages_view'] = $admin->get_post('languages_view');
		$system_permissions['languages_install'] = $admin->get_post('languages_install');
		$system_permissions['languages_uninstall'] = $admin->get_post('languages_uninstall');
		if($system_permissions['languages_install'] == 1 OR $system_permissions['languages_uninstall'] == 1) {
			$system_permissions['languages'] = 1;
		} else {
			$system_permissions['languages'] = '';
		}
		// Admintools
		$system_permissions['admintools_settings'] = $admin->get_post('admintools_settings');
		if($system_permissions['admintools_settings'] == 1) {
			$system_permissions['admintools'] = 1;
		} else {
			$system_permissions['admintools'] = '';
		}
	if($system_permissions['modules'] == 1 OR $system_permissions['templates'] == 1 OR $system_permissions['languages'] == 1) {
		$system_permissions['addons'] = 1;
	} else {
		$system_permissions['addons'] = '';
	}
	// Settings
	$system_permissions['settings_basic'] = $admin->get_post('settings_basic');
	$system_permissions['settings_advanced'] = $admin->get_post('settings_advanced');
	if($system_permissions['settings_basic'] == 1 OR $system_permissions['settings_advanced'] == 1) {
		$system_permissions['settings'] = 1;
	} else {
		$system_permissions['settings'] = '';
	}
	// Access
		// Users
		$system_permissions['users_view'] = $admin->get_post('users_view');
		$system_permissions['users_add'] = $admin->get_post('users_add');
		$system_permissions['users_modify'] = $admin->get_post('users_modify');
		$system_permissions['users_delete'] = $admin->get_post('users_delete');
		if($system_permissions['users_view'] == 1 OR $system_permissions['users_add'] == 1 OR $system_permissions['users_modify'] == 1 OR $system_permissions['users_delete'] == 1) {
			$system_permissions['users'] = 1;
		} else {
			$system_permissions['users'] = '';
		}
		// Groups
		$system_permissions['groups_view'] = $admin->get_post('groups_view');
		$system_permissions['groups_add'] = $admin->get_post('groups_add');
		$system_permissions['groups_modify'] = $admin->get_post('groups_modify');
		$system_permissions['groups_delete'] = $admin->get_post('groups_delete');
		if($system_permissions['groups_view'] == 1 OR $system_permissions['groups_add'] == 1 OR $system_permissions['groups_modify'] == 1 OR $system_permissions['groups_delete'] == 1) {
			$system_permissions['groups'] = 1;
		} else {
			$system_permissions['groups'] = '';
		}
	if($system_permissions['users'] == 1 OR $system_permissions['groups'] == 1) {
		$system_permissions['access'] = 1;
	} else {
		$system_permissions['access'] = '';
	}
}

// Implode system permissions
$imploded_system_permissions = '';
foreach($system_permissions AS $name => $value) {
	if($value == true) {
		if($imploded_system_permissions == '') {
			$imploded_system_permissions = $name;
		} else {
			$imploded_system_permissions .= ','.$name;
		}
	}
}

$system_permissions = $imploded_system_permissions;

// Get module permissions
$module_permissions = '';
if($handle = opendir(WB_PATH.'/modules/')) {
	while (false !== ($file = readdir($handle))) {
		if($file != "." AND $file != ".." AND $file != ".svn" AND is_dir(WB_PATH."/modules/$file") AND file_exists(WB_PATH."/modules/$file/info.php")) {
			// Include the modules info file
			require(WB_PATH.'/modules/'.$file.'/info.php');
			// Check if it was selected to be used or not
			$count = 0;
			if(is_array($admin->get_post('module_permissions'))) {
				foreach($admin->get_post('module_permissions') AS $selected_name) {
					if($file == $selected_name) {
						$count = $count+1;
					}
				}
			}
			if($count == 0) {
				// Add unselected modules to list
				if($module_permissions == '') {
					$module_permissions = $file;
				} else {
					$module_permissions .= ','.$file;
				}
			}
		}
	}
}

// Get template permissions
$template_permissions = '';
if($handle = opendir(WB_PATH.'/templates/')) {
	while (false !== ($file = readdir($handle))) {
		if($file != "." AND $file != ".." AND $file != ".svn" AND is_dir(WB_PATH."/templates/$file") AND file_exists(WB_PATH."/templates/$file/info.php")) {
			// Include the modules info file
			require(WB_PATH.'/templates/'.$file.'/info.php');
			// Check if it was selected to be used or not
			$count = 0;
			if(is_array($admin->get_post('template_permissions'))) {
				foreach($admin->get_post('template_permissions') AS $selected_name) {
					if($file == $selected_name) {
						$count = $count+1;
					}
				}
			}
			if($count == 0) {
				// Add unselected modules to list
				if($template_permissions == '') {
					$template_permissions = $file;
				} else {
					$template_permissions .= ','.$file;
				}
			}
		}
	}
}

?>