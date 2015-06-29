<?php
/**
 *
 * @category        admin
 * @package         access
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: index.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource		$HeadURL:  $
 * @lastmodified    $Date:  $
 *
 *
 */
 
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Access', 'access');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('access.htt')));
// $template->debug = true;
$template->set_file('page', 'access.htt');

$template->set_block('page', 'main_block', 'main');
$template->set_block('main_block', 'users_block', 'user');
$template->set_block('main_block', 'groups_block', 'group');

// Insert values into the template object
$template->set_var(array(
		'ADMIN_URL' => ADMIN_URL,
		'THEME_URL' => THEME_URL,
		'WB_URL' => WB_URL
	)
);

/**
 *	Insert permission values into the template object
 *	Deprecated - as we are using blocks.
 */
$display_none = "style=\"display: none;\"";
if($admin->get_permission('users') != true)	$template->set_var('DISPLAY_USERS', $display_none);
if($admin->get_permission('groups') != true) $template->set_var('DISPLAY_GROUPS', $display_none);

// Insert section names and descriptions
$template->set_var(array(
		'USERS' => $MENU['USERS'],
		'GROUPS' => $MENU['GROUPS'],
		'ACCESS' => $MENU['ACCESS'],
		'USERS_OVERVIEW' => $OVERVIEW['USERS'],
		'GROUPS_OVERVIEW' => $OVERVIEW['GROUPS'],
	)
);

if ( $admin->get_permission('users') == true )	$template->parse('main_block', "users_block", true);
if ( $admin->get_permission('groups') == true )	$template->parse('main_block', "groups_block", true);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();

?>