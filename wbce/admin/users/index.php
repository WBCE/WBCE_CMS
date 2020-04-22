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
$oMsgBox = new MessageBox();
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
        'STATUS_ICON'         => ($iUserStatus == 0) ? $UserStatusActive : $UserStatusInactive, 
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
            $lastlogin = date(DATE_FORMAT." ".TIME_FORMAT, $user['login_when'] + TIMEZONE);
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


/**/
if(isset($_POST['submit'])){
    $aError = [];
    if (!$admin->checkFTAN()) {
        $admin->print_header();
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
    }
    
    // Get details entered
    $groups_id    = (isset($_POST['groups'])) ? implode(",", $admin->add_slashes($_POST['groups'])) : ''; //should check permissions
    $groups_id    = trim($groups_id, ','); // there will be an additional ',' when "Please Choose" was selected, too
    $active       = $admin->add_slashes($_POST['active'][0]);
    $usernameTmp  = $admin->get_post_escaped('username_fieldname');
    $username     = strtolower($admin->get_post_escaped($usernameTmp));
    $sNewPassword = $admin->get_post('password');
    $sRePassword  = $admin->get_post('password2');
    $display_name = $admin->get_post_escaped('display_name');
    $email        = $admin->get_post_escaped('email');
    $home_folder  = $admin->get_post_escaped('home_folder');

    // Check values
    if ($groups_id == '') {
        echo($MESSAGE['USERS_NO_GROUP']);
    }

    // Validate the Username
    if (!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {    
        $aError[] = $MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.$MESSAGE['USERS_USERNAME_TOO_SHORT'];
    }

    // Validate the Email
    if ($email != ''){
        if ($admin->validate_email($email) == false){
            $_SESSSION['add_user'] = 'moin';
            $aError[] = $MESSAGE['USERS_INVALID_EMAIL'];
        }
    } else { 
        // e-mail must be present
        $aError[] = $MESSAGE['SIGNUP_NO_EMAIL'];
    }

    // Choose group_id from groups_id - workaround for still remaining calls to group_id (to be cleaned-up)
    $gid_tmp = explode(',', $groups_id);
    $group_id = (in_array('1', $gid_tmp)) ? '1' : $gid_tmp[0]; 
    unset($gid_tmp);

    // Check if username already exists
    $results = $database->query("SELECT `user_id` FROM `{TP}users` WHERE `username` = '".$username."'");
    if ($results->numRows() > 0) {
        $aError[] = $MESSAGE['USERS_USERNAME_TAKEN'];
    }

    // Check if the email already exists
    $rCheckEmail = "SELECT `user_id` FROM `{TP}users` WHERE `email` = '".$email."'";
    if ($database->get_one($rCheckEmail) != false) {
        $aError[] = $MESSAGE['USERS_EMAIL_TAKEN'];
    }

    // Validate the Password
    $sEncodedPassword = '';   
    if($sNewPassword != ''){
        $checkPassword =  $admin->checkPasswordPattern($sNewPassword, $sRePassword);
        if (is_array($checkPassword)){
            foreach($checkPassword as $str)
                $aError[] = $str;
        } else {
            $sEncodedPassword = $checkPassword;
        }            
    } else {
        $aError[] = $MESSAGE['USERS_PASSWORD_TOO_SHORT'];
    }

    // Insert User record into the Database
    $aInsert = array(
        'group_id'           => $group_id,
        'groups_id'          => $groups_id,
        'active'             => $active,
        'username'           => $username,
        'password'           => $sEncodedPassword,
        'display_name'       => $display_name,
        'home_folder'        => $home_folder,
        'email'              => $email,
        'timezone'           => -72000, 
        'language'           => DEFAULT_LANGUAGE,
        'signup_checksum'    => date("Y-m-d H:i:s", time()),
        'signup_timestamp'   => time(),
        'signup_confirmcode' => 'by admin uid: '.$admin->get_user_id(),
    );
    
    if(empty($aError)){
        $database->insertRow('{TP}users', $aInsert);
        if ($database->is_error()) {
            $aError[] = ($database->get_error());
        } else {
            $oMsgBox->success('<b>'.$display_name.'</b><br>'.$MESSAGE['USERS_ADDED']);
            $oMsgBox->redirect(ADMIN_URL.'/users/');
            
        }
    } else {
        foreach($aError as $str) $oMsgBox->error($str);
    }
}

// Setup template object, parse vars to it, then parse it
// Create new template object
$oTemplate = new Template(dirname($admin->correct_theme_source('users_form.htt')));
// $oTemplate->debug = true;
$oTemplate->set_file('page', 'users_form.htt');
$oTemplate->set_block('page', 'main_block', 'main');


$oTemplate->set_var(array(
        'HEADING_ADD_USER'   => $HEADING['ADD_USER'],
        'TEXT_CANCEL'        => $TEXT['CANCEL'],
        'DISPLAY_EXTRA'      => 'display:none;', 
        #'ACTION_URL'         => ADMIN_URL.'/users/add.php', 
        'ACTION_URL'         => '', 
        'SUBMIT_TITLE'       => $TEXT['ADD'], 
        'FTAN'               => $admin->getFTAN(), 
        'INPUT_NEW_PASSWORD' => $admin->passwordField('password'), 
        // insert urls
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL'    => WB_URL,
        'THEME_URL' => THEME_URL,
    
        'USERNAME'     => isset($aInsert['username']) ? $aInsert['username'] : '',
        'DISPLAY_NAME' => isset($aInsert['display_name']) ? $aInsert['display_name'] : '',
        'EMAIL'        => isset($aInsert['email']) ? $aInsert['email'] : '',
    )
);
$oTemplate->set_var('ACTIVE_CHECKED', ' checked="checked"');
$oTemplate->set_var('DISABLED_CHECKED', '');
if (isset($aInsert['active']) && $aInsert['active'] == 0) {
    $oTemplate->set_var('ACTIVE_CHECKED', '');
    $oTemplate->set_var('DISABLED_CHECKED', ' checked="checked"');
}

$oMsgBox->display();
// Add groups to list
$oTemplate->set_block('main_block', 'group_list_block', 'group_list');
$results = $database->query(
    "SELECT `group_id`, `name` FROM `{TP}groups` WHERE `group_id` != '1'"
);
$aSelectedGroups = explode(",", $aInsert['groups_id']);
if($results->numRows() > 0) {
    $oTemplate->set_var('ID', '');
    $oTemplate->set_var('NAME', $TEXT['PLEASE_SELECT'].'...');
    $oTemplate->set_var('SELECTED', ' selected="selected"');
    $oTemplate->parse('group_list', 'group_list_block', true);
    while($group = $results->fetchRow()) {
        $oTemplate->set_var('ID', $group['group_id']);
        $oTemplate->set_var('NAME', $group['name']);
        $oTemplate->set_var('SELECTED', '');
        if (in_array($group['group_id'], $aSelectedGroups)) {
            $oTemplate->set_var('SELECTED', ' selected="selected"');
        }
        $oTemplate->parse('group_list', 'group_list_block', true);
    }
}
// Only allow the user to add a user to the Administrators group if they belong to it
if(in_array(1, $admin->get_groups_id())) {
    $users_groups = $admin->get_groups_name();
    $oTemplate->set_var('ID', '1');
    $oTemplate->set_var('NAME', $users_groups[1]);
    $oTemplate->set_var('SELECTED', '');
    if (in_array(1, $aSelectedGroups)) {
        $oTemplate->set_var('SELECTED', ' selected="selected"');
    }
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
