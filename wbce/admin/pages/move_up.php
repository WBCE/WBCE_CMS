<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * More Baking. Less Struggling.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require('../../config.php');

// Get id
if(isset($_GET['page_id']) AND is_numeric($_GET['page_id'])) {
	if(isset($_GET['section_id']) AND is_numeric($_GET['section_id'])) {
		$page_id = $_GET['page_id'];
		$id = $_GET['section_id'];
		$id_field = 'section_id';
		$common_field = 'page_id';
		$table = TABLE_PREFIX.'sections';
	} else {
		$id = $_GET['page_id'];
		$id_field = 'page_id';
		$common_field = 'parent';
		$table = TABLE_PREFIX.'pages';
	}
} else {
	header("Location: index.php");
	exit(0);
}

// Create new admin object and print admin header
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_settings');

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

// Create new order object an reorder
$order = new order($table, 'position', $id_field, $common_field);
if($id_field == 'page_id') {
	if($order->move_up($id)) {
		$admin->print_success($MESSAGE['PAGES_REORDERED']);
	} else {
		$admin->print_error($MESSAGE['PAGES_CANNOT_REORDER']);
	}
} else {
	if($order->move_up($id)) {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
	} else {
		$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
	}
}

// Print admin footer
$admin->print_footer();

?>
