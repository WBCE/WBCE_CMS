<?php 
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

//Das muss hier so gemacht werden:
require_once('../info.php');
$mod_dir = $module_directory;
$tablename = $module_directory;

// Get id
if(isset($_REQUEST['section_id']) AND is_numeric($_REQUEST['section_id']) AND isset($_REQUEST['page_id']) AND is_numeric($_REQUEST['page_id']) AND isset($_REQUEST['topic_id']) AND is_numeric($_REQUEST['topic_id']) ) {
	$section_id = (int) $_REQUEST['section_id']; 
	$page_id = (int) $_REQUEST['page_id']; 
	$topic_id = (int) $_REQUEST['topic_id']; 
} else {
   die('missing parameters');
}
$p = "page_id=$page_id&amp;section_id=$section_id&amp;topic_id=$topic_id";
$p = "page_id=$page_id&section_id=$section_id&topic_id=$topic_id";




$whatdir = 'thumbs'; //Ziel
if(isset($_GET['what']) AND $_GET['what'] == 'view') {$whatdir = 'view';}
if(isset($_POST['what']) AND $_POST['what'] == 'view') {$whatdir = 'view';}

if(!isset($_GET['fn'])) {die('no filename');}
$file_name = $_GET['fn']; //zb file.jpg - gemeint ist immer zoom, auszer es existiert nicht.

//Vortest fuer getbasics.inc.php
$subdir = '';
$morepicstest = explode('/', $file_name);
if (count($morepicstest) == 2) {
	$subdir = $morepicstest[0].'/';
}
//Jetzt erst includen
include('getbasics.inc.php');

//Ist dieses eines der "morepics":
$subdir = '';
$morepicstest = explode('/', $file_name);
if (count($morepicstest) == 2) {
	$subdir = $morepicstest[0]. '/';	
	$file_name = $morepicstest[1];
	$picture_dir .= '/'.$morepicstest[0];
}



//echo '<h1>'.$file_name.'</h1>';


$full_file = WB_PATH.$picture_dir.'/zoom/'.$file_name;
$full_file_link = WB_URL.$picture_dir.'/zoom/'.$file_name;
if(!file_exists($full_file)) {
	$full_file = WB_PATH.$picture_dir.'/'.$file_name;	
	$full_file_link = WB_URL.$picture_dir.'/'.$file_name;
	if(!file_exists($full_file)) {
		echo '<p>No File: '.$full_file.'<p>';
		die();
	}
	if ($whatdir == 'view') {
		echo '<p>Not possible to create a view-file from a view-file<p>';
		die();
	}
}


if(isset($_GET['what']) AND $_GET['what'] == 'delete') {
	$query_pic= $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE picture = '$file_name'");
	if($query_pic->numRows() > 0) { 
		echo "<h3>This picture is in use. You cannot delete it</h3>";
		$admin->print_success('deleted', WB_URL.'/modules/'.$mod_dir.'/picupload/uploadview.php?'.$p.'&fn='.$subdir.$file_name);
	} else {
		unlink(WB_PATH.$picture_dir.'/thumbs/'.$file_name);
		unlink(WB_PATH.$picture_dir.'/zoom/'.$file_name);
		unlink(WB_PATH.$picture_dir.'/'.$file_name);
		//$admin->print_success('deleted', WB_URL.'/modules/'.$mod_dir.'/picupload/uploadview.php?page_id='.$page_id.'&section_id='.$section_id.'&fn='.$file_name);
		echo '<script type="text/javascript">
		';
		if ($subdir == '') {
			echo 'parent.openpicturepreviews();';
		} else {
			echo 'parent.closepicturepreviews();
			parent.reloadadditionalpictures();';
		}
		echo '
		</script>';
		die();
	}
}

list($width, $height, $type, $attr) = getimagesize($full_file);
/*
$thumb_file = str_replace('zoom/','',$file_name );
$thumb_file = str_replace('thumbs/','',$thumb_file );
$thumb_file = str_replace('//','/',$thumb_file );
*/
$thumb_file =WB_PATH.$picture_dir.'/thumbs/'.$file_name;

$previewWidth = $w_thumb;
$previewHeight = $h_thumb;
if ($whatdir == 'view') {
	$previewWidth = $w_view;
	$previewHeight = $h_view;
	$w_thumb = $w_view;
	$h_thumb = $h_view;
	
	$thumb_file =WB_PATH.$picture_dir.'/'.$file_name;
}

//echo "<p>Werte wie angegeben: previewWidth: $previewWidth, previewHeight: $previewHeight<p>";




