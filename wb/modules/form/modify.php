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
 * @version         $Id: modify.php 1624 2012-02-29 00:42:03Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/modules/form/modify.php $
 * @lastmodified    $Date: 2012-02-29 01:42:03 +0100 (Mi, 29. Feb 2012) $
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

//overwrite php.ini on Apache servers for valid SESSION ID Separator
if(function_exists('ini_set')) {
	ini_set('arg_separator.output', '&amp;');
}

// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

include_once(WB_PATH.'/framework/functions.php');

$sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? '#'.SEC_ANCHOR.$section['section_id'] : '' );

//Delete all form fields with no title
$sql  = 'DELETE FROM `'.TABLE_PREFIX.'mod_form_fields` ';
$sql .= 'WHERE page_id = '.(int)$page_id.' ';
$sql .=   'AND section_id = '.(int)$section_id.' ';
$sql .=   'AND title=\'\' ';
if( !$database->query($sql) ) {
// error msg
}

?>
<table summary="" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td align="left" width="50%">
		<input type="button" value="<?php echo $TEXT['ADD'].' '.$TEXT['FIELD']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/form/add_field.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
	<td align="right" width="50%">
		<input type="button" value="<?php echo $TEXT['SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/form/modify_settings.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
</tr>
</table>

<br />

<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$TEXT['FIELD']; ?></h2>
<?php

// Loop through existing fields
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_fields` ';
$sql .= 'WHERE `section_id` = '.(int)$section_id.' ';
$sql .= 'ORDER BY `position` ASC';
if($query_fields = $database->query($sql)) {
	if($query_fields->numRows() > 0) {
		$num_fields = $query_fields->numRows();
		$row = 'a';
		?>
		<table summary="" width="100%" cellpadding="2" cellspacing="0" border="0">
		<thead>
			<tr style="background-color: #dddddd; font-weight: bold;">
				<th width="20" style="padding-left: 5px;">&nbsp;</th>
				<th width="30" style="text-align: right;">ID</th>
				<th width="400"><?php print $TEXT['FIELD']; ?></th>
				<th width="175"><?php print $TEXT['TYPE']; ?></th>
				<th width="100"><?php print $TEXT['REQUIRED']; ?></th>
				<th width="175">
				<?php
					echo $TEXT['MULTISELECT'];
				?>
				</th>
				<th width="175" colspan="3">
				<?php
					echo $TEXT['ACTIONS'];
				?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		while($field = $query_fields->fetchRow(MYSQL_ASSOC)) {
			?>
			<tr class="row_<?php echo $row; ?>">
				<td style="padding-left: 5px;">
					<a href="<?php echo WB_URL; ?>/modules/form/modify_field.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;field_id=<?php echo $admin->getIDKEY($field['field_id']); ?>" title="<?php echo $TEXT['MODIFY']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/modify_16.png" border="0" alt="^" />
					</a>
				</td>
				<td style="text-align: right;">
					<a href="<?php echo WB_URL; ?>/modules/form/modify_field.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;field_id=<?php echo $admin->getIDKEY($field['field_id']); ?>">
						<?php echo $field['field_id']; ?>
					</a>
				</td>
				<td>
					<a href="<?php echo WB_URL; ?>/modules/form/modify_field.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;field_id=<?php echo $admin->getIDKEY($field['field_id']); ?>">
						<?php echo $field['title']; ?>
					</a>
				</td>
				<td>
					<?php
					if($field['type'] == 'textfield') {
						echo $TEXT['SHORT_TEXT'];
					} elseif($field['type'] == 'textarea') {
						echo $TEXT['LONG_TEXT'];
					} elseif($field['type'] == 'heading') {
						echo $TEXT['HEADING'];
					} elseif($field['type'] == 'select') {
						echo $TEXT['SELECT_BOX'];
					} elseif($field['type'] == 'checkbox') {
						echo $TEXT['CHECKBOX_GROUP'];
					} elseif($field['type'] == 'radio') {
						echo $TEXT['RADIO_BUTTON_GROUP'];
					} elseif($field['type'] == 'email') {
						echo $TEXT['EMAIL_ADDRESS'];
					}
					?>
				</td>
				<td style="text-align: center;">
				<?php
				if ($field['type'] != 'group_begin') {
					if($field['required'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; }
				}
				?>
				</td>
				<td>
				<?php
				if ($field['type'] == 'select') {
					$field['extra'] = explode(',',$field['extra']);
					 if($field['extra'][1] == 'multiple') { echo $TEXT['YES']; } else { echo $TEXT['NO']; }
				}
				?>
				</td>
				<td width="20" style="text-align: center;">
				<?php if($field['position'] != 1) { ?>
					<a href="<?php echo WB_URL; ?>/modules/form/move_up.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;field_id=<?php echo $admin->getIDKEY($field['field_id']); ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/up_16.png" border="0" alt="^" />
					</a>
				<?php } ?>
				</td>
				<td width="20" style="text-align: center;">
				<?php if($field['position'] != $num_fields) { ?>
					<a href="<?php echo WB_URL; ?>/modules/form/move_down.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;field_id=<?php echo $admin->getIDKEY($field['field_id']); ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/down_16.png" border="0" alt="v" />
					</a>
				<?php } ?>
				</td>
				<td width="20" style="text-align: center;">
<?php
				$url = (WB_URL.'/modules/form/delete_field.php?page_id='.$page_id.'&amp;section_id='.$section_id.'&amp;field_id='.$admin->getIDKEY($field['field_id']))
?>
					<a href="javascript: confirm_link('<?php echo url_encode($TEXT['ARE_YOU_SURE']); ?>', '<?php echo $url; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="X" />
					</a>
				</td>
			</tr>
<?php
			// Alternate row color
			if($row == 'a') {
				$row = 'b';
			} else {
				$row = 'a';
			}
		}
?>
		</tbody>
		</table>
		<?php
	} else {
		echo $TEXT['NONE_FOUND'];
	}
}
?>

<br /><br />

<h2><?php echo $TEXT['SUBMISSIONS']; ?></h2>

<?php
// Query submissions table
/*
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_submissions`  ';
$sql .= 'WHERE `section_id` = '.(int)$section_id.' ';
$sql .= 'ORDER BY `submitted_when` ASC ';
*/
$sql  = 'SELECT s.*, u.`display_name`, u.`email` ';
$sql .=            'FROM `'.TABLE_PREFIX.'mod_form_submissions` s ';
$sql .= 'LEFT OUTER JOIN `'.TABLE_PREFIX.'users` u ';
$sql .= 'ON u.`user_id` = s.`submitted_by` ';
$sql .= 'WHERE s.`section_id` = '.(int)$section_id.' ';
$sql .= 'ORDER BY s.`submitted_when` ASC ';

