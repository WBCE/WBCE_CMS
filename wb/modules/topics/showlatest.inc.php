<?php



// Must include code to stop this file being access directly
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;

$query_topics = $database->query("SELECT active,posted_modified,link,picture,title,short_description FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE active > 3 ORDER BY published_when DESC LIMIT 6");
if($query_topics->numRows() > 0) {
	$num_topics = $query_topics->numRows();	
	$counter = 0;
	$public = 0;
	$commentscount = 0;
	echo "<h3>News</h3>";
	while($topic = $query_topics->fetchRow()) {
		$news = '<a class="rightpnsa" href="'.WB_URL.'/topics/'.$topic['link'].PAGE_EXTENSION.'"><img src="'.WB_URL.'/media/topics-pictures/thumbs/'.$topic['picture'].'" alt="'.$topic['title'].'" /><strong>'.$topic['title'].'</strong>'.$topic['short_description'].'<span class="rightpnsatrenner"></span></a>';
		echo $news ;		
	}
}

?>

