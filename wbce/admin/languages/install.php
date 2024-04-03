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

// do not display notices and warnings during installation
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Include required files
require '../../config.php';
require_once WB_PATH . '/framework/functions.php';

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'languages_install', false, true);
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    $admin->print_footer();
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();



require_once WB_PATH . '/languages/' . LANGUAGE . '.php';
$msg = array();
$table = TABLE_PREFIX . 'addons';
$js_back = ADMIN_URL . '/languages/index.php';

if ($handle = opendir(WB_PATH . '/languages/')) {
	// delete languages from database
	$sql = "DELETE FROM `$table` WHERE `type` = 'language'";
	$database->query($sql);

	// loop over all languages
	while (false !== ($file = readdir($handle))) {
		if ($file != '' && substr($file, 0, 1) != '.' && $file != 'index.php') {
			load_language(WB_PATH . '/languages/' . $file);
		}
	}
	closedir($handle);
	// add success message
	$msg[] = $MESSAGE['ADDON_LANGUAGES_RELOADED'];
} else {
	// provide error message and stop
	$admin->print_error($MESSAGE['ADDON_ERROR_RELOAD'], $js_back);
}

// Print admin footer
$admin->print_success(implode('<br />', $msg), $js_back);
$admin->print_footer();
