<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_POST['topic_id']) OR !is_numeric($_POST['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$topic_id = (int) $_POST['topic_id'];
	$id = $topic_id;	
}

$update_when_modified = true;
require('permissioncheck.php');
$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
require_once($mpath.'/functions.php');
require_once($mpath.'defaults/module_settings.default.php');
require_once($mpath.'module_settings.php');

$t = topics_localtime();



require_once(WB_PATH."/include/jscalendar/jscalendar-functions.php");

// Include WB functions file
require(WB_PATH.'/framework/functions.php');
// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

// Get Settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();

// Get this topic
$query_topic = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$topic_id'");
$topic_fetch = $query_topic->fetchRow();

// Correct and validate all fields
if($admin->get_post('title') == '') {
	$title = "Untitled".$topic_id;
	$active = 0;
} else {
	$title = $admin->get_post_escaped('title');
	$active = $admin->get_post_escaped('active');
}


$short_description = $admin->get_post_escaped('short_description');

$short = $admin->get_post_escaped('short');
$long = $admin->get_post_escaped('long');
$extra = $admin->get_post_escaped('extra');

if( $topics_use_plain_text > 0) {
	$short = strip_tags($short);
	$long = strip_tags($long);
	$extra = strip_tags($extra);
}

/**************************/
//Use the extra-Editor only for pictures:
if (isset($extra_is_pics_only) AND $extra_is_pics_only == 1) {
	$extra = 'nix'.strip_tags($extra, '<img>').'nix';
	$neu = '';
	$bildArr = explode('<img ', $extra);
	foreach($bildArr as $bild) {	
		$p = strpos($bild, '>');
		if ($p > 10) {
			$neu .= '<img '.(substr($bild, 0, $p) ) . ">\n";
		}	
	}
	$extra = $neu;
}
/**************************/


$txtr1 = $admin->get_post_escaped('txtr1');
$txtr2 = $admin->get_post_escaped('txtr2');
$txtr3 = $admin->get_post_escaped('txtr3');


/*=================================================================================================================
New in Topics 2.8: groups_id */
$groups_id = '';
if (isset($_POST['groups_id'])) {
	$groups_id = ','.implode(',',$_POST['groups_id']).',';
}

/*if ($extrafield_1_name = "Homepage") {
	$hpstart = substr ($txtr1, 0, 7);
	if ($hpstart  != 'http://') { $txtr1 = 'http://'.$txtr1; }
	if ($txtr1 == 'http://') {$txtr1  = '';}
}*/
	

$description = $admin->get_post_escaped('description');
if (substr($description, 0, 1) == ' ') {$description = '';}
if ($description == '') { $description = makemetadescription ( strip_tags($short_description .' '.$short . ' ' . $title) );}

$keywords = $admin->get_post_escaped('keywords');
if (substr($keywords, 0, 1) == ' ') {$keywords = '';}
if ($keywords == '') { $keywords = makemetakeywords ( strtolower(strip_tags($title .' '.$short_description . ' ' . $short)));}
	
$hascontent = 1;
if (strlen($long.$extra) < 7) {$hascontent = 0;}
if (strlen($long.$extra) > 400) {$hascontent = 2;}
$query_comments = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id = '$topic_id'");
if($query_comments->numRows() > 0) {$hascontent = 3;}



$commenting = $admin->get_post_escaped('commenting');	
$picture = $admin->get_post_escaped('picture');
$picture = $admin->get_post_escaped('picture');
		
$see_also = $admin->get_post_escaped('see_also');
$gototopicslist =  (int) $_POST['gototopicslist'];

$queryextra = '';

//Move Topic?
$movetopic = (int)$admin->get_post_escaped('movetopic');

if ($movetopic > 0) {
	$picture_dir = $settings_fetch['picture_dir'];
	$queryextra .= topics_move_topic($movetopic);		
}



$queryextra .= topics_updatetimeNuser ($topic_id);
$t = topics_localtime();

$publishedwhen = jscalendar_to_timestamp($admin->get_post_escaped('publishdate'));
$posted_first = $admin->get_post_escaped('posted_first');
if ($posted_first == 0) {$posted_first = $t;}
if($publishedwhen == '' || $publishedwhen < 1) { $publishedwhen=$posted_first; }
	
$publisheduntil = jscalendar_to_timestamp($admin->get_post_escaped('enddate'), $publishedwhen);
if($publisheduntil == '' || $publisheduntil < 1) $publisheduntil=0;


//if usage as eventkalender use both time fields, (and make sure they are not in the past? No - it should be possible to edit past events)
if ($settings_fetch['sort_topics'] == 3) {
	if ($publishedwhen < $t) {echo '<h2 style="text-align:center">'.$MOD_TOPICS['TIME_WARNING1'].'</h2>';}
	if ($publisheduntil==0) { $publisheduntil = $publishedwhen + 3600;}
	if ($publisheduntil < $publishedwhen) {echo '<h2 style="text-align:center">'.$MOD_TOPICS['TIME_WARNING2'].'</h2>'; $publisheduntil = $publishedwhen + 3600;}
}


$title_link = page_filename($title);
$old_link = $admin->get_post_escaped('link');
$user_link =  page_filename($admin->get_post_escaped('user_link'));

$topic_link = topics_findlink ($title_link, $old_link, $user_link);
if ($create_topics_accessfiles > 0 ) {topics_createaccess_file ($old_link, $topic_link, $movetopic, $topics_directory, $topics_directory_depth);}

//$title= addslashes($title);


$theqbase = " short_description = '$short_description', content_short = '$short', content_long = '$long', content_extra = '$extra', txtr1 = '$txtr1', txtr2 = '$txtr2', txtr3 = '$txtr3', commenting = '$commenting', hascontent = '$hascontent', picture = '$picture', description = '$description', keywords = '$keywords'" . $queryextra;

//New field groups_id:
if (isset($topic_fetch['groups_id'])) { $theqbase .= ", groups_id = '$groups_id'"; }

// Update row
$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename. " SET ".$theqbase.", title = '$title', link = '$topic_link', published_when = '$publishedwhen', published_until = '$publisheduntil', active = '$active' WHERE topic_id = '$topic_id'";
	//echo $theq;
$database->query($theq);

$copytopic = $admin->get_post_escaped('copytopic');
if ($copytopic == 1) {
	//Copy Topic?
	$posted_by = $admin->get_user_id();
	$topic_link = $topic_link.'-'.$id;
	$title = "COPY: ".$title;
	$active = $activedefault;
	$posted_first = $t;
	if ($publishedwhen < $t) $publishedwhen = $t;
	if ($publisheduntil > 0 AND $publisheduntil < $t + 3600) $publisheduntil = $t + 3600;
	
	$theq = "INSERT INTO ".TABLE_PREFIX."mod_".$tablename." SET section_id = '$section_id', page_id = '$page_id', posted_first = '$posted_first', posted_by = '$posted_by', authors = '$posted_by', title = '$title',  link = '$topic_link', active = '$active', published_when = '$publishedwhen', published_until = '$publisheduntil', ".$theqbase;
	//echo $theq;
	$database->query($theq);
	//$admin->print_error($database->get_error());
	// Get the id
	$id = $database->get_one("SELECT LAST_INSERT_ID()");
	$topic_id = $id;
	topics_createaccess_file ('', $topic_link, 1, $topics_directory, $topics_directory_depth);
}



/*if (isset($_POST['resizepics']) AND $_POST['resizepics'] == '1' AND $picture != '' ) {
	// Include Image functions
	require_once(WB_PATH.'/modules/'.$mod_dir.'/resize_img.php');
	echo '<p>Resizing '.$picture.'</p>';
	removeallpic ($topic_id);
	makeallsizes ($topic_id);
	//die();
}*/

$modifyurl = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if ($fredit == 1) {$modifyurl = WB_URL.'/modules/'.$mod_dir.'/modify_fe.php?page_id='.$page_id.'&section_id='.$section_id.'&hl='.$id.'&fredit=1';}


// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$id.'&fredit='.$fredit);
} else {
	if ($gototopicslist == 1) {
		$admin->print_success($TEXT['SUCCESS'], $modifyurl);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$id.'&fredit='.$fredit);
	}
		
}
//die();
if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>