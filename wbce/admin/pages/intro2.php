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

// Create new admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_intro',false);
if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Get posted content
if(!isset($_POST['content'])) {
	header("Location: intro".PAGE_EXTENSION."");
	exit(0);
} else {
	$content = $admin->strip_slashes($_POST['content']);
}

// $content = $admin->strip_slashes($content);

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

$admin->print_header();
// Write new content
$filename = WB_PATH.PAGES_DIRECTORY.'/intro'.PAGE_EXTENSION;
if(file_put_contents($filename, utf8_encode($content))===false){
	$admin->print_error($MESSAGE['PAGES_NOT_SAVED']);
} else {
	change_mode($filename);
	$admin->print_success($MESSAGE['PAGES_INTRO_SAVED']);
}
if(!is_writable($filename)) {
	$admin->print_error($MESSAGE['PAGES_INTRO_NOT_WRITABLE']);
}

// Print admin footer
$admin->print_footer();
