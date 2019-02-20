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
require '../../config.php';
require_once WB_PATH.'/framework/class.admin.php';
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Access', 'users_add',false);

// Create a javascript back link
$js_back = ADMIN_URL.'/users/index.php';

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}
// After check print the header
$admin->print_header();

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
    $admin->print_error($MESSAGE['USERS_NO_GROUP'], $js_back);
}

// Validate the Username
if (!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {
    $admin->print_error($MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.$MESSAGE['USERS_USERNAME_TOO_SHORT'], $js_back);
}

// Validate the Email
if ($email != ''){
    if ($admin->validate_email($email) == false){
        $admin->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back);
    }
} else { 
    // e-mail must be present
    $admin->print_error($MESSAGE['SIGNUP_NO_EMAIL'], $js_back);
}

// Choose group_id from groups_id - workaround for still remaining calls to group_id (to be cleaned-up)
$gid_tmp = explode(',', $groups_id);
$group_id = (in_array('1', $gid_tmp)) ? '1' : $gid_tmp[0]; 
unset($gid_tmp);

// Check if username already exists
$results = $database->query("SELECT `user_id` FROM `{TP}users` WHERE `username` = '".$username."'");
if ($results->numRows() > 0) {
    $admin->print_error($MESSAGE['USERS_USERNAME_TAKEN'], $js_back);
}

// Check if the email already exists
$rCheckEmail = "SELECT `user_id` FROM `{TP}users` WHERE `email` = '".$email."'";
if ($database->get_one($rCheckEmail) != false) {
    $admin->print_error($MESSAGE['USERS_EMAIL_TAKEN'], $js_back);
}

// Validate the Password
$sEncodedPassword = '';   
if($sNewPassword != ''){
    $checkPassword =  $admin->checkPasswordPattern($sNewPassword, $sRePassword);
    if (is_array($checkPassword)){
        $admin->print_error(explode('<br />', $checkPassword[0]), $js_back);
    } else {
        $sEncodedPassword = $checkPassword;
    }            
}

// Insert User record into the Database
$aInsert = array(
    'group_id'     => $group_id,
    'groups_id'    => $groups_id,
    'active'       => $active,
    'username'     => $username,
    'password'     => $sEncodedPassword,
    'display_name' => $display_name,
    'home_folder'  => $home_folder,
    'email'        => $email,
    'timezone'     => -72000, 
    'language'     => DEFAULT_LANGUAGE
);

$database->insertRow('{TP}users', $aInsert);
    if ($database->is_error()) {
        $admin->print_error($database->get_error());
    } else {
        $admin->print_success($MESSAGE['USERS_ADDED']);
    }
// Print admin footer
$admin->print_footer();
