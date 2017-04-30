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
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }

// Get Settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();
$picture_dir = ''.$settings_fetch['picture_dir'];
$pv = explode(',',$settings_fetch['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
$zoomclass = $pv[6]; if ($zoomclass == "-2") {$zoomclass = "fbx";}
$zoomrel= $pv[7]; if ($zoomrel == "-2") {$zoomrel = "fbx";}
$zoomclass2 = $pv[14]; if ($zoomclass2 == "-2") {$zoomclass2 = "fbx";}
$zoomrel2= $pv[15]; if ($zoomrel2 == "-2") {$zoomrel2 = "fbx";}




if( isset($_GET['do']) AND $_GET['do'] == 'upload') {
	if ( isset($_FILES['uploadpic']['tmp_name']) AND $_FILES['uploadpic']['tmp_name'] != '') {
		include('uploader.inc.php');
		echo $messages;
		$picture_dir = ''.$settings_fetch['picture_dir']; //hat der Uploader geändert
		//die();
	}
}

$list = 'thumbs';
if( isset($_GET['list'])) {
	if ($_GET['list'] == 'thumblink') {$list = 'thumblink';}
	if ($_GET['list'] == 'viewlink') {$list = 'viewlink';}
	if ($_GET['list'] == 'zoomlink') {$list = 'zoomlink';}
}

?><!DOCTYPE html><html>
<head>
<title>Additional Pictures</title>
<link href="picupload.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	background-color: #fff;
	margin: 0px;
	padding: 0px;
}


.<?php echo $list; ?> {font-weight:bold; color:#000;}
-->

</style>

</head>
<body>



<div class="additional_pictures">
<?php 
//Weitere Bilder

$params = '&page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id; 

$additional_picture_path = WB_PATH.''.$picture_dir.'/topic'.$topic_id;
$pics = array();
if (is_dir($additional_picture_path)) {
	$files = glob($additional_picture_path."/*.{jpg,png,gif}", GLOB_BRACE);
 	if(count($files) > 0 ) {
		echo '<div class="switchmenu"><a class="thumbs" href="?list=thumbs'.$params.'">Thumb (edit)</a> | ';
		echo '<a class="thumblink" href="?list=thumblink'.$params.'">Thumb</a> | ';
		echo '<a class="viewlink" href="?list=viewlink'.$params.'">Standard</a> | ';
		echo '<a class="zoomlink" href="?list=zoomlink'.$params.'">Zoom</a></div>';
		
		natcasesort($files);
		
		$additional_picture_url = WB_URL.''.$picture_dir.'/topic'.$topic_id.'/thumbs/';
		if ($list=='viewlink') {$additional_picture_url = WB_URL.''.$picture_dir.'/topic'.$topic_id.'/';}
		if ($list=='zoomlink') {$additional_picture_url = WB_URL.''.$picture_dir.'/topic'.$topic_id.'/zoom/';}
		
		foreach($files as $file) {
			$pic = basename($file);
			$pics[] = $pic;
			$link = '<a href="javascript:parent.openpicturemodify(\'topic'.$topic_id.'/'.$pic.'\');">';
			if ($list != 'thumbs') {$link = '<a class="'.$zoomclass2.'" rel="'.$zoomrel2.'" href="'.WB_URL.$picture_dir.'/topic'.$topic_id.'/zoom/'.$pic.'" target="_blank">';}
			
			
			echo $link.'<img src="'.$additional_picture_url.$pic.'" title="'.$pic.'" alt="'.$pic.'" /></a>';
			
			
		}
	} else {
		echo '<p>&nbsp; Note: Uploaded pictures will be displayed in alphabetic order.</p>';
	}
}
?>
</div>

<div id="additional_pictures_uploader">
<div id="loaderanimation"><img src="../img/loader.gif" /><br/>don't interrupt..</div>
<form name="upload" action="?do=upload&<?php echo $p; ?>" method="post" style="margin: 0; width:90%; font-size:10px;"  enctype="multipart/form-data">
<div id="uploadbutton"><input type="file" class="inputfield" name="uploadpic[]" multiple style="width:250px;font-size:11px;" onchange="startupload()"/></div>
<!--input type="submit" style="width:30px;font-size:11px;" value="GO!"-->
<p class="notoverwrite"><input type="checkbox" name="nooverwrite" value="1">Do NOT overwrite existing files</p>
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
	<input type="hidden" name="additional" value="1">
</form>
</div>

<script type="text/javascript">
	function startupload() {
		document.getElementById("loaderanimation").style.display="block";
		document.upload.submit()
	}
</script>


</body></html>