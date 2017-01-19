<?php

// Stop this file from being accessed directly
if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

function topics_localtime() {
	//return time();
	return gmmktime ( (int) gmdate("H"), (int) gmdate("i"), (int) gmdate("s"), (int) gmdate("n"), (int) gmdate("j"), (int) gmdate("Y")) + DEFAULT_TIMEZONE;
}

function topics_update_comments_count ($topic_id) {
	global $database;
	$mod_dir = basename(dirname(__FILE__));
	$tablename = $mod_dir;

	$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id = '".$topic_id."' AND active>'0'";
	$query_comments = $database->query($theq);
	$comments_count = $query_comments->numRows();

	$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename." SET comments_count = '".$comments_count."' WHERE topic_id = '".$topic_id."'";
	$database->query($theq);

}



function topics_commentsclass ($comments_count) {
	$commentsclass = 0;
	if ($comments_count > 0) {$commentsclass = 1;
		if ($comments_count > 2) {$commentsclass = 2;
			if ($comments_count > 5) {$commentsclass = 3;
			  	if ($comments_count > 8) {$commentsclass = 4;}
			}
		}
	}
	return $commentsclass;
}

function get_sort_topics_by($sort_topics) {

	switch ($sort_topics) {
		case 0: return(' position DESC');
		case 1: return(' published_when DESC, posted_first DESC');
		case 2: return(' topic_score DESC');
		case 3: return(' published_when ASC');
		case 4: return(' title ASC');

		case -1: return(' position ASC');
		case -2: return(' published_when ASC, posted_first ASC');
		case -3: return(' topic_score ASC');
		case -4: return(' published_when DESC');
		case -5: return(' title DESC');
	}
}



function users_lowest_groupid() {
	//obsolete?
	global $database;
	global $authorsgroup;

	if (!isset($_SESSION["GROUPS_ID"])) {return 0;};

	//Das wird wohl einfacher gehen:
	//In welcher (niedrigsten) Gruppe ist der aktuelle User?
	//M�glicher Bug: Wie werden Groups > 9 sortiert?

	$checkstring = ','.$_SESSION ["GROUPS_ID"].',';
	if (strpos($checkstring, ',1,') !== false) {return 1;}

	if ($authorsgroup  > 0) {
		if (strpos($checkstring, ','.$authorsgroup.',') !== false) {return $authorsgroup ;}
		//Fallstrick:
		//Ist autorgruppe=3 und und der aktuelle User in Gruppe 2 und 3, dann wird er trotzdem NUR als Autor gef�hrt.

	}

	return 0;
}

function topics_archive_file ($filename, $t_id, $s_id, $p_id, $create_topics_accessfiles = 0) {
	
	if ($create_topics_accessfiles != 1) {return 0;}
	
	
	//global $topics_directory;
	//global $topics_directory_depth;

	//Asume, everything is alright if we came so far
	//$filename = WB_PATH.$topics_directory.$link.PAGE_EXTENSION;
	//echo $filename;
	if (file_exists($filename)) { unlink($filename);}

	// Write to the filename
	$content = '<?php
$page_id = '.$p_id.';
$section_id = '.$s_id.';
$topic_id = '.$t_id.';
define("TOPIC_ID", '.$t_id.');
require("'.TOPICS_DIRECTORY_DEPTH.'config.php");
require(WB_PATH."/index.php");

?>';
	$handle = fopen($filename, 'w');
	fwrite($handle, $content);
	fclose($handle);
	
	//echo '<p>Auto-archived: '.$link.'</p>';
	
}

function topics_frontendfooter () {
	echo '<div class="freditfooter"></div></div></body></html>';

}

function get_any_sections ($section) {
	global $database, $wb;
	global $section_id, $page_id;

	$tpreal_section = $section_id;
	$tpreal_page = $page_id;

	$theq = "SELECT module, page_id FROM ".TABLE_PREFIX."sections WHERE section_id = '$section'";
	$result = $database->query($theq);
	if ($result->numRows() != 1) {return ("no valid section_id");}

	$fetch_result = $result->fetchRow();
	$page_id = $fetch_result['page_id'];
	$section_id = $section;

	ob_start();
	include(WB_PATH.'/modules/'.$fetch_result['module'].'/view.php');
	$content=ob_get_contents();
	ob_end_clean();

	$wb->preprocess($content);
	return ($content);
}

?>
