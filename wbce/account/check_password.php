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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// Check Ftan
if (!$wb->checkFTAN()) {
    $wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],WB_URL );
}

// Get entered values
	$iMinPassLength      = 6;
	$sCurrentPassword    = $wb->get_post('current_password');
	$sCurrentPassword    = (is_null($sCurrentPassword) ? '' : $sCurrentPassword);
	$sNewPassword        = $wb->get_post('new_password');
	$sNewPassword        = is_null($sNewPassword) ? '' : $sNewPassword;
	$sNewPasswordRetyped = $wb->get_post('new_password2');
	$sNewPasswordRetyped = is_null($sNewPasswordRetyped) ? '' : $sNewPasswordRetyped;
	
	// Check existing password
	$sSql  = 'SELECT `password` FROM `{TP}users` WHERE `user_id` = '.$wb->get_user_id();
	
	// Validate values
	if (md5($sCurrentPassword) != $database->get_one($sSql)) {
		$error[] = $MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'];
	} else {
		if(strlen($sNewPassword) < $iMinPassLength) {
			$error[] = $MESSAGE['USERS_PASSWORD_TOO_SHORT'];
		} else {
			if($sNewPassword != $sNewPasswordRetyped) {
				$error[] = $MESSAGE['USERS_PASSWORD_MISMATCH'];
			} else {
				$pattern = '/[^'.$wb->password_chars.']/';
				if (preg_match($pattern, $sNewPassword)) {
					$error[] = $MESSAGE['PREFERENCES_INVALID_CHARS'];
				} else {
					// generate new password hash
					$sPwHashNew = md5($sNewPassword);
					$aUpdate = array(
						'user_id' => $wb->get_user_id(),
						'password' => $sPwHashNew
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
	}
