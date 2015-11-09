<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
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
	$admin->print_error($MESSAGE['GROUPS_GROUP_NAME_BLANK'], $js_back);
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
	$admin->print_success($MESSAGE['GROUPS_SAVED'], ADMIN_URL.'/groups/index.php');
}

// Print admin footer
$admin->print_footer();
