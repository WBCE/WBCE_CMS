<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * More Baking. Less Struggling.
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
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Access', 'users_modify', false);

// Create a javascript back link
$js_back = ADMIN_URL.'/users/index.php';

if( !$admin->checkFTAN() )
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$js_back);
}
// After check print the header
$admin->print_header();

// Check if user id is a valid number and doesnt equal 1
if(!isset($_POST['user_id']) OR !is_numeric($_POST['user_id']) OR $_POST['user_id'] == 1) {
    header("Location: index.php");
    exit(0);
} else {
    $user_id = $_POST['user_id'];
}

// Gather details entered
$groups_id = (isset($_POST['groups'])) ? implode(",", $admin->add_slashes($_POST['groups'])) : '';
$active = $admin->add_slashes($_POST['active'][0]);
$username_fieldname = $admin->get_post_escaped('username_fieldname');
$username = strtolower($admin->get_post_escaped($username_fieldname));
$password = $admin->get_post('password');
$password2 = $admin->get_post('password2');
$display_name = $admin->get_post_escaped('display_name');
$email = $admin->get_post_escaped('email');
$home_folder = $admin->get_post_escaped('home_folder');

// Check values
if($groups_id == "") {
    $admin->print_error($MESSAGE['USERS_NO_GROUP'], $js_back);
}
if(!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {
    $admin->print_error( $MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.
                      $MESSAGE['USERS_USERNAME_TOO_SHORT'], $js_back);
}
if($password != "") {
    if(strlen($password) < 2) {
        $admin->print_error($MESSAGE['USERS_PASSWORD_TOO_SHORT'], $js_back);
    }
    if($password != $password2) {
        $admin->print_error($MESSAGE['USERS_PASSWORD_MISMATCH'], $js_back);
    }
}

// choose group_id from groups_id - workaround for still remaining calls to group_id (to be cleaned-up)
$gid_tmp = explode(',', $groups_id);
if(in_array('1', $gid_tmp)) $group_id = '1'; // if user is in administrator-group, get this group
else $group_id = $gid_tmp[0]; // else just get the first one
unset($gid_tmp);


if($email != "")
{
    if($admin->validate_email($email) == false)
    {
        $admin->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back);
    }
} else { // e-mail must be present
    $admin->print_error($MESSAGE['SIGNUP_NO_EMAIL'], $js_back);
}

// Check if the email already exists
$results = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE email = '".$admin->add_slashes($_POST['email'])."' AND user_id <> '".$user_id."' ");
if($results->numRows() > 0)
{
    if(isset($MESSAGE['USERS_EMAIL_TAKEN']))
    {
        $admin->print_error($MESSAGE['USERS_EMAIL_TAKEN'], $js_back);
    } else {
        $admin->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back);
    }
}

// Prevent from renaming user to "admin"
if($username != 'admin') {
    $username_code = ", username = '$username'";
} else {
    $username_code = '';
}

// Update the database
if($password == "") {
    $query = "UPDATE ".TABLE_PREFIX."users SET groups_id = '$groups_id', group_id = '$group_id', active = '$active'$username_code, display_name = '$display_name', home_folder = '$home_folder', email = '$email' WHERE user_id = '$user_id'";
} else {
    // MD5 supplied password
    $md5_password = md5($password);
    $query = "UPDATE ".TABLE_PREFIX."users SET groups_id = '$groups_id', group_id = '$group_id', active = '$active'$username_code, display_name = '$display_name', home_folder = '$home_folder', email = '$email', password = '$md5_password' WHERE user_id = '$user_id'";
}
$database->query($query);
if($database->is_error()) {
    $admin->print_error($database->get_error(),$js_back);
} else {
    $admin->print_success($MESSAGE['USERS_SAVED']);
}

// Print admin footer
$admin->print_footer();
