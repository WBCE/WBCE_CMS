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

require '../../config.php';
require_once WB_PATH.'/framework/class.admin.php';
$admin = new admin('Access', 'users');

$iUserStatus = 1;
$iUserStatus = ( ( $admin->get_get('status')==1 ) ? 0 : $iUserStatus );
unset($_GET);

// Setup template object, parse vars to it, then parse it
// Create new template object
$oTemplate = new Template(dirname($admin->correct_theme_source('users.htt')));
// $oTemplate->debug = true;

$oTemplate->set_file('page', 'users.htt');
$oTemplate->set_block('page', 'main_block', 'main');
$oTemplate->set_block("main_block", "manage_groups_block", "groups");

$UserStatusActive = 'url('.THEME_URL.'/images/user.png)';
$UserStatusInactive = 'url('.THEME_URL.'/images/user_red.png)';
$sUserTitle = ($iUserStatus == 0) ? $MENU['USERS'].' '.strtolower($TEXT['ACTIVE']) : $MENU['USERS'].' '.strtolower($TEXT['DELETED']) ;

$oTemplate->set_var(
    array(
        'ADMIN_URL'           => ADMIN_URL, 
        'FTAN'                => $admin->getFTAN(), 
        'USER_STATUS'         => $iUserStatus, 
        'INPUT_NEW_PASSWORD'  => $admin->passwordField('password'), 
        'TEXT_USERS'          => $sUserTitle.' '.$TEXT['SHOW'] , 
        'STATUS_ICON'         => ($iUserStatus==0) ? $UserStatusActive : $UserStatusInactive, 
        'ADVANCED_SEARCH'     => $TEXT['ADVANCED_SEARCH'], 
        'QUICK_SEARCH_STRG_F' => $TEXT['QUICK_SEARCH_STRG_F'], 
    )
);
        
// Get existing values from database
$sSql  = "SELECT * FROM `{TP}users` WHERE 1 AND user_id != 1 
    AND active = ".$iUserStatus." 
    ORDER BY `display_name`, `username`";

$results = $database->query($sSql);
if($database->is_error()) {
    $admin->print_error($database->get_error(), 'index.php');
}

$sUserList  = $TEXT['LIST_OPTIONS'].' ';
$sUserList .= ($iUserStatus == 1) ? $MENU['USERS'].' '.strtolower($TEXT['ACTIVE']) : $MENU['USERS'].' '.strtolower($TEXT['DELETED']) ;
// Insert values into the modify/remove menu
$oTemplate->set_block('main_block', 'list_block', 'list');
$oTemplate->set_var('USERTYPE', $sUserList);
if($results->numRows() > 0) {
    // Loop through users
    while($user = $results->fetchRow()) {
        //print_r($user);
        if ($user['login_when'] == 0) {
            $lastlogin = '-';
        } else {
            $lastlogin = gmdate(DATE_FORMAT." ".TIME_FORMAT, $user['login_when'] + TIMEZONE);
        }
        if ($user['login_ip'] == 0) {
            $lastip="0.0.0.0";
        } else {
            $lastip=$user['login_ip'];
        }
        $oTemplate->set_var('VALUE',$admin->getIDKEY($user['user_id']));
        $oTemplate->set_var('STATUS', ($user['active']==false ? 'class="user-inactive"' : 'class="user-active"') );
        $oTemplate->set_var('NAME', $user['display_name'].' ('.$user['username'].')&nbsp;&nbsp;&nbsp;'.$lastlogin.'&nbsp;&nbsp;&nbsp;'.$lastip);
        $oTemplate->parse('list', 'list_block', true);
    }
} else {
    // Insert single value to say no users were found
    $oTemplate->set_var('NAME', $TEXT['NONE_FOUND']);
    $oTemplate->parse('list', 'list_block', true);
}

