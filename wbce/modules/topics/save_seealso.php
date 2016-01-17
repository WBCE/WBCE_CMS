<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_POST['topic_id']) OR !is_numeric($_POST['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$id = $_POST['topic_id'];
	$topic_id = $id;
}

$update_when_modified = true; // Tells script to update when this page was last updated
require('permissioncheck.php');

// Include WB functions file
require_once(WB_PATH.'/framework/functions.php');

$topiclinks_text = '';
if (isset($_POST['topiclinks'])) {
	$topiclinks = $_POST['topiclinks'];	//Inhalt der Checkboxen
	$topiclinks_text = '';
	if (count($topiclinks) > 0) {
		foreach($topiclinks as $t_id) { $topiclinks_text .= (int)$t_id.',';}
		$topiclinks_text = ','.$topiclinks_text;
		$topiclinks_text = str_replace(',,',',',$topiclinks_text);
	}
}

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_".$tablename." SET see_also = '$topiclinks_text' WHERE topic_id = '$topic_id'");

// Check if there is a db error, otherwise say successful
$gobackto = WB_URL.'/modules/'.$mod_dir.'/topicslist.php';
if ($topic_seealso_support == 'bakery') {$gobackto = WB_URL.'/modules/'.$mod_dir.'/topicslist-bakery.php';}
$gobackto .= '?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$id.'&fredit='.$fredit;

if($database->is_error()) {
	$admin->print_error($database->get_error(), $gobackto);
} else {	
	$admin->print_success($TEXT['SUCCESS'], $gobackto);
}

if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>
