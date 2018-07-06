<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: signup_form.php 1599 2012-02-06 15:59:24Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/signup_form.php $
 * @lastmodified    $Date: 2012-02-06 16:59:24 +0100 (Mo, 06. Feb 2012) $
 *
 */

// stop this file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly");

$sHttpReferer =  isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $_SERVER['SCRIPT_NAME'];


require_once __DIR__ . '/functions/functions.php';
$config = account_getConfig(); // get config from INI file

if(ENABLED_CAPTCHA) {
	require_once WB_PATH.'/include/captcha/captcha.php';
}

$sASPFields = '';
if(ENABLED_ASP) { 
	$sTime = time();
	$_SESSION['submitted_when'] = $sTime;
	// add some honeypot-fields
	ob_start();	
?>
	<div style="display:none;">
		<input type="hidden" name="submitted_when" value="<?=$sTime ?>" />
		<p class="nixhier">
		email-address:
		<label for="email-address">Leave this field email-address blank:</label>
		<input id="email-address" name="email-address" size="60" value="" /><br />
		username (id):
		<label for="name">Leave this field name blank:</label>
		<input id="name" name="name" size="60" value="" /><br />
		Full Name:
		<label for="full_name">Leave this field full_name blank:</label>
		<input id="full_name" name="full_name" size="60" value="" /><br />
		</p>
	</div>		
<?php 
	$sASPFields = ob_get_clean();
} //end:ENABLED_ASP

// initiate vars used in the form template
$username     = ''; 
$display_name = ''; 
$email        = ''; 
$groups_id    = ''; 

// errors related vars
$errors             = array(); 
$username_error     = false; 
$display_name_error = false; 
$email_error        = false; 


// load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

if(isset($_POST['signup_form_sent'])){
	include WB_PATH.'/account/signup_form.inc.php';
}

// Get the template file for signup
include account_getTemplate('form_signup');

# #####################################################################################################
// patch the database and include the following columns if not already existing
//   `signup_confirmcode`, `signup_timeout` and `signup_checksum` 
# #####################################################################################################

if($database->field_exists('{TP}users', 'signup_confirmcode') == false){
	$database->field_add('{TP}users', 'signup_confirmcode', "VARCHAR(64) DEFAULT ''");
}
if($database->field_exists('{TP}users', 'signup_checksum') == false){
	$database->field_add('{TP}users', 'signup_checksum', "VARCHAR(64) DEFAULT ''");
}
if($database->field_exists('{TP}users', 'signup_timeout') == false){
	$database->field_add('{TP}users', 'signup_timeout', "INT(11) NOT NULL DEFAULT '0'");
}
if($database->field_exists('{TP}users', 'signup_timestamp') == false){
	$database->field_add('{TP}users', 'signup_timestamp', "INT(11) NOT NULL DEFAULT '0'");
}
