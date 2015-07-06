<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }
require('permissioncheck.php');

// Get id
if(!isset($_GET['comment_id']) OR !is_numeric($_GET['comment_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$comment_id = $_GET['comment_id'];
}


$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE comment_id = '$comment_id'");
$fetch_content = $query_content->fetchRow();

?>

<h2><?php echo $TEXT['MODIFY'].' '.$TEXT['COMMENT']; ?></h2>

<form name="modify" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_comment.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="topic_id" value="<?php echo $fetch_content['topic_id']; ?>">
<input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
<input type="hidden" name="fredit" value="<?php echo $fredit; ?>" />
<?php echo $fetch_content['commentextra']; ?>

<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td width="80"><?php echo $TEXT['NAME']; ?>:</td>
	<td>
		<input type="text" name="name" value="<?php echo (htmlspecialchars($fetch_content['name'])); ?>" style="width: 98%;" maxlength="255" />
	</td>
</tr>
<tr>
	<td width="80"><?php echo $TEXT['EMAIL']; ?>:</td>
	<td>
		<input type="text" name="email" value="<?php echo (htmlspecialchars($fetch_content['email'])); ?>" style="width: 98%;" maxlength="255" />
	</td>
</tr>
<tr>
	<td width="80">website:</td>
	<td>
		<input type="text" name="website" value="<?php echo (htmlspecialchars($fetch_content['website'])); ?>" style="width: 98%;" maxlength="255" />
	</td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td><?php if ($fetch_content['website'] <> '') { ?><select name="show_link" style="width: 98%;">					
					<option value="0" <?php if($fetch_content['show_link'] == '0') { echo 'selected'; } ?>><?php echo $MOD_TOPICS['HP_LINK_OFF']; ?></option>
					<option value="1" <?php if($fetch_content['show_link'] == '1') { echo 'selected'; } ?>><?php echo $MOD_TOPICS['HP_LINK_MASKED']; ?></option>
					<option value="2" <?php if($fetch_content['show_link'] == '2') { echo 'selected'; } ?>><?php echo $MOD_TOPICS['HP_LINK_NOFOLLOW']; ?></option>
					<option value="3" <?php if($fetch_content['show_link'] == '3') { echo 'selected'; } ?>><?php echo $MOD_TOPICS['HP_LINK_SHOW']; ?></option>
				</select><?php } else echo '<input type="hidden" name="show_link" value="'.$fetch_content['show_link'].'" />'; ?></td>
</tr>
<tr>
	<td valign="top"><?php echo $TEXT['COMMENT']; ?>:</td>
	<td>
		<textarea name="comment" style="width: 98%; height: 150px;"><?php echo (htmlspecialchars($fetch_content['comment'])); ?></textarea>
	</td>
</tr>
<tr>
	<td valign="top"><?php echo $TEXT['ACTIVE']; ?>:</td>
	<td>	
	    <label><input type="radio" name="active" value="0" <?php if ($fetch_content['active'] != 1) echo 'checked="checked"'; ?>>  <?php echo $TEXT['NO']; ?></label>
	    <label><input type="radio" name="active" value="1" <?php if ($fetch_content['active'] == 1) echo 'checked="checked"'; ?>>  <?php echo $TEXT['YES']; ?></label>   
		
	</td>
</tr>

</table>
<?php 
$backurl = WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'topic_id='.$fetch_content['topic_id'].$paramdelimiter.'fredit='.$fredit;
$deleteurl = WB_URL.'/modules/'.$mod_dir.'/delete_comment.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'topic_id='.$fetch_content['topic_id'].$paramdelimiter.'comment_id='.$fetch_content['comment_id'].$paramdelimiter.'fredit='.$fredit;
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;">
		<input type="button" value="<?php echo $TEXT['DELETE']; ?>" onclick="javascript: window.location = '<?php echo $deleteurl; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo $backurl; ?>#comments';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>
</form>

<?php

if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>