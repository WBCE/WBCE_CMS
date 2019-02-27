<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (since 2015)
 * @license GNU GPL2 (or any later version)
 */

defined('WB_PATH') or die("Cannot access this file directly");
require_once dirname(__DIR__) . '/functions.php';

$oMsgBox = new MessageBox();
$sLC     = defined('LANGUAGE') ? LANGUAGE : defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN';
$sEmail  = '';

if(isset($_POST['email']) && $_POST['email'] != "" ) {
    $sEmail = strip_tags($wb->get_post('email'));
    if($admin->validate_email($sEmail) == false) {
        $oMsgBox->error($MESSAGE['USERS_INVALID_EMAIL']);
        $sEmail  = '';
    } else {
        // Check if the email exists in the database
        $sSql  = "SELECT * FROM `{TP}users` WHERE `email`='".$sEmail."'";

        if(($rRow = $database->query($sSql)))	{
            if(($aUser = $rRow->fetchRow(MYSQL_ASSOC))) {
                if(($aUser['signup_confirmcode'] == '') === false){
                    header("Location: ".ACCOUNT_URL."/signup_continue_page.php?switch=wrong_inputs");	
                    exit(0); // break up the script here
                }
                $iUserID = (int) $aUser['user_id'];
                // Get the id, username, email, and last_reset from the above db query

                // Check if the password has been reset in the last 2 hours
                if( ( time() - intval($aUser['last_reset']) ) < (2 * 3600) ) {
                    // Tell the user that their password cannot be reset more than once per hour
                    $oMsgBox->error($MESSAGE['FORGOT_PASS_ALREADY_RESET']);
                } else {
                    // current password
                    $sCurrentPw = $aUser['password'];

                    // generate new password 
                    $sNewPasswordRaw = account_GenerateRandomPassword();
                    $sNewPasswordEnc = $wb->doPasswordEncode($sNewPasswordRaw);

                    // update the new password in the database
                    $aUpdateUser = array(
                        'user_id'    => $iUserID,
                        'password'   => $sNewPasswordEnc,
                        'last_reset' => time(),
                    );

                    if($database->updateRow('{TP}users', 'user_id', $aUpdateUser)){ 						

                        $aTokenReplace = array(
                            'LOGIN_DISPLAY_NAME'  => $aUser['display_name'], 
                            'LOGIN_NAME'          => $aUser['username'], 
                            'LOGIN_WEBSITE_TITLE' => WEBSITE_TITLE, 
                            'LOGIN_PASSWORD'      => $sNewPasswordRaw,							
                            'LOGIN_URL'           => ACCOUNT_URL . '/login.php'
                        );					

                        // prepare E-Mail with login details to send to the prospect
                        $sOnScreenSwitch    = 'forgot_login_details_sent';
                        $sEmailTemplateName = 'resend_forgot_login_details';	
                        $sMailTo            = $sEmail;

                        if ( account_sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName) == true ) {
                            header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=".$sOnScreenSwitch);							
                        } else {
                            $aUpdateUser = array(
                                'user_id'    => $aUser['user_id'],
                                'password'   => $sCurrentPw
                            );			
                            $database->updateRow('{TP}users', 'user_id', $aUpdateUser);
                            header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&from=resend_forgot_pass");							
                        }

                    } else { 
                        // Error updating database
                        $oMsgBox->error($MESSAGE['RECORD_MODIFIED_FAILED']);
                        if(WB_DEBUG) {
                            $oMsgBox->error($database->get_error().'<br />'.$sSql);
                        }
                    }
                }
            } else { // no record found - Email doesn't exist, so tell the user
                $oMsgBox->error($MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND']);
            }
        } else { 
            // Query failed
            if(WB_DEBUG) {
                $oMsgBox->error('SystemError:: Database query failed!');
                $oMsgBox->error($database->get_error().'<br />'.$sSql);
            }
        }
    }
} 
if($oMsgBox->hasErrors() == false ) {
    $oMsgBox->info($MESSAGE['FORGOT_PASS_NO_DATA'], 0, 1);
}
$email = $sEmail;
$sHttpReferer = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $_SERVER['SCRIPT_NAME'];

// load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

// Get the template file for forgot_login_details
include account_getTemplate('form_forgot_login_details');