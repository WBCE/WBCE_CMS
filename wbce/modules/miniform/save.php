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
 * @version         0.15.0
 * @lastmodified    April 30, 2019
 *
 */

require('../../config.php');
require_once (WB_PATH.'/framework/functions.php');
$update_when_modified = true; 
require(WB_PATH.'/modules/admin.php');

if(isset($_POST['section_id'])) {
	$email = strip_tags($_POST['email']);
	$emailfrom = strip_tags($_POST['emailfrom']);
	$subject = strip_tags($_POST['subject']);
	$confirm_user = (int)$_POST['confirm_user'];
	$confirm_subject = strip_tags($_POST['confirm_subject']);
	$template = strip_tags($_POST['template']);
	$no_store = (int)$_POST['no_store'];
	$use_ajax = (int)$_POST['use_ajax'];
	// $disable_tls = (int)$_POST['disable_tls'];
	$use_recaptcha = (int)$_POST['use_recaptcha'];
	$recaptcha_key = strip_tags($_POST['recaptcha_key']);
	$recaptcha_secret = strip_tags($_POST['recaptcha_secret']);
	$success = (int)$_POST['successpage'];

	$database->query(
		"UPDATE {TP}mod_miniform SET
			`email` = ?,
			`emailfrom` = ?,
			`subject` = ?,
			`confirm_user` = ?,
			`confirm_subject` = ?,
			`successpage` = ?,
			`template` = ?,
			`no_store` = ?,
			`use_ajax` = ?,
			`use_recaptcha` = ?,
			`recaptcha_key` = ?,
			`recaptcha_secret` = ?
			WHERE `section_id` = ?",
		[$email, $emailfrom, $subject, $confirm_user, $confirm_subject, $success,
		 $template, $no_store, $use_ajax, $use_recaptcha, $recaptcha_key, $recaptcha_secret, $section_id]
	);
}

// Check if there is a database error, otherwise say successful
if($database->hasError()) {
	$admin->print_error($database->getError(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES_SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>