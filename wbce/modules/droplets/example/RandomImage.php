//:Get a random image from a folder in the MEDIA folder.
//:Usage [[RandomImage?dir=subfolder_in_mediafolder]]
$dir  = (isset($dir) && $dir != '') ? trim(str_replace('\\', '/', html_entity_decode($dir)), '/') : '';
$base = realpath(WB_PATH.MEDIA_DIRECTORY);
$path = ($base === false) ? false : realpath($base.'/'.$dir);
// only folders inside the media folder
if ($path === false || !is_dir($path) || ($path !== $base && strpos($path, $base.DIRECTORY_SEPARATOR) !== 0)) {
    return true;
}
$names = array();
foreach (scandir($path) as $file) {
    $ext = strtolower(substr($file, -4));
    if ($ext == '.jpg' || $ext == '.gif' || $ext == '.png') {
        $names[] = $file;
    }
}
if (empty($names)) {
    return true;
}
$image = $names[array_rand($names)];
$url   = WB_URL.MEDIA_DIRECTORY.'/'.($dir !== '' ? $dir.'/' : '').$image;
$size  = (isset($width) ? ' width="'.(int)$width.'"' : '').(isset($height) ? ' height="'.(int)$height.'"' : '');
return '<img src="'.htmlspecialchars($url).'" alt="'.htmlspecialchars(substr($image, 0, -4)).'"'.$size.' />';
