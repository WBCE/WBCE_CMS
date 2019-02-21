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
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// Check Ftan
if (!$wb->checkFTAN()) {
    $wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
}

// Get entered values
$sCurrentPassword  = $wb->get_post('current_password');
$sCurrentPassword  = (is_null($sCurrentPassword) ? '' : $sCurrentPassword);
$sNewPassword      = $wb->get_post('new_password');
$sRePassword       = $wb->get_post('new_password2');

// Get existing password from Database
$sDbPassword = $database->get_one('SELECT `password` FROM `{TP}users` WHERE `user_id` = '.$wb->get_user_id());
	
if ($wb->doPasswordEncode($sCurrentPassword) != $sDbPassword) {
    // Passwords don't match - access denied
    $aErrMsg[] = $MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'];
} else {
    // Validate new password  
    if($sNewPassword != ''){
        $checkPassword =  $wb->checkPasswordPattern($sNewPassword, $sRePassword);
        if (is_array($checkPassword)){
            $error[] = $checkPassword[0];
        } else {
            $sEncodedPassword = $checkPassword;
            $aUpdate = array(
                'user_id' => $wb->get_user_id(),
                'password' => $sEncodedPassword
            );
            // Update the database
            if ($database->updateRow('{TP}users', 'user_id', $aUpdate)) {
                $success[] = $MESSAGE['PREFERENCES_PASSWORD_CHANGED'];
            } else {
                $error[] = $database->get_error();
            }
        }            
    }
}