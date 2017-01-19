<?php 
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

// Get id
if(isset($_GET['section_id']) AND is_numeric($_GET['section_id']) AND isset($_GET['page_id']) AND is_numeric($_GET['page_id'])) {
	$section_id = (int) $_GET['section_id']; 
	$page_id = (int) $_GET['page_id']; 
} else {
   die('no section given');
}

if (!function_exists('getimagesize')) { die("<h2>function 'getimagesize' doesnt exist</h2>"); }

$theauto_header = false;
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }

//Das muss hier so gemacht werden:
require_once('../info.php');
$mod_dir = $module_directory;
$tablename = $module_directory;

// Get Settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();
$picture_dir = ''.$settings_fetch['picture_dir'];

$vv = explode(',',$settings_fetch['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
$w_zoom = (int) $vv[0]; if ($w_zoom == -2) {$w_zoom = 1000;}
$h_zoom = (int) $vv[1]; if ($h_zoom == -2) {$h_zoom = 0;}
$w_view = (int) $vv[2]; if ($w_view == -2) {$w_view = 200;}
$h_view = (int) $vv[3]; if ($h_view == -2) {$h_view = 0;}
$w_thumb = (int) $vv[4]; if ($w_thumb == -2) {$w_thumb = 100;}
$h_thumb = (int) $vv[5]; if ($h_thumb == -2) {$h_thumb = 100;}

?>
Upload:
<form name="upload" action="uploader2.php" method="post" style="margin: 0; width:90%;"  enctype="multipart/form-data">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	
	
<input type="file" class="inputfield" name="uploadpic" style="width:350px; "/>
<p><input type="checkbox" name="nooverwrite" value="1"> Do NOT overwrite existing files</p>
<p>&nbsp;</p>	
<p>Current Resize Settings (width/height):<br />
<?php echo 'Zoom: '.$w_zoom.'/'.$h_zoom.', View: '.$w_view.'/'.$h_view.', Thumbs: '.$w_thumb.'/'.$h_thumb.'<br/>'; 
?>
Resize all: <input type="checkbox" name="resize[]" value="thumb"> Thumbs | <input type="checkbox" name="resize[]" value="view"> View | <input type="checkbox" name="resize[]" value="zoom"> Zoom 
</p>	

<!--table>
<tr><td>&nbsp;</td><td>width:</td><td>height:</td></tr>
<tr><td>Zoom:</td><td><input type="text" name="w_zoom" size="4" value="900" style="width:40px; "></td><td><input type="text" name="h_zoom" size="4" value="600" style="width:40px; "></td></tr>
<tr><td>View:</td><td><input type="text" name="w_view" size="4" value="200" style="width:40px; "></td><td><input type="text" name="h_view" size="4" value="160" style="width:40px; "></td></tr>
<tr><td>Thumb:</td><td><input type="text" name="w_thumb" size="4" value="100" style="width:40px; "></td><td><input type="text" name="h_thumb" size="4" value="100" style="width:40px; "></td></tr>

</table-->
	
	



<p style="text-align:right;"><input type="submit"  value="SUBMIT"></p>
</form>