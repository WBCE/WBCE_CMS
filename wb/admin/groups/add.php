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
 * @version         $Id: add.php 1473 2011-07-09 00:40:50Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/groups/add.php $
 * @lastmodified    $Date: 2011-07-09 02:40:50 +0200 (Sa, 09. Jul 2011) $
 *
 */

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');

// suppress to print the header, so no new FTAN will be set
$admin = new admin('Access', 'groups_add', false);
// Create a javascript back link
$js_back = ADMIN_URL.'/groups/index.php';

if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$js_back);
}
// After check print the header
$admin->print_header();

// Gather details entered
$group_name = $database->escapeString(trim(strip_tags($admin->get_post('group_name'))));

// Check values
if($group_name == "") {
	$admin->print_error($MESSAGE['GROUPS_GROUP_NAME_BLANK'], $js_back);
}
$sql = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'groups` '
     . 'WHERE `name`=\''.$group_name.'\'';
if ($database->get_one($sql)) {
	$admin->print_error($MESSAGE['GROUPS_GROUP_NAME_EXISTS'], $js_back);  
}

// Get system and module permissions
require(ADMIN_PATH.'/groups/get_permissions.php');

// Update the database
$sql = 'INSERT INTO `'.TABLE_PREFIX.'groups` '
     . 'SET `name`=\''.$group_name.'\', '
     .     '`system_permissions`=\''.$system_permissions.'\', '
     .     '`module_permissions`=\''.$module_permissions.'\', '
     .     '`template_permissions`=\''.$template_permissions.'\'';
if (($database->query($sql))) {
	$admin->print_success($MESSAGE['GROUPS_ADDED'], ADMIN_URL.'/groups/index.php');
} else {
	$admin->print_error($database->get_error());
}
// Print admin footer
$admin->print_footer();

