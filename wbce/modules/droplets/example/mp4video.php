//:mp4video
//:Use: [[mp4video?mp4=dateiname.mp4&w=640&h=480]]
// w and h are optional
// load mp4-Video from /media/ or subfolder
if (!isset($w)) $w="100%";
if (!isset($h)) $h="auto";
if (!isset($p)) $p="preview-video.jpg";
$rv= "<video width=\"".$w."\" height=\"".$h."\" poster=\"".WB_URL."/media/".$p."\" controls>";
$rv.="<source src=\"".WB_URL."/media/".$mp4."\" type=\"video/mp4\" />";
$rv.="Your browser does not support the video element.</video>";
return $rv;