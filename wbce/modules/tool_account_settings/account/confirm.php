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

require_once realpath('../../../config.php');
require_once dirname(__DIR__) . '/functions.php';

$config = account_getConfig(); // get config from INI file

$sAutoLanguage = (isset($_GET['lc']) ? $_GET['lc'] : 'EN'); 
defined('LANGUAGE') or define('LANGUAGE', $sAutoLanguage); 

$sConfirmationID  = (isset($_GET['id']) ? $_GET['id'] :  '');

if(!class_exists('frontend', false)){ 
    include WB_PATH.'/framework/class.frontend.php'; 
}

require_once WB_PATH .'/framework/functions.php';

// Create new $wb object
$wb = new frontend(false);
$sConfirmTimeout = (string)(time() + 86400); // now + 24hours
$sConfirmCode    = md5(md5($sUsername.$sConfirmTimeout).$sConfirmTimeout);

if(isset($_GET['mng']) && $_GET['mng'] > intval(1)){
    header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&excessive_manager_use");	
    exit(0);
}
if($iUserID = account_userIdFromConfirmcode($sConfirmationID)){
	
    // Get User Data
    $aUser = account_getUserData($iUserID);	

    // Get LanguageCode		
    $sLC = $aUser['language'];
    $sLC = ($sLC == '') ? $sAutoLanguage : $sLC;

    // Generate password
    $sNewPasswordRaw = account_GenerateRandomPassword();
    $sNewPasswordEnc = $wb->doPasswordEncode($sNewPasswordRaw);

    $sReadableDateTime = date("Y-m-d H:i:s", time());

    if(isset($_GET['mng']) && $_GET['mng'] == intval(1)){		

        if(account_checkConfirmSum($_GET['sum'], $iUserID) == false){	
            header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&check_sum");	
            exit(0);
        }

        // //////////////////////////////////////////////
        //  AccountManager clicked on approval link;
        //  Send mail to user with his login details			

        $aUpdateUser = array(
            'user_id'            => $iUserID, 
            'signup_confirmcode' => '', 
            'signup_timeout'     => 0,
            'signup_checksum'    => $sReadableDateTime,
            'password'           => $sNewPasswordEnc,
            'active'             => 1,
        );			
        $database->updateRow('{TP}users', 'user_id', $aUpdateUser);

        // Create Token Replacement Array
        $aTokenReplace = array( 
            'USER_ID'             => $iUserID,
            'LOGIN_NAME'          => $aUser['username'], 
            'LOGIN_DISPLAY_NAME'  => $aUser['display_name'], 
            'LOGIN_PASSWORD'      => $sNewPasswordRaw,
            'LOGIN_WEBSITE_TITLE' => WEBSITE_TITLE, 
            'SUPPORT_EMAIL'       => account_getSupportEmail(),
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
        $sConfirmationUrl = WB_URL.'/modules/tool_account_settings/account/confirm.php?id='.$sConfirmCode.'&lc='.$sLC;
        $sCheckSum        = substr(md5($sConfirmCode), 0, 10);

        $aTokenReplace = array( 
            'USER_ID'              => $iUserID,
            'LOGIN_NAME'           => $aUser['username'], 
            'LOGIN_DISPLAY_NAME'   => $aUser['display_name'], 
            'LOGIN_PASSWORD'       => $sNewPasswordRaw,
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
                'password'           => $sNewPasswordEnc,
                'active'             => 1,
            );			
            $database->updateRow('{TP}users', 'user_id', $aUpdateUser);

            // Send email to AccountsManager so he is informed about the new registration
            account_sendEmail(account_getAccountsManagerEmail(), $aTokenReplace, 'new_user_manager_info');			

            // Send email to user and provide him with his login details
            // Prepare vars for E-Mail to user
            $sEmailTemplateName = 'welcome_login_info';
            $sErrorMessage      = $MESSAGE['FORGOT_PASS_CANNOT_EMAIL'];	

            if ( account_sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject) ) {			
                header("Location: ".WB_URL."/account/signup_continue_page.php?lc=".$sLC."&switch=login_details_just_sent");				
            } else {
                $wb->print_error($sErrorMessage, $js_back, false);				
            }	
        } else { 	
            // Send email to AccountsManager so he can confirm new user
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