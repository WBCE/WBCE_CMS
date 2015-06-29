<?php
/**
 *
 * @category        module
 * @package         Form
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: view_submission.php 1582 2012-01-19 03:07:05Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/form/view_submission.php $
 * @lastmodified    $Date: 2012-01-19 04:07:05 +0100 (Do, 19. Jan 2012) $
 * @description     
 */

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
/* */

// Get id
$submission_id = intval($admin->checkIDKEY('submission_id', false, 'GET'));
if (!$submission_id) {
 $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Get submission details
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_form_submissions` ';
$sql .= 'WHERE submission_id = '.$submission_id.' ';
if($query_content = $database->query($sql)) {
	$submission = $query_content->fetchRow(MYSQL_ASSOC);
}
// Get the user details of whoever did this submission
$sql  = 'SELECT `username`,`display_name` FROM `'.TABLE_PREFIX.'users` ';
$sql .= 'WHERE `user_id` = '.$submission['submitted_by'];
if($get_user = $database->query($sql)) {
	if($get_user->numRows() != 0) {
		$user = $get_user->fetchRow(MYSQL_ASSOC);
	} else {
		$user['display_name'] = 'Unknown';
		$user['username'] = 'unknown';
	}
}
$sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? '#'.SEC_ANCHOR.$section['section_id'] : '' );
?>
<table class="frm-submission" summary="" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td><?php echo $TEXT['SUBMISSION_ID']; ?>:</td>
	<td><?php echo $submission['submission_id']; ?></td>
</tr>
<tr>
	<td><?php echo $TEXT['SUBMITTED']; ?>:</td>
	<td><?php echo gmdate(DATE_FORMAT .', '.TIME_FORMAT, $submission['submitted_when']+TIMEZONE); ?></td>
</tr>
<tr>
	<td><?php echo $TEXT['USER']; ?>:</td>
	<td><?php echo $user['display_name'].' ('.$user['username'].')'; ?></td>
</tr>
<tr>
	<td colspan="2">
		<hr />
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php echo nl2br($submission['body']); ?>
	</td>
</tr>
</table>

<br />

<input type="button" value="<?php echo $TEXT['CLOSE']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id.$sec_anchor; ?>';" style="width: 150px; margin-top: 5px;" />
<input type="button" value="<?php echo $TEXT['DELETE']; ?>" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/form/delete_submission.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&submission_id=<?php echo $admin->getIDKEY($submission_id); ?>');" style="width: 150px; margin-top: 5px;" />
<?php

// Print admin footer
$admin->print_footer();
