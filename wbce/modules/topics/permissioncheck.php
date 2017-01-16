<?php 
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); } 
if (!defined('THEME_URL')) define ("THEME_URL", ADMIN_URL);

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;

$fredit = 0; //frontend edit

// include module_settings
$topic_seealso_support = '';
require_once(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
require_once(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');


$isget = 0;
if(isset($_GET['fredit']) AND $_GET['fredit'] == 1) { 
	$fredit = 1; $isget = 1;
} else {
	if(isset($_POST['fredit']) AND $_POST['fredit'] == 1) { $fredit = 1; $isget = 0;}
}

if ($fredit == 1) { //frontend
	$theauto_header = false;
	require_once(WB_PATH.'/framework/class.admin.php');
	$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
	if ($isget==1) {
		$page_id = (int) $_GET['page_id'];
		$section_id = (int) $_GET['section_id'];
	} else {
		$page_id = (int) $_POST['page_id'];
		$section_id = (int) $_POST['section_id'];
	}
		
} else {	
	if (!isset($admin)) { require_once(WB_PATH.'/modules/admin.php'); } //if (!isset($admin)).. Darf man das?
}

if(!$admin->is_authenticated()) {
	die();
} else {
	if(!$admin->get_permission($mod_dir, 'module')) {
		die();
	}
}

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}

require_once(WB_PATH.'/modules/'.$mod_dir.'/functions_small.php');

if (isset($section_id)) {
	$topic_id = 0;
	if(isset($_REQUEST['topic_id'])) { $topic_id = (int) $_REQUEST['topic_id']; }
	if(isset($_REQUEST['t_id'])) { $topic_id = (int) $_REQUEST['t_id']; }
	//...
	if ($topic_id > 0) {
		$secq = $database->query("SELECT section_id, page_id FROM ".TABLE_PREFIX."mod_".$mod_dir." WHERE topic_id = '$topic_id'");
		$secqfetch = $secq->fetchRow();
		if ($secqfetch['page_id'] != $page_id OR $secqfetch['section_id'] != $section_id) {die("Parameter mismatch"); }
	} else {
		$secq = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE module = '$mod_dir' AND section_id = '$section_id' AND page_id = '$page_id'");
		if($secq->numRows() != 1) { die("Something strange has happened!"); } 
	}
}


//Aus module_settings.php:
//$authorsgroup: Die Gruppe, der Autoren angehören. 
//$noadmin_nooptions: Default: 1: Nur der Admin (Gruppe 1) kann Settings ändern

$user_id = $admin->get_user_id();
$user_in_groups = $admin->get_groups_id();

$authoronly = false; //$authoronly: Zeigt im weiteren Verlauf an, ob der User nur als Autor berechtigt ist. 
$showoptions = true;
$author_invited = false; //Flag, zeigt an: Ist als Autor eingeladen = darf bearbeiten, aber ist NICHT Ersteller (posted_by)
if ($authorsgroup > 0) { //Care about users	
	if (in_array($authorsgroup, $user_in_groups)) {
		$authoronly = true; $showoptions = false; echo "AUTOR";
	} else {
		$author_trust_rating = 0; //Best Trust; Flag aus module_settings.php wird zurückgesetzt
	}
}

if (!in_array(1, $user_in_groups)) {	
	if ($noadmin_nooptions > 0) { $showoptions = false; }
} else {
	$authoronly = false; //An admin cannot be autor only
}

//Hier könnte man abwürgen, dass ein Autor ins Backend kommt. 
if ($authoronly == true) {$fredit = 1;} //Provisorisch

if ($fredit == 1) { 
	$showoptions = false;
	//Header Ausgeben
	require(WB_PATH.'/modules/'.$mod_dir .'/inc/fredithead.php');
}
?>