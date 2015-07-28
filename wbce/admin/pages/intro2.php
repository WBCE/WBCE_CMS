<?php
/**
 *
 * @category        admin
 * @package         pages
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: intro2.php 1511 2011-09-14 17:24:09Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/pages/intro2.php $
 * @lastmodified    $Date: 2011-09-14 19:24:09 +0200 (Mi, 14. Sep 2011) $
 *
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
	$admin->print_success($MESSAGE['PAGES']['INTRO_SAVED']);
}
if(!is_writable($filename)) {
	$admin->print_error($MESSAGE['PAGES']['INTRO_NOT_WRITABLE']);
}

// Print admin footer
$admin->print_footer();
