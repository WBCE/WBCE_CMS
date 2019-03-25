<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.14.0
 * @lastmodified    May 22, 2019
 *
 */

require('../../config.php');
require_once (WB_PATH.'/framework/functions.php');
require(WB_PATH.'/modules/admin.php');
$update_when_modified = true; 

if(isset($_POST['section_id'])) {
	$email = $admin->add_slashes(strip_tags($_POST['email']));
	$emailfrom = $admin->add_slashes(strip_tags($_POST['emailfrom']));
	$subject = $admin->add_slashes(strip_tags($_POST['subject']));
	$confirm_user = (int)$_POST['confirm_user'];
	$confirm_subject = $admin->add_slashes(strip_tags($_POST['confirm_subject']));
	$template = $admin->add_slashes(strip_tags($_POST['template']));
	$no_store = (int)$_POST['no_store'];
	$use_ajax = (int)$_POST['use_ajax'];
	// $disable_tls = (int)$_POST['disable_tls'];
	$use_recaptcha = (int)$_POST['use_recaptcha'];
	$recaptcha_key = $admin->add_slashes(strip_tags($_POST['recaptcha_key']));
	$recaptcha_secret = $admin->add_slashes(strip_tags($_POST['recaptcha_secret']));
	$success = (int)$_POST['successpage'];
	
	$query = "UPDATE ".TABLE_PREFIX."mod_miniform SET 
			`email` = '$email', 
			`emailfrom` = '$emailfrom', 
			`subject` = '$subject', 
			`confirm_user` = '$confirm_user', 
			`confirm_subject` = '$confirm_subject', 
			`successpage` = '$success', 
			`template` = '$template',
			`no_store` = '$no_store',
			`use_ajax` = '$use_ajax',
			`use_recaptcha` = '$use_recaptcha',
			`recaptcha_key` = '$recaptcha_key',
			`recaptcha_secret` = '$recaptcha_secret'
			WHERE `section_id` = '$section_id'";
	$database->query($query);	
}

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>