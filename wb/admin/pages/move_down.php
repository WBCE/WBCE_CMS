<?php
/**
 *
 * @category        admin
 * @package         pages
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: move_down.php 1457 2011-06-25 17:18:50Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/pages/move_down.php $
 * @lastmodified    $Date: 2011-06-25 19:18:50 +0200 (Sa, 25. Jun 2011) $
 *
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
	if($order->move_down($id)) {
		$admin->print_success($MESSAGE['PAGES']['REORDERED']);
	} else {
		$admin->print_error($MESSAGE['PAGES']['CANNOT_REORDER']);
	}
} else {
	if($order->move_down($id)) {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
	} else {
		$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
	}
}

// Print admin footer
$admin->print_footer();

?>