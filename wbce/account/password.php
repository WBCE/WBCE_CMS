<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       2009-2012, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: password.php 1569 2012-01-10 08:49:08Z darkviper $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/password.php $
 * @lastmodified    $Date: 2012-01-10 09:49:08 +0100 (Di, 10. Jan 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// Get entered values
	$iMinPassLength = 6;
	$sCurrentPassword = $wb->get_post('current_password');
	$sCurrentPassword = (is_null($sCurrentPassword) ? '' : $sCurrentPassword);
	$sNewPassword = $wb->get_post('new_password');
	$sNewPassword = is_null($sNewPassword) ? '' : $sNewPassword;
	$sNewPasswordRetyped = $wb->get_post('new_password2');
	$sNewPasswordRetyped= is_null($sNewPasswordRetyped) ? '' : $sNewPasswordRetyped;
// Check existing password
	$sql  = 'SELECT `password` ';
	$sql .= 'FROM `'.TABLE_PREFIX.'users` ';
	$sql .= 'WHERE `user_id` = '.$wb->get_user_id();
// Validate values
	if (md5($sCurrentPassword) != $database->get_one($sql)) {
		$error[] = $MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'];
	}else {
		if(strlen($sNewPassword) < $iMinPassLength) {
			$error[] = $MESSAGE['USERS_PASSWORD_TOO_SHORT'];
		}else {
			if($sNewPassword != $sNewPasswordRetyped) {
				$error[] = $MESSAGE['USERS_PASSWORD_MISMATCH'];
			}else {
				$pattern = '/[^'.$wb->password_chars.']/';
				if (preg_match($pattern, $sNewPassword)) {
					$error[] = $MESSAGE['PREFERENCES_INVALID_CHARS'];
				}else {
// generate new password hash
					$sPwHashNew = md5($sNewPassword);
// Update the database
					$sql  = 'UPDATE `'.TABLE_PREFIX.'users` ';
					$sql .= 'SET `password`=\''.$sPwHashNew.'\' ';
					$sql .= 'WHERE `user_id`='.$wb->get_user_id();
					if ($database->query($sql)) {
						$success[] = $MESSAGE['PREFERENCES_PASSWORD_CHANGED'];
					}else {
						$error[] = $database->get_error();
					}
				}
			}
		}
	}
