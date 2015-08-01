<?php
/**
 *
 * @category        admin
 * @package         addons
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @author          Werner v.d. Decken
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: index.php 5 2015-04-27 08:02:19Z luisehahne $
 * @filesource      $HeadURL: https://localhost:8443/svn/wb283Sp4/SP4/branches/wb/admin/addons/index.php $
 * @lastmodified    $Date: 2015-04-27 10:02:19 +0200 (Mo, 27. Apr 2015) $
 *
 */

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'addons');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('addons.htt')));
$template->set_file('page', 'addons.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values into the template object
$template->set_var(array(
		'ADMIN_URL' => ADMIN_URL,
		'THEME_URL' => THEME_URL,
		'WB_URL' => WB_URL
	)
);

/**
 *	Setting up the blocks
 */
$template->set_block('main_block', "modules_block", "modules");
$template->set_block('main_block', "templates_block", "templates");
$template->set_block('main_block', "languages_block", "languages");
$template->set_block('main_block', "reload_block", "reload");

/**
 *	Insert permission values into the template object
 *	Obsolete as we are using blocks ... see "parsing the blocks" section
 */
$display_none = "style=\"display: none;\"";
if($admin->get_permission('modules') != true) 	$template->set_var('DISPLAY_MODULES', $display_none);	
if($admin->get_permission('templates') != true)	$template->set_var('DISPLAY_TEMPLATES', $display_none);
if($admin->get_permission('languages') != true)	$template->set_var('DISPLAY_LANGUAGES', $display_none);
if($admin->get_permission('admintools') != true)	$template->set_var('DISPLAY_ADVANCED', $display_none);

if(!isset($_GET['advanced']) || $admin->get_permission('admintools') != true)
	$template->set_var('DISPLAY_RELOAD', $display_none);

/**
 *	Insert section names and descriptions
 */
$template->set_var(array(
	'ADDONS_OVERVIEW' => $MENU['ADDONS'],
	'MODULES' => $MENU['MODULES'],
	'TEMPLATES' => $MENU['TEMPLATES'],
	'LANGUAGES' => $MENU['LANGUAGES'],
	'MODULES_OVERVIEW' => $OVERVIEW['MODULES'],
	'TEMPLATES_OVERVIEW' => $OVERVIEW['TEMPLATES'],
	'LANGUAGES_OVERVIEW' => $OVERVIEW['LANGUAGES'],
	'TXT_ADMIN_SETTINGS' => $TEXT['ADMIN'] . ' ' . $TEXT['SETTINGS'],
	'MESSAGE_RELOAD_ADDONS' => $MESSAGE['ADDON_RELOAD'],
	'TEXT_RELOAD' => $TEXT['RELOAD'],
	'RELOAD_URL' => ADMIN_URL . '/addons/reload.php',
	'URL_ADVANCED' => $admin->get_permission('admintools')
                ? '<a href="' . ADMIN_URL . '/addons/index.php?advanced">' . $TEXT['ADVANCED'] . '</a>' : '',
	'ADVANCED_URL' => $admin->get_permission('admintools') ? ADMIN_URL . '/addons/index.php' : '',
    'TEXT_ADVANCED' => $TEXT['ADVANCED'],
	'FTAN'			=> $admin->getFTAN()
	)
);

/**
 *	Parsing the blocks ...
 */
if ( $admin->get_permission('modules') == true) $template->parse('main_block', "modules_block", true);
if ( $admin->get_permission('templates') == true) $template->parse('main_block', "templates_block", true);
if ( $admin->get_permission('languages') == true) $template->parse('main_block', "languages_block", true);
if ( isset($_GET['advanced']) AND $admin->get_permission('admintools') == true) $template->parse('main_block', "reload_block", true);

/**
 *	Parse template object
 */
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

/**
 *	Print admin footer
 */
$admin->print_footer();