if($query_submissions = $database->query($sql)) {
?>
<!-- submissions -->
		<table summary="" width="100%" cellpadding="2" cellspacing="0" border="0" class="" id="frm-ScrollTable" >
		<thead>
		<tr style="background-color: #dddddd; font-weight: bold;">
			<th width="23" style="text-align: center;">&nbsp;</th>
			<th width="33" style="text-align: right;"> ID </th>
			<th width="250" style="padding-left: 10px;"><?php echo $TEXT['SUBMITTED'] ?></th>
			<th width="240" style="padding-left: 10px;"><?php echo $TEXT['USER']; ?></th>
			<th width="250"><?php echo $TEXT['EMAIL'].' '.$MOD_FORM['FROM'] ?></th>
			<th width="20">&nbsp;</th>
			<th width="20">&nbsp;</th>
			<th width="20">&nbsp;</th>
			<th width="20">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
<?php
	if($query_submissions->numRows() > 0) {
		// List submissions
		$row = 'a';
		while($submission = $query_submissions->fetchRow(MYSQL_ASSOC)) {
	        $submission['display_name'] = (($submission['display_name']!=null) ? $submission['display_name'] : '');
			$sBody = $submission['body'];
			$regex = "/[a-z0-9\-_]?[a-z0-9.\-_]+[a-z0-9\-_]?@[a-z0-9.-]+\.[a-z]{2,}/iU";
			preg_match ($regex, $sBody, $output);
// workout if output is empty
			$submission['email'] = (isset($output['0']) ? $output['0'] : '');
?>
			<tr class="row_<?php echo $row; ?>">
				<td width="20" style="padding-left: 5px;text-align: center;">
					<a href="<?php echo WB_URL; ?>/modules/form/view_submission.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;submission_id=<?php echo $admin->getIDKEY($submission['submission_id']); ?>" title="<?php echo $TEXT['OPEN']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/folder_16.png" alt="<?php echo $TEXT['OPEN']; ?>" border="0" />
					</a>
				</td>
				<td width="30" style="padding-right: 5px;text-align: right;"><?php echo $submission['submission_id']; ?></td>
				<td width="250" style="padding-left: 10px;"><?php echo gmdate(DATE_FORMAT.', '.TIME_FORMAT, $submission['submitted_when']+TIMEZONE ); ?></td>
				<td width="250" style="padding-left: 10px;"><?php echo $submission['display_name']; ?></td>
				<td width="240"><?php echo $submission['email']; ?></td>
				<td width="20" style="text-align: center;">&nbsp;</td>
				<td width="20">&nbsp;</td>
				<td width="20" style="text-align: center;">
<?php
				$url = (WB_URL.'/modules/form/delete_submission.php?page_id='.$page_id.'&amp;section_id='.$section_id.'&amp;submission_id='.$admin->getIDKEY($submission['submission_id']))
?>
					<a href="javascript: confirm_link('<?php echo url_encode($TEXT['ARE_YOU_SURE']); ?>', '<?php echo $url; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="X" />
					</a>
				</td>
				<td width="20">&nbsp;</td>
			</tr>
<?php
			// Alternate row color
			if($row == 'a') {
				$row = 'b';
			} else {
				$row = 'a';
			}
		}
	} else {
?>
<tr><td colspan="8"><?php echo $TEXT['NONE_FOUND'] ?></td></tr>
<?php
	}
?>
		</tbody>
		<tfoot>
		<tr style="background-color: #dddddd; font-weight: bold;">
			<th width="23" style="text-align: center;">&nbsp;</th>
			<th width="33" style="text-align: right;"> ID </th>
			<th width="250" style="padding-left: 10px;"><?php echo $TEXT['SUBMITTED'] ?></th>
			<th width="250" style="padding-left: 10px;"><?php echo $TEXT['USER']; ?></th>
			<th width="250"><?php echo $TEXT['EMAIL'].' '.$MOD_FORM['FROM'] ?></th>
			<th width="20">&nbsp;</th>
			<th width="20">&nbsp;</th>
			<th width="20">&nbsp;</th>
			<th width="20">&nbsp;</th>
		</tr>
		</tfoot>
		</table>
<?php
} else {
	echo $database->get_error().'<br />';
	echo $sql;
}