// Insert permissions values
if($admin->get_permission('users_add') != true) {
    $oTemplate->set_var('DISPLAY_ADD', 'hide');
}
if($admin->get_permission('users_modify') != true) {
    $oTemplate->set_var('DISPLAY_MODIFY', 'hide');
}
if($admin->get_permission('users_delete') != true) {
    $oTemplate->set_var('DISPLAY_DELETE', 'hide');
}
$HeaderTitle = $HEADING['MODIFY_DELETE_USER'].' ';
$HeaderTitle .= (($iUserStatus == 1) ? strtolower($TEXT['ACTIVE']) : strtolower($TEXT['DELETED']));
$oTemplate->set_var(
    array(
        // Insert language headings
        'HEADING_MODIFY_DELETE_USER' => $HeaderTitle,
        'HEADING_ADD_USER' => $HEADING['ADD_USER'],
        'HEADING_ACCESS' => $MENU['ACCESS'],
        'HEADING_USERS' => $MENU['USERS'],
        // insert urls
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL,
        // Insert language text and messages
        'TEXT_MODIFY' => $TEXT['MODIFY'],
        'TEXT_DELETE' => $TEXT['DELETE'],
        'TEXT_MANAGE_GROUPS' => ( $admin->get_permission('groups') == true ) ? $TEXT['MANAGE_GROUPS'] : "**",
        'CONFIRM_DELETE' => (($iUserStatus == 1) ? $TEXT['ARE_YOU_SURE'] : $MESSAGE['USERS_CONFIRM_DELETE'])
        )
);
if ( $admin->get_permission('groups') == true ) 
    $oTemplate->parse("groups", "manage_groups_block", true);
// Parse template object
$oTemplate->parse('main', 'main_block', false);
$oTemplate->pparse('output', 'page');

// Setup template object, parse vars to it, then parse it
// Create new template object
$oTemplate = new Template(dirname($admin->correct_theme_source('users_form.htt')));
// $oTemplate->debug = true;
$oTemplate->set_file('page', 'users_form.htt');
$oTemplate->set_block('page', 'main_block', 'main');

$oTemplate->set_var(array(
        'DISPLAY_EXTRA'      => 'display:none;', 
        'ACTIVE_CHECKED'     => ' checked="checked"', 
        'ACTION_URL'         => ADMIN_URL.'/users/add.php', 
        'SUBMIT_TITLE'       => $TEXT['ADD'], 
        'FTAN'               => $admin->getFTAN(), 
        'INPUT_NEW_PASSWORD' => $admin->passwordField('password'), 
        // insert urls
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL'    => WB_URL,
        'THEME_URL' => THEME_URL
    )
);

// Add groups to list
$oTemplate->set_block('main_block', 'group_list_block', 'group_list');
$results = $database->query("SELECT group_id, name FROM ".TABLE_PREFIX."groups WHERE group_id != '1'");
if($results->numRows() > 0) {
    $oTemplate->set_var('ID', '');
    $oTemplate->set_var('NAME', $TEXT['PLEASE_SELECT'].'...');
    $oTemplate->set_var('SELECTED', ' selected="selected"');
    $oTemplate->parse('group_list', 'group_list_block', true);
    while($group = $results->fetchRow()) {
        $oTemplate->set_var('ID', $group['group_id']);
        $oTemplate->set_var('NAME', $group['name']);
        $oTemplate->set_var('SELECTED', '');
        $oTemplate->parse('group_list', 'group_list_block', true);
    }
}
// Only allow the user to add a user to the Administrators group if they belong to it
if(in_array(1, $admin->get_groups_id())) {
    $users_groups = $admin->get_groups_name();
    $oTemplate->set_var('ID', '1');
    $oTemplate->set_var('NAME', $users_groups[1]);
    $oTemplate->set_var('SELECTED', '');
    $oTemplate->parse('group_list', 'group_list_block', true);
} else {
    if($results->numRows() == 0) {
        $oTemplate->set_var('ID', '');
        $oTemplate->set_var('NAME', $TEXT['NONE_FOUND']);
        $oTemplate->parse('group_list', 'group_list_block', true);
    }
}

// Insert permissions values
if($admin->get_permission('users_add') != true) {
    $oTemplate->set_var('DISPLAY_ADD', 'hide');
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
    $oTemplate->set_var('DISPLAY_HOME_FOLDERS', 'display:none;');
}

// Include the WB functions file
require_once WB_PATH.'/framework/functions.php';

// Add media folders to home folder list
$oTemplate->set_block('main_block', 'folder_list_block', 'folder_list');
foreach(directory_list(WB_PATH.MEDIA_DIRECTORY) AS $name) {
    $oTemplate->set_var('NAME', str_replace(WB_PATH, '', $name));
    $oTemplate->set_var('FOLDER', str_replace(WB_PATH.MEDIA_DIRECTORY, '', $name));
    $oTemplate->set_var('SELECTED', ' ');
    $oTemplate->parse('folder_list', 'folder_list_block', true);
}

// Insert language text and messages
$oTemplate->set_var(array(
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
            'CHANGING_PASSWORD' => $MESSAGE['USERS_CHANGING_PASSWORD']
            )
    );

// Parse template for add user form
$oTemplate->parse('main', 'main_block', false);
$oTemplate->pparse('output', 'page');

$admin->print_footer();
