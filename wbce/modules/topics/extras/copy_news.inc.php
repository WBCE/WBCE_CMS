<?php
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

//Call:
//http://localhost/2016/_dev/wbce113/modules/cmdev_topics_putzt/extras/copy_news.inc.php?page_id=17&section_id=21&delete=
//http://localhost/2016/_dev/wbce113/modules/cmdev_topics_putzt/extras/copy_news.inc.php?page_id=17&section_id=21

//$queryloops = 5; //convert posts 


$pA = explode(DIRECTORY_SEPARATOR,dirname(__FILE__));
array_pop ($pA);
$mod_dir = array_pop ($pA );
$tablename = $mod_dir;

$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
require_once($mpath.'defaults/module_settings.default.php');
require_once($mpath.'module_settings.php');

$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
require_once($mpath.'/functions.php');

// Include WB functions file
require_once(WB_PATH.'/framework/functions.php');

$theauto_header = false;
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }
if( $admin->get_user_id() > 1 ) {echo '<h3>Access for admin 1 only</h3>'; return 0;}

// only copy a specific section?
$copysectionsql = ''; $copysection = 0;
if ( isset($_GET['copysection']) AND is_numeric($_GET['copysection']) AND $_GET['copysection'] > 0 ) {
	$copysectionsql = ' AND section_id = '.$_GET['copysection'].' ';
	$copysection = (int) $_GET['copysection'];
}

$section_id = (int) $_GET['section_id'];
$page_id = (int) $_GET['page_id'];

if ( isset($_GET['delete']) ) {
	deletetesttopics();
	die('<h1>Testdata deleted</h1>');
}

//now: GET INFO:
$sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_news_posts` WHERE  active > -1 '.$copysectionsql;
echo $sql;
$query_post = $database->query($sql);
if ($database->is_error()) {
	die('no posts found');
} else {
	$numposts = $query_post->numRows();
	if ($numposts < 1 ) { die('<h2>no (more) posts found</h2>');}
	echo '<h2>Info:</h2><p>posts found: <b>'.$numposts.'</b></p>';
	
	$sql = 'SELECT * FROM `'.TABLE_PREFIX."mod_news_comments` WHERE  comment <> ''" .$copysectionsql;
	$query_comments = $database->query($sql);
	$numcomments = $query_comments->numRows();
	echo '<p>comments found: <b>'.$numcomments.'</b></p>';
}

if ( isset($_GET['do']) AND $_GET['do'] =='copy' ) {

} else {
	$dolink = WB_URL.'/modules/'.$mod_dir.'/extras/copy_news.inc.php?page_id='.$page_id.'&amp;section_id='.$section_id.'&amp;copysection='.$copysection.'&amp;do=copy';
	echo '<p><a href="'.$dolink.'">COPY</a></p>';
	die();
}


//



//Add field is_topic_id:

$post = $query_post->fetchRow();
if (!isset($post['is_topic_id'])) {
	$sql = 'ALTER TABLE  `'.TABLE_PREFIX.'mod_news_posts` ADD  `is_topic_id` INT NOT NULL DEFAULT  \'0\'';
	$database->query($sql);
	if(!$database->is_error()) {
		echo '<h1>Datenbankfeld is_topic_id wurde angelegt</h1>';
		//die();
	} else {	
		$admin->print_error($database->get_error());
		die('!$post');
	}
}

$i = 0;


$sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_news_posts` WHERE  active > -1 '.$copysectionsql .' AND is_topic_id = 0';
$query_post = $database->query($sql);

if ($query_post->numRows() < 1 ) {	
	die('<h1>No more Posts</h1>');
} 

while ($post = $query_post->fetchRow()) {
	$post_id = $post['post_id'];
	$title = $post['title'];
	$groups_id = ','.$post['group_id'].',';
	$position = $post['position'];
	$content_short = '<!-- copy of news post '.$post_id.' -->'.$post['content_short'];
	$content_long = $post['content_long'];
	//$short_description = $post['content_short']; //Wird nicht uebertragen
	
	$description = makemetadescription ( strip_tags($content_short . ' ' . $title) );
	$keywords = makemetakeywords ( strtolower(strip_tags($title .' '.$content_short)));
		
	$hascontent = 1;
	if (strlen($content_long) < 7) {$hascontent = 0;}
	if (strlen($content_long) > 400) {$hascontent = 2;}
	
	$content_short = addslashes($content_short);
	$content_long = addslashes($content_long);
	
	
	$commenting_topics = -2; //default
	$commenting = $post['commenting'];
	if ($commenting == 'none') {$commenting_topics = -1;}
	if ($commenting == 'private') {$commenting_topics = 1;}
	
	$active_topics = 4; //public
	$active = $post['active'];
	if ($active == 0) {$active_topics = 0;} //none
	
	$link = str_replace('/posts/','',$post['link']);
	
	$published_when =  $post['published_when'];
	$published_until =  $post['published_until'];
	$posted_by =  (int) $post['created_by'];
	if ($posted_by < 1) {$posted_by = 1;}
	$posted_first =  $post['created_when'];
	$modified_by =  $post['posted_by'];
	$posted_modified =  $post['posted_when'];
	
	$picture =  rand(1,3).'.jpg';
	
	
	$theqbase = " 
	title = '$title', 
	groups_id = '$groups_id', 
	position = '$position', 
	content_short = '$content_short', 
	content_long = '$content_long', 
	commenting = '$commenting_topics', 
	active = '$active_topics', 
	
	hascontent = '$hascontent', 
	
	description = '$description', 
	keywords = '$keywords', 
	link = '$link', 
	picture = '$picture', 
	
	published_when = '$published_when', 
	published_until = '$published_until', 
	posted_by = '$posted_by', 
	posted_first = '$posted_first', 
	modified_by = '$modified_by', 
	posted_modified = '$posted_modified', 
	authors = '$posted_by'
	";
	
	
	
	
	$theq = "INSERT INTO ".TABLE_PREFIX."mod_".$tablename." SET section_id = '$section_id', page_id = '$page_id', ".$theqbase;
	//echo $theq;
	$database->query($theq);
	//$admin->print_error($database->get_error());
	// Get the id
	$topic_id = $database->get_one("SELECT LAST_INSERT_ID()");
	
	topics_createaccess_file ('', $link, 1, $topics_directory, $topics_directory_depth);
	
	//===================================================================
	
	//comments
	
	$users = array();
	$sql = 'SELECT `user_id`,`username`,`display_name`,`email` FROM `'.TABLE_PREFIX.'users`';
	if (($resUsers = $database->query($sql))) {
		while ($recUser = $resUsers->fetchRow()) {
			$users[$recUser['user_id']] = $recUser;
		}
	}
	
	$comments_count = 0;
	$sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_news_comments` WHERE `post_id`='.$post_id.' ORDER BY `commented_when` ASC';
	if (($query_comments = $database->query($sql))) {
		while (($entry = $query_comments->fetchRow())) {  
			$title = $entry['title'];		
			$comment = addslashes('<b>'.$title."</b>\n".$entry['comment']);
			
			$commented_when = $entry['commented_when'];
			$commented_by = $entry['commented_by'];
			
			$name = 'Guest'; $email = '';
			if ($commented_by > 0)  {
				if (isset($users[$commented_by]['username']) AND $users[$commented_by]['username'] != '') {			
					$name  = $users[$commented_by]['display_name'];
					$email = $users[$commented_by]['email'];
				}
			}	
			
			
			$theq = "INSERT INTO ".TABLE_PREFIX."mod_".$tablename."_comments SET topic_id = '$topic_id', name = '$name', email = '$email', comment = '$comment', commented_when = '$commented_when', commented_by = '$commented_by', active = 1";
			//echo $theq;
			$database->query($theq);
			$comments_count ++;
		}
		$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename." SET comments_count = ".$comments_count." WHERE topic_id = ".$topic_id;		
		//echo $theq;
		$database->query($theq);
	}
	
	$theq = "UPDATE `".TABLE_PREFIX."mod_news_posts` SET is_topic_id = '$topic_id' WHERE post_id = '$post_id'";
	//echo $theq;
	$database->query($theq);
	
	echo '<div>Post '.$post_id.' was copied as topic '.$topic_id.'</div>';
	
	$i++;
}  // end while ($i < $queryloops)


function deletetesttopics() {
	global $database;
	global $tablename;
	$theq = "ALTER TABLE  `".TABLE_PREFIX."mod_news_posts` DROP `is_topic_id`";
	$database->query($theq);
	
	$theq = "DELETE FROM ".TABLE_PREFIX."mod_".$tablename." WHERE groups_id <> ''";
	$database->query($theq);
}

?>