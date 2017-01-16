<?php

// Stop this file from being accessed directly
if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}



function makemetadescription ($thestring) {
	
	$the_description = stripslashes($thestring);
	$wsp   = "\\x00-\\x20";    //all white-spaces and control chars
	$the_description = preg_replace( "/[".$wsp."]+/" , ' ', $the_description );
	
	$the_description = str_replace('"', ' ', $the_description); 
	$the_description = str_replace("'", ' ', $the_description); 
	$the_description = str_replace('\'', ' ', $the_description); 
	if (strlen($the_description) > 160) {
		if(preg_match('/.{0,160}(?:[.!?:,])/su', $the_description, $match)) {  $the_description = $match[0]; }  //thanks to thorn	
					
		if (strlen($the_description) > 160) {
			$pos = strpos($the_description, " ", 120);
			if ($pos > 0) {$the_description = substr($the_description, 0,  $pos); }					
		}
	}
	//$the_description = ' '.$the_description;
	$the_description = trim(str_replace('   ',' ',$the_description));
	$the_description = trim(str_replace('  ',' ',$the_description));

	return (' '.$the_description);
}

function makemetakeywords ($thestring ) {
	
	$the_keywords = $thestring;
	if (strlen($the_keywords) > 100) {
		if(preg_match('/.{0,100}(?:[.!?:,])/su', $the_keywords, $match)) {  $the_keywords = $match[0]; }  //thanks to thorn
		if (strlen($the_keywords) > 100) {
			$pos = strpos($the_keywords, " ", 100);
			if ($pos > 0) {$the_keywords = substr($the_keywords, 0,  $pos); }					
		}
	}	
	$bad = array(
	'\'', /* /  */ '"', /* " */	'<', /* < */	'>', /* > */
	'{', /* { */	'}', /* } */	'[', /* [ */	']', /* ] */	'`', /* ` */
	'!', /* ! */	'@', /* @ */	'#', /* # */	'$', /* $ */	'%', /* % */
	'^', /* ^ */	'&', /* & */	'*', /* * */	'(', /* ( */	')', /* ) */
	'=', /* = */	'+', /* + */	'|', /* | */	'/', /* / */	'\\', /* \ */
	';', /* ; */	':', /* : */	' ', /*   */	'.', /* . */	'?' /* ? */
	);
	$the_keywords = str_replace($bad, ',', $the_keywords);
	$the_keywords = str_replace(',,,',',',$the_keywords);
	$the_keywords = str_replace(',,',',',$the_keywords);

	return (' '.$the_keywords);
}






function topics_move_topic($movetopic) {
	global $database;
	global $admin;
	//global $queryextra;
	global $page_id;
	global $section_id;
	global $picture_dir;
	global $restrict2picdir;
	
	$mod_dir = basename(dirname(__FILE__));
	$tablename = $mod_dir;

	//change page_id and section_id to get back to new page!
	$query_sections = $database->query("SELECT section_title, page_id, picture_dir FROM `".TABLE_PREFIX."mod_".$tablename."_settings` WHERE section_id = '".$movetopic."'");
	if($query_sections->numRows() > 0) {
		$sections_fetch = $query_sections->fetchRow();
		
		
		$newpicture_dir = $sections_fetch['picture_dir'];
		if ($restrict2picdir > 0 AND $newpicture_dir != $picture_dir) { die('No Permission'); }
		
		//Hier muss überprüft werden, ob der User überhaupt in die andere Section speichern darf.				
		if (!$admin->get_page_permission($sections_fetch['page_id']))  { die('No Permission'); }
		//------------------------------------------------------------
		
		$section_id = $movetopic;
		$page_id = $sections_fetch['page_id'];		
		$section_title = $sections_fetch['section_title'];
		$order = new order(TABLE_PREFIX.'mod_'.$tablename, 'position', 'topic_id', 'section_id');
		$position = $order->get_new($section_id);
		$qextra = ", page_id = '$page_id', section_id = '$section_id', position = '$position'" ;
		
	
		echo "<p>Save it in ".$section_title.'</p>';
		return $qextra;
	} else {
		return '';
	}
}

