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

// Print admin header
require('../../config.php');

require_once(WB_PATH.'/framework/class.admin.php');
// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// suppress to print the header, so no new FTAN will be set
$admin = new admin('Media', 'media_create', false);

// Get dir name and target location
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$name = (isset(${$requestMethod}['name'])) ? ${$requestMethod}['name'] : '';

// Check to see if name or target contains ../
if(strstr($name, '..')) {
	$admin->print_header();
	$admin->print_error($MESSAGE['MEDIA_NAME_DOT_DOT_SLASH']);
}

// Remove bad characters
$name = trim(media_filename($name),'.');

// Target location
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$target = (isset(${$requestMethod}['target'])) ? ${$requestMethod}['target'] : '';

if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

if (!check_media_path($target, false)) {
	$admin->print_error($MESSAGE['MEDIA_TARGET_DOT_DOT_SLASH']);
}

// Create relative path of the new dir name
$directory = WB_PATH.$target.'/'.$name;

// Check to see if the folder already exists
if(file_exists($directory)) {
	$admin->print_error($MESSAGE['MEDIA_DIR_EXISTS']);
}

if ( sizeof(createFolderProtectFile( $directory )) )
{
	$admin->print_error($MESSAGE['MEDIA_DIR_NOT_MADE']);
} else {
	$usedFiles = array();
    // feature freeze
	// require_once(ADMIN_PATH.'/media/dse.php');
	$admin->print_success($MESSAGE['MEDIA_DIR_MADE']);
}

// Print admin
$admin->print_footer();
