<?php
/**
 *
 * @category        admin
 * @package         users
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: index.php 5 2015-04-27 08:02:19Z luisehahne $
 * @filesource      $HeadURL: https://localhost:8443/svn/wb283Sp4/SP4/branches/wb/admin/users/index.php $
 * @lastmodified    $Date: 2015-04-27 10:02:19 +0200 (Mo, 27. Apr 2015) $
 *
*/

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Access', 'users');

$iUserStatus = 1;
$iUserStatus = ( ( $admin->get_get('status')==1 ) ? 0 : $iUserStatus );
unset($_GET);

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('users.htt')));
// $template->debug = true;

$template->set_file('page', 'users.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_block("main_block", "manage_groups_block", "groups");
$template->set_var('ADMIN_URL', ADMIN_URL);
$template->set_var('FTAN', $admin->getFTAN());
$template->set_var('USER_STATUS', $iUserStatus );

$UserStatusActive = 'url('.THEME_URL.'/images/user.png)';
$UserStatusInactive = 'url('.THEME_URL.'/images/user_red.png)';

$sUserTitle = ($iUserStatus == 0) ? $MENU['USERS'].' '.strtolower($TEXT['ACTIVE']) : $MENU['USERS'].' '.strtolower($TEXT['DELETED']) ;

$template->set_var('TEXT_USERS', $sUserTitle.' '.$TEXT['SHOW'] );
$template->set_var('STATUS_ICON', ( ($iUserStatus==0) ? $UserStatusActive : $UserStatusInactive) );

// Get existing value from database
$sql  = 'SELECT `user_id`, `username`, `display_name`, `active` FROM `'.TABLE_PREFIX.'users` ' ;
$sql .= 'WHERE user_id != 1 ';
$sql .=     'AND active = '.$iUserStatus.' ';
$sql .= 'ORDER BY `display_name`,`username`';

$query = "SELECT user_id, username, display_name, active FROM ".TABLE_PREFIX."users WHERE user_id != '1' ORDER BY display_name,username";
$results = $database->query($sql);
if($database->is_error()) {
    $admin->print_error($database->get_error(), 'index.php');
}

$sUserList  = $TEXT['LIST_OPTIONS'].' ';
$sUserList .= ($iUserStatus == 1) ? $MENU['USERS'].' '.strtolower($TEXT['ACTIVE']) : $MENU['USERS'].' '.strtolower($TEXT['DELETED']) ;
// Insert values into the modify/remove menu
$template->set_block('main_block', 'list_block', 'list');
if($results->numRows() > 0) {
    // Insert first value to say please select
    $template->set_var('VALUE', '');
    $template->set_var('NAME', $sUserList);
    $template->set_var('STATUS', 'class="user-active"' );
    $template->parse('list', 'list_block', true);
    // Loop through users
    while($user = $results->fetchRow()) {
        $template->set_var('VALUE',$admin->getIDKEY($user['user_id']));
        $template->set_var('STATUS', ($user['active']==false ? 'class="user-inactive"' : 'class="user-active"') );
        $template->set_var('NAME', $user['display_name'].' ('.$user['username'].')');
        $template->parse('list', 'list_block', true);
    }
} else {
    // Insert single value to say no users were found
    $template->set_var('NAME', $TEXT['NONE_FOUND']);
    $template->parse('list', 'list_block', true);
}

// Insert permissions values
if($admin->get_permission('users_add') != true) {
    $template->set_var('DISPLAY_ADD', 'hide');
}
if($admin->get_permission('users_modify') != true) {
    $template->set_var('DISPLAY_MODIFY', 'hide');
}
if($admin->get_permission('users_delete') != true) {
    $template->set_var('DISPLAY_DELETE', 'hide');
}
$HeaderTitle = $HEADING['MODIFY_DELETE_USER'].' ';
$HeaderTitle .= (($iUserStatus == 1) ? strtolower($TEXT['ACTIVE']) : strtolower($TEXT['DELETED']));
// Insert language headings
$template->set_var(array(
        'HEADING_MODIFY_DELETE_USER' => $HeaderTitle,
        'HEADING_ADD_USER' => $HEADING['ADD_USER']
        )
);
// insert urls
$template->set_var(array(
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL
        )
);
// Insert language text and messages
$template->set_var(array(
        'TEXT_MODIFY' => $TEXT['MODIFY'],
        'TEXT_DELETE' => $TEXT['DELETE'],
        'TEXT_MANAGE_GROUPS' => ( $admin->get_permission('groups') == true ) ? $TEXT['MANAGE_GROUPS'] : "**",
        'CONFIRM_DELETE' => (($iUserStatus == 1) ? $TEXT['ARE_YOU_SURE'] : $MESSAGE['USERS']['CONFIRM_DELETE'])
        )
);
if ( $admin->get_permission('groups') == true ) $template->parse("groups", "manage_groups_block", true);
// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('users_form.htt')));
// $template->debug = true;
$template->set_file('page', 'users_form.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('DISPLAY_EXTRA', 'display:none;');
$template->set_var('ACTIVE_CHECKED', ' checked="checked"');
$template->set_var('ACTION_URL', ADMIN_URL.'/users/add.php');
$template->set_var('SUBMIT_TITLE', $TEXT['ADD']);
$template->set_var('FTAN', $admin->getFTAN());
// insert urls
$template->set_var(array(
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL
        )
);

// Add groups to list
$template->set_block('main_block', 'group_list_block', 'group_list');
$results = $database->query("SELECT group_id, name FROM ".TABLE_PREFIX."groups WHERE group_id != '1'");
if($results->numRows() > 0) {
    $template->set_var('ID', '');
    $template->set_var('NAME', $TEXT['PLEASE_SELECT'].'...');
    $template->set_var('SELECTED', ' selected="selected"');
    $template->parse('group_list', 'group_list_block', true);
    while($group = $results->fetchRow()) {
        $template->set_var('ID', $group['group_id']);
        $template->set_var('NAME', $group['name']);
        $template->set_var('SELECTED', '');
        $template->parse('group_list', 'group_list_block', true);
    }
}
// Only allow the user to add a user to the Administrators group if they belong to it
if(in_array(1, $admin->get_groups_id())) {
    $users_groups = $admin->get_groups_name();
    $template->set_var('ID', '1');
    $template->set_var('NAME', $users_groups[1]);
    $template->set_var('SELECTED', '');
    $template->parse('group_list', 'group_list_block', true);
} else {
    if($results->numRows() == 0) {
        $template->set_var('ID', '');
        $template->set_var('NAME', $TEXT['NONE_FOUND']);
        $template->parse('group_list', 'group_list_block', true);
    }
}

// Insert permissions values
if($admin->get_permission('users_add') != true) {
    $template->set_var('DISPLAY_ADD', 'hide');
}

// Generate username field name
$username_fieldname = 'username_';
$salt = "abchefghjkmnpqrstuvwxyz0123456789";
srand((double)microtime()*1000000);
$i = 0;
while ($i <= 7) {
    $num = rand() % 33;
    $tmp = substr($salt, $num, 1);
    $username_fieldname = $username_fieldname . $tmp;
    $i++;
}

// Work-out if home folder should be shown
if(!HOME_FOLDERS) {
    $template->set_var('DISPLAY_HOME_FOLDERS', 'display:none;');
}

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Add media folders to home folder list
$template->set_block('main_block', 'folder_list_block', 'folder_list');
foreach(directory_list(WB_PATH.MEDIA_DIRECTORY) AS $name) {
    $template->set_var('NAME', str_replace(WB_PATH, '', $name));
    $template->set_var('FOLDER', str_replace(WB_PATH.MEDIA_DIRECTORY, '', $name));
    $template->set_var('SELECTED', ' ');
    $template->parse('folder_list', 'folder_list_block', true);
}

// Insert language text and messages
$template->set_var(array(
            'TEXT_CANCEL' => $TEXT['CANCEL'],
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
            'TEXT_NONE' => $TEXT['NONE'],
            'TEXT_HOME_FOLDER' => $TEXT['HOME_FOLDER'],
            'USERNAME_FIELDNAME' => $username_fieldname,
            'CHANGING_PASSWORD' => $MESSAGE['USERS']['CHANGING_PASSWORD']
            )
    );

// Parse template for add user form
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

$admin->print_footer();
