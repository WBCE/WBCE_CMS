<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.7
 * @lastmodified    april 7, 2014
 *
 */


require('../../config.php');
require_once (WB_PATH.'/framework/functions.php');
require(WB_PATH.'/modules/admin.php');
$update_when_modified = true; 

if(isset($_POST['section_id'])) {
	$email = $admin->add_slashes(strip_tags($_POST['email']));
	$subject = $admin->add_slashes(strip_tags($_POST['subject']));
	$template = $admin->add_slashes(strip_tags($_POST['template']));
	$success = intval($_POST['successpage']);
	
	$query = "UPDATE ".TABLE_PREFIX."mod_miniform SET 
			`email` = '$email', 
			`subject` = '$subject', 
			`successpage` = '$success', 
			`template` = '$template' 
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