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

// Get entered values
	$password = $wb->get_post('current_password');
	$email = $wb->get_post('email');
// validate password
	$sql  = "SELECT `user_id` FROM `".TABLE_PREFIX."users` ";
	$sql .= "WHERE `user_id` = ".$wb->get_user_id()." AND `password` = '".md5($password)."'";
	$rowset = $database->query($sql);
// Validate values
	if($rowset->numRows() == 0) {
		$error[] = $MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'];
	}else {
		if(!$wb->validate_email($email)) {
			$error[] = $MESSAGE['USERS_INVALID_EMAIL'];
		}else {
			$email = $wb->add_slashes($email);
// Update the database
			$sql = "UPDATE `".TABLE_PREFIX."users` SET `email` = '".$database->escapeString($email)."' WHERE `user_id` = ".$wb->get_user_id();
			$database->query($sql);
			if($database->is_error()) {
				$error[] = $database->get_error();
			} else {
				$success[] = $MESSAGE['PREFERENCES_EMAIL_UPDATED'];
				$_SESSION['EMAIL'] = $email;
			}
		}
	}
