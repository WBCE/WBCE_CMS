<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: signup2.php 1635 2012-03-09 13:47:42Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/account/signup2.php $
 * @lastmodified    $Date: 2012-03-09 14:47:42 +0100 (Fr, 09. Mrz 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// require_once(WB_PATH.'/framework/class.wb.php');
$wb = new wb('Start', 'start', false, false);

// Get details entered
$groups_id = FRONTEND_SIGNUP;
$active = 1;
$username = strtolower(strip_tags($wb->get_post_escaped('username')));
$display_name = strip_tags($wb->get_post_escaped('display_name'));
$email = $wb->get_post('email');

// Create a javascript back link
$js_back = WB_URL.'/account/signup.php';
/*
if (!$wb->checkFTAN())
{
	$wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back, false);
	exit();
}
*/
// Check values
if($groups_id == "") {
	$wb->print_error($MESSAGE['USERS_NO_GROUP'], $js_back, false);
}
if(!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {
	$wb->print_error( $MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.
	                  $MESSAGE['USERS_USERNAME_TOO_SHORT'], $js_back);
}
if($email != "") {
	if($wb->validate_email($email) == false) {
		$wb->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back, false);
	}
} else {
	$wb->print_error($MESSAGE['SIGNUP_NO_EMAIL'], $js_back, false);
}

$email = $wb->add_slashes($email);
$search = array('{SERVER_EMAIL}');
$replace = array( SERVER_EMAIL);
// Captcha
if(ENABLED_CAPTCHA) {
	$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = str_replace($search,$replace,$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA']);
	if(isset($_POST['captcha']) AND $_POST['captcha'] != ''){
		// Check for a mismatch
		if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
			$wb->print_error($MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'], $js_back, false);
		}
	} else {
		$wb->print_error($MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'], $js_back, false);
	}
}
if(isset($_SESSION['captcha'])) { unset($_SESSION['captcha']); }

// Generate a random password then update the database with it
$new_pass = '';
$salt = "abchefghjkmnpqrstuvwxyz0123456789";
srand((double)microtime()*1000000);
$i = 0;
while ($i <= 7) {
	$num = rand() % 33;
	$tmp = substr($salt, $num, 1);
	$new_pass = $new_pass . $tmp;
	$i++;
}
$md5_password = md5($new_pass);

// Check if username already exists
$results = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE username = '$username'");
if($results->numRows() > 0) {
	$wb->print_error($MESSAGE['USERS_USERNAME_TAKEN'], $js_back, false);
}

// Check if the email already exists
$results = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE email = '".$wb->add_slashes($email)."'");
if($results->numRows() > 0) {
	if(isset($MESSAGE['USERS_EMAIL_TAKEN'])) {
		$wb->print_error($MESSAGE['USERS_EMAIL_TAKEN'], $js_back, false);
	} else {
		$wb->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back, false);
	}
}

// MD5 supplied password
$md5_password = md5($new_pass);

// Inser the user into the database
$query = "INSERT INTO ".TABLE_PREFIX."users (group_id,groups_id,active,username,password,display_name,email) VALUES ('$groups_id', '$groups_id', '$active', '$username','$md5_password','$display_name','$email')";
$database->query($query);

if($database->is_error()) {
	// Error updating database
	$message = $database->get_error();
} else {
	// Setup email to send
	$mail_to = $email;
	$mail_subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];

	// Replace placeholders from language variable with values
	$search = array('{LOGIN_DISPLAY_NAME}', '{LOGIN_WEBSITE_TITLE}', '{LOGIN_NAME}', '{LOGIN_PASSWORD}');
	$replace = array($display_name, WEBSITE_TITLE, $username, $new_pass); 
	$mail_message = str_replace($search, $replace, $MESSAGE['SIGNUP2_BODY_LOGIN_INFO']);

	// Try sending the email
	if($wb->mail(SERVER_EMAIL,$mail_to,$mail_subject,$mail_message)) {
		$display_form = false;
		$wb->print_success($MESSAGE['FORGOT_PASS_PASSWORD_RESET'], WB_URL.'/account/login.php' );
	} else {
		$database->query("DELETE FROM ".TABLE_PREFIX."users WHERE username = '$username'");
		$wb->print_error($MESSAGE['FORGOT_PASS_CANNOT_EMAIL'], $js_back, false);
	}
}

