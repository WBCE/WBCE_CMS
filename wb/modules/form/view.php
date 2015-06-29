<?php
/**
 *
 * @category        module
 * @package         Form
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: view.php 1607 2012-02-09 19:29:58Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/form/view.php $
 * @lastmodified    $Date: 2012-02-09 20:29:58 +0100 (Do, 09. Feb 2012) $
 * @description
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

include_once(WB_PATH .'/framework/functions.php');
/*
function removebreaks($value) {
	return trim(preg_replace('=((<CR>|<LF>|0x0A/%0A|0x0D/%0D|\\n|\\r)\S).*=i', null, $value));
}
function checkbreaks($value) {
	return $value === removebreaks($value);
}
*/

if (!function_exists('emailAdmin')) {
	function emailAdmin() {
		global $database,$admin;
        $retval = $admin->get_email();
        if($admin->get_user_id()!='1') {
			$sql  = 'SELECT `email` FROM `'.TABLE_PREFIX.'users` ';
			$sql .= 'WHERE `user_id`=\'1\' ';
	        $retval = $database->get_one($sql);

        }
		return $retval;
	}
}

// Function for generating an optionsfor a select field
if (!function_exists('make_option')) {
	function make_option(&$n, $k, $values) {
		// start option group if it exists
		if (substr($n,0,2) == '[=') {
		 	$n = '<optgroup label="'.substr($n,2,strlen($n)).'">'.PHP_EOL;
		} elseif ($n == ']') {
			$n = '</optgroup>'.PHP_EOL;
		} else {
			if(in_array($n, $values)) {
				$n = '<option selected="selected" value="'.$n.'">'.$n.'</option>'.PHP_EOL;
			} else {
				$n = '<option value="'.$n.'">'.$n.'</option>'.PHP_EOL;
			}
		}
	}
}
// Function for generating a checkbox
if (!function_exists('make_checkbox')) {
	function make_checkbox(&$key, $idx, $params) {
		$field_id = $params[0][0];
		$seperator = $params[0][1];

		$label_id = 'wb_'.preg_replace('/[^a-z0-9]/i', '_', $key).$field_id;
		if(in_array($key, $params[1])) {
			$key = '<input class="frm-field_checkbox" type="checkbox" id="'.$label_id.'" name="field'.$field_id.'['.$idx.']" value="'.$key.'" />'.'<label for="'.$label_id.'" class="frm-checkbox_label">'.$key.'</lable>'.$seperator.PHP_EOL;
		} else {
			$key = '<input class="frm-field_checkbox" type="checkbox" id="'.$label_id.'" name="field'.$field_id.'['.$idx.']" value="'.$key.'" />'.'<label for="'.$label_id.'" class="frm-checkbox_label">'.$key.'</label>'.$seperator.PHP_EOL;
		}
	}
}
// Function for generating a radio button
if (!function_exists('make_radio')) {
	function make_radio(&$n, $idx, $params) {
		$field_id = $params[0];
		$group = $params[1];
		$seperator = $params[2];
		$label_id = 'wb_'.preg_replace('/[^a-z0-9]/i', '_', $n).$field_id;
		if($n == $params[3]) {
			$n = '<input class="frm-field_checkbox" type="radio" id="'.$label_id.'" name="field'.$field_id.'" value="'.$n.'" checked="checked" />'.'<label for="'.$label_id.'" class="frm-checkbox_label">'.$n.'</label>'.$seperator.PHP_EOL;
		} else {
			$n = '<input class="frm-field_checkbox" type="radio" id="'.$label_id.'" name="field'.$field_id.'" value="'.$n.'" />'.'<label for="'.$label_id.'" class="frm-checkbox_label">'.$n.'</label>'.$seperator.PHP_EOL;
		}
	}
}

if (!function_exists("new_submission_id") ) {
	function new_submission_id() {
		$submission_id = '';
		$salt = "abchefghjkmnpqrstuvwxyz0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($salt, $num, 1);
			$submission_id = $submission_id . $tmp;
			$i++;
		}
		return $submission_id;
	}
}

