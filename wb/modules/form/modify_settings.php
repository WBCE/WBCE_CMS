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
 * @version         $Id: modify_settings.php 1580 2012-01-17 14:40:55Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/form/modify_settings.php $
 * @lastmodified    $Date: 2012-01-17 15:40:55 +0100 (Di, 17. Jan 2012) $
 * @description     
 */

require('../../config.php');

$print_info_banner = true;
// Tells script to update when this page was last updated
$update_when_modified = false;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
include_once(WB_PATH .'/framework/module.functions.php');

// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

$sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? '#'.SEC_ANCHOR.$section['section_id'] : '' );

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

// Get Settings from DB
$sql  = 'SELECT * FROM '.TABLE_PREFIX.'mod_form_settings ';
$sql .= 'WHERE `section_id` = '.(int)$section_id.'';
if($query_content = $database->query($sql)) {
	$setting = $query_content->fetchRow(MYSQL_ASSOC);
	$setting['email_to'] = ($setting['email_to'] != '' ? $setting['email_to'] : emailAdmin());
	$setting['email_subject'] = ($setting['email_subject']  != '') ? $setting['email_subject'] : '';
	$setting['success_email_subject'] = ($setting['success_email_subject']  != '') ? $setting['success_email_subject'] : '';
	$setting['success_email_from'] = $admin->add_slashes(SERVER_EMAIL);
	$setting['success_email_fromname'] = ($setting['success_email_fromname'] != '' ? $setting['success_email_fromname'] : WBMAILER_DEFAULT_SENDERNAME);
	$setting['success_email_subject'] = ($setting['success_email_subject']  != '') ? $setting['success_email_subject'] : '';
}

// Set raw html <'s and >'s to be replace by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');
/*
// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/form/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/form/backend.css');
	echo "\n</style>\n";
}
*/
?>
<h2><?php echo $MOD_FORM['SETTINGS']; ?></h2>
<?php
// include the button to edit the optional module CSS files
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
	edit_module_css('form');
}
?>

<form name="edit" action="<?php echo WB_URL; ?>/modules/form/save_settings.php" method="post" style="margin: 0;">

<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<?php echo $admin->getFTAN(); ?>

<table summary="" class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td colspan="2"><strong><?php echo $HEADING['GENERAL_SETTINGS']; ?></strong></td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['CAPTCHA_VERIFICATION']; ?>:</td>
		<td>
			<input type="radio" name="use_captcha" id="use_captcha_true" value="1"<?php if($setting['use_captcha'] == true) { echo ' checked="checked"'; } ?> />
			<label for="use_captcha_true"><?php echo $TEXT['ENABLED']; ?></label>
			<input type="radio" name="use_captcha" id="use_captcha_false" value="0"<?php if($setting['use_captcha'] == false) { echo ' checked="checked"'; } ?> />
			<label for="use_captcha_false"><?php echo $TEXT['DISABLED']; ?></label>
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['MAX_SUBMISSIONS_PER_HOUR']; ?>:</td>
		<td class="frm-setting_value">
			<input type="text" name="max_submissions" style="width: 30px;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['max_submissions'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['SUBMISSIONS_STORED_IN_DATABASE']; ?>:</td>
		<td class="frm-setting_value">
			<input type="text" name="stored_submissions" style="width: 30px;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['stored_submissions'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['HEADER']; ?>:</td>
		<td class="frm-setting_value">
			<textarea name="header" cols="80" rows="6" style="width: 98%; height: 80px;"><?php echo ($setting['header']); ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['FIELD'].' '.$TEXT['LOOP']; ?>:</td>
		<td class="frm-setting_value">
			<textarea name="field_loop" cols="80" rows="6" style="width: 98%; height: 80px;"><?php echo ($setting['field_loop']); ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['FOOTER']; ?>:</td>
		<td class="frm-setting_value">
			<textarea name="footer" cols="80" rows="6" style="width: 98%; height: 80px;"><?php echo str_replace($raw, $friendly, ($setting['footer'])); ?></textarea>
		</td>
	</tr>
