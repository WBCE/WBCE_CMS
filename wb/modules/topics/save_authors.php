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
//die();
require('permissioncheck.php');



if ($authorsgroup  == 0) {die("Pfui");}
if (!isset($_POST['user_id'])) {die("Pfui");}
if (!isset($_POST['authors'])) {
	$authors = array();
} else {
	$authors = $_POST['authors'];	//Inhalt der Checkboxen
}

$user_id = $_POST['user_id'];
$theuser_id = $admin->get_user_id();	
if ($user_id != $theuser_id) {die("Pfui");}
	
if ($_POST['set_author'] == 1) {
	$posted_by = (int) $authors;
	$query_topics = $database->query("SELECT authors FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE page_id = '$page_id' AND section_id = '$section_id' AND topic_id = '$topic_id'");
	if (($query_topics->numRows()) != 1) {
		exit("So what happend with the topic?"); 
	}
	$thistopic = $query_topics->fetchRow();
	$authors_text = $thistopic['authors'];
	$pos = strpos ($authors_text,','.$posted_by.',');
	if ($pos === false){$authors_text = ','.$posted_by.$authors_text;}
	$database->query("UPDATE ".TABLE_PREFIX."mod_".$tablename." SET posted_by = '$authors',authors = '$authors_text' WHERE page_id = '$page_id' AND section_id = '$section_id' AND topic_id = '$topic_id'");

} else {	
	$authors_text = ','.$user_id.',' ;
	if (count($authors) > 0) {
		foreach($authors as $u_id) { $authors_text .= (int)$u_id.',';}
		$authors_text = ','.$authors_text;
		$authors_text = str_replace(',,,',',',$authors_text);
		$authors_text = str_replace(',,',',',$authors_text);
	}	
	$database->query("UPDATE ".TABLE_PREFIX."mod_".$tablename." SET authors = '$authors_text' WHERE page_id = '$page_id' AND section_id = '$section_id' AND topic_id = '$topic_id'");
	
}
//die($authors_text);
// Update row


// Check if there is a db error, otherwise say successful
$gobackto = WB_URL.'/modules/'.$mod_dir.'/modify_topic.php';
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