// Work-out if the form has been submitted or not
if($_POST == array()) {
	require_once(WB_PATH.'/include/captcha/captcha.php');

	// Set new submission ID in session
	$_SESSION['form_submission_id'] = new_submission_id();
    $out = '';
	$header = '';
	$field_loop = '';
	$footer = '';
	$form_name = 'form';
	$use_xhtml_strict = false;
	// Get settings
	$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_settings` ';
	$sql .= 'WHERE section_id = '.$section_id.' ';
	if($query_settings = $database->query($sql)) {
		if($query_settings->numRows() > 0) {
			$fetch_settings = $query_settings->fetchRow(MYSQL_ASSOC);
			$header = str_replace('{WB_URL}',WB_URL,$fetch_settings['header']);
			$field_loop = $fetch_settings['field_loop'];
			$footer = str_replace('{WB_URL}',WB_URL,$fetch_settings['footer']);
			$use_captcha = $fetch_settings['use_captcha'];
			$form_name = 'form';
			$use_xhtml_strict = false;
		}
	}

// do not use sec_anchor, can destroy some layouts
$sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? '#'.SEC_ANCHOR.$section['section_id'] : '' );

	// Get list of fields
	$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_fields` ';
	$sql .= 'WHERE section_id = '.$section_id.' ';
	$sql .= 'ORDER BY position ASC ';

	if($query_fields = $database->query($sql)) {
		if($query_fields->numRows() > 0) {
?>
			<form <?php echo ( ( (strlen($form_name) > 0) AND (false == $use_xhtml_strict) ) ? "name=\"".$form_name."\"" : ""); ?> action="<?php echo htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'';?>" method="post">
				<input type="hidden" name="submission_id" value="<?php echo $_SESSION['form_submission_id']; ?>" />
				<?php
				$iFormRequestId = isset($_GET['fri']) ? intval($_GET['fri']) : 0;
				if($iFormRequestId) {
					echo '<input type="hidden" name="fri" value="'.$iFormRequestId.'" />'."\n";
				}
				?>
				<?php // echo $admin->getFTAN(); ?>
				<?php
				if(ENABLED_ASP) { // first add some honeypot-fields
				?>
					<input type="hidden" name="submitted_when" value="<?php $t=time(); echo $t; $_SESSION['submitted_when']=$t; ?>" />
					<p class="frm-nixhier">
					email address:
					<label for="email">Leave this field email-address blank:</label>
					<input id="email" name="email" size="56" value="" /><br />
					Homepage:
					<label for="homepage">Leave this field homepage blank:</label>
					<input id="homepage" name="homepage" size="55" value="" /><br />
					URL:
					<label for="url">Leave this field url blank:</label>
					<input id="url" name="url" size="61" value="" /><br />
					Comment:
					<label for="comment">Leave this field comment blank:</label>
					<textarea id="comment" name="comment" cols="50" rows="10"></textarea><br />
					</p>
			<?php }

	// Print header  MYSQL_ASSOC
   echo $header.PHP_EOL;
			while($field = $query_fields->fetchRow(MYSQL_ASSOC)) {
				// Set field values
				$field_id = $field['field_id'];
				$value = $field['value'];
				// Print field_loop after replacing vars with values
				$vars = array('{TITLE}', '{REQUIRED}');
				if (($field['type'] == "radio") || ($field['type'] == "checkbox")) {
					$field_title = $field['title'];
				} else {
					$field_title = '<label for="field'.$field_id.'">'.$field['title'].'</label>'.PHP_EOL;
				}
				$values = array($field_title);
				if ($field['required'] == 1) {
					$values[] = '<span class="frm-required">*</span>'.PHP_EOL;
				} else {
					$values[] = '';
				}
				if($field['type'] == 'textfield') {
					$vars[] = '{FIELD}';
					$max_lenght_para = (intval($field['extra']) ? ' maxlength="'.intval($field['extra']).'"' : '');
					$values[] = '<input type="text" name="field'.$field_id.'" id="field'.$field_id.'"'.$max_lenght_para.' value="'.(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:$value).'" class="frm-textfield" />'.PHP_EOL;
				} elseif($field['type'] == 'textarea') {
					$vars[] = '{FIELD}';
					$values[] = '<textarea name="field'.$field_id.'" id="field'.$field_id.'" class="frm-textarea" cols="30" rows="8">'.(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:$value).'</textarea>'.PHP_EOL;
				} elseif($field['type'] == 'select') {
					$vars[] = '{FIELD}';
					$options = explode(',', $value);
					array_walk($options, 'make_option', (isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:array()));
					$field['extra'] = explode(',',$field['extra']);
					$values[] = '<select name="field'.$field_id.'[]" id="field'.$field_id.'" size="'.$field['extra'][0].'" '.$field['extra'][1].' class="frm-select">'.implode($options).'</select>'.PHP_EOL;
				} elseif($field['type'] == 'heading') {
					$vars[] = '{FIELD}';
					$str = '<input type="hidden" name="field'.$field_id.'" id="field'.$field_id.'" value="===['.$field['title'].']===" />';
					$values[] = ( true == $use_xhtml_strict) ? "<div>".$str."</div>" : $str;
					$tmp_field_loop = $field_loop;		// temporarily modify the field loop template
					$field_loop = $field['extra'];
				} elseif($field['type'] == 'checkbox') {
					$vars[] = '{FIELD}';
					$options = explode(',', $value);
					array_walk($options, 'make_checkbox', array(array($field_id,$field['extra']),(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:array())));
                    $x = sizeof($options)-1;
					$options[$x]=substr($options[$x],0,strlen($options[$x]));
					$values[] = implode($options);
				} elseif($field['type'] == 'radio') {
					$vars[] = '{FIELD}';
					$options = explode(',', $value);
					array_walk($options, 'make_radio', array($field_id,$field['title'],$field['extra'], (isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:'')));
                    $x = sizeof($options)-1;
					$options[$x]=substr($options[$x],0,strlen($options[$x]));
					$values[] = implode($options);
				} elseif($field['type'] == 'email') {
					$vars[] = '{FIELD}';
					$max_lenght_para = (intval($field['extra']) ? ' maxlength="'.intval($field['extra']).'"' : '');
					$values[] = '<input type="text" name="field'.$field_id.'" id="field'.$field_id.'" value="'.(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:'').'"'.$max_lenght_para.' class="frm-email" />'.PHP_EOL;
				}
				if(isset($_SESSION['field'.$field_id])) unset($_SESSION['field'.$field_id]);
				if($field['type'] != '') {
					echo str_replace($vars, $values, $field_loop);
				}
				if (isset($tmp_field_loop)){ $field_loop = $tmp_field_loop; }
			}
			// Captcha
			if($use_captcha) { ?>
				<tr>
				<td class="frm-field_title"><?php echo $TEXT['VERIFICATION']; ?>:</td>
				<td><?php call_captcha(); ?></td>
				</tr>
				<?php
			}
		// Print footer
		// $out = $footer.PHP_EOL;
		$out .= str_replace('{SUBMIT_FORM}', $MOD_FORM['SUBMIT_FORM'], $footer);
		echo $out;
// Add form end code
?>
</form>
<?php
		}
	}

} else {

	// Check that submission ID matches
	if(isset($_SESSION['form_submission_id']) AND isset($_POST['submission_id']) AND $_SESSION['form_submission_id'] == $_POST['submission_id']) {

		// Set new submission ID in session
		$_SESSION['form_submission_id'] = new_submission_id();

		if(ENABLED_ASP && ( // form faked? Check the honeypot-fields.
			(!isset($_POST['submitted_when']) OR !isset($_SESSION['submitted_when'])) OR
			($_POST['submitted_when'] != $_SESSION['submitted_when']) OR
			(!isset($_POST['email']) OR $_POST['email']) OR
			(!isset($_POST['homepage']) OR $_POST['homepage']) OR
			(!isset($_POST['comment']) OR $_POST['comment']) OR
			(!isset($_POST['url']) OR $_POST['url'])
		)) {
			// spam
			header("Location: ".WB_URL.PAGES_DIRECTORY."");
            exit();
		}
		// Submit form data
		// First start message settings
		$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_settings` ';
		$sql .= 'WHERE `section_id` = '.(int)$section_id.'';
		if($query_settings = $database->query($sql) ) {
			if($query_settings->numRows() > 0) {
				$fetch_settings = $query_settings->fetchRow(MYSQL_ASSOC);

				// $email_to = $fetch_settings['email_to'];
				$email_to = (($fetch_settings['email_to'] != '') ? $fetch_settings['email_to'] : emailAdmin());
				$email_from = $admin->add_slashes(SERVER_EMAIL);
/*
				if(substr($email_from, 0, 5) == 'field') {
					// Set the email from field to what the user entered in the specified field
					$email_from = htmlspecialchars($wb->add_slashes($_POST[$email_from]));
				}
*/
				$email_fromname = $fetch_settings['email_fromname'];
				if(substr($email_fromname, 0, 5) == 'field') {
					// Set the email_fromname to field to what the user entered in the specified field
					$email_fromname = htmlspecialchars($wb->add_slashes($_POST[$email_fromname]));
				}
				$email_subject = (($fetch_settings['email_subject'] != '') ? $fetch_settings['email_subject'] : $MOD_FORM['EMAIL_SUBJECT']);
				$success_page = $fetch_settings['success_page'];
				$success_email_to = (($fetch_settings['success_email_to'] != '') ? $fetch_settings['success_email_to'] : '');
				if(substr($success_email_to, 0, 5) == 'field') {
					// Set the success_email to field to what the user entered in the specified field
					$success_email_to = htmlspecialchars($wb->add_slashes($_POST[$success_email_to]));
				}
				$success_email_from = $admin->add_slashes(SERVER_EMAIL);
				$success_email_fromname = $fetch_settings['success_email_fromname'];
				$success_email_text = htmlspecialchars($wb->add_slashes($fetch_settings['success_email_text']));
				$success_email_text = (($success_email_text != '') ? $success_email_text : $MOD_FORM['SUCCESS_EMAIL_TEXT']);
				$success_email_subject = (($fetch_settings['success_email_subject'] != '') ? $fetch_settings['success_email_subject'] : $MOD_FORM['SUCCESS_EMAIL_SUBJECT']);
				$max_submissions = $fetch_settings['max_submissions'];
				$stored_submissions = $fetch_settings['stored_submissions'];
				$use_captcha = $fetch_settings['use_captcha'];
			} else {
				exit($TEXT['UNDER_CONSTRUCTION']);
			}
		}
		$email_body = '';

		// Create blank "required" array
		$required = array();

		// Captcha
		if($use_captcha) {
			if(isset($_POST['captcha']) AND $_POST['captcha'] != ''){
				// Check for a mismatch get email user_id
				if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
					$replace = array('webmaster_email' => emailAdmin() );
					$captcha_error = replace_vars($MOD_FORM['INCORRECT_CAPTCHA'], $replace);
				}
			} else {
				$replace = array('webmaster_email'=>emailAdmin() );
				$captcha_error = replace_vars($MOD_FORM['INCORRECT_CAPTCHA'],$replace );
			}
		}
		if(isset($_SESSION['captcha'])) { unset($_SESSION['captcha']); }

		// Loop through fields and add to message body
		// Get list of fields
		$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_fields` ';
		$sql .= 'WHERE `section_id` = '.(int)$section_id.' ';
		$sql .= 'ORDER BY position ASC';
		if($query_fields = $database->query($sql)) {
			if($query_fields->numRows() > 0) {
				while($field = $query_fields->fetchRow(MYSQL_ASSOC)) {
					// Add to message body
					if($field['type'] != '') {
						if(!empty($_POST['field'.$field['field_id']])) {
							// do not allow droplets in user input!
							if (is_array($_POST['field'.$field['field_id']])) {
								$_SESSION['field'.$field['field_id']] = str_replace(array("[[", "]]"), array("&#91;&#91;", "&#93;&#93;"), $_POST['field'.$field['field_id']]);
							} else {
								$_SESSION['field'.$field['field_id']] = str_replace(array("[[", "]]"), array("&#91;&#91;", "&#93;&#93;"), htmlspecialchars($_POST['field'.$field['field_id']]));
							}
							if($field['type'] == 'email' AND $admin->validate_email($_POST['field'.$field['field_id']]) == false) {
								$email_error = $MESSAGE['USERS_INVALID_EMAIL'];
							}
							if($field['type'] == 'heading') {
								$email_body .= $_POST['field'.$field['field_id']]."\n\n";
							} elseif (!is_array($_POST['field'.$field['field_id']])) {
								$email_body .= $field['title'].': '.$_POST['field'.$field['field_id']]."\n\n";
							} else {
								$email_body .= $field['title'].": \n";
								foreach ($_POST['field'.$field['field_id']] as $k=>$v) {
									$email_body .= $v."\n";
								}
								$email_body .= "\n";
							}
						} elseif($field['required'] == 1) {
							$required[] = $field['title'];
						}
					}
				} //  while
			}  // numRows
		} //  query
// Check if the user forgot to enter values into all the required fields
		if(sizeof($required )) {

			if(!isset($MESSAGE['MOD_FORM_REQUIRED_FIELDS'])) {
				echo '<h3>You must enter details for the following fields</h3>';
			} else {
				echo '<h3>'.$MESSAGE['MOD_FORM_REQUIRED_FIELDS'].'</h3>';
			}
			echo '<ul>'.PHP_EOL;
			foreach($required AS $field_title) {
				echo '<li>'.$field_title.PHP_EOL;
			}
			if(isset($email_error)) {
				echo '<li>'.$email_error.'</li>'.PHP_EOL;
			}
			if(isset($captcha_error)) {
				echo '<li>'.$captcha_error.'</li>'.PHP_EOL;
			}
			// Create blank "required" array
			$required = array();
			echo '</ul>'.PHP_EOL;
			echo '<p>&nbsp;</p>'.PHP_EOL.'<p><a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a></p>'.PHP_EOL;
		} else {
			if(isset($email_error)) {
				echo '<br /><ul>'.PHP_EOL;
				echo '<li>'.$email_error.'</li>'.PHP_EOL;
				echo '</ul>'.PHP_EOL;
				echo '<a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a>';
			} elseif(isset($captcha_error)) {
				echo '<br /><ul>'.PHP_EOL;
				echo '<li>'.$captcha_error.'</li>'.PHP_EOL;
				echo '</ul>'.PHP_EOL;
				echo '<p>&nbsp;</p>'.PHP_EOL.'<p><a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a></p>'.PHP_EOL;
			} else {
				// Check how many times form has been submitted in last hour
				$last_hour = time()-3600;
				$sql  = 'SELECT `submission_id` FROM `'.TABLE_PREFIX.'mod_form_submissions` ';
				$sql .= 'WHERE `submitted_when` >= '.$last_hour.'';
				$sql .= '';
				if($query_submissions = $database->query($sql)){
					if($query_submissions->numRows() > $max_submissions) {
						// Too many submissions so far this hour
						echo $MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'];
						$success = false;
					} else {
						// Adding the IP to the body and try to send the email
						// $email_body .= "\n\nIP: ".$_SERVER['REMOTE_ADDR'];
						$iFormRequestId = isset($_POST['fri']) ? intval($_POST['fri']) : 0;
						if($iFormRequestId) {
							$email_body .= "\n\nFormRequestID: ".$iFormRequestId;
						}
						$recipient = preg_replace( "/[^a-z0-9 !?:;,.\/_\-=+@#$&\*\(\)]/im", "", $email_fromname );
						$email_fromname = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $recipient );
						$email_body = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $email_body );
						if($email_to != '') {
							if($email_from != '') {
								if($wb->mail(SERVER_EMAIL,$email_to,$email_subject,$email_body,$email_fromname)) {
									$success = true;
								}
							} else {
								if($wb->mail('',$email_to,$email_subject,$email_body,$email_fromname)) {
									$success = true;
								}
							}
						}

						$recipient = preg_replace( "/[^a-z0-9 !?:;,.\/_\-=+@#$&\*\(\)]/im", "", $success_email_fromname );
						$success_email_fromname = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $recipient );
						$success_email_text = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $success_email_text );
						if($success_email_to != '') {
							if($success_email_from != '') {
								if($wb->mail(SERVER_EMAIL,$success_email_to,$success_email_subject,($success_email_text).$MOD_FORM['SUCCESS_EMAIL_TEXT_GENERATED'],$success_email_fromname)) {
									$success = true;
								}
							} else {
								if($wb->mail('',$success_email_to,$success_email_subject,($success_email_text).$MOD_FORM['SUCCESS_EMAIL_TEXT_GENERATED'],$success_email_fromname)) {
									$success = true;
								}
							}
						}

						// Write submission to database
						if(isset($admin) AND $admin->is_authenticated() AND $admin->get_user_id() > 0) {
							$submitted_by = $admin->get_user_id();
						} else {
							$submitted_by = 0;
						}
						$email_body = htmlspecialchars($wb->add_slashes($email_body));
						$sql  = 'INSERT INTO '.TABLE_PREFIX.'mod_form_submissions ';
						$sql .= 'SET ';
						$sql .= 'page_id='.$wb->page_id.',';
						$sql .= 'section_id='.$section_id.',';
						$sql .= 'submitted_when='.time().',';
						$sql .= 'submitted_by=\''.$submitted_by.'\', ';
						$sql .= 'body=\''.$email_body.'\' ';
						if($database->query($sql)) {

						if(!$database->is_error()) {
							$success = true;
						}
						// Make sure submissions table isn't too full
						$query_submissions = $database->query("SELECT submission_id FROM ".TABLE_PREFIX."mod_form_submissions ORDER BY submitted_when");
						$num_submissions = $query_submissions->numRows();
						if($num_submissions > $stored_submissions) {
							// Remove excess submission
							$num_to_remove = $num_submissions-$stored_submissions;
							while($submission = $query_submissions->fetchRow(MYSQL_ASSOC)) {
								if($num_to_remove > 0) {
									$submission_id = $submission['submission_id'];
									$database->query("DELETE FROM ".TABLE_PREFIX."mod_form_submissions WHERE submission_id = '$submission_id'");
									$num_to_remove = $num_to_remove-1;
								}
							}
						}
					}  // numRows
	 			}
	 			}
			}
		}  // email_error
	} else {

	echo '<p>&nbsp;</p>'.PHP_EOL.'<p><a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a></p>'.PHP_EOL;
	}

	// Now check if the email was sent successfully
	if(isset($success) AND $success == true) {
	   if ($success_page=='none') {
			echo str_replace("\n","<br />",($success_email_text));
				echo '<p>&nbsp;</p>'.PHP_EOL.'<p><a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a></p>'.PHP_EOL;
  		} else {
			$query_menu = $database->query("SELECT link,target FROM ".TABLE_PREFIX."pages WHERE `page_id` = '$success_page'");
			if($query_menu->numRows() > 0) {
  	        	$fetch_settings = $query_menu->fetchRow(MYSQL_ASSOC);
			   $link = WB_URL.PAGES_DIRECTORY.$fetch_settings['link'].PAGE_EXTENSION;
			   echo "<script type='text/javascript'>location.href='".$link."';</script>";
			}
		}
		// clearing session on success
		$query_fields = $database->query("SELECT field_id FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id'");
		while($field = $query_fields->fetchRow(MYSQL_ASSOC)) {
			$field_id = $field['field_id'];
			if(isset($_SESSION['field'.$field_id])) unset($_SESSION['field'.$field_id]);
		}
	} else {
		if(isset($success) AND $success == false) {
			echo $TEXT['ERROR'];
		}
	}

}