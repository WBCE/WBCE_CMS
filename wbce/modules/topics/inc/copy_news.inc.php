<?php
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}


//Das muss hier so gemacht werden:
require_once('../info.php');
$mod_dir = $module_directory;
$tablename = $module_directory;

$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
require_once($mpath.'/functions.php');

// Include WB functions file
require(WB_PATH.'/framework/functions.php');

$theauto_header = false;
 
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }

if( $admin->get_user_id() > 1 ) {echo '<h1>Access for admin 1 only</h1>';}

// Get id
$copysection = '';
if ( isset($_GET['copysection']) AND is_numeric($_POST['copysection']) ) {
	$copysection = ' AND section_id = '.$_GET['copysection'].' ';
	//Nur diese Section copieren
}


// Einen Datensatz abfragen unf ggf Feld 'is_topic_id' einf�gen.
$sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_news_posts` WHERE  active=1 '.$copysection .' AND is_topic_id = 0 LIMIT 1';
$query_post = $database->query($sql);
if($database->is_error()) {
	$sql = 'ALTER TABLE  `'.TABLE_PREFIX.'mod_news_posts` ADD  `is_topic_id` INT NOT NULL DEFAULT  \'0\'';
	$database->query($sql);
	if(!$database->is_error()) {
		echo '<h1>Datenbankfeld is_topic_id wurde angelegt</h1>';
		die();
	} else {	
		$admin->print_error($database->get_error());
		die('!$post');
	}
}


if (!$post = $query_post->fetchRow()) {	
	die('<h1>Nix mehr da</h1>');
} 

$post_id = $post['post_id'];
$title = $post['title'];
$groups_id = ','.$post['group_id'].',';
$position = $post['position'];
$content_short = '<!-- copy of news post '.$post_id.' -->'.$post['content_short'];
$content_long = $post['content_long'];
//$short_description = $post['content_short']; //Wird nicht �bertragen

$description = makemetadescription ( strip_tags($content_short . ' ' . $title) );
$keywords = makemetakeywords ( strtolower(strip_tags($title .' '.$content_short)));
	
$hascontent = 1;
if (strlen($content_long) < 7) {$hascontent = 0;}
if (strlen($content_long) > 400) {$hascontent = 2;}

$content_short = addslashes($content_short);
$content_long = addslashes($content_long);


$commenting_topics = 2; //verz�gert
$commenting = $post['commenting'];
if ($commenting == 'none') {$commenting_topics = -1;}
if ($commenting == 'private') {$commenting_topics = 1;}

$active_topics = 4; //�ffentlich
$active = $post['active'];
if ($active == 0) {$active_topics = 0;}

$link = str_replace('/posts/','',$post['link']);

$published_when =  $post['published_when'];
$published_until =  $post['published_until'];
$posted_by =  $post['created_by'];
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


 $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_news_comments` WHERE `post_id`='.$post_id.' ORDER BY `commented_when` ASC';
if (($query_comments = $database->query($sql))) {
	while (($entry = $query_comments->fetchRow())) {  
		$title = $entry['title'];		
		$comment = addslashes($title."\n".$entry['comment']);
		
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
	}
}

$theq = "UPDATE `".TABLE_PREFIX."mod_news_posts` SET is_topic_id = '$topic_id' WHERE post_id = '$post_id'";
//echo $theq;
$database->query($theq);

	echo '<h1>News-Eintrag '.$post_id.' wurde als Topic '.$topic_id.' &uuml;bertragen</h1>';

?>