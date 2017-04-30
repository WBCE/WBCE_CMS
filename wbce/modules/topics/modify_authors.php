<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$id = $_GET['topic_id'];
	$topic_id = $id;
}

require('permissioncheck.php');

/*

$user_id = $admin->get_user_id();
$showoptions = true;
$authoronly = false;
if ($noadmin_nooptions > 0  OR $authorsgroup > 0) { //Care about users	
	$users_lowest_groupid = users_lowest_groupid($user_id);
	if ($noadmin_nooptions  > 0 AND $users_lowest_groupid > 1) {$showoptions = false;}
	if ($authorsgroup  > 0 AND $users_lowest_groupid == $authorsgroup) {$authoronly = true;}
	if ( $authorsgroup  > 0 AND $users_lowest_groupid > 99999) {die("Pfui");}
}
*/

//get This Topic Params
$query_topics = $database->query("SELECT authors,posted_by,modified_by  FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE topic_id = '$topic_id'");
if (($query_topics->numRows()) != 1) {
	exit("So what happend with the topic?"); 
}
$thistopic = $query_topics->fetchRow();
$authors = $thistopic['authors'];
$posted_by = $thistopic['posted_by']; 
//Check if user is owner:
if ($authoronly) {
	if ($posted_by != $user_id) {die("Nix da");}	
	//$pos = strpos ($authors,','.$user_id.',');
	//if ($pos === false){die("Nix da");}
} 

$set_author = 0;
if ($showoptions) {
	//No Author but admin
	if(isset($_GET['do']) AND $_GET['do'] == 'setauthor') { $set_author = 1;}
}



// Get Authors
$query_users = $database->query("SELECT * FROM ".TABLE_PREFIX."users WHERE groups_id LIKE '".$authorsgroup."'");
echo $authorsgroup;
if ($query_users->numRows() > 0) {

	if ($set_author) {
		echo '<h2>'.$MOD_TOPICS['SETAUTHORSHEADLINE'].'</h2>';
	} else {
		echo '<h2>'.$MOD_TOPICS['AUTHORSHEADLINE'].'</h2>';
	}
	
	?>
	<form name="topicauthors" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_authors.php" method="post" style="margin: 0;">
    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
	<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>" />
	<input type="hidden" name="fredit" value="<?php echo $fredit; ?>" />
	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
	<input type="hidden" name="set_author" value="<?php echo $set_author; ?>" />
   
	<table class="authorslist-maintable">	
	
	<?php
	
	
	
	$counter=0;
	$row = 'a';
	$output = '';
	while($users = $query_users->fetchRow()) { 
		$theuser_id = $users['user_id'];
		
		if ($theuser_id == $user_id) {continue;}
		$thegroups_id = ','.$users['groups_id'].',';
		$pos = strpos ($thegroups_id,','.$authorsgroup.',');
		if ($pos === false){continue;}
		$counter++;
		
		
		$tr = '<tr><td class="authorcheck">';
		if ($set_author) {
			$tr .= '<input type="radio" name="authors" value="'.$theuser_id.'"';
			if ($posted_by == $theuser_id) {$tr .=  ' checked="checked"';} 
		} else {
			$tr .=  '<input type="checkbox" name="authors[]" value="'.$theuser_id.'"';
			$pos = strpos ($authors,','.$theuser_id.',');
			if ($pos !== false){ $tr .=  ' checked="checked"';} 
		}
		
		$tr .=  '/></td><td class="author">';
		$tr .=  $users['display_name'].' ('.$users['username'].')';
		if ($theuser_id == $posted_by) {$tr .=  " <b>!!</b><p>&nbsp;</p>";}
		$tr .=  '</td></tr>';
		
		if ($theuser_id == $posted_by) {$output = $tr.$output;} else {$output .= $tr;}
		
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
		
		
	}	//End while
	echo $output;	
	?>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="left">
				<?php if ($output != '') echo '<input name="save" type="submit" value="'.$TEXT['SAVE'].'" style="width: 100px; margin-top: 5px;" />'; ?>
			</td>
			<td align="right">
				<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fredit='.$fredit; ?>';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
	 </form>
	<?php
	
	if ($showoptions AND !$set_author) {
		echo '<h2><a href="modify_authors.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'topic_id='.$topic_id.$paramdelimiter.'do=setauthor">set owner</a></h2>';
	}
	
} else {
	echo $TEXT['NONE_FOUND'];
}

if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}
  
?>