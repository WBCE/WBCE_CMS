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
$gdpr_check   = ''; 

// errors related vars
$errors             = array(); 
$username_error     = false; 
$display_name_error = false; 
$email_error        = false; 
$gdpr_error         = false; 


// load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

if(isset($_POST['signup_form_sent'])){
	include WB_PATH.'/account/signup_form.inc.php';
}

// Get the template file for signup
include account_getTemplate('form_signup');

# #####################################################################################################
// patch the database and include the following columns if not already existing:
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
if($database->field_exists('{TP}users', 'gdpr_check') == false){
	$database->field_add('{TP}users', 'gdpr_check', "INT(1) NOT NULL DEFAULT '0'");
}
