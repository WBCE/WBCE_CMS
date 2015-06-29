<?php
/**
 *
 * @category        modules
 * @package         news
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: move_up.php 1473 2011-07-09 00:40:50Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/move_up.php $
 * @lastmodified    $Date: 2011-07-09 02:40:50 +0200 (Sa, 09. Jul 2011) $
 *
 */

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

$backlink = ADMIN_URL.'/pages/modify.php?page_id='.(int)$page_id;
// Get id
$pid = $admin->checkIDKEY('post_id', false, 'GET');
$gid = $admin->checkIDKEY('group_id', false, 'GET');
if (!$pid) {
	if (!$gid) {
		$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $backlink);
	} else {
		$id = $gid;
		$id_field = 'group_id';
		$table = TABLE_PREFIX.'mod_news_groups';
	}
} else {
	$id = $pid;
	$id_field = 'post_id';
	$table = TABLE_PREFIX.'mod_news_posts';
}

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

// Create new order object an reorder
$order = new order($table, 'position', $id_field, 'section_id');
if($order->move_up($id)) {
	$admin->print_success($TEXT['SUCCESS'], $backlink);
} else {
	$admin->print_error($TEXT['ERROR'], $backlink);
}

// Print admin footer
$admin->print_footer();