//echo '<p>ratio: '.$ratio.'<p>';
/*
if ($previewHeight == 0) { $h_thumb = 0; }
if ($previewWidth == 0) { $w_thumb = 0; }
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {	

	require_once("imagefunctions.php");
	//Löscht das bisherige Thumbnail
	//deleteFile($thumb_file);
	
	//Neues Thumb erstellen
	$post_w = (int)$_POST['w'];
	$post_h = (int)$_POST['h'];
	
	//echo "<p>w_thumb: $w_thumb, h_thumb: $h_thumb, w: ".$post_w.' h: '. $post_h.'<p>';
	//die();
	if (resizepic($full_file, $thumb_file, $w_thumb, $h_thumb, 0, $_POST['x'], $_POST['y'], $post_w, $post_h)) {
		$admin->print_success('Thumb erfolgreich geaendert', WB_URL.'/modules/'.$mod_dir.'/picupload/uploadview.php?'.$p.'&fn='.$subdir.$file_name);
	}
} 	else {

	//==============================================================
	//Vorgabewerte fuer jCrop
	//==============================================================
	if ($previewWidth == 0 OR $previewHeight == 0) {
		$ratio = ''; //keine Ratio-Vorgabe beim Beschneiden
		$thumb_size = 100;		
	} else {
		$ratio = $previewWidth / $previewHeight;
		$thumb_size = $previewHeight;
		if ($ratio > 1) {$thumb_size = $previewWidth;}
	}
			
			
	echo '
	<!-- Gives the Jcrop the variable to work -->
	<script type="text/javascript">
		var relWidth = \''.$width.'\';
		var relHeight = \''.$height.'\';
		var thumbSize = \''.$thumb_size.'\';
		var settingsRatio = \''.$ratio.'\'
	</script>
		<div style="float: left; padding: 0 20px 0 20px;">
		<img src="'.$full_file_link.'" id="cropbox" style="max-width: 500px; max-height: 500px;"/>
	</div>
	<div style="float:left;" align="center">
		<div style="overflow: hidden; width: '.$previewWidth.'px; height: '.$previewHeight.'px;">
			<img src="'.$full_file_link.'" id="preview">
		</div>
		<br />
		<!-- This is the form that our event handler fills -->
		<form id="topics_pic_crop" action="'.WB_URL.'/modules/'.$mod_dir.'/picupload/modify_thumb.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fn='.$subdir.$file_name.'" method="post" onsubmit="return checkCoords();">
								<input type="hidden" name="section_id" value="'.$section_id.'">
								<input type="hidden" name="page_id" value="'.$page_id.'">
								<input type="hidden" name="topic_id" value="'.$topic_id.'">
								<input type="hidden" name="fn" value="'.$subdir.$file_name.'">
								<input type="hidden" name="what" value="'.$whatdir.'">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input class="cropbutton cropbutton_go"  type="submit" value="GO!" /><br />
			<input class="cropbutton cropbutton_cancel" type="button" value="'.$TEXT['CANCEL'].'" onClick="window.location=\''.WB_URL.'/modules/'.$mod_dir.'/picupload/uploadview.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fn='.$subdir.$file_name.'\'"/>
		</form>
	</div>';

}


echo '<script src="'.WB_URL.'/modules/'.$mod_dir.'/picupload/modify_thumb.js" type="text/javascript"></script>';
echo '<script src="'.WB_URL.'/include/jquery/jquery-ui-min.js" type="text/javascript"></script>';

/*



$sql = 'SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_files WHERE id='.$_GET['id'].';';
if($query = $database->query($sql)){
	$result = $query->fetchRow();
	$bildfilename = $result['file_name'];
	$parent_id = $result['parent_id'];
	
	
		$query2 = $database->query('SELECT * FROM '.TABLE_PREFIX.'mod_foldergallery_categories WHERE id='.$parent_id.' LIMIT 1;');
		$categorie = $query2->fetchRow();
	if ($categorie['parent'] != "-1") {
		$parent   = $categorie['parent'].'/'.$categorie['categorie'];
	}
	else $parent = '';
	
	$full_file_link = $url.$root_dir.$parent.'/'.$bildfilename;
	$full_file = $path.$root_dir.$parent.'/'.$bildfilename;
	$thumb_file = $path.$root_dir.$parent.$thumbdir.'/'.$bildfilename;
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{	
		//Löscht das bisherige Thumbnail
		deleteFile($thumb_file);
		
		//Neues Thumb erstellen
		if (generateThumb($full_file, $thumb_file, $settings['thumb_size'], 1, $settings['ratio'], $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'])) {
			$admin->print_success('Thumb erfolgreich geaendert', WB_URL.'/modules/foldergallery/modify_cat.php?page_id='.$page_id.'&section_id='.$section_id.'&cat_id='.$cat_id);
		}
	}
	else {
		
	}
}

*/


?>

</body>
</html>