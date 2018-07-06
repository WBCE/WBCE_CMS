<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       Website Baker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: forgot_form.php 1601 2012-02-07 22:48:27Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/forgot_form.php $
 * @lastmodified    $Date: 2012-02-07 23:48:27 +0100 (Di, 07. Feb 2012) $
 *
 */

defined('WB_PATH') or die("Cannot access this file directly");

require_once __DIR__ .'/functions/functions.php';

$sLC     = defined('LANGUAGE') ? LANGUAGE : defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN';
$message = $MESSAGE['FORGOT_PASS_NO_DATA'];
$errMsg  ='';
$sEmail  = '';

if(isset($_POST['email']) && $_POST['email'] != "" ) {
	$sEmail = strip_tags($wb->get_post('email'));
	if($admin->validate_email($sEmail) == false) {
		$errMsg = $MESSAGE['USERS_INVALID_EMAIL'];
		$sEmail  = '';
	} else {
		// Check if the email exists in the database
		$sSql  = "SELECT * FROM `{TP}users`	WHERE `email`='".$sEmail."'";
	
		if(($rRow = $database->query($sSql)))	{
			if(($aUser = $rRow->fetchRow(MYSQL_ASSOC))) {
				if(($aUser['signup_confirmcode'] == '') === false){
					header("Location: ".WB_URL."/account/signup_continue_page.php?switch=wrong_inputs");	
					exit(0); // break up the script here
				}
				$iUserID = (int) $aUser['user_id'];
				// Get the id, username, email, and last_reset from the above db query
				
				// Check if the password has been reset in the last 2 hours
				if( ( time() - intval($aUser['last_reset']) ) < (2 * 3600) ) {
					// Tell the user that their password cannot be reset more than once per hour
					$errMsg = $MESSAGE['FORGOT_PASS_ALREADY_RESET'];
				} else {
					// current password
					$sCurrentPw = $aUser['password'];
					
					// generate new password 
					$sPassword   = account_GenerateRandomPassword();
					$md5password = md5($sPassword);
					
					// update the new password in the database
					$aUpdateUser = array(
						'user_id'    => $iUserID,
						'password'   => $md5password,
						'last_reset' => time(),
					);
					
					if($database->updateRow('{TP}users', 'user_id', $aUpdateUser)){ 						
										
						$aTokenReplace = array(
							'LOGIN_DISPLAY_NAME'  => $aUser['display_name'], 
							'LOGIN_NAME'          => $aUser['username'], 
							'LOGIN_WEBSITE_TITLE' => WEBSITE_TITLE, 
							'LOGIN_PASSWORD'      => $sPassword,							
							'LOGIN_URL'           => defined('LOGIN_URL') ? LOGIN_URL : WB_URL.'/account/login.php'
						);					
						
						// prepare E-Mail with login details to send to the prospect
						$sOnScreenSwitch    = 'forgot_login_details_sent';
						$sEmailTemplateName = 'resend_forgot_login_details';	
						$sMailTo            = $sEmail;
						
						if ( account_sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName) == true ) {
							header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=".$sOnScreenSwitch);							
						} else {
							$aUpdateUser = array(
								'user_id'    => $aUser['user_id'],
								'password'   => $sCurrentPw
							);			
							$database->updateRow('{TP}users', 'user_id', $aUpdateUser);
							header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&from=resend_forgot_pass");							
						}
						
					} else { 
						// Error updating database
						$errMsg = $MESSAGE['RECORD_MODIFIED_FAILED'];
						if(DEBUG) {
							$message .= '<br />'.$database->get_error();
							$message .= '<br />'.$sSql;
						}
					}
				}
			} else { // no record found - Email doesn't exist, so tell the user
				$errMsg = $MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'];
			}
		} else { // Query failed
			$errMsg = 'SystemError:: Database query failed!';
			if(DEBUG) {
				$errMsg .= '<br />'.$database->get_error();
				$errMsg .= '<br />'.$sSql;
			}
		}
	}
} 

$email = $sEmail;
$sHttpReferer = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $_SERVER['SCRIPT_NAME'];

// load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

// Get the template file for forgot
include account_getTemplate('form_forgot_login_details');