<?php 
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}



// Get id
if(isset($_REQUEST['section_id']) AND is_numeric($_REQUEST['section_id']) AND isset($_REQUEST['page_id']) AND is_numeric($_REQUEST['page_id']) AND isset($_REQUEST['topic_id']) AND is_numeric($_REQUEST['topic_id']) ) {
	$section_id = (int) $_REQUEST['section_id']; 
	$page_id = (int) $_REQUEST['page_id']; 
	$topic_id = (int) $_REQUEST['topic_id']; 
} else {
   die('no section given');
}

$theauto_header = false;
 
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Upload</title>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	background-color: #fff;
	margin: 0px;
	padding: 0px;
}
-->
</style>
</head>
<body>
<?php

include('uploader.inc.php');
echo $messages;
/*
$tl='?t='.time();
$newfilepath = $picture_dir.'/thumbs/'.$imagename;
if (file_exists($newfilepath)) {
	echo '<div><img class="uploadviewpic" src="'.$picture_dirurl.'/thumbs/'.$imagename.$tl.'" alt="thumb" />
	<a href="modify_thumb.php?page_id='.$page_id.'&amp;section_id='.$section_id.'&amp;fn='.$imagename.'&amp;what=thumb">crop</a>
 	</div>';
} else {
	echo '<p>/thumbs/'.$imagename.':<br /> not found</p>';
}
*/

echo '<script type="text/javascript">
';

if ($additional == '') {
	echo 'parent.choosethispicture(\''.$imagename.'\');';		
} else {
	echo 'parent.additionalpicture('.$topic_id.', \''.$picture_dir.'/thumbs/'.$imagename.'\');';	
}
if ($messages=='') echo 'parent.closepictureupload(); ';
?>
</script>


</body></html>