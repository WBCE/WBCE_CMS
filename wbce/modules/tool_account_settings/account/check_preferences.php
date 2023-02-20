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

// Must include code to stop this file being access directly
defined('WB_PATH') or die("Cannot access this file directly"); 



$oAccounts = new Accounts();
// Check FTAN
if (!$oAccounts->checkFTAN()) {
    $oAccounts->print_error('MESSAGE:GENERIC_SECURITY_ACCESS', WB_URL);
}

$oMsgBox = new MessageBox();
$aMsg    = array();

$sCurrPassword = $oAccounts->get_post('current_password');
$sEncPassword  = $oAccounts->checkPasswordPattern($sCurrPassword);
$sNewEmail     = $oAccounts->get_post('email');
$iUserID       = (int)$oAccounts->get_user_id();

// validate confirmation password
if (is_array($sEncPassword)){
    $aMsg['error'][] = 'MESSAGE:PREFERENCES_CURRENT_PASSWORD_INCORRECT';
} elseif ($oAccounts->doCheckPassword($iUserID,$sCurrPassword)===false) {
    $aMsg['error'][] = 'MESSAGE:PREFERENCES_CURRENT_PASSWORD_INCORRECT';
} else { 

    // Get entered values
    $sDisplayName  = remove_special_characters($oAccounts->add_slashes(strip_tags($oAccounts->get_post('display_name'))));
    $sLC           = $oAccounts->get_post('language');
    $sLanguage     = preg_match('/^[A-Z]{2}$/', $sLC) ? $sLC : 'EN';
    $sTimezone     = is_numeric($oAccounts->get_post('timezone')) ? $oAccounts->get_post('timezone')*60*60 : 0;
    $sDateFormat   = $oAccounts->get_post('date_format');
    $sTimeFormat   = $oAccounts->get_post('time_format');

    // Update user data
    $aUpdate = array(
        'user_id'      => $oAccounts->get_user_id(),
        'display_name' => $database->escapeString($sDisplayName),
        'language'     => $database->escapeString($sLanguage),
        'timezone'     => $database->escapeString($sTimezone),
        'date_format'  => $database->escapeString($sDateFormat),
        'time_format'  => $database->escapeString($sTimeFormat),
    );

    // Validate email format
    if (!$oAccounts->validate_email($sNewEmail)) {
        $aMsg['error'][] = 'MESSAGE:USERS_INVALID_EMAIL';
    } else {
        $aUpdate['email'] = $database->escapeString($sNewEmail);
    }

    // Validate new password if entered
    if ($oAccounts->get_post('new_password') != ''){
        
        $sNewPassword = $oAccounts->get_post('new_password');
        $sRePassword  = $oAccounts->get_post('new_password2');
    
        $checkPassword =  $oAccounts->checkPasswordPattern($sNewPassword, $sRePassword);
        if (is_array($checkPassword)){
            $aMsg['error'][] = $checkPassword[0];
        } else {
            // Add password to Update array
            $aUpdate['password'] = $checkPassword;
        }            
    }

    // Update Data in Database
    if (empty($aMsg['error']) && $database->updateRow('{TP}users', 'user_id', $aUpdate)) {
        $aMsg['success'][] = 'MESSAGE:PREFERENCES_DETAILS_SAVED';
        if (isset($aUpdate['password']))
            $aMsg['success'][] = 'MESSAGE:PREFERENCES_PASSWORD_CHANGED';

        // update SESSION values
        $_SESSION['DISPLAY_NAME'] = $sDisplayName;
        $_SESSION['TIMEZONE']     = $sTimezone;
        $_SESSION['LANGUAGE']     = $sLanguage; 
        $_SESSION['HTTP_REFERER'] = ($_SESSION['LANGUAGE'] == LANGUAGE) ? $_SESSION['HTTP_REFERER'] : WB_URL;
        $_SESSION['HTTP_REFERER'] = PREFERENCES_URL.'?lang='.$_SESSION['LANGUAGE'];
        $_SESSION['CHECK_PREFERENCES'] = true;
        
        // Update DATE_FORMAT 
        if ($sDateFormat != '') {
            $_SESSION['DATE_FORMAT'] = $sDateFormat;
            if (isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) unset($_SESSION['USE_DEFAULT_DATE_FORMAT']); 
        } else {
            $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
            if (isset($_SESSION['DATE_FORMAT']))             unset($_SESSION['DATE_FORMAT']); 
        }

        // Update TIME_FORMAT 
        if ($sTimeFormat != '') {
            $_SESSION['TIME_FORMAT'] = $sTimeFormat;
            if (isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) unset($_SESSION['USE_DEFAULT_TIME_FORMAT']); 
        } else {
            $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
            if (isset($_SESSION['TIME_FORMAT']))             unset($_SESSION['TIME_FORMAT']); 
        }
        
        // notify_on_user_changes 
        if($oAccounts->cfg['notify_on_user_changes'] == true){
            $aTokenReplace = array(
                'BACKEND_VIEW_LINK' => ADMIN_URL.'/admintools/tool.php?tool=tool_account_settings&user_id='.$aUpdate['user_id'],
                'USER_DISPLAY_NAME' => $aUpdate['display_name'],
                'USER_LOGIN_NAME'   => $database->get_one("SELECT `username` FROM `{TP}users` WHERE `user_id` = ".$aUpdate['user_id'])
            );
            $oAccounts->sendChangeNotificationEmail($aTokenReplace);
        }
        
    } else {
        $aMsg['error'][] = $database->get_error();
    }
}
if (!empty($aMsg)){
    foreach($aMsg as $sTypeName => $aTypeArr){
        foreach($aTypeArr as $sMsg){
            $oMsgBox->$sTypeName($sMsg);
        }
    }
}
$oMsgBox->redirect(PREFERENCES_URL);
