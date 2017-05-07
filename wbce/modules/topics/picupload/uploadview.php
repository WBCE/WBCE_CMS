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
$p = "page_id=$page_id&amp;section_id=$section_id&amp;topic_id=$topic_id";



require_once(WB_PATH."/framework/functions.php");
require_once("imagefunctions.php");



//Actions?:
if(isset($_REQUEST['resize']) ) {
	$resize = $_REQUEST['resize'];
	echo '<h1>Die Funktion Resize wurde entfernt</h1>';
	die();
	
} 
	

if(!isset($_REQUEST['fn']) ) {
	echo '<h1>Die Funktion Upload wurde entfernt</h1>';
	die();

} 


//Filename given: Only show:
$imagename = $_REQUEST['fn'];
$imagename = str_replace('zoom/','',$imagename );
$imagename = str_replace('thumbs/','',$imagename );

//Vortest fuer getbasics.inc.php
$subdir = '';
$morepicstest = explode('/', $imagename);
if (count($morepicstest) == 2) {
	$subdir = $morepicstest[0].'/';
}

//Jetzt erst includen
include('getbasics.inc.php');

$picture_dir = WB_PATH.$picture_dir;
//Ist dieses eines der "additional pictures":
$subdir = '';
$morepicstest = explode('/', $imagename);
if (count($morepicstest) == 2) {
	$subdir = $morepicstest[0].'/';
	$picture_dir .= '/'.$morepicstest[0];
	$picture_dirurl .= '/'.$morepicstest[0];
	$imagename = $morepicstest[1];
}





if (($w_view == 0 AND $h_view == 0) OR ($w_thumb == 0 AND $h_thumb == 0)) {
	echo '<h2>no dimensions given!</h2><p><a href="javascript:window.history.back()">BACK</a></p>';
	die();
}


//Show:
?>
<script type="text/javascript">
function closeuploadview(dir) {
	if (dir=='') {parent.choosethispicture('<?php echo $imagename; ?>');} else {parent.reloadadditionalpictures ();}
	parent.choosethispicture(0);
}
</script>

<?php
echo '<table class="modifyheader"><tr>
';
$query_pic= $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE picture = '$imagename'");
	if($query_pic->numRows() == 0) { 	
	echo '<td class="deletepic"><a href="modify_thumb.php?'.$p.'&amp;fn='.$subdir.$imagename.'&amp;what=delete"><img src="../img/delete.png" alt="delete" title="delete" /></a></td>';
}

echo '<td><h2><a href="javascript:closeuploadview(\''.$subdir .'\')">'.$imagename.' <img src="../img/apply.png" alt="apply" title="apply" /></a></h2>
</td></tr></table>';




$tl='?t='.time();
$newfilepath = $picture_dir.'/thumbs/'.$imagename;

if (file_exists($newfilepath)) {
	echo '<table class="showpics"><tr><td class="showpicspic">Thumb:<br/><img class="uploadviewpic" src="'.$picture_dirurl.'/thumbs/'.$imagename.$tl.'" alt="thumb" /></td><td><br/>
	<a href="modify_thumb.php?'.$p.'&amp;fn='.$subdir.$imagename.'&amp;what=thumb"><img src="../img/crop.gif" alt="crop" title="crop" /></a>
 	</td></tr></table>';
} else {
	echo '<a href="modify_thumb.php?'.$p.'&amp;fn='.$subdir.$imagename.'&amp;what=thumb"><img src="../img/crop.gif" alt="crop" title="crop" /></a><p>/thumbs/'.$imagename.':<br /> not found</p>';
}

$newfilepath = $picture_dir.'/'.$imagename;
if (file_exists($newfilepath)) {
	echo '<table class="showpics"><tr><td class="showpicspic">View:<br/><img class="uploadviewpic" src="'.$picture_dirurl.'/'.$imagename.$tl.'" alt="view" /></td><td><br/>
	<a href="modify_thumb.php?'.$p.'&amp;fn='.$subdir.$imagename.'&amp;what=view"><img src="../img/crop.gif" alt="crop" title="crop" /></a>
 	</td></tr></table>';
} else {
	echo '<a href="modify_thumb.php?'.$p.'&amp;fn='.$subdir.$imagename.'&amp;what=view"><img src="../img/crop.gif" alt="crop" title="crop" /></a>
	<p>/'.$imagename.':<br /> not found</p>';
}

$newfilepath = $picture_dir.'/zoom/'.$imagename;
if (file_exists($newfilepath)) {
	echo '<p style="clear:both;">Zoom:<br /><img src="'.$picture_dirurl.'/zoom/'.$imagename.$tl.'" alt="zoom" /></p>';
} else {
	echo '<p>'.$newfilepath.'<br />Not found</p>';
}


	
?>


</body></html>