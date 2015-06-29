<?php
/**
 *
 * @category        admin
 * @package         groups
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: save.php 1493 2011-08-11 14:56:05Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/groups/save.php $
 * @lastmodified    $Date: 2011-08-11 16:56:05 +0200 (Do, 11. Aug 2011) $
 *
 */

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Access', 'groups_modify', false);
// Create a javascript back link
$js_back = ADMIN_URL.'/groups/index.php';

if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$js_back);
}

// Check if group group_id is a valid number and doesnt equal 1
$group_id = intval($admin->checkIDKEY('group_id', 0, $_SERVER['REQUEST_METHOD']));
if( ($group_id < 2 ) )
{
	// if($admin_header) { $admin->print_header(); }
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'] );
}

// Gather details entered
$group_name = $admin->get_post_escaped('group_name');

// Check values
if($group_name == "") {
	$admin->print_error($MESSAGE['GROUPS']['GROUP_NAME_BLANK'], $js_back);
}
// After check print the header
$admin->print_header();

// Get system permissions
require_once(ADMIN_PATH.'/groups/get_permissions.php');

// Update the database
$query = "UPDATE `".TABLE_PREFIX."groups` SET `name` = '$group_name', `system_permissions` = '$system_permissions', `module_permissions` = '$module_permissions', `template_permissions` = '$template_permissions' WHERE `group_id` = '$group_id'";

$database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['GROUPS']['SAVED'], ADMIN_URL.'/groups/index.php');
}

// Print admin footer
$admin->print_footer();
