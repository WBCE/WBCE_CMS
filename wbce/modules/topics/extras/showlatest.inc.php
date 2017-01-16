<?php

//include this file via code(2) section wherever you want.
//include(WB_PATH.'/modules/topics/extras/showlatest.inc.php');

//===========================================================
//This are default values, you might change them:
$showlatest_topics = 6;
$picture_dir = MEDIA_DIRECTORY.'/topics-pictures';
$topics_directory = WB_URL.PAGES_DIRECTORY.'/topics/';
$headline = '<h3>News</h3>';
//===========================================================


$pA = explode(DIRECTORY_SEPARATOR,dirname(__FILE__));
array_pop ($pA);
$mod_dir = array_pop ($pA );
$tablename = $mod_dir;

//You may not need this line:
echo '<link href="'.WB_URL.'/modules/'.$mod_dir.'/frontend.css" rel="stylesheet" type="text/css"  />';

//===========================================================
//===========================================================

if (!is_dir(WB_PATH.$picture_dir)) { 
	echo ('picture_dir doesnt exist');
	return 0;
}


//OK, lets go:
// Must include code to stop this file being access directly
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

//Get the topics-sections with the same picture_dir;
$theq = "SELECT section_id, page_id FROM ".TABLE_PREFIX."mod_".$mod_dir."_settings WHERE picture_dir = '".$picture_dir."'";	
$query = $database->query($theq);
$sectionsArr = array();				
if($query->numRows() > 0) {
	while($thesection = $query->fetchRow()) { 
		$pid = $thesection['page_id'];
		//CHeck if the page is visible or hidden. Others will not be listed.
		$theq = "SELECT visibility FROM ".TABLE_PREFIX."pages WHERE page_id =".$pid;			
		$query2 = $database->query($theq);
		$thepage = $query2->fetchRow();
		if ($thepage['visibility'] != 'public' AND $thepage['visibility'] != 'hidden' ) {continue; } 

		//OK, get it
		$sectionsArr[] = $thesection['section_id']; 
	}
}

//are ther still sections left?
if (count($sectionsArr) < 1) {	
	return 0; //NO.
}

$picture_dir = WB_URL.$picture_dir;
$the_sections = implode(',', $sectionsArr);
$theq = "SELECT active,posted_modified,link,picture,title,short_description,comments_count FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE active > 3 AND section_id IN (".$the_sections.") ORDER BY published_when DESC LIMIT ".$showlatest_topics;
$query_topics = $database->query($theq);
$num_topics = $query_topics->numRows();


if ($num_topics > 0) {
	echo $headline ;
	
	while($topic = $query_topics->fetchRow()) {
		$comments_count = $topic['comments_count'];
		$active = $topic['active'];
		$news = '
		<a class="pnsa_block mod_topic_loop mod_topic_active'.$active.' mod_topic_comments'.$comments_count.'" href="'.$topics_directory.$topic['link'].PAGE_EXTENSION.'">
		<img src="'.$picture_dir.'/thumbs/'.$topic['picture'].'" alt="'.$topic['title'].'" />
		<strong>'.$topic['title'].'</strong><br />'.$topic['short_description'].'
		<span class="pnsaclear"></span>
		</a>
		';
		echo $news ;		
	}
}

?>

