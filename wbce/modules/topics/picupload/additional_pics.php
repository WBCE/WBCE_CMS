<?php 
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

// Get id
if(isset($_REQUEST['section_id']) AND is_numeric($_REQUEST['section_id']) AND isset($_REQUEST['page_id']) AND is_numeric($_REQUEST['page_id']) AND isset($_REQUEST['topic_id']) AND is_numeric($_REQUEST['topic_id']) ) {
	$section_id = (int) $_REQUEST['section_id']; 
	$page_id = (int) $_REQUEST['page_id']; 
	$topic_id = (int) $_REQUEST['topic_id']; 
} else {
   die('missing parameters');
}

$p = "page_id=$page_id&section_id=$section_id&topic_id=$topic_id";

//Das muss hier so gemacht werden:
require_once('../info.php');
$mod_dir = $module_directory;
$tablename = $module_directory;


$theauto_header = false;
 
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }

// Get Settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();
$picture_dir = ''.$settings_fetch['picture_dir'];


if( isset($_GET['do']) AND $_GET['do'] == 'upload') {
	if ( isset($_FILES['uploadpic']['tmp_name']) AND $_FILES['uploadpic']['tmp_name'] != '') {
	include('uploader.inc.php');
	echo $messages;
	$picture_dir = ''.$settings_fetch['picture_dir']; //hat der Uploader ge�ndert
	//die();
}
}

?><!DOCTYPE html><html>
<head>
<title>Additional Pictures</title>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	background-color: #fff;
	margin: 0px;
	padding: 0px;
}

.additional_pictures img {float:left; margin:10px;}
-->
</style>
</head>
<body>

<div style="float:right; width:250px; height:200px;">
<div id="loaderanimation" style="display:none; height:50px;text-align:center;"><img src="../img/loader.gif" width="208" height="13" /><br/>don't interrupt..</div>
<form name="upload" action="?do=upload&<?php echo $p; ?>" method="post" style="margin: 0; width:90%; font-size:10px;"  enctype="multipart/form-data">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
	<input type="hidden" name="additional" value="1">
	
	
<input type="file" class="inputfield" name="uploadpic[]" multiple style="width:250px;font-size:11px;" onchange="startupload()"/>
<input type="submit" style="width:30px;font-size:11px;" value="GO!">
<br/><input type="checkbox" name="nooverwrite" value="1">Do NOT overwrite existing files
</form>
</div>

<div class="additional_pictures">
<?php 
//Weitere Bilder

$additional_picture_path = WB_PATH.''.$picture_dir.'/topic'.$topic_id;
$pics = array();
if (is_dir($additional_picture_path)) {
	$files = glob($additional_picture_path."/*.{jpg,png,gif}", GLOB_BRACE);
 	if(count($files) > 0 ) {	
		natcasesort($files);
		$additional_picture_url = WB_URL.''.$picture_dir.'/topic'.$topic_id.'/thumbs/';
		
		foreach($files as $file) {
			$pic = basename($file);
			$pics[] = $pic;
			echo '<a href="javascript:parent.openpicturemodify(\'topic'.$topic_id.'/'.$pic.'\');"><img src="'.$additional_picture_url.$pic.'" title="'.$pic.'" alt="'.$pic.'" /></a>';
			
			
		}
	}
}
?>
</div>

<script type="text/javascript">
	function startupload() {
		document.getElementById("loaderanimation").style.display="block";
		document.upload.submit()
	}
</script>


</body></html>