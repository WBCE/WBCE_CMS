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

 
/**
 * This file is included in signup_form.php
**/

// Must include code to stop this file being access directly
defined('WB_PATH') or die('Cannot access this file directly');

// Get raw user inputs
$username     = strtolower(strip_tags($wb->get_post('username')));
$display_name = strip_tags($wb->get_post('display_name'));
$email        = $wb->get_post('email');
$gdpr_check   = $wb->get_post('gdpr_check');
$groups_id    = FRONTEND_SIGNUP;

// Prepare user variables for SQL queries
$sUsername    = $database->escapeString($username);
$sDisplayName = $database->escapeString($display_name);
$sEmail       = $database->escapeString($email);
$sGroupsId    = $database->escapeString($groups_id);

// //////////////////////////////
// Check user inputs

/**
 * we don't need to query $groups_id, as the  groups_id  is set to a  default  value  right now;
 * maybe in a future update this setting could be made selectable, but it ain't the case for now.
 *
 *	if ($groups_id == '') 
 *		$errors['USERS_NO_GROUP'] = $MESSAGE['USERS_NO_GROUP'];
**/

// check username
if (!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)){ 
	$username_error = true;
	$errors['USERS_NAME_INVALID_CHARS'] = $MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.$MESSAGE['USERS_USERNAME_TOO_SHORT'];
}
if (account_usernameAlreadyTaken($sUsername) != false){
	$username_error = true;
	$errors['USERS_USERNAME_TAKEN'] = $MESSAGE['USERS_USERNAME_TAKEN'];
}

// check display_name
if ($display_name == ''){
	$display_name_error = true;
	$errors['DISPLAY_NAME_EMPTY'] = $MESSAGE['DISPLAY_NAME_EMPTY'];

}

// check email
if ($email != '' && $wb->validate_email($email) == false){
	$email_error = true;
	$errors['USERS_INVALID_EMAIL'] = $MESSAGE['USERS_INVALID_EMAIL'];
}
if ($email == ''){
	$email_error = true;
	$errors['SIGNUP_NO_EMAIL'] = $MESSAGE['SIGNUP_NO_EMAIL'];
}
if (account_emailAlreadyTaken($sEmail) != false){
	$email_error = true;
	$errors['USERS_EMAIL_TAKEN'] = $MESSAGE['USERS_EMAIL_TAKEN'];
}


// check GDPR agreement checkbox
if ($gdpr_check == false){
	$gdpr_error = true;
	$errors['GDPR_AGREEMENT_MISSING'] = $MESSAGE['GDPR_AGREEMENT_MANDATORY'];
}

