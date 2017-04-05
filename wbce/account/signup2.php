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

// Must include code to stop this file being access directly
if (! defined('WB_PATH')) {
	die('Cannot access this file directly');
}

// Setup wb object, skip header and skip permission checks
$wb = new wb('Start', 'start', false, false);
$js_back = WB_URL . '/account/signup.php';

// Get raw user inputs
$username = strtolower(strip_tags($wb->get_post('username')));
$display_name = strip_tags($wb->get_post('display_name'));
$email = $wb->get_post('email');

// Set some other variables
$groups_id = FRONTEND_SIGNUP;
$active = 1;
$bSignError = false;

// Check user inputs
if ($groups_id == '') {
	$wb->print_error($MESSAGE['USERS_NO_GROUP'], $js_back, false);
	$bSignError = true;
}
if (!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {
	$wb->print_error(
		$MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.
		$MESSAGE['USERS_USERNAME_TOO_SHORT'], $js_back
	);
	$bSignError = true;
}
if ($email != '') {
	if($wb->validate_email($email) == false) {
		$wb->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back, false);
		$bSignError = true;
	}
} else {
	$wb->print_error($MESSAGE['SIGNUP_NO_EMAIL'], $js_back, false);
	$bSignError = true;
}

// Replace placeholder with system values
$search = array('{SERVER_EMAIL}');
$replace = array(SERVER_EMAIL);

// Captcha
if (ENABLED_CAPTCHA) {
	$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'] = str_replace($search,$replace,$MESSAGE['MOD_FORM_INCORRECT_CAPTCHA']);
	if (isset($_POST['captcha']) AND $_POST['captcha'] != ''){
		// Check for a mismatch
		if (!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
			$wb->print_error($MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'], $js_back, false);
			$bSignError = true;
		}
	} else {
		$wb->print_error($MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'], $js_back, false);
		$bSignError = true;
	}
}
if (isset($_SESSION['captcha'])) {
	unset($_SESSION['captcha']);
}

// Prepare user land variables for SQL querries
$username_escaped = $database->escapeString($username);
$display_name_escaped = $database->escapeString($display_name);
$email_escaped = $database->escapeString($email);
$groups_id_escaped = $database->escapeString($groups_id);

// Check if username already exists
$sql = 'SELECT `user_id` FROM `'.TABLE_PREFIX.'users` WHERE `username` = \'' . $username_escaped . '\'';
$results = $database->query($sql);
if ($results->numRows() > 0) {
	$wb->print_error($MESSAGE['USERS_USERNAME_TAKEN'], $js_back, false);
	$bSignError = true;
}

// Check if the email already exists
$sql = 'SELECT `user_id` FROM `'.TABLE_PREFIX.'users` WHERE `email` = \'' . $email_escaped . '\'';
$results = $database->query($sql);
if ($results->numRows() > 0) {
	if (isset($MESSAGE['USERS_EMAIL_TAKEN'])) {
		$wb->print_error($MESSAGE['USERS_EMAIL_TAKEN'], $js_back, false);
		$bSignError = true;
	} else {
		$wb->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back, false);
		$bSignError = true;
	}
}

if ($bSignError === false) {
	// Generate a random password and hash it
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
	// hash password using MD5
	$md5_password = md5($new_pass);

	// add new user into database
	$sql = "INSERT INTO ".TABLE_PREFIX."users (group_id,groups_id,active,username,password,display_name,email) VALUES ('$groups_id_escaped', '$groups_id_escaped', '$active', '$username_escaped','$md5_password','$display_name_escaped','$email_escaped')";
	$database->query($sql);
	if ($database->is_error()) {
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
		if ($wb->mail(SERVER_EMAIL,$mail_to,$mail_subject,$mail_message)) {
			$display_form = false;
			$wb->print_success($MESSAGE['FORGOT_PASS_PASSWORD_RESET'], WB_URL.'/account/login.php' );
		} else {
			$database->query("DELETE FROM ".TABLE_PREFIX."users WHERE username = '$username_escaped'");
			$wb->print_error($MESSAGE['FORGOT_PASS_CANNOT_EMAIL'], $js_back, false);
		}
	}
}
