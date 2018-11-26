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
require_once '../../config.php';

$admin_header         = false;        // don't print header immediately
$update_when_modified = true;         // Tell script to update when this page was last updated
require WB_PATH.'/modules/admin.php'; // Include WB admin wrapper script
$js_back = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;

if (!$admin->checkFTAN()) {
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}

$admin->print_header();

if(isset($_POST['menu_link'])) {	
	
	$aUpdatePageTable = array(
		'page_id'  => $page_id,	
		'target'   => $admin->add_slashes($admin->get_post('target')),	
	);	
	$database->updateRow('{TP}pages', 'page_id', $aUpdatePageTable);
	
	$aUpdateModTable = array(
		'page_id'        => $page_id,
		'target_page_id' => ($_POST['linktype'] == 'ext') ? '-1' : intval($admin->get_post('menu_link')), 	
		'redirect_type'  => $admin->add_slashes($admin->get_post('r_type')), 	
		'anchor'         => $admin->add_slashes($admin->get_post('anchor')),
		'extern'         => isset($_POST['extern']) && $_POST['linktype'] == 'ext' ? $admin->add_slashes($admin->get_post('extern')) : '',  	
	);	
	$database->updateRow('{TP}mod_menu_link', 'page_id', $aUpdateModTable);

	// Check if there is a database error, otherwise say successful
	if ($database->is_error()) {
		$admin->print_error($database->get_error(), $js_back);
	} else {
		$admin->print_success($MESSAGE['PAGES_SAVED'], $js_back);
	}
} else {
	$admin->print_error('No Data was set', $js_back);
}
$admin->print_footer();
