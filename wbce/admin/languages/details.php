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

// Include required files
require '../../config.php';
require_once WB_PATH . '/framework/functions.php';	// for WBCE 1.1.x compatibility

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'languages_view', false, true);
if(! $admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user selected a valid language file
$lang_code = $admin->get_post('code');
if (! preg_match('/^[A-Z]{2}$/', $lang_code)) {
	// no valid WBCE language code defined (e.g. EN, DE ..)
	$admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Check if the language files exists
if(! file_exists(WB_PATH . '/languages/' . $lang_code . '.php')) {
	$admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Create new template object
$template = new Template(dirname($admin->correct_theme_source('languages_details.htt')));
$template->set_file('page', 'languages_details.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values
require(WB_PATH.'/languages/'.$lang_code.'.php');
$template->set_var(
	array(
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
$template->set_var(
	array(
		// Headings
		'HEADING_LANGUAGE_DETAILS' => $HEADING['LANGUAGE_DETAILS'],

		// Text messages
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
