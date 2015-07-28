<?php
/**
 *
 * @category        modules
 * @package         menu_link
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: save.php 1537 2011-12-10 11:04:33Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/menu_link/save.php $
 * @lastmodified    $Date: 2011-12-10 12:04:33 +0100 (Sa, 10. Dez 2011) $
 *
*/

require_once('../../config.php');

$admin_header = false;
// Tells script to update when this page was last updated
$update_when_modified = true;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
$backlink = ADMIN_URL.'/pages/modify.php?page_id='.(int)$page_id;
if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$backlink );
}
$admin->print_header();

// Update id, anchor and target
if(isset($_POST['menu_link'])) {
	$foreign_page_id = $admin->add_slashes($_POST['menu_link']);
	$page_target = $admin->add_slashes($_POST['page_target']);
	$url_target = $admin->add_slashes($_POST['target']);
	$r_type = $admin->add_slashes($_POST['r_type']);
	if(isset($_POST['extern']))
		$extern = $admin->add_slashes($_POST['extern']);
	else
		$extern='';

	$table_pages = TABLE_PREFIX.'pages';
	$table_mod = TABLE_PREFIX.'mod_menu_link';
	$database->query("UPDATE `$table_pages` SET `target` = '$url_target' WHERE `page_id` = '$page_id'");
	$database->query("UPDATE `$table_mod` SET `target_page_id` = '$foreign_page_id', `anchor` = '$page_target', `extern` = '$extern', `redirect_type` = '$r_type' WHERE `page_id` = '$page_id'");
}

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'],$backlink );
}

// Print admin footer
$admin->print_footer();