function topics_updatetimeNuser ($topic_id) {
	global $database;
	global $queryextra;
	global $admin;
	
	$mod_dir = basename(dirname(__FILE__));
	$tablename = $mod_dir;
	
	$t = time();
	$query_posted = $database->query("SELECT posted_first, posted_by, posted_modified, modified_by FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE topic_id = '".$topic_id."'");
	if($query_posted->numRows() == 1) {	
		$posted_fetch = $query_posted->fetchRow();		
		$posted_first = $posted_fetch['posted_first'];
		$posted_by = $posted_fetch['posted_by'];
		$posted_modified = $t;
		$modified_byArr = explode(',',$posted_fetch['modified_by']);
		if ( count($modified_byArr) > 2) {
			if ($modified_byArr[0] == '') {unset($modified_byArr[0]);}
			if ( count($modified_byArr) > 30) {unset($modified_byArr[0]);}
			if($modified_byArr[(count($modified_byArr) - 1)] != $admin->get_user_id()) {$modified_byArr[] = $admin->get_user_id();}
		} else {
			$modified_byArr[] = $admin->get_user_id();
		}			
		$modified_by = implode(',',$modified_byArr);		
	}
	return (", posted_modified = '$posted_modified', modified_by = '$modified_by' ");	
}
	
		
function topics_findlink ($title_link, $old_link, $user_link) {
	global $database;
	global $topic_id;
	
	$mod_dir = basename(dirname(__FILE__));
	$tablename = $mod_dir;
	
	if (substr($old_link,0,1) == '/') {$old_link = substr($old_link,1,strlen($old_link));}
	if ($old_link == '') $old_link = $title_link;

	if (substr($user_link,0,8) == 'untitled') {$user_link = $title_link;}
	if ($user_link == '') $user_link = $old_link; //No link given or User has deleted it

	$topic_link = $user_link;
	if ( $topic_link == "rss") {$topic_link = 'rss-'.$topic_id; }
	if ( $topic_link == "index") {$topic_link = 'index-'.$topic_id; }

	// Find a unique Link:
	$query_links = $database->query("SELECT link FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE link = '$topic_link' AND topic_id <> '$topic_id'");
	if($query_links->numRows() > 0) { 
		$count = 2;
		while($count < $topic_id) {
			$suggestlink = $topic_link.'-'.$count;
			$query_links = $database->query("SELECT link FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE link = '$suggestlink' AND topic_id <> '$topic_id'");
			if($query_links->numRows() == 0) { 
				$topic_link = $suggestlink;
				break;
			}
   			$count++;
   		}		
	}
	return ($topic_link);	
}	

function topics_createaccess_file ($old_link, $topic_link, $movetopic, $topics_directory, $topics_directory_depth) {
	
	global $topic_id;
	global $page_id;
	global $section_id;
	global $admin;
	global $MESSAGE;
	
	make_dir(WB_PATH.$topics_directory.'/');
	if(!is_writable(WB_PATH.$topics_directory.'/')) {
		$admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
	} elseif($old_link != $topic_link OR !file_exists(WB_PATH.$topics_directory.$topic_link.PAGE_EXTENSION) OR $movetopic > 0) {
		// We need to create a new file
		// First, delete old file if it exists
		if(file_exists(WB_PATH.$topics_directory.$old_link.PAGE_EXTENSION)) {
			unlink(WB_PATH.$topics_directory.$old_link.PAGE_EXTENSION);
		}
		// Specify the filename
		
		$filename = WB_PATH.$topics_directory.$topic_link.PAGE_EXTENSION;	
		// Write to the filename
		$content = '<?php
$page_id = '.$page_id.';
$section_id = '.$section_id.';
$topic_id = '.$topic_id.';
define("TOPIC_ID", '.$topic_id.');
require("'.$topics_directory_depth.'config.php");
require(WB_PATH."/index.php");
?>';
		$handle = fopen($filename, 'w');
		fwrite($handle, $content);
		fclose($handle);
		change_mode($filename);
		echo "<p>Access-file written</p>";
	}
}



?>