</table>	
<!-- E-Mail Optionen -->
<table summary="<?php echo $TEXT['EMAIL'].' '.$TEXT['SETTINGS']; ?>" class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 3px;">
	<tr>
		<td colspan="2"><strong><?php echo $TEXT['EMAIL'].' '.$TEXT['SETTINGS']; ?></strong></td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['EMAIL'].' '.$MOD_FORM['TO']; ?>:</td>
		<td class="frm-setting_value">
			<input type="text" name="email_to" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['email_to'])); ?>" />
		</td>
	</tr>

	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['DISPLAY_NAME']; ?>:</td>
		<td class="frm-setting_value">
			<input type="text" name="email_fromname" id="email_fromname" style="width: 98%;  ?>;" maxlength="255" value="<?php  echo $setting['success_email_fromname'];  ?>" />
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['SUBJECT']; ?>:</td>
		<td class="frm-setting_value">
			<input type="text" name="email_subject" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['email_subject'])); ?>" />
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>
<!-- Erfolgreich Optionen -->
<table summary="<?php echo $TEXT['EMAIL'].' '.$MOD_FORM['CONFIRM']; ?>" class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 3px;">
	<thead>
	<tr>
		<th colspan="2"><strong><?php echo $TEXT['EMAIL'].' '.$MOD_FORM['CONFIRM']; ?></strong></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['EMAIL'].' '.$MOD_FORM['TO']; ?>:</td>
		<td class="frm-setting_value">
			<select name="success_email_to" style="width: 98%;">
			<option value="" onclick="javascript: document.getElementById('success_email_to').style.display = 'block';"><?php echo $TEXT['NONE']; ?></option>
			<?php
			$success_email_to = str_replace($raw, $friendly, ($setting['success_email_to']));
			$sql  = 'SELECT `field_id`, `title` FROM `'.TABLE_PREFIX.'mod_form_fields` ';
			$sql .= 'WHERE `section_id` = '.(int)$section_id.' ';
			$sql .= '  AND  `type` = \'email\' ';
			$sql .= 'ORDER BY `position` ASC ';
			if($query_email_fields = $database->query($sql)) {
				if($query_email_fields->numRows() > 0) {
					while($field = $query_email_fields->fetchRow(MYSQL_ASSOC)) {
						?>
						<option value="field<?php echo $field['field_id']; ?>"<?php if($success_email_to == 'field'.$field['field_id']) { echo ' selected'; $selected = true; } ?> onclick="javascript: document.getElementById('email_from').style.display = 'none';">
							<?php echo $TEXT['FIELD'].': '.$field['title']; ?>
						</option>
						<?php
					}
				}
			}
			?>
			</select>
		</td>
	</tr>

	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['DISPLAY_NAME']; ?>:</td>
		<td class="frm-setting_value">
			<?php $setting['success_email_fromname'] = ($setting['success_email_fromname'] != '' ? $setting['success_email_fromname'] : WBMAILER_DEFAULT_SENDERNAME); ?>
			<input type="text" name="success_email_fromname" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['success_email_fromname'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['SUBJECT']; ?>:</td>
		<td class="frm-setting_value">
			<input type="text" name="success_email_subject" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['success_email_subject'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="frm-setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['TEXT']; ?>:</td>
		<td class="frm-setting_value">
			<textarea name="success_email_text" cols="80" rows="1" style="width: 98%; height: 80px;"><?php echo str_replace($raw, $friendly, ($setting['success_email_text'])); ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="frm-newsection"><?php echo $TEXT['SUCCESS'].' '.$TEXT['PAGE']; ?>:</td>
		<td class="frm-newsection">
			<select name="success_page">
			<option value="none"><?php echo $TEXT['NONE']; ?></option>
			<?php 
			// Get exisiting pages and show the pagenames
			$query = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE visibility <> 'deleted'");
			while($mail_page = $query->fetchRow(MYSQL_ASSOC)) {
				if(!$admin->page_is_visible($mail_page))
					continue;
				$mail_pagename = $mail_page['menu_title'];		
				$success_page = $mail_page['page_id'];
			  //	echo $success_page.':'.$setting['success_page'].':'; not vailde
				if($setting['success_page'] == $success_page) {
					$selected = ' selected="selected"';
				} else {
					$selected = '';
				}
				echo '<option value="'.$success_page.'"'.$selected.'>'.$mail_pagename.'</option>';
		 	}
			?>
			</select>
		</td>
	</tr>
	</tbody>
</table>

<table summary="" cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;">
		</td>
		<td align="right">
			<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id.$sec_anchor; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
</table>
</form>
<?php

// Print admin footer
$admin->print_footer();