// check captcha
if (ENABLED_CAPTCHA) {
	$aRplc = array('SERVER_EMAIL' => SERVER_EMAIL);
	$sIncorrectCaptcha = replace_vars($MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'], $aRplc);	
	if (isset($_POST['captcha']) AND $_POST['captcha'] != ''){
		if (!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
			$errors['IncorrectCaptcha'] = $sIncorrectCaptcha;
		}
	} else {
		$errors['IncorrectCaptcha'] = $sIncorrectCaptcha;
	}
	// unset captcha if set
	if (isset($_SESSION['captcha'])) unset($_SESSION['captcha']);
}

// ///////////////////////////////////////////////////////////////////
// if no errors so far, proceed with database update and mail sending
// ///////////////////////////////////////////////////////////////////

if (empty($errors) === true) {	
	$sLC             = defined('LANGUAGE') ? LANGUAGE : defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN';
	$sOnScreenSwitch = '';	
	
	// generate password
	$sPassword       = account_GenerateRandomPassword();
	$md5password     = md5($sPassword);
	
	$sReadableDateTime = date("Y-m-d H:i:s", time());

	// /////////////////////////////
	// add new user into database
	// /////////////////////////////
	
	$aInsertUser = array(
		'group_id'         => $sGroupsId,
		'groups_id'        => $sGroupsId,
		'active'           => $config['user_activated_on_signup'],
		'username'         => $sUsername,
		'password'         => $md5password,
		'display_name'     => $sDisplayName,
		'email'            => $sEmail,
		'gdpr_check'       => intval(1),
		'signup_checksum'  => $sReadableDateTime,
		'signup_timestamp' => time(),
	); 	 		
	$database->insertRow('{TP}users', $aInsertUser);
	
	if ($database->is_error()) {		
		header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs"); //&err=".$database->get_error());
		exit(0);
	}				
	
	// ///////////////////////////////
	// set up the E-mail to be send
	// ///////////////////////////////	
	
	$iUserID           = $database->getLastInsertId();
	$sMailFrom         = SERVER_EMAIL;
	$sMailTo           = $email;
	$sEmailSubject     = '';
	$sErrorMessage     = $MESSAGE['GENERIC_ISSUE_MESSAGE'];	
	$sConfirmTimeout   = (string)(time() + 86400); // now + 24hours
	$sConfirmCode      = md5(md5($sUsername.$sTimeOut).$sTimeOut);
	$sConfirmationUrl  = WB_URL.'/account/confirm.php?id='.$sConfirmCode.'&lc='.$sLC;
	$sCheckSum         = substr(md5($sConfirmCode), 0, 10);
	
	// template token replacement array (will be added to down the code)
	$aTokenReplace = array( 
		'USER_ID'              => $iUserID,
		'LOGIN_NAME'           => $sUsername, 
		'LOGIN_DISPLAY_NAME'   => $sDisplayName, 
		'LOGIN_PASSWORD'       => $sPassword,
		'LOGIN_WEBSITE_TITLE'  => WEBSITE_TITLE, 
		'SIGNUP_DATE'          => date("Y-m-d H:i:s", time()),
		'LOGIN_EMAIL'          => $email,
		'SUPPORT_EMAIL'        => account_getAccountsManagerEmail(),
		'APPROVAL_LINK'        => account_genEmailLinkFromUri($sConfirmationUrl.'&mng=1&sum='.$sCheckSum),
		'CONFIRMATION_LINK'    => account_genEmailLinkFromUri($sConfirmationUrl.'&mng=0&sum='.substr(md5($sCheckSum), 0, 10)),
		'CONFIRMATION_TIMEOUT' => date("Y-m-d H:i:s", $sConfirmTimeout), 
		'LOGIN_URL'            => defined('LOGIN_URL') ? LOGIN_URL : WB_URL.'/account/login.php'
	);	

	if ($config['signup_double_opt_in'] == 1){
		// prepare E-Mail with activation link to send to the prospect
		$sOnScreenSwitch    = 'activation_link_sent';
		$sEmailTemplateName = 'activation_link';	
		$sMailTo            = $email;

		$aUpdateUser = array(
			'user_id'            => $iUserID,
			'signup_confirmcode' => $sConfirmCode,
			'signup_timeout'     => $sConfirmTimeout,
			'signup_checksum'    => $sCheckSum,
		);
		$database->updateRow('{TP}users', 'user_id', $aUpdateUser);
	}
		
	if ($config['signup_double_opt_in'] == 0 && $config['user_activated_on_signup'] == 1){
		// prepare E-Mail with login details to send to the prospect
		$sOnScreenSwitch    = 'login_details_just_sent';
		$sEmailTemplateName = 'welcome_login_info';	
		$sMailTo            = $email;
		
		// silently send E-Mail to AccountsManager to inform him about the new sign-up
		account_sendEmail(account_getAccountsManagerEmail(), $aTokenReplace, 'new_user_manager_info');
	}	
		
	if($config['signup_double_opt_in'] == 0 && $config['user_activated_on_signup'] == 0){
		$aUpdateUser = array(
			'user_id'            => $iUserID,
			'signup_confirmcode' => $sConfirmCode,
			'signup_timeout'     => $sConfirmTimeout,
		);
		$database->updateRow('{TP}users', 'user_id', $aUpdateUser);
		
		// prepare E-Mail with approval link to send to the AccountsManager
		$sOnScreenSwitch    = 'manager_confirm_new_signup';
		$sEmailTemplateName = 'manager_approve_new_user';
		$sMailTo            = account_getAccountsManagerEmail();
	}		
	
	// ///////////////////////////////
	// Send prepared E-Mail
	// ///////////////////////////////
	if ( account_sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject) == true ) {		
		header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=".$sOnScreenSwitch);		
	} else {
		header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&sEmailTemplateName:".$sEmailTemplateName);		
	} 
}