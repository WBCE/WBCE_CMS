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

// Prevent this file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly");
require_once dirname( __DIR__ ) . '/functions.php';
// Load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

// Init vars used in the form template
$username     = ''; 
$display_name = ''; 
$email        = ''; 
$groups_id    = ''; 
$gdpr_check   = ''; 
$sASPFields   = (ENABLED_ASP) ? renderAspHoneypots() : '';
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
    $username     = strtolower(strip_tags($wb->get_post('username')));
    $display_name = strip_tags($wb->get_post('display_name'));
    $email        = $wb->get_post('email');
    $gdpr_check   = $wb->get_post('gdpr_check');
    $groups_id    = FRONTEND_SIGNUP;

    /**
     * we don't need to query $groups_id, as the  groups_id  is set to a  default  value  right now;
     * maybe in a future update this setting could be made selectable, but it ain't the case for now.
     *
     *     if ($groups_id == '') 
     *         $errors['USERS_NO_GROUP'] = $MESSAGE['USERS_NO_GROUP'];
    **/
    
    // //////////////////////////////
    // Check user inputs
    // //////////////////////////////

    // Check username
    if (!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)){ 
        $username_error = true;
        $errors['USERS_NAME_INVALID_CHARS'] = $MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.$MESSAGE['USERS_USERNAME_TOO_SHORT'];
    }
    if (account_usernameAlreadyTaken($username) != false){
        $username_error = true;
        $errors['USERS_USERNAME_TAKEN'] = $MESSAGE['USERS_USERNAME_TAKEN'];
    }

    // Check display_name
    if ($display_name == ''){
        $display_name_error = true;
        $errors['DISPLAY_NAME_EMPTY'] = $MESSAGE['DISPLAY_NAME_EMPTY'];
    }

    // Check email
    if ($email != '' && $wb->validate_email($email) == false){
        $email_error = true;
        $errors['USERS_INVALID_EMAIL'] = $MESSAGE['USERS_INVALID_EMAIL'];
    }
    if ($email == ''){
        $email_error = true;
        $errors['SIGNUP_NO_EMAIL'] = $MESSAGE['SIGNUP_NO_EMAIL'];
    }
    if (account_emailAlreadyTaken($email) != false){
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
        debug_dump($sIncorrectCaptcha);
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

    // ///////////////////////////////////////////////////////////////////
    // If no errors so far, proceed with database update and mail sending
    // ///////////////////////////////////////////////////////////////////

    if (empty($errors) === true) {	
        $sLC             = defined('LANGUAGE') ? LANGUAGE : defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN';
        $sOnScreenSwitch = '';	    

        // /////////////////////////////
        // Add new user into database
        // /////////////////////////////

        // Prepare user variables for SQL queries
        $sUsername    = $database->escapeString($username);
        $sDisplayName = $database->escapeString($display_name);
        $sEmail       = $database->escapeString($email);
        $sGroupsID    = $database->escapeString($groups_id);
        $sPassword    = account_GenerateRandomPassword(); // generate password
        $aInsertUser = array(
            'group_id'         => $sGroupsID,
            'groups_id'        => $sGroupsID,
            'active'           => $config['user_activated_on_signup'],
            'username'         => $sUsername,
            'password'         => $wb->doPasswordEncode($sPassword),
            'display_name'     => $sDisplayName,
            'email'            => $sEmail,
            'gdpr_check'       => intval(1),
            'signup_checksum'  => date("Y-m-d H:i:s", time()),
            'signup_timestamp' => time(),
        ); 	 		
        $database->insertRow('{TP}users', $aInsertUser);

        if ($database->is_error()) {		
            header("Location: " . ACCOUNT_URL . "/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs"); //&err=".$database->get_error());
            exit(0);
        }				

        // ///////////////////////////////
        // Set up the E-mail to be send
        // ///////////////////////////////	

        $iUserID           = $database->getLastInsertId();
        $sMailFrom         = SERVER_EMAIL;
        $sMailTo           = $email;
        $sEmailSubject     = '';
        $sErrorMessage     = $TEXT['ERROR']; //$MESSAGE['GENERIC_ISSUE_MESSAGE'];	
        $sConfirmTimeout   = (string)(time() + 86400); // now + 24hours
        $sConfirmCode      = md5(md5($sUsername.$sConfirmTimeout).$sConfirmTimeout);
        $sConfirmationUrl  = ACCOUNT_TOOL_PATH . '/account/confirm.php?id='.$sConfirmCode.'&lc='.$sLC;
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
            'SUPPORT_EMAIL'        => account_getSupportEmail(),
            'APPROVAL_LINK'        => account_genEmailLinkFromUri($sConfirmationUrl.'&mng=1&sum='.$sCheckSum),
            'CONFIRMATION_LINK'    => account_genEmailLinkFromUri($sConfirmationUrl.'&mng=0&sum='.substr(md5($sCheckSum), 0, 10)),
            'CONFIRMATION_TIMEOUT' => date("Y-m-d H:i:s", $sConfirmTimeout), 
            'LOGIN_URL'            => ACCOUNT_URL . '/login.php'
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

            // Silently send E-Mail to AccountsManager to inform him about the new sign-up
            account_sendEmail(account_getAccountsManagerEmail(), $aTokenReplace, 'new_user_manager_info');
        }	

        if($config['signup_double_opt_in'] == 0 && $config['user_activated_on_signup'] == 0){
            $aUpdateUser = array(
                'user_id'            => $iUserID,
                'signup_confirmcode' => $sConfirmCode,
                'signup_timeout'     => $sConfirmTimeout,
            );
            $database->updateRow('{TP}users', 'user_id', $aUpdateUser);

            // Prepare E-Mail with approval link to send to the AccountsManager
            $sOnScreenSwitch    = 'manager_confirm_new_signup';
            $sEmailTemplateName = 'manager_approve_new_user';
            $sMailTo            = account_getAccountsManagerEmail();
        }		

        // ///////////////////////////////
        // Send prepared E-Mail
        // ///////////////////////////////
        if (account_sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject) == true) {		
            header("Location: ". ACCOUNT_URL ."/signup_continue_page.php?lc=".$sLC."&switch=".$sOnScreenSwitch);		
        } else {
            header("Location: " . ACCOUNT_URL ."/signup_continue_page.php?lc=".$sLC."&switch=wrong_inputs&sEmailTemplateName:".$sEmailTemplateName);		
        } 
    } else {
        foreach($errors as $key=>$sMsg){
            $oMsgBox->error($sMsg);
        }
        unset($errors);
    }
}

// Get the template file for signup
include account_getTemplate('form_signup');

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