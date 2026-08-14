<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require('../../config.php');

$admin_header = false;
// Tells script to update when this page was last updated
$update_when_modified = true;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}
$admin->print_header();

// Update the mod_wrapper table with the contents
if(isset($_POST['url'])) {
	$url = strip_tags($_POST['url']);
	$height = $_POST['height'];
	if(!is_numeric($height)) {
		$height = 400;
	}
	$database->upsertRow('{TP}mod_wrapper', 'section_id', [
		'section_id' => $section_id,
		'url'        => $url,
		'height'     => $height,
	]);
}

// Check if there is a database error, otherwise say successful
if($database->hasError()) {
	$admin->print_error($database->getError(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES_SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();
