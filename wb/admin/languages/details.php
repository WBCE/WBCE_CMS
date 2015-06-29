<?php
/**
 *
 * @category        admin
 * @package         languages
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: details.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/languages/details.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 * @description
 *
 */

// Include the config code
require('../../config.php');

// Print admin header
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'languages_view', false);
if( !$admin->checkFTAN() )
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Get language name
if(!isset($_POST['code']) OR $_POST['code'] == "") {
	$code = '';
} else {
	$code = $_POST['code'];
}

// fix secunia 2010-93-2
if (!preg_match('/^[A-Z]{2}$/', $code)) {
	header("Location: index.php");
	exit(0);
}

// Check if the language exists
if(!file_exists(WB_PATH.'/languages/'.$code.'.php')) {
	header("Location: index.php");
	exit(0);
}

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('languages_details.htt')));
// $template->debug = true;
$template->set_file('page', 'languages_details.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values
require(WB_PATH.'/languages/'.$code.'.php');
$template->set_var(array(
								'CODE' => $language_code,
								'NAME' => $language_name,
								'AUTHOR' => $language_author,
								'VERSION' => $language_version,
								'DESIGNED_FOR' => $language_platform,
								'ADMIN_URL' => ADMIN_URL,
								'WB_URL' => WB_URL,
								'THEME_URL' => THEME_URL
								)
						);

// Restore language to original code
require(WB_PATH.'/languages/'.LANGUAGE.'.php');

// Insert language headings
$template->set_var(array(
								'HEADING_LANGUAGE_DETAILS' => $HEADING['LANGUAGE_DETAILS']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'TEXT_CODE' => $TEXT['CODE'],
								'TEXT_NAME' => $TEXT['NAME'],
								'TEXT_TYPE' => $TEXT['TYPE'],
								'TEXT_AUTHOR' => $TEXT['AUTHOR'],
								'TEXT_VERSION' => $TEXT['VERSION'],
								'TEXT_DESIGNED_FOR' => $TEXT['DESIGNED_FOR'],
								'TEXT_BACK' => $TEXT['BACK']
								)
						);

// Parse language object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
