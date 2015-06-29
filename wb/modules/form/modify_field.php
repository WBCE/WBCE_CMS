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
 * @version         $Id: modify_field.php 1553 2011-12-31 15:03:03Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/form/modify_field.php $
 * @lastmodified    $Date: 2011-12-31 16:03:03 +0100 (Sa, 31. Dez 2011) $
 * @description     
 */

require('../../config.php');

$print_info_banner = true;
// Tells script to update when this page was last updated
$update_when_modified = false;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

$sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? '#'.SEC_ANCHOR.$section['section_id'] : '' );
/* */
// Get id
$field_id = intval($admin->checkIDKEY('field_id', false, 'GET'));
if (!$field_id) {
 $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.$sec_anchor);
}
// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

$type = 'none';
// Get header and footer
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_fields` ';
$sql .= 'WHERE `field_id` = '.$field_id.'';
$sql .= '';
if($query_content = $database->query($sql)) {
	$form = $query_content->fetchRow(MYSQL_ASSOC);
	$type = (($form['type'] == '') ? 'none' : $form['type']);
}
// set new idkey for save_field
$field_id = $admin->getIDKEY($form['field_id']);
// Set raw html <'s and >'s to be replaced by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');
?>

<form name="modify" action="<?php echo WB_URL; ?>/modules/form/save_field_new.php" method="post" style="margin: 0;">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="field_id" value="<?php echo $field_id; ?>" />
<?php echo $admin->getFTAN(); ?>

<table summary="" class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td colspan="2"><strong><?php echo $TEXT['MODIFY'].' '.$TEXT['FIELD']; ?></strong></td>
	</tr>
	<tr>
		<td width="20%"><?php echo $TEXT['TITLE']; ?>:</td>
		<td>
			<input type="text" name="title" value="<?php echo htmlspecialchars(($form['title'])); ?>" style="width: 98%;" maxlength="255" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['TYPE']; ?>:</td>
		<td>
			<select name="type" style="width: 98%;">
				<option value=""><?php echo $TEXT['PLEASE_SELECT']; ?>...</option>
				<option value="heading"<?php if($type == 'heading') { echo ' selected="selected"'; } ?>><?php echo $TEXT['HEADING']; ?></option>
				<option value="textfield"<?php if($type == 'textfield') { echo ' selected="selected"'; } ?>><?php echo $TEXT['SHORT'].' '.$TEXT['TEXT']; ?> (input)</option>
				<option value="textarea"<?php if($type == 'textarea') { echo ' selected="selected"'; } ?>><?php echo $TEXT['LONG'].' '.$TEXT['TEXT']; ?> (textarea)</option>
				<option value="select"<?php if($type == 'select') { echo ' selected="selected"'; } ?>><?php echo $TEXT['SELECT_BOX']; ?> (select)</option>
				<option value="checkbox"<?php if($type == 'checkbox') { echo ' selected="selected"'; } ?>><?php echo $TEXT['CHECKBOX_GROUP']; ?> (checkbox)</option>
				<option value="radio"<?php if($type == 'radio') { echo ' selected="selected"'; } ?>><?php echo $TEXT['RADIO_BUTTON_GROUP']; ?> (radiobox)</option>
				<option value="email"<?php if($type == 'email') { echo ' selected="selected"'; } ?>><?php echo $TEXT['EMAIL_ADDRESS']; ?></option>
			</select>
		</td>
	</tr>
<?php if($type != 'none' AND $type != 'email') { ?>
	<?php if($type == 'heading') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['TEMPLATE']; ?>:</td>
		<td>
			<textarea name="template" style="width: 98%; height: 20px;"><?php echo htmlspecialchars(($form['extra'])); ?></textarea>
		</td>
	</tr>
	<?php } elseif($type == 'textfield') { ?>
	<tr>
		<td><?php echo $TEXT['LENGTH']; ?>:</td>
		<td>
			<input type="text" name="length" value="<?php echo $form['extra']; ?>" style="width: 98%;" maxlength="3" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['DEFAULT_TEXT']; ?>:</td>
		<td>
			<input type="text" name="value" value="<?php echo $form['value']; ?>" style="width: 98%;" />
		</td>
	</tr>
	<?php } elseif($type == 'textarea') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['DEFAULT_TEXT']; ?>:</td>
		<td>
			<textarea name="value" style="width: 98%; height: 100px;"><?php echo $form['value']; ?></textarea>
		</td>
	</tr>
	<?php } elseif($type == 'select' OR $type = 'radio' OR $type = 'checkbox') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['LIST_OPTIONS']; ?>:</td>
		<td>
			<table summary="<?php echo $TEXT['LIST_OPTIONS']; ?>" cellpadding="3" cellspacing="0" width="100%" border="0">
			<?php
			$option_count = 0;
			$list = explode(',', $form['value']);
			foreach($list AS $option_value) {
				$option_count = $option_count+1;
				?>
				<tr>
					<td width="70"><?php echo $TEXT['OPTION'].' '.$option_count; ?>:</td>
					<td>
						<input type="text" name="value<?php echo $option_count; ?>" value="<?php echo $option_value; ?>" style="width: 250px;" />
					</td>
				</tr>
				<?php
			}
			for($i = 0; $i < 2; $i++) {
				$option_count = $option_count+1;
				?>
				<tr>
					<td width="70"><?php echo $TEXT['OPTION'].' '.$option_count; ?>:</td>
					<td>
						<input type="text" name="value<?php echo $option_count; ?>" value="" style="width: 250px;" />
					</td>
				</tr>
				<?php
			}
			?>
				</table>
			<input type="hidden" name="list_count" value="<?php echo $option_count; ?>" />
		</td>
	</tr>
	<?php } ?>
	<?php if($type == 'select') { ?>
	<tr>
		<td><?php echo $TEXT['SIZE']; ?>:</td>
		<td>
			<?php $form['extra'] = explode(',',$form['extra']); ?>
			<input type="text" name="size" value="<?php echo trim($form['extra'][0]); ?>" style="width: 98%;" maxlength="3" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['ALLOW_MULTIPLE_SELECTIONS']; ?>:</td>
		<td>
			<input type="radio" name="multiselect" id="multiselect_true" value="multiple" <?php if($form['extra'][1] == 'multiple') { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('multiselect_true').checked = true;">
			<?php echo $TEXT['YES']; ?>
			</a>
			&nbsp;
			<input type="radio" name="multiselect" id="multiselect_false" value="" <?php if($form['extra'][1] == '') { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('multiselect_false').checked = true;">
			<?php echo $TEXT['NO']; ?>
			</a>
		</td>
	</tr>
	<?php } elseif($type == 'checkbox' OR $type == 'radio') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['SEPERATOR']; ?>:</td>
		<td>
			<input type="text" name="seperator" value="<?php echo $form['extra']; ?>" style="width: 98%;" />
		</td>
	</tr>
	<?php } ?>
<?php } ?>
<?php if($type != 'heading' AND $type != 'none') { ?>
	<tr>
		<td><?php echo $TEXT['REQUIRED']; ?>:</td>
		<td>
			<input type="radio" name="required" id="required_true" value="1" <?php if($form['required'] == 1) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('required_true').checked = true;">
			<?php echo $TEXT['YES']; ?>
			</a>
			&nbsp;
			<input type="radio" name="required" id="required_false" value="0" <?php if($form['required'] == 0) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('required_false').checked = true;">
			<?php echo $TEXT['NO']; ?>
			</a>
		</td>
	</tr>
<?php } ?>
</table>

<table summary="" cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
		</td>
		<?php
		// added by John Maats, PCWacht, 12 januar 2006
		if ($type<>'none') {
		?>
		<td align="center">
			<input type="button" value="<?php echo $TEXT['ADD'].' '.$TEXT['FIELD']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/form/add_field.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>';" style="width: 200px; margin-top: 5px;" />
		</td>
		<?php } 
		// end addition
		?>
		<td align="right">
			<input type="button" value="<?php echo $TEXT['CLOSE']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id.$sec_anchor; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
</table>
</form>
<?php

// Print admin footer
$admin->print_footer();
