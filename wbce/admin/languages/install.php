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

// do not display notices and warnings during installation
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Include required files
require '../../config.php';
require_once WB_PATH . '/framework/addon.precheck.inc.php';
require_once WB_PATH . '/framework/functions.php';	        // WBCE 1.1.x compatibility
require_once WB_PATH . '/include/pclzip/pclzip.lib.php';    // WBCE 1.1.x compatibility

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'languages_install', false, true);
if(! $admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    $admin->print_footer();
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user uploaded a file
if(! (isset($_FILES['userfile']) && isset($_FILES['userfile']['name']))) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Check write permissions for languages folder
if(! is_writable(WB_PATH.'/languages/')) {
    $admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS']);
}

// Create unique file within WBCE /temp folder
$temp_dir = WB_PATH . '/temp/';
$temp_file = tempnam($temp_dir, 'wb_');

// Move uploaded file into WBCE /temp folder
if(! move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_file)) {
	if(file_exists($temp_file)) {
		unlink($temp_file);
	}
	$admin->print_error($MESSAGE['GENERIC_CANNOT_UPLOAD']);
}

// Check if uploaded file is a valid language file (no binary file etc.)
$content = file_get_contents($temp_file);
if (strpos($content, '<?php') === false) {
	$admin->print_error($MESSAGE['GENERIC_INVALID_LANGUAGE_FILE']);
}

// Remove any vars with name "language_code"
unset($language_code);

// Read the temp file and look for a language code
require($temp_file);
$new_language_version=$language_version;

// Check if the file is valid
if(!isset($language_code)) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	// Restore to correct language
	require(WB_PATH.'/languages/'.LANGUAGE.'.php');
	$admin->print_error($MESSAGE['GENERIC_INVALID_LANGUAGE_FILE']);
}

// Set destination for language file
$language_file = WB_PATH.'/languages/'.$language_code.'.php';
$action="install";

// Move to new location
if (file_exists($language_file)) {
	require($language_file);
	if (versionCompare($language_version, $new_language_version, '>=')) {
		// Restore to correct language
		require(WB_PATH . '/languages/' . LANGUAGE . '.php');
		$admin->print_error($MESSAGE['GENERIC_ALREADY_INSTALLED']);
	}
	$action="upgrade";
	unlink($language_file);
}

rename($temp_file, $language_file);

// Chmod the file
change_mode($language_file, 'file');

// Load language info into DB
load_language($language_file);

// Restore to correct language
require(WB_PATH.'/languages/'.LANGUAGE.'.php');

// Print success message
if ($action=="install") {
	$admin->print_success($MESSAGE['GENERIC_INSTALLED']);
} else {
	$admin->print_success($MESSAGE['GENERIC_UPGRADED']);
}

// Print admin footer
$admin->print_footer();
