<?php  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Uploader2</title>
</head><body>
<?php
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

// Get id
if(isset($_POST['section_id']) AND is_numeric($_POST['section_id']) AND isset($_POST['page_id']) AND is_numeric($_POST['page_id'])) {
	$section_id = (int) $_POST['section_id']; 
	$page_id = (int) $_POST['page_id']; 
} else {
   die('no section given');
}


$theauto_header = false;
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }

//Das muss hier so gemacht werden:
require_once('../info.php');
$mod_dir = $module_directory;
$tablename = $module_directory;

// Get Settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id' AND page_id = '$page_id'");
if($query_settings->numRows() != 1) { die('Wahh!'); }
$settings_fetch = $query_settings->fetchRow();
$picture_dir = WB_PATH.$settings_fetch['picture_dir'];
$picture_dirurl = WB_URL.$settings_fetch['picture_dir'];

$vv = explode(',',$settings_fetch['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
$w_zoom = (int) $vv[0]; if ($w_zoom == -2) {$w_zoom = 1000;}
$h_zoom = (int) $vv[1]; if ($h_zoom == -2) {$h_zoom = 0;}
$w_view = (int) $vv[2]; if ($w_view == -2) {$w_view = 200;}
$h_view = (int) $vv[3]; if ($h_view == -2) {$h_view = 0;}
$w_thumb = (int) $vv[4]; if ($w_thumb == -2) {$w_thumb = 100;}
$h_thumb = (int) $vv[5]; if ($h_thumb == -2) {$h_thumb = 100;}

/*
$w_zoom = (int) $_POST['w_zoom'];
$h_zoom = (int) $_POST['h_zoom'];
$w_view = (int) $_POST['w_view'];
$h_view = (int) $_POST['h_view'];
$w_thumb = (int) $_POST['w_thumb'];
$h_thumb = (int) $_POST['h_thumb'];
*/

if (($w_view == 0 AND $h_view == 0) OR ($w_thumb == 0 AND $h_thumb == 0)) {
	echo '<h2>no dimensions given!</h2><p><a href="javascript:window.history.back()">BACK</a></p>';
	die();
}

require_once(WB_PATH."/framework/functions.php");

require_once("imagefunctions.php");



//Actions?:
if(isset($_POST['resize']) ) {
	$resize = $_POST['resize'];
	
	//get all source pictures:
	$pic_arr = array();
	$file_dir = $picture_dir; //in view nachsehen und dann erst die zoom-bilder suchen
	$pic_dir=opendir($file_dir);
	while ($file=readdir($pic_dir)) {
		if ($file != "." && $file != "..") { $pic_arr[] = $file; }		
	}
	$found = count($pic_arr);
	$counter = 0;
	echo "resizing...";
	foreach ($pic_arr as $imagename ) {
		//echo $file.'<br>';
		$origpath = '';			
		$testpath = $picture_dir.'/zoom/orig-'.$imagename;
		
		
		if (file_exists($testpath)) { 
			$origpath = $testpath;				
			if (in_array("zoom", $resize) AND ( $w_zoom > 0 OR $h_zoom > 0) ) {
				$newfilepath = $picture_dir.'/zoom/'.$imagename; 
				$counter += resizepic($origpath, $newfilepath, $w_thumb, $h_thumb, 1);
			}
		} else {
			//no orig-
			$testpath = $picture_dir.'/zoom/'.$imagename; 
			if (file_exists($testpath)) { $origpath = $testpath; }
		}
		
		if ($origpath != '') {
			if (in_array("view", $resize)) {
				$newfilepath = $picture_dir.'/'.$imagename;
				$counter += resizepic($origpath, $newfilepath, $w_view, $h_view, 1);
			}
			if (in_array("thumb", $resize)) {
				$newfilepath = $picture_dir.'/thumbs/'.$imagename;
				$counter += resizepic($origpath, $newfilepath, $w_thumb, $h_thumb, 1);
			}			
		}	
	} // end for each
	
	echo '<br/>'.$counter. ' out of '.$found.' pictures resized';
} 

//--------------------------------------------------------------------------
//deleting not used pictures
//not finished.
/*
if ($resize == 'delnonused') {
	$query_pics = $database->query("SELECT picture FROM ".TABLE_PREFIX."mod_".$tablename." WHERE section_id = '$section_id' AND page_id = '$page_id' AND picture <> ''");
	$found = $query_pics->numRows();
	if($found == 0) { echo 'No pictures found'; return 0;}
	//to be continued
}
*/	
	


list($width, $height, $type, $attr) = getimagesize($_FILES['uploadpic']['tmp_name']);

$bildtype = "";
if ($type == 1) {$bildtype = ".gif";}
if ($type == 2) {$bildtype = ".jpg";}			
if ($type == 3) {$bildtype = ".png";}
	

if ($bildtype == "" OR $width == 0) {
	echo '<p style="color: red; text-align: center;">no picture file uploaded (jpg, gif, png)</p>';
	return 0;
}

//Verzeichnis erstellen, falls noch nicht vorhanden
$newfileFolder = $picture_dir.'/zoom';
if(!is_dir($newfileFolder)){
	$u = umask(0);
	if(!@mkdir($newfileFolder, 0777)){
		echo '<p style="color: red; text-align: center;">Could not create Zoom-folder</p>';
	}
	umask($u);
}


$fname = $_FILES['uploadpic']['name']; 
$fname = substr($fname, 0, strlen($fname) - 4);
$fname = page_filename($fname); 

if(isset($_POST['nooverwrite']) ) {
	$ncount = 1;
	while ($ncount < 100) {
		if ($ncount == 1) {
			$imagename = $fname.$bildtype;
		} else {
			$imagename = $fname.'-'.$ncount.$bildtype;
		}
		$imagepfad = $picture_dir.'/'.$imagename;
		if (!file_exists($imagepfad)) {break;}
		$ncount++;
	}
} else {
	$imagename = $fname.$bildtype;
	$imagepfad = $picture_dir.'/'.$imagename;
}


$orig_ratio = $width / $height;

if ($w_zoom == 0 AND $h_zoom == 0) {$w_zoom = $width; $h_zoom = $height;}

if ($width > $w_zoom AND $height > $h_zoom) {
	// Original behalten und dann verkleinern
	$filepath = $picture_dir.'/zoom/orig-'.$imagename;
	if (! move_uploaded_file($_FILES['uploadpic']['tmp_name'], $filepath))  { die (' <h2>Speichern fehlgeschlagen!</h2>'); }
	$newfilepath = $picture_dir.'/zoom/'.$imagename;
	resizepic($filepath, $newfilepath, $w_zoom, $h_zoom);
	$filepath = $newfilepath;	
} else {
	//nur verschieben
	$filepath = $picture_dir.'/zoom/'.$imagename;
	if (! move_uploaded_file($_FILES['uploadpic']['tmp_name'], $filepath))  { die (' <h2>Speichern fehlgeschlagen!</h2>'); }
}



$newfilepath = $picture_dir.'/'.$imagename;
resizepic($filepath, $newfilepath, $w_view, $h_view);

$newfilepath = $picture_dir.'/thumbs/'.$imagename;
resizepic($filepath, $newfilepath, $w_thumb, $h_thumb);

//Show:
?>
<script type="text/javascript">
function finishit() {
	parent.choosethispicture('<?php echo $imagename; ?>');
	parent.choosethispicture(0);
}
</script>
<p><a href="javascript:finishit()">OK</a>
<?php

$newfilepath = $picture_dir.'/thumbs/'.$imagename;
if (file_exists($newfilepath)) {
	echo '<p>'.$newfilepath.'<br /><img src="'.$picture_dirurl.'/thumbs/'.$imagename.'" alt="thumb" /></p>';
} else {
	echo '<p>'.$newfilepath.'<br />Not found</p>';
}

$newfilepath = $picture_dir.'/'.$imagename;
if (file_exists($newfilepath)) {
	echo '<p>'.$newfilepath.'<br /><img src="'.$picture_dirurl.'/'.$imagename.'" alt="view" /></p>';
} else {
	echo '<p>'.$newfilepath.'<br />Not found</p>';
}

$newfilepath = $picture_dir.'/zoom/'.$imagename;
if (file_exists($newfilepath)) {
	echo '<p>'.$newfilepath.'<br /><img src="'.$picture_dirurl.'/zoom/'.$imagename.'" alt="zoom" /></p>';
} else {
	echo '<p>'.$newfilepath.'<br />Not found</p>';
}

/*
if($bildtype == ".jpg") { $source = imagecreatefromjpeg($_FILES['uploadpic']['tmp_name']);}
if($bildtype == ".gif") { $source = imagecreatefromgif($_FILES['uploadpic']['tmp_name']); }
if($bildtype == ".png") { $source = imagecreatefrompng($_FILES['uploadpic']['tmp_name']); }

//View Pic
$width = 200;
$height = $width / $size[0] * $size[1];
$imagepfad = $picture_dir.'/'.$imagename;

$destination = imagecreatetruecolor($width, $height);	
imagecopyresampled($destination, $source, 0, 0, 0, 0, $width, $height, imagesx($source), imagesy($source));

if($bildtype == ".jpg") { imagejpeg($destination,$imagepfad); }
if($bildtype == ".gif") { imagegif($destination,$imagepfad); }
if($bildtype == ".png") { imagegif($destination,$imagepfad); }
imagedestroy($destination);

//Thumb Pic
$width = 80;
$height = $width / $size[0] * $size[1];
$imagepfad = $picture_dir.'/thumbs/'.$imagename;

$destination = imagecreatetruecolor($width, $height);	
imagecopyresampled($destination, $source, 0, 0, 0, 0, $width, $height, imagesx($source), imagesy($source));

if($bildtype == ".jpg") { imagejpeg($destination,$imagepfad); }
if($bildtype == ".gif") { imagegif($destination,$imagepfad); }
if($bildtype == ".png") { imagegif($destination,$imagepfad); }
imagedestroy($destination);


//Zoom , Original
if(is_dir($picture_dir.'/zoom')) {
	$width = $size[0];
	$imagepfad = $picture_dir.'/zoom/'.$imagename;
	if ($width > 990) {
		$height = 990 / $size[0] * $size[1];
		$width = 990;
	
		$destination = imagecreatetruecolor($width, $height);	
		imagecopyresampled($destination, $source, 0, 0, 0, 0, $width, $height, imagesx($source), imagesy($source));
		
		if($bildtype == ".jpg") { imagejpeg($destination,$imagepfad); }
		if($bildtype == ".gif") { imagegif($destination,$imagepfad); }
		if($bildtype == ".png") { imagegif($destination,$imagepfad); }
		imagedestroy($destination);
	} else {
	
	 //move only
		if (! move_uploaded_file($_FILES['uploadpic']['tmp_name'], $imagepfad))  {		
			  echo ' <h2>Speichern fehlgeschlagen!</h2>';
		}
	}
}*/
	
?>


</body></html>