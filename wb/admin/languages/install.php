<?php
/**
 *
 * @category        admin
 * @package         languages
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: install.php 1467 2011-07-02 00:06:53Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/languages/install.php $
 * @lastmodified    $Date: 2011-07-02 02:06:53 +0200 (Sa, 02. Jul 2011) $
 * @description
 *
 */

// do not display notices and warnings during installation
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Setup admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'languages_install', false);
if( !$admin->checkFTAN() )
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Check if user uploaded a file
if(!isset($_FILES['userfile'])) {
	header("Location: index.php");
	exit(0);
}

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Create temp string
$temp_string = '';
$salt = "abchefghjkmnpqrstuvwxyz0123456789";
srand((double)microtime()*1000000);
$i = 0;
while ($i <= 7) {
	$num = rand() % 33;
	$tmp = substr($salt, $num, 1);
	$temp_string = $temp_string . $tmp;
	$i++;
}

// Set temp vars
$temp_dir = WB_PATH.'/temp/';
$temp_file = $temp_dir . 'language'.$temp_string;

// Check if language dir is writable
if(!is_writable(WB_PATH.'/languages/')) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	$admin->print_error($MESSAGE['GENERIC']['BAD_PERMISSIONS']);
}

// Try to upload the file to the temp dir
if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_file)) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UPLOAD']);
}

// Check if uploaded file is a valid language file (no binary file etc.)
$content = file_get_contents($temp_file);
if (strpos($content, '<?php') === false) $admin->print_error($MESSAGE['GENERIC']['INVALID_LANGUAGE_FILE']);

// Remove any vars with name "language_code"
unset($language_code);

// Include precheck files for versionCompare routine
require(WB_PATH . '/framework/addon.precheck.inc.php');

// Read the temp file and look for a language code
require($temp_file);
$new_language_version=$language_version;

// Check if the file is valid
if(!isset($language_code)) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	// Restore to correct language
	require(WB_PATH.'/languages/'.LANGUAGE.'.php');
	$admin->print_error($MESSAGE['GENERIC']['INVALID_LANGUAGE_FILE']);
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
		$admin->print_error($MESSAGE['GENERIC']['ALREADY_INSTALLED']);
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
	$admin->print_success($MESSAGE['GENERIC']['INSTALLED']);
} else {
	$admin->print_success($MESSAGE['GENERIC']['UPGRADED']);
}

// Print admin footer
$admin->print_footer();

?>