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

require_once dirname(__DIR__) . '/config.php';
$oAccounts = new Accounts();

$sAutoLanguage = (isset($_GET['lc']) ? $_GET['lc'] : 'EN'); 
defined('LANGUAGE') or define('LANGUAGE', $sAutoLanguage); 

$sConfirmTimeout = (string)(time() + 86400); // now + 24hours
$sConfirmCode    = md5(md5($sUsername.$sConfirmTimeout).$sConfirmTimeout);

if(isset($_GET['mng']) && $_GET['mng'] > intval(1)){
    header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&excessive_manager_use");	
    exit(0);
}

$sConfirmationID = (isset($_GET['id']) ? $_GET['id'] :  '');
if($iUserID = $oAccounts->userIdFromConfirmcode($sConfirmationID)){
	
    // Get User Data
    $aUser = $oAccounts->getUserData($iUserID);	

    // Get LanguageCode		
    $sLC = $aUser['language'];
    $sLC = ($sLC == '') ? $sAutoLanguage : $sLC;

    // Generate password
    $sNewPasswordRaw = $oAccounts->GenerateRandomPassword();
    $sNewPasswordEnc = $oAccounts->doPasswordEncode($sNewPasswordRaw);

    $sReadableDateTime = date("Y-m-d H:i:s", time());

    if(isset($_GET['mng']) && $_GET['mng'] == intval(1)){		

        if($oAccounts->checkConfirmSum($_GET['sum'], $iUserID) == false){	
            header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&check_sum");	
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
            'SUPPORT_EMAIL'       => $oAccounts->getSupportEmail(),
            'LOGIN_URL'           => ACCOUNT_URL . '/login.php'
        );	

        // Try sending the 'welcome_login_info' Mail to User
        $checkSend = $oAccounts->sendEmail($aUser['email'], $aTokenReplace, 'welcome_login_info');
        if ($checkSend === true) {			
            header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=manager_approval_feedback");			
        } else {			
            header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&mail_err=".$checkSend);
        }	

    } else {
        $sMailFrom        = SERVER_EMAIL;
        $sMailTo          = $aUser['email'];
        $sEmailSubject    = '';
        $sErrorMessage    = $MESSAGE['GENERIC_ISSUE_MESSAGE'];

        $sConfirmTimeout  = (string)(time() + 86400); // now + 24hours
        $sConfirmCode     = md5(md5($sUsername.$sConfirmTimeout).$sConfirmTimeout);
        $sConfirmationUrl = ACCOUNT_URL.'/confirm.php?id='.$sConfirmCode.'&lc='.$sLC;
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
            'LOGIN_URL'            => ACCOUNT_URL.'/login.php', 
            'APPROVAL_LINK'        => $oAccounts->genEmailLinkFromUri($sConfirmationUrl.'&mng=1&sum='.$sCheckSum), 
            'CONFIRMATION_LINK'    => $oAccounts->genEmailLinkFromUri($sConfirmationUrl.'&mng=0&sum='.substr(md5($sCheckSum), 0, 10)),
        );

        if($oAccounts->cfg['user_activated_on_signup'] == 1) { 
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
            $oAccounts->sendEmail($oAccounts->getAccountsManagerEmail(), $aTokenReplace, 'new_user_manager_info');			

            // Send email to user and provide him with his login details
            // Prepare vars for E-Mail to user
            $sEmailTemplateName = 'welcome_login_info';
            $sErrorMessage      = $MESSAGE['FORGOT_PASS_CANNOT_EMAIL'];	
            
            $checkSend = $oAccounts->sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject);
            if ($checkSend === true) {			
                header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=login_details_just_sent");				
            } else {
                $oAccounts->print_error($sErrorMessage.' err['.$checkSend.']', $js_back, false);				
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
            
            $checkSend = $oAccounts->sendEmail($oAccounts->getAccountsManagerEmail(), $aTokenReplace, 'manager_approve_new_user');
            if ($checkSend === true) {			
                header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=manager_confirm_new_signup");				
            } else {
                $oAccounts->print_error($sErrorMessage.' err['.$checkSend.']', $js_back, false);				
            }
        }  
    }
    
} else {		
    header("Location: ".ACCOUNT_URL."/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs");	
}