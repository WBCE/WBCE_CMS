<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_POST['comment_id']) OR !is_numeric($_POST['comment_id']) OR !isset($_POST['topic_id']) OR !is_numeric($_POST['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$comment_id = $_POST['comment_id'];
}

$update_when_modified = true; // Tells script to update when this page was last updated
require('permissioncheck.php');


// Validate all fields
if($admin->get_post('name') == '' AND $admin->get_post('comment') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/'.$mod_dir.'/modify_comment.php?page_id='.$page_id.'&section_id='.$section_id.'&comment_id='.$id.'#comments');
} else {
	$thename = trim($admin->get_post_escaped('name'));
	$thesite = trim($admin->get_post_escaped('website'));
	$themail= trim($admin->get_post_escaped('email'));
	$comment = $admin->get_post_escaped('comment');
	$active = $admin->get_post_escaped('active');
	$show_link = $admin->get_post_escaped('show_link');
	$topic_id = $admin->get_post('topic_id');
}

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_".$tablename."_comments SET name = '$thename', email = '$themail',website = '$thesite',comment = '$comment',active = '$active',show_link = '$show_link' WHERE comment_id = '$comment_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/'.$mod_dir.'/modify_comment.php?page_id='.$page_id.'&section_id='.$section_id.'&fredit='.$fredit.'&comment_id='.$comment_id);
} else { 
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fredit='.$fredit.'&cid='.$comment_id.'#comments');
}

if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>