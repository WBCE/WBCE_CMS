<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: email.php 1509 2011-09-07 21:58:13Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/email.php $
 * @lastmodified    $Date: 2011-09-07 23:58:13 +0200 (Mi, 07. Sep 2011) $
 *
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
		$error[] = $MESSAGE['PREFERENCES']['CURRENT_PASSWORD_INCORRECT'];
	}else {
		if(!$wb->validate_email($email)) {
			$error[] = $MESSAGE['USERS']['INVALID_EMAIL'];
		}else {
			$email = $wb->add_slashes($email);
// Update the database
			$sql = "UPDATE `".TABLE_PREFIX."users` SET `email` = '".$email."' WHERE `user_id` = ".$wb->get_user_id();
			$database->query($sql);
			if($database->is_error()) {
				$error[] = $database->get_error();
			} else {
				$success[] = $MESSAGE['PREFERENCES']['EMAIL_UPDATED'];
				$_SESSION['EMAIL'] = $email;
			}
		}
	}
