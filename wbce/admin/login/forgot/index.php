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

// Include the configuration file
require('../../../config.php');
// Include the language file
require(WB_PATH.'/languages/'.DEFAULT_LANGUAGE.'.php');
// Include the database class file and initiate an object
$admin = new admin('Start', 'start', false, false);

// Get the website title
$results = $database->query("SELECT value FROM ".TABLE_PREFIX."settings WHERE name = 'title'");
$results = $results->fetchRow();
$website_title = $results['value'];

// Check if the user has already submitted the form, otherwise show it
if(isset($_POST['email']) AND $_POST['email'] != "") {
	
	$email = htmlspecialchars($_POST['email'],ENT_QUOTES);
	
	// Check if the email exists in the database
	$query = "SELECT user_id,username,display_name,email,last_reset,password FROM ".TABLE_PREFIX."users WHERE email = '".$admin->add_slashes($_POST['email'])."'";
	$results = $database->query($query);
	if($results->numRows() > 0) {

		// Get the id, username, email, and last_reset from the above db query
		$results_array = $results->fetchRow();
		
		// Check if the password has been reset in the last 2 hours
		$last_reset = $results_array['last_reset'];
		$time_diff = time()-$last_reset; // Time since last reset in seconds
		$time_diff = $time_diff/60/60; // Time since last reset in hours
		if($time_diff < 2) {
			
			// Tell the user that their password cannot be reset more than once per hour
			$message = $MESSAGE['FORGOT_PASS_ALREADY_RESET'];
			
		} else {
			
			$old_pass = $results_array['password'];
			
			// Generate a random password then update the database with it
			$new_pass = WbAuth::GenerateRandomPassword();
			
			$database->query("UPDATE ".TABLE_PREFIX."users SET password = '".WbAuth::Hash($new_pass)."', last_reset = '".time()."' WHERE user_id = '".$results_array['user_id']."'");
			
			if($database->is_error()) {
				// Error updating database
				$message = $database->get_error();
			} else {
				// Setup email to send
				$mail_to = $email;
				$mail_subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];

				// Replace placeholders from language variable with values
				$search = array('{LOGIN_DISPLAY_NAME}', '{LOGIN_WEBSITE_TITLE}', '{LOGIN_NAME}', '{LOGIN_PASSWORD}');
				$replace = array($results_array['display_name'], WEBSITE_TITLE, $results_array['username'], $new_pass); 
				$mail_message = str_replace($search, $replace, $MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT']);

				// Try sending the email
				if($admin->mail(SERVER_EMAIL,$mail_to,$mail_subject,$mail_message)) { 
					$message = $MESSAGE['FORGOT_PASS_PASSWORD_RESET'];
					$display_form = false;
				} else {
					$database->query("UPDATE ".TABLE_PREFIX."users SET password = '".$old_pass."' WHERE user_id = '".$results_array['user_id']."'");
					$message = $MESSAGE['FORGOT_PASS_CANNOT_EMAIL'];
				}
			}
		
		}
		
	} else {
		// Email doesn't exist, so tell the user
		$message = $MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'];
		// and delete the wrong Email
		$email = '';
	}
	
} else {
	$email = '';
}

if(!isset($message)) {
	$message = $MESSAGE['FORGOT_PASS_NO_DATA'];
	$message_color = '000000';
} else {
	$message_color = 'FF0000';
}
	
// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('login_forgot.htt')));
$template->set_file('page', 'login_forgot.htt');
$template->set_block('page', 'main_block', 'main');
if(defined('FRONTEND')) {
	$template->set_var('ACTION_URL', 'forgot.php');
} else {
	$template->set_var('ACTION_URL', 'index.php');
}
$template->set_var('EMAIL', $email);

if(isset($display_form)) {
	$template->set_var('DISPLAY_FORM', 'display:none;');
}

$template->set_var(array(
								'SECTION_FORGOT' => $MENU['FORGOT'],
								'MESSAGE_COLOR' => $message_color,
								'MESSAGE' => $message,
								'WB_URL' => WB_URL,
								'ADMIN_URL' => ADMIN_URL,
								'THEME_URL' => THEME_URL,
								'LANGUAGE' => strtolower(LANGUAGE),
								'TEXT_EMAIL' => $TEXT['EMAIL'],
								'TEXT_SEND_DETAILS' => $TEXT['SEND_DETAILS'],
								'TEXT_HOME' => $TEXT['HOME'],
								'TEXT_NEED_TO_LOGIN' => $TEXT['NEED_TO_LOGIN']
								)
						);

if(defined('FRONTEND')) {
	$template->set_var('LOGIN_URL', WB_URL.'/account/login.php');
} else {
	$template->set_var('LOGIN_URL', ADMIN_URL);
}
$template->set_var('INTERFACE_URL', ADMIN_URL.'/interface');	

if(defined('DEFAULT_CHARSET')) {
	$charset=DEFAULT_CHARSET;
} else {
	$charset='utf-8';
}

$template->set_var('CHARSET', $charset);	

$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');
