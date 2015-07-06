<?php
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

$additional_picture_path = WB_PATH.$settings_fetch['picture_dir'].'/topic'.TOPIC_ID;
$additionaloutput = '';
$additional_pictures = '';

if (is_dir($additional_picture_path)) {
	$additional_picture_url = WB_URL.$settings_fetch['picture_dir'].'/topic'.TOPIC_ID.'/';
	
	$files = glob($additional_picture_path."/*.{jpg,png,gif}", GLOB_BRACE);
 	if(count($files) > 0 ) {	
		natcasesort($files);
		
		$counter = 0;
		foreach($files as $file) {
			$picture = basename($file); //[PICTURE] = only the filename
			$counter ++;
			if ($zoomrel2 !='') {$rel =' rel="'.$zoomrel2.'" ';} else {$rel='';}
			if ($zoomclass2 != '') {
				$picture_link = '<a href="'.$additional_picture_url.'zoom/'.$picture.'" class="'.$zoomclass2.'" '.$rel.'><img src="'.$additional_picture_url.$picture.'" title="'.$picture.'" alt="'.$picture.'" /></a>';
				$thumb_link =   '<a href="'.$additional_picture_url.'zoom/'.$picture.'" class="'.$zoomclass2.'" '.$rel.'><img src="'.$additional_picture_url.'thumbs/'.$picture.'" title="'.$picture.'" alt="'.$picture.'" /></a>';
			} else {
				$picture_link = '<img src="'.$additional_picture_url.$picture.'" title="'.$picture.'" alt="'.$picture.'" />';
				$thumb_link = '<img src="'.$additional_picture_url.'thumbs/'.$picture.'" title="'.$picture.'" alt="'.$picture.'" />';
			}
			$vars = array('[PICTURE]','{PICTURE}','{THUMB}');
			$values = array($picture, $picture_link, $thumb_link);			
			$additionaloutput .= str_replace($vars, $values, $setting_additionalpics_string);			
		}
		if ($counter > 0) {$additional_pictures = '<div class="additional_pictures">'.$additionaloutput.'</div>';}
	}
}

?>
