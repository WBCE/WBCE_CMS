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

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Access', 'groups');
$ftan = $admin->getFTAN();

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('groups.htt')));
// $template->debug = true;
$template->set_file('page', 'groups.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_block('main_block', 'manage_users_block', 'users');
// insert urls
$template->set_var(array(
	'ADMIN_URL' => ADMIN_URL,
	'WB_URL' => WB_URL,
	'THEME_URL' => THEME_URL,
	'FTAN' => $ftan
	)
);

// Get existing groups from database (and get users in that group)
$query = "SELECT g.group_id, CONCAT(name,' (',COUNT(u.group_id),')') AS name
            FROM {TP}groups AS g LEFT JOIN {TP}users AS u
            ON (g.group_id = u.group_id
            OR FIND_IN_SET(g.group_id, u.groups_id) > '0')
            WHERE g.group_id != '1'
            GROUP BY g.group_id
            ORDER BY g.name";

$results = $database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error(), 'index.php');
}

// Insert values into the modify/remove menu
$template->set_block('main_block', 'list_block', 'list');
if($results->numRows() > 0) {
	// Insert first value to say please select
	$template->set_var('VALUE', '');
	$template->set_var('NAME', $TEXT['PLEASE_SELECT'].'...');
	$template->parse('list', 'list_block', true);
	// Loop through groups
	while($group = $results->fetchRow()) {
		$template->set_var('VALUE',$admin->getIDKEY($group['group_id']));
		$template->set_var('NAME', $group['name']);
		$template->parse('list', 'list_block', true);
	}
} else {
	// Insert single value to say no groups were found
	$template->set_var('NAME', $TEXT['NONE_FOUND']);
	$template->parse('list', 'list_block', true);
}

// Insert permissions values
if($admin->get_permission('groups_add') != true) {
	$template->set_var('DISPLAY_ADD', 'hide');
}
if($admin->get_permission('groups_modify') != true) {
	$template->set_var('DISPLAY_MODIFY', 'hide');
}
if($admin->get_permission('groups_delete') != true) {
	$template->set_var('DISPLAY_DELETE', 'hide');
}

