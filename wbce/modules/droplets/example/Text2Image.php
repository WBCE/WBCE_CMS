//:Create an image from the textparameter
//:Use [[text2image?text=The text to create]]
//clean up old files..
$dir = WB_PATH.'/temp/';
$dp = opendir($dir) or die ('Could not open '.$dir);
while ($file = readdir($dp)) {
	if ((preg_match('/img_/',$file)) && (filemtime($dir.$file)) <  (strtotime('-10 minutes'))) {
		unlink($dir.$file);
	}
}
closedir($dp);

$imgfilename = 'img_'.rand().'_'.time().'.jpg';
//create image
$padding = 0;
$font = 3;  	

$height = imagefontheight($font) + ($padding * 2);
$width = imagefontwidth($font) * strlen($text) + ($padding * 2);
$image_handle = imagecreatetruecolor($width, $height);
$text_color = imagecolorallocate($image_handle, 0, 0, 0);
$background_color = imagecolorallocate($image_handle, 255, 255, 255);
$bg_height = imagesy($image_handle);
$bg_width = imagesx($image_handle);
imagefilledrectangle($image_handle, 0, 0, $bg_width, $bg_height, $background_color);
imagestring($image_handle, $font, $padding, $padding, $text, $text_color);
imagejpeg($image_handle,WB_PATH.'/temp/'.$imgfilename,100);
imagedestroy($image_handle);

return '<img src="'.WB_URL.'/temp/'.$imgfilename.'" style="border:0px;margin:0px;padding:0px;vertical-align:middle;" />';