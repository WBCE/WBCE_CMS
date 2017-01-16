<?php
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

function checkforsame($w_soll, $h_soll, $width, $height) {
	if ($w_soll == 0 OR $h_soll == 0) {
		if ($width == $w_soll OR $height == $h_soll) {return true;} //nur eines muss stimmen
	} else {
		if ($width == $w_soll AND $height == $h_soll) {return true;} //beide stimmen
	}
	return false;
}

//Das muss hier so gemacht werden:
require_once('../info.php');
$mod_dir = $module_directory;
$tablename = $module_directory;

if (!$_FILES['uploadpic']['tmp_name'] OR $_FILES['uploadpic']['tmp_name'] == '') {
	echo '<p>no file uploaded!</p>';
	return 0;
	//die();
} 

// Get Settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id' AND page_id = '$page_id'");
if($query_settings->numRows() != 1) { die('Wahh!'); }
$settings_fetch = $query_settings->fetchRow();
$picture_dir = $settings_fetch['picture_dir'];
$picture_dirurl = WB_URL.$settings_fetch['picture_dir'];

$picture_dir = WB_PATH.$picture_dir;
$messages = '';
//==================================================== 
//Verzeichnis erstellen, falls noch nicht vorhanden

$dirArr = array('/zoom', '/thumbs', '/original');
foreach ($dirArr as $newdir) {
	$newfileFolder = $picture_dir.$newdir;
	if(!is_dir($newfileFolder)){
		$u = umask(0);
		if(!@mkdir($newfileFolder, 0777)){
			echo '<p style="color: red; text-align: center;">Could not create Folder <b>'.$newdir.'</b></p>';
		}
		umask($u);
	}
}
	

//NEU in 0.8.2
$additional = '';
if(isset($_REQUEST['additional']) ) {
	//Verzeichnisse erstellen, falls noch nicht vorhanden
	$additional = '/topic'.$topic_id; 
	$picture_dir .= $additional;
	$dirArr = array('','/zoom', '/thumbs', '/original');
	foreach ($dirArr as $newdir) {
		$newfileFolder = $picture_dir.$newdir;
		if(!is_dir($newfileFolder)){
			$u = umask(0);
			if(!@mkdir($newfileFolder, 0777)){
				echo '<p style="color: red; text-align: center;">Could not create Folder <b>'.$additional.$newdir.'</b></p>';
			}
			umask($u);
		}
	}
}







$o = 0; 
if ($additional != '') {$o = 8;} //Offset in picture_values

$vv = explode(',',$settings_fetch['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
$w_zoom = (int) $vv[0+$o]; if ($w_zoom == -2) {$w_zoom = 1000;}
$h_zoom = (int) $vv[1+$o]; if ($h_zoom == -2) {$h_zoom = 0;}
$w_view = (int) $vv[2+$o]; if ($w_view == -2) {$w_view = 300;}
$h_view = (int) $vv[3+$o]; if ($h_view == -2) {$h_view = 0;}
$w_thumb = (int) $vv[4+$o]; if ($w_thumb == -2) {$w_thumb = 70;}
$h_thumb = (int) $vv[5+$o]; if ($h_thumb == -2) {$h_thumb = 70;}

//die($w_thumb.' '.$h_thumb );





if (($w_view == 0 AND $h_view == 0) OR ($w_thumb == 0 AND $h_thumb == 0)) {
	echo '<p>no dimensions given! <a href="javascript:window.history.back()">BACK</a></p>';
	die();
}

require_once(WB_PATH."/framework/functions.php");
require_once("imagefunctions.php");


//Hinweis: $picture_dir kann jetzt auch ein Unterverzeichnis sein
//====================================================

//Multiple uploads: GO:
$uploaded = count($_FILES['uploadpic']['name']);	
for($i=0; $i<$uploaded; $i++){
	//echo $_FILES['uploadpic']['name'][$i]; echo '<br/>';
	
	$tmp_name = $_FILES['uploadpic']['tmp_name'][$i];
	
	list($width, $height, $type, $attr) = getimagesize($tmp_name);
	
	$bildtype = "";
	if ($type == 1) {$bildtype = ".gif";}
	if ($type == 2) {$bildtype = ".jpg";}			
	if ($type == 3) {$bildtype = ".png";}
		
	
	if ($bildtype == "" OR $width == 0) {
		echo '<p style="color: red; text-align: center;">no picture file uploaded (jpg, gif, png)</p>';
		continue;
		//nur überspringen!
	}
	
	$fname = $_FILES['uploadpic']['name'][$i]; 
	//Extension weg und standardisieren:
	$fname = substr($fname, 0, strlen($fname) - 4);
	$fname = page_filename($fname); 
	
	//ggf eine Zahl anhängen:
	if(isset($_REQUEST['nooverwrite']) ) {
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
	
	
	//---------------------------------------------------------------------------------
	//If there is already a picture with exacly the same size and the same name: 
	//simply replace it an do not!! resize again:
	
	//view:
	if (checkforsame($w_view, $h_view, $width, $height)) {
		$filepath = $picture_dir.'/'.$imagename;
		if (file_exists($filepath)) {
			if (! move_uploaded_file($tmp_name, $filepath))  { 
			die (' <h2>Speichern fehlgeschlagen!</h2>'); 
			} else {
				$messages .= ' <b>view-picture replaced</b>';
				return 0;
			}
		} 
	}
	
	//thumb:
	if (checkforsame($w_thumb, $h_thumb, $width, $height)) {
	
		$filepath = $picture_dir.'/thumbs/'.$imagename;		
		if (file_exists($filepath)) {
			
			if (! move_uploaded_file($tmp_name, $filepath))  { 
			die (' <h2>Speichern fehlgeschlagen!</h2>'); 
			} else {
				$messages .= ' <b>thumb replaced</b>';
				return 0;
			}
		} 
	}
	 

	//---------------------------------------------------------------------------------
	if ($width > $w_zoom AND $height > $h_zoom) {
		// Original behalten und dann verkleinern
		$filepath = $picture_dir.'/original/'.$imagename;
		if (! move_uploaded_file($tmp_name, $filepath))  { die (' <h2>Speichern fehlgeschlagen!</h2>'); }
		$newfilepath = $picture_dir.'/zoom/'.$imagename;
		resizepic($filepath, $newfilepath, $w_zoom, $h_zoom);
		$filepath = $newfilepath;	
	} else {
		//nur verschieben
		$filepath = $picture_dir.'/zoom/'.$imagename;
		if (! move_uploaded_file($tmp_name, $filepath))  { die (' <h2>Speichern fehlgeschlagen!</h2>'); }
	}
	
	
	
	$newfilepath = $picture_dir.'/'.$imagename;
	resizepic($filepath, $newfilepath, $w_view, $h_view);
	
	$newfilepath = $picture_dir.'/thumbs/'.$imagename;
	resizepic($filepath, $newfilepath, $w_thumb, $h_thumb);
}	




?>