$template->set_var(
    array(
        // Insert language headings
        'HEADING_MODIFY_DELETE_GROUP' => $HEADING['MODIFY_DELETE_GROUP'],
        'HEADING_ADD_GROUP' => $HEADING['ADD_GROUP'],
        'HEADING_ACCESS' => $MENU['ACCESS'],
        'HEADING_GROUPS' => $MENU['GROUPS'],
        
        // Insert language text and messages
	'TEXT_MODIFY' => $TEXT['MODIFY'],
	'TEXT_DELETE' => $TEXT['DELETE'],
	'TEXT_MANAGE_USERS' => ( $admin->get_permission('users') == true ) ? $TEXT['MANAGE_USERS']: "",
	'CONFIRM_DELETE' => $MESSAGE['GROUPS_CONFIRM_DELETE']
    )
);
if ( $admin->get_permission('users') == true ) $template->parse("users", "manage_users_block", true);
// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('groups_form.htt')));
// $template->debug = true;
$template->set_file('page', 'groups_form.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('DISPLAY_EXTRA', 'display:none;');
$template->set_var('ACTION_URL', ADMIN_URL.'/groups/add.php');
$template->set_var('SUBMIT_TITLE', $TEXT['ADD']);
$template->set_var('ADVANCED_LINK', 'index.php');

// Tell the browser whether or not to show advanced options
if ( true == (isset( $_POST['advanced']) AND ( strpos( $_POST['advanced'], ">>") > 0 ) ) ) {
    $template->set_var('DISPLAY_ADVANCED', '');
    $template->set_var('DISPLAY_BASIC', 'display:none;');
    $template->set_var('ADVANCED', 'yes');
    $template->set_var('ADVANCED_BUTTON', '<< '.$TEXT['HIDE_ADVANCED']);
} else {
    $template->set_var('DISPLAY_ADVANCED', 'display:none;');
    $template->set_var('DISPLAY_BASIC', '');
    $template->set_var('ADVANCED', 'no');
    $template->set_var('ADVANCED_BUTTON', $TEXT['SHOW_ADVANCED'].' >>');
}

// Insert permissions values
if($admin->get_permission('groups_add') != true) {
    $template->set_var('DISPLAY_ADD', 'hide');
}

// Insert values into module list
$template->set_block('main_block', 'module_list_block', 'module_list');
$result = $database->query('SELECT * FROM `{TP}addons` WHERE `type` = "module" AND `function` = "page" ORDER BY `name`');
if($result->numRows() > 0) {
    while($addon = $result->fetchRow()) {
        $addon['name'] = $admin->get_module_name($addon['directory'], true, '&nbsp; <i>(%s)</i>');
        $template->set_var('VALUE', $addon['directory']);
        $template->set_var('NAME', $addon['name']);
        $template->parse('module_list', 'module_list_block', true);
    }
}

// Insert values into template list
$template->set_block('main_block', 'template_list_block', 'template_list');
$result = $database->query('SELECT * FROM `{TP}addons` WHERE `type` = "template" ORDER BY `name`');
if($result->numRows() > 0) {
    while($addon = $result->fetchRow()) {
        $template->set_var('VALUE', $addon['directory']);
        $template->set_var('NAME', $addon['name']);
        $template->parse('template_list', 'template_list_block', true);
    }
}

// Insert language text and messages
$template->set_var(
    array(
        'HEADING_ADD_GROUP' => $HEADING['ADD_GROUP'],
        'TEXT_CANCEL'        => $TEXT['CANCEL'],
        'TEXT_RESET' => $TEXT['RESET'],
        'TEXT_ACTIVE' => $TEXT['ACTIVE'],
        'TEXT_DISABLED' => $TEXT['DISABLED'],
        'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
        'TEXT_USERNAME' => $TEXT['USERNAME'],
        'TEXT_PASSWORD' => $TEXT['PASSWORD'],
        'TEXT_RETYPE_PASSWORD' => $TEXT['RETYPE_PASSWORD'],
        'TEXT_DISPLAY_NAME' => $TEXT['DISPLAY_NAME'],
        'TEXT_EMAIL' => $TEXT['EMAIL'],
        'TEXT_GROUP' => $TEXT['GROUP'],
        'TEXT_SYSTEM_PERMISSIONS' => $TEXT['SYSTEM_PERMISSIONS'],
        'TEXT_MODULE_PERMISSIONS' => $TEXT['MODULE_PERMISSIONS'],
        'TEXT_TEMPLATE_PERMISSIONS' => $TEXT['TEMPLATE_PERMISSIONS'],
        'TEXT_NAME' => $TEXT['NAME'],
        'SECTION_PAGES' => $MENU['PAGES'],
        'SECTION_MEDIA' => $MENU['MEDIA'],
        'SECTION_MODULES' => $MENU['MODULES'],
        'SECTION_TEMPLATES' => $MENU['TEMPLATES'],
        'SECTION_SETTINGS' => $MENU['SETTINGS'],
        'SECTION_LANGUAGES' => $MENU['LANGUAGES'],
        'SECTION_USERS' => $MENU['USERS'],
        'SECTION_GROUPS' => $MENU['GROUPS'],
        'SECTION_ADMINTOOLS' => $MENU['ADMINTOOLS'],
        'TEXT_VIEW' => $TEXT['VIEW'],
        'TEXT_ADD' => $TEXT['ADD'],
        'TEXT_LEVEL' => $TEXT['LEVEL'],
        'TEXT_MODIFY' => $TEXT['MODIFY'],
        'TEXT_DELETE' => $TEXT['DELETE'],
        'TEXT_MODIFY_CONTENT' => $TEXT['MODIFY_CONTENT'],
        'TEXT_MODIFY_SETTINGS' => $TEXT['MODIFY_SETTINGS'],
        'HEADING_MODIFY_INTRO_PAGE' => $HEADING['MODIFY_INTRO_PAGE'],
        'TEXT_CREATE_FOLDER' => $TEXT['CREATE_FOLDER'],
        'TEXT_RENAME' => $TEXT['RENAME'],
        'TEXT_UPLOAD_FILES' => $TEXT['UPLOAD_FILES'],
        'TEXT_BASIC' => $TEXT['BASIC'],
        'TEXT_ADVANCED' => $TEXT['ADVANCED'],
        'CHANGING_PASSWORD' => $MESSAGE['USERS_CHANGING_PASSWORD'],
        'CHECKED' => ' checked="checked"',
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL,
        'FTAN' => $ftan
    )
);

// Parse template for add group form
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print the admin footer
$admin->print_footer();
