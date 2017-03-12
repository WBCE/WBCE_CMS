<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       Website Baker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: forgot_form.php 1601 2012-02-07 22:48:27Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/forgot_form.php $
 * @lastmodified    $Date: 2012-02-07 23:48:27 +0100 (Di, 07. Feb 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }
// Check if the user has already submitted the form, otherwise show it
$sCallingScript = $_SERVER['SCRIPT_NAME'];
$_SESSION['HTTP_REFERER'] =  isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $sCallingScript;
$message = $MESSAGE['FORGOT_PASS_NO_DATA'];
$errMsg ='';
if(isset($_POST['email']) && $_POST['email'] != "" )
{
	$email = strip_tags($_POST['email']);
	if($admin->validate_email($email) == false)
    {
		$errMsg = $MESSAGE['USERS_INVALID_EMAIL'];
		$email = '';
	} else {
// Check if the email exists in the database
	$sql  = 'SELECT `user_id`,`username`,`display_name`,`email`,`last_reset`,`password` '.
	        'FROM `'.TABLE_PREFIX.'users` '.
	        'WHERE `email`=\''.$wb->add_slashes($_POST['email']).'\'';
	if(($results = $database->query($sql)))
	{
		if(($results_array = $results->fetchRow()))
		{ // Get the id, username, email, and last_reset from the above db query
		// Check if the password has been reset in the last 2 hours
			if( (time() - (int)$results_array['last_reset']) < (2 * 3600) ) {
			// Tell the user that their password cannot be reset more than once per hour
				$errMsg = $MESSAGE['FORGOT_PASS_ALREADY_RESET'];
			} else {
				$old_pass = $results_array['password'];
			// Generate a random password then update the database with it
				$new_pass = WbAuth::GenerateRandomPassword();
				$sql = 'UPDATE `'.TABLE_PREFIX.'users` '.
				       'SET `password`=\''.WbAuth::Hash($new_pass).'\', '.
				           '`last_reset`='.time().' '.
				       'WHERE `user_id`='.(int)$results_array['user_id'];
				unset($pwh); // destroy $pwh-Object
				if($database->query($sql))
				{ // Setup email to send
					$mail_to = $email;
					$mail_subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];
				// Replace placeholders from language variable with values
					$search = array('{LOGIN_DISPLAY_NAME}', '{LOGIN_WEBSITE_TITLE}', '{LOGIN_NAME}', '{LOGIN_PASSWORD}');
					$replace = array($results_array['display_name'], WEBSITE_TITLE, $results_array['username'], $new_pass);
					$mail_message = str_replace($search, $replace, $MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT']);
				// Try sending the email
					if($wb->mail(SERVER_EMAIL,$mail_to,$mail_subject,$mail_message)) {
						$message = $MESSAGE['FORGOT_PASS_PASSWORD_RESET'];
						$display_form = false;
					}else { // snd mail failed, rollback
						$sql = 'UPDATE `'.TABLE_PREFIX.'users` '.
						       'SET `password`=\''.$old_pass.'\' '.
						       'WHERE `user_id`='.(int)$results_array['user_id'];
						$database->query($sql);
						$errMsg = $MESSAGE['FORGOT_PASS_CANNOT_EMAIL'];
					}
				}else { // Error updating database
					$errMsg = $MESSAGE['RECORD_MODIFIED_FAILED'];
					if(DEBUG) {
						$message .= '<br />'.$database->get_error();
						$message .= '<br />'.$sql;
					}
				}
			}
		}else { // no record found - Email doesn't exist, so tell the user
			$errMsg = $MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'];
		}
	} else { // Query failed
		$errMsg = 'SystemError:: Database query failed!';
		if(DEBUG) {
			$errMsg .= '<br />'.$database->get_error();
			$errMsg .= '<br />'.$sql;
		}
	}
	}
} else {
	$email = '';
}


?>


<style type="text/css">

table.forgot-form-table {
	width:100%;
}

table.forgot-form-table td {
	padding:0.5em;
}

table.forgot-form-table td input[type="text"] {
	background-color:#fff;
	border-style:solid; 
	border-width:1px;
	padding:0.2em;
	width:20em;
}

table.forgot-form-table td button {
	width:100%;
}
</style>

<h1><?php echo $MENU['FORGOT']; ?></h1>
<form name="forgot_pass" action="<?php echo WB_URL.'/account/forgot.php'; ?>" method="post">
	<input type="hidden" name="url" value="{URL}" />
	<p><strong><?php echo $message; ?></strong></p>

	
<?php if(!isset($display_form) OR $display_form != false) { ?>
<table class="forgot-form-table">
	<tr>
		<td><?php echo $TEXT['EMAIL']; ?>:</td>
		<td><input type="text" maxlength="255" name="email" value="<?php echo $email; ?>" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submit" value="<?php echo $TEXT['SEND_DETAILS']; ?>" /> &nbsp;&nbsp;&nbsp;
			<input type="button" value="<?php print $TEXT['CANCEL'] ?>" onClick="javascript: window.location = '<?php print $_SESSION['HTTP_REFERER'] ?>';">
		</td>
	</tr>
</table>	
<?php } ?>
		
</form>