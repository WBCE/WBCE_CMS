<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       2009-2012, WebsiteBaker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id$
 * @filesource		$HeadURL$
 * @lastmodified    $Date$
 *
 */

require_once realpath('../config.php');
require_once __DIR__ .'/functions/functions.php';
$config = account_getConfig(); // get config from INI file

$sAutoLanguage = (isset($_GET['lc']) ? $_GET['lc'] : 'EN'); 
defined('LANGUAGE') or define('LANGUAGE', $sAutoLanguage); 

$sConfirmationId  = (isset($_GET['id']) ? $_GET['id'] :  '');

if(!class_exists('frontend', false)){ 
	include WB_PATH.'/framework/class.frontend.php'; 
}

require_once WB_PATH .'/framework/functions.php';

// Create new frontend object
$wb = new frontend(false);
$sConfirmTimeout = (string)(time() + 86400); // now + 24hours
$sConfirmCode    = md5(md5($sUsername.$sConfirmTimeout).$sConfirmTimeout);

if(isset($_GET['mng']) && $_GET['mng'] > intval(1)){
	header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&excessive_manager_use");	
	exit(0);
}
if($iUserID = account_userIdFromConfirmcode($sConfirmationId)){
	
	// get User Data
	$aUser = account_getUserData($iUserID);	
	
	// get LanguageCode		
	$sLC = $aUser['language'];
	$sLC = ($sLC == '') ? $sAutoLanguage : $sLC;
	
	// generate password
	$sPassword   = account_GenerateRandomPassword();
	$md5password = md5($sPassword);
	
	$sReadableDateTime = date("Y-m-d H:i:s", time());
		
	if(isset($_GET['mng']) && $_GET['mng'] == intval(1)){
		
	
		if(account_checkConfirmSum($_GET['sum'], $iUserID) == false){	
			header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&check_sum");	
			exit(0);
		}

		// //////////////////////////////////////////////
		// AccountManager clicked on approval link;
		// send mail to user with his login details			

		$aUpdateUser = array(
			'user_id'            => $iUserID, 
			'signup_confirmcode' => '', 
			'signup_timeout'     => 0,
			'signup_checksum'    => $sReadableDateTime,
			'password'           => $md5password,
			'active'             => 1,
		);			
		$database->updateRow('{TP}users', 'user_id', $aUpdateUser);
		
		// create Token Replacement Array
		$aTokenReplace = array( 
			'USER_ID'             => $iUserID,
			'LOGIN_NAME'          => $aUser['username'], 
			'LOGIN_DISPLAY_NAME'  => $aUser['display_name'], 
			'LOGIN_PASSWORD'      => $sPassword,
			'LOGIN_WEBSITE_TITLE' => WEBSITE_TITLE, 
			'SUPPORT_EMAIL'       => account_getAccountsManagerEmail(),
			'LOGIN_URL'           => defined('LOGIN_URL') ? LOGIN_URL : WB_URL.'/account/login.php'
		);	

		// Try sending the 'welcome_login_info' Mail to User
		if ( account_sendEmail($aUser['email'], $aTokenReplace, 'welcome_login_info') ) {			
			header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=manager_approval_feedback");			
		} else {			
			header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&err=");
		}	
		
	} else {
		$sMailFrom        = SERVER_EMAIL;
		$sMailTo          = $aUser['email'];
		$sEmailSubject    = '';
		$sErrorMessage    = $MESSAGE['GENERIC_ISSUE_MESSAGE'];
		
		$sConfirmTimeout  = (string)(time() + 86400); // now + 24hours
		$sConfirmCode     = md5(md5($sUsername.$sConfirmTimeout).$sConfirmTimeout);
		$sConfirmationUrl = WB_URL.'/account/confirm.php?id='.$sConfirmCode.'&lc='.$sLC;
		$sCheckSum        = substr(md5($sConfirmCode), 0, 10);
		
		$aTokenReplace = array( 
			'USER_ID'              => $iUserID,
			'LOGIN_NAME'           => $aUser['username'], 
			'LOGIN_DISPLAY_NAME'   => $aUser['display_name'], 
			'LOGIN_PASSWORD'       => $sPassword,
			'LOGIN_WEBSITE_TITLE'  => WEBSITE_TITLE, 
			'SIGNUP_DATE'          => $sReadableDateTime,
			'LOGIN_EMAIL'          => $aUser['email'],
			'CONFIRMATION_TIMEOUT' => date("Y-m-d H:i:s", $sConfirmTimeout),
			'LOGIN_URL'            => defined('LOGIN_URL') ? LOGIN_URL : WB_URL.'/account/login.php', 
			'APPROVAL_LINK'        => account_genEmailLinkFromUri($sConfirmationUrl.'&mng=1&sum='.$sCheckSum), 
			'CONFIRMATION_LINK'    => account_genEmailLinkFromUri($sConfirmationUrl.'&mng=0&sum='.substr(md5($sCheckSum), 0, 10)),
		);
		
		if($config['user_activated_on_signup'] == 1) { 
			$aUpdateUser = array(
				'user_id'            => $iUserID, 
				'signup_confirmcode' => '', 
				'signup_timeout'     => 0,
				'signup_checksum'    => $sReadableDateTime,
				'password'           => $md5password,
				'active'             => 1,
			);			
			$database->updateRow('{TP}users', 'user_id', $aUpdateUser);
		
			# send email to AccountsManager so he is informed about the new registration
			account_sendEmail(account_getAccountsManagerEmail(), $aTokenReplace, 'new_user_manager_info');			
			
			# send email to user and provide him with his login details
			// prepare vars for E-Mail to user
			$sEmailTemplateName = 'welcome_login_info';
			$sErrorMessage      = $MESSAGE['FORGOT_PASS_CANNOT_EMAIL'];	
			
			if ( account_sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject) ) {			
				header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=login_details_just_sent");				
			} else {
				$wb->print_error($sErrorMessage, $js_back, false);				
			}		

		} else { 	
			# send email to AccountsManager so he can confirm new user
			$aUpdateUser = array(
				'user_id'            => $iUserID, 
				'signup_confirmcode' => $sConfirmCode,
				'signup_timeout'     => $sConfirmTimeout,
				'signup_checksum'    => $sCheckSum,
			);			
			$database->updateRow('{TP}users', 'user_id', $aUpdateUser);
			
			if ( account_sendEmail(account_getAccountsManagerEmail(), $aTokenReplace, 'manager_approve_new_user') ) {			
				header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=manager_confirm_new_signup");				
			} else {
				$wb->print_error($sErrorMessage, $js_back, false);				
			}
		}  
		
	}
	
} else {		
	header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs");	
}