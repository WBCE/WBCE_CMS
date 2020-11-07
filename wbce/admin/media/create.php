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

// Create admin object
require('../../config.php');
$admin = new admin('Media', 'media_create', false);

// Include WBCE functions file (legacy for WBCE 1.1.x)
require_once WB_PATH . '/framework/functions.php';

// Check FTAN
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Get new directory name
$requestMethod = '_' . strtoupper($_SERVER['REQUEST_METHOD']);
$name = (isset(${$requestMethod}['name'])) ? ${$requestMethod}['name'] : '';
$name = media_filename($name);

// Get target location and ensure target is inside WBCE media folder
$requestMethod = '_' . strtoupper($_SERVER['REQUEST_METHOD']);
$target = (isset(${$requestMethod}['target'])) ? ${$requestMethod}['target'] : '';
if (!check_media_path($target, false)) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Create absolute path of the new dir name
$directory = WB_PATH . $target . '/' . $name;

// Check to see if the folder already exists
if (file_exists($directory)) {
    $admin->print_error($MESSAGE['MEDIA_DIR_EXISTS']);
}

// Create folder and add an index.php to prevent directory listing
if (sizeof(createFolderProtectFile($directory))) {
    $admin->print_error($MESSAGE['MEDIA_DIR_NOT_MADE']);
} else {
    $admin->print_success($MESSAGE['MEDIA_DIR_MADE']);
}

// Print admin
$admin->print_footer();
