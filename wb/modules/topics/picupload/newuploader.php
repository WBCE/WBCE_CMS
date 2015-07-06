<?php 
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}



if(isset($_REQUEST['section_id']) AND is_numeric($_REQUEST['section_id']) AND isset($_REQUEST['page_id']) AND is_numeric($_REQUEST['page_id']) AND isset($_REQUEST['topic_id']) AND is_numeric($_REQUEST['topic_id']) ) {
	$section_id = (int) $_REQUEST['section_id']; 
	$page_id = (int) $_REQUEST['page_id']; 
	$topic_id = (int) $_REQUEST['topic_id']; 
} else {
   die('no section given');
}

$theauto_header = false;
require_once(WB_PATH.'/framework/class.admin.php');
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
<div id="loaderanimation" style="display:none; height:50px;text-align:center;"><img src="../img/loader.gif" width="208" height="13" /><br/>don't interrupt..
</div>
<form name="upload" action="newuploader2.php" method="post" style="margin: 0; width:100%; font-size:10px;"  enctype="multipart/form-data">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
	
	
<input type="file" class="inputfield" name="uploadpic[]" style="width:250px;font-size:11px;" onchange="startupload()"/>
<input type="submit" style="width:30px;font-size:11px;" value="GO!">
<br/><input type="checkbox" name="nooverwrite" value="1">Do NOT overwrite existing files
</form>

<script type="text/javascript">
	function startupload() {
		document.getElementById("loaderanimation").style.display="block";
		document.upload.submit()
	}
</script>


</body></html>