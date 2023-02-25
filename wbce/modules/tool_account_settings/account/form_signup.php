<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (since 2015)
 * @license GNU GPL2 (or any later version)
 */

// Prevent this file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly");
$oAccounts = new Accounts();

// Load Language Files 
foreach ($oAccounts->getLanguageFiles() as $sLangFile) require_once $sLangFile;

// Init vars used in the form template
$username     = ''; 
$display_name = ''; 
$email        = ''; 
$groups_id    = ''; 
$gdpr_check   = ''; 
$sASPFields   = (ENABLED_ASP) ? $oAccounts->renderAspHoneypots() : '';
$sHttpReferer = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $_SERVER['SCRIPT_NAME'];
// Error related vars
$errors             = array(); 
$username_error     = false; 
$display_name_error = false; 
$email_error        = false; 
$gdpr_error         = false; 

$oMsgBox = new MessageBox();

// Load Captcha if enabled
if(ENABLED_CAPTCHA) {
    require_once WB_PATH.'/include/captcha/captcha.php';
}


// ************************************************************************* //
//   SIGNUP FORM SENT? Process the form data, update the DB and send mails   //
// ************************************************************************* //
if(isset($_POST['signup_form_sent'])){
    // Get raw user inputs
    $username     = remove_special_characters(strtolower(strip_tags($oAccounts->get_post('username'))));
    $display_name = remove_special_characters(strip_tags($oAccounts->get_post('display_name')));
    $email        = $oAccounts->get_post('email');
    $gdpr_check   = $oAccounts->get_post('gdpr_check');
    $groups_id    = FRONTEND_SIGNUP;

    /**
     * we don't need to query $groups_id, as the  groups_id  is set to a  default  value  right now;
     * maybe in a future update this setting could be made selectable, but it ain't the case for now.
     *
     *     if ($groups_id == '') 
     *         $errors['USERS_NO_GROUP'] = $MESSAGE['USERS_NO_GROUP'];
    **/
    
    // //////////////////////////////
    //     Check user inputs
    // //////////////////////////////

    // Check username
    if (!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)){ 
        $username_error = true;
        $errors['USERS_NAME_INVALID_CHARS'] = $MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.$MESSAGE['USERS_USERNAME_TOO_SHORT'];
    }
    if ($oAccounts->usernameAlreadyTaken($username) != false){
        $username_error = true;
        $errors['USERS_USERNAME_TAKEN'] = $MESSAGE['USERS_USERNAME_TAKEN'];
    }

    // Check display_name
    if ($display_name == ''){
        $display_name_error = true;
        $errors['DISPLAY_NAME_EMPTY'] = $MESSAGE['DISPLAY_NAME_EMPTY'];
    }

    // Check email
    if ($email != '' && $oAccounts->validate_email($email) == false){
        $email_error = true;
        $errors['USERS_INVALID_EMAIL'] = $MESSAGE['USERS_INVALID_EMAIL'];
    }
    if ($email == ''){
        $email_error = true;
        $errors['SIGNUP_NO_EMAIL'] = $MESSAGE['SIGNUP_NO_EMAIL'];
    }
    if ($oAccounts->emailAlreadyTaken($email) != false){
        $email_error = true;
        $errors['USERS_EMAIL_TAKEN'] = $MESSAGE['USERS_EMAIL_TAKEN'];
    }

    // Check GDPR agreement checkbox
    if ($gdpr_check == false){
        $gdpr_error = true;
        $errors['GDPR_AGREEMENT_MISSING'] = $MESSAGE['GDPR_AGREEMENT_MANDATORY'];
    }

    if (ENABLED_CAPTCHA) {
        $sIncorrectCaptcha = strtr($MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'], ['{SERVER_EMAIL}' => SERVER_EMAIL]);	
        if (isset($_POST['captcha']) AND $_POST['captcha'] != ''){
            if (!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
                $errors['IncorrectCaptcha'] = $sIncorrectCaptcha;
            }
        } else {
            $errors['IncorrectCaptcha'] = $sIncorrectCaptcha;
        }
        // unset captcha if set
        if (isset($_SESSION['captcha'])) 
            unset($_SESSION['captcha']);
    }

    // ////////////////////////////////////////////////////////////////////////
    //    If no errors so far, proceed with database update and mail sending
    // ////////////////////////////////////////////////////////////////////////

    if (empty($errors) === true) {	
        $sLC             = defined('LANGUAGE') ? LANGUAGE : (defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN');
        $sOnScreenSwitch = '';	    

        // ///////////////////////////////////
        //    Add new user into database
        // ///////////////////////////////////

        // Prepare user variables for SQL queries
        $sUsername    = $database->escapeString($username);
        $sDisplayName = $database->escapeString($display_name);
        $sEmail       = $database->escapeString($email);
        $sGroupsID    = $database->escapeString($groups_id);
        $sPassword    = $oAccounts->GenerateRandomPassword(); // generate password
        $aInsertUser = array(
            'group_id'         => $sGroupsID,
            'groups_id'        => $sGroupsID,
            'active'           => $oAccounts->cfg['user_activated_on_signup'],
            'username'         => $sUsername,
            'password'         => $oAccounts->doPasswordEncode($sPassword),
            'display_name'     => $sDisplayName,
            'email'            => $sEmail,
            'gdpr_check'       => intval(1),
            'signup_checksum'  => date("Y-m-d H:i:s", time()),
            'signup_timestamp' => time(),
            'signup_confirmcode' => 'signup gid: '.$sGroupsID,
        ); 	 		
        $database->insertRow('{TP}users', $aInsertUser);

        if ($database->is_error()) {		
            header("Location: " . ACCOUNT_URL . "/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs"); //&err=".$database->get_error());
            exit(0);
        }				

        // ////////////////////////////////////
        //     Set up the E-mail to be send
        // ////////////////////////////////////	

        $iUserID           = $database->getLastInsertId();
        $sMailFrom         = SERVER_EMAIL;
        $sMailTo           = $email;
        $sEmailSubject     = '';
        $sErrorMessage     = $TEXT['ERROR']; //$MESSAGE['GENERIC_ISSUE_MESSAGE'];	
        $sConfirmTimeout   = (string)(time() + 86400); // now + 24hours
        $sConfirmCode      = md5(md5($sUsername.$sConfirmTimeout).$sConfirmTimeout);
        $sConfirmationUrl  = ACCOUNT_URL.'/confirm.php?id='.$sConfirmCode.'&lc='.$sLC;
        $sCheckSum         = substr(md5($sConfirmCode), 0, 10);

        // Template token replacement array (will be added to down the code)
        $aTokenReplace = array( 
            'USER_ID'              => $iUserID,
            'LOGIN_NAME'           => $sUsername, 
            'LOGIN_DISPLAY_NAME'   => $sDisplayName, 
            'LOGIN_PASSWORD'       => $sPassword,
            'LOGIN_WEBSITE_TITLE'  => WEBSITE_TITLE, 
            'SIGNUP_DATE'          => date("Y-m-d H:i:s", time()),
            'LOGIN_EMAIL'          => $email,
            'SUPPORT_EMAIL'        => $oAccounts->cfg['support_email'],
            'APPROVAL_LINK'        => $oAccounts->genEmailLinkFromUri($sConfirmationUrl.'&mng=1&sum='.$sCheckSum),
            'CONFIRMATION_LINK'    => $oAccounts->genEmailLinkFromUri($sConfirmationUrl.'&mng=0&sum='.$sCheckSum),
            'CONFIRMATION_TIMEOUT' => date("Y-m-d H:i:s", $sConfirmTimeout), 
            'LOGIN_URL'            => ACCOUNT_URL . '/login.php'
        );	
        
        // prepare DB updateRow array
        $aUpdateUser = array(
            'user_id'            => $iUserID,
            'signup_confirmcode' => $sConfirmCode,
            'signup_timeout'     => $sConfirmTimeout,
            'signup_checksum'    => $sCheckSum,
        );
        
        if ($oAccounts->cfg['signup_double_opt_in'] == 1){
            // prepare E-Mail with ACTIVATION LINK to send to the prospect
            $sOnScreenSwitch    = 'activation_link_sent';
            $sEmailTemplateName = 'activation_link';	
            $sMailTo            = $email;
          
            $database->updateRow('{TP}users', 'user_id', $aUpdateUser);
        }

        if ($oAccounts->cfg['signup_double_opt_in'] == 0 && $oAccounts->cfg['user_activated_on_signup'] == 1){
            // prepare E-Mail with LOGIN DETAILS to send to the prospect
            $sOnScreenSwitch    = 'login_details_just_sent';
            $sEmailTemplateName = 'welcome_login_info';	
            $sMailTo            = $email;

            // Silently send E-Mail to AccountsManager to inform him about the new sign-up
            $oAccounts->sendEmail($oAccounts->cfg['accounts_manager_email'], $aTokenReplace, 'new_user_manager_info');
        }	

        if($oAccounts->cfg['signup_double_opt_in'] == 0 && $oAccounts->cfg['user_activated_on_signup'] == 0){
          
            $database->updateRow('{TP}users', 'user_id', $aUpdateUser);

            // Prepare E-Mail with APPROVAL LINK to send to the AccountsManager
            $sOnScreenSwitch    = 'manager_confirm_new_signup';
            $sEmailTemplateName = 'manager_approve_new_user';
            $sMailTo            = $oAccounts->cfg['accounts_manager_email'];
        }		

        // ///////////////////////////////
        // Send prepared E-Mail
        // ///////////////////////////////
        $sLocPfX = ACCOUNT_URL ."/signup_continue_page.php?lc=".$sLC."&switch=";
        $checkSend = $oAccounts->sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject);
        if ($checkSend === true) {		
            header("Location: " . $sLocPfX . $sOnScreenSwitch);	
        } else {
            header("Location: " . $sLocPfX ."wrong_inputs&sEmailTemplateName:".$sEmailTemplateName."&mail_err=".$checkSend);	
        } 
        exit(0);
    } else {
        foreach($errors as $key=>$sMsg){
            $oMsgBox->error($sMsg);
        }
        unset($errors);
    }
}

$show_captcha = '';
if(ENABLED_CAPTCHA) {
    ob_start(); call_captcha(); $show_captcha = ob_get_clean();
}
$aToTwig = array(
    'USER_NAME'        => $username, 
    'DISPLAY_NAME'     => $display_name, 
    'EMAIL'            => $email, 
    'GDPR_VALUE'       => $gdpr_check, 
    'SHOW_CAPTCHA'     => $show_captcha, 
    'ASP_HONEYPOTS'    => $oAccounts->renderAspHoneypots(), 
    'MESSAGE_BOX'      => $oMsgBox->fetchDisplay(), 
    
    'USER_NAME_ERR'    => $username_error, 
    'DISPLAY_NAME_ERR' => $display_name_error, 
    'EMAIL_ERR'        => $email_error, 
    'GDPR_ERR'         => $gdpr_error, 
    'CurrentUrl'       => $_SERVER["REQUEST_URI"], 
);
// Get the template file for signup
$oAccounts->useTwigTemplate('form_signup.twig', $aToTwig);

// ##########################################################################################
// patch the database and include the following columns if not already existing:
// ##########################################################################################

if($database->field_exists('{TP}users', 'signup_confirmcode') == false)
    $database->field_add('{TP}users', 'signup_confirmcode', "VARCHAR(64) DEFAULT ''");

if($database->field_exists('{TP}users', 'signup_checksum') == false)
    $database->field_add('{TP}users', 'signup_checksum', "VARCHAR(64) DEFAULT ''");

if($database->field_exists('{TP}users', 'signup_timeout') == false)
    $database->field_add('{TP}users', 'signup_timeout', "INT(11) NOT NULL DEFAULT '0'");

if($database->field_exists('{TP}users', 'signup_timestamp') == false)
    $database->field_add('{TP}users', 'signup_timestamp', "INT(11) NOT NULL DEFAULT '0'");

if($database->field_exists('{TP}users', 'gdpr_check') == false)
    $database->field_add('{TP}users', 'gdpr_check', "INT(1) NOT NULL DEFAULT '0'");
