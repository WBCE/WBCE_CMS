<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Create admin object
require('../../config.php');
$admin = new admin('Media', 'media', false);

// Include WBCE functions file (legacy for WBCE 1.1.x)
require_once WB_PATH . '/framework/functions.php';

// Include parameters
include 'parameters.php';

// include theme language file matching users language or default
if(file_exists(THEME_PATH .'/languages/'.LANGUAGE .'.php')) {
	require_once(THEME_PATH .'/languages/'.LANGUAGE .'.php');
} else {
	require_once(THEME_PATH .'/languages/EN.php');
}

// Byte convert for filesize
function byte_convert($bytes) {
	$symbol = array(' bytes', ' KB', ' MB', ' GB', ' TB');
	$exp = 0;
	$converted_value = 0;
	if( $bytes > 0 ) {
		$exp = floor( log($bytes)/log(1024) );
		$converted_value = ( $bytes/pow(1024,floor($exp)) );
	}
	return sprintf( '%.2f '.$symbol[$exp], $converted_value );
}

// Get file extension
function get_filetype($fname) {
	$pathinfo = pathinfo($fname);
	$extension = (isset($pathinfo['extension'])) ? strtolower($pathinfo['extension']) : '';
	return $extension;
}

// Get file extension for icons
function get_filetype_icon($fname) {
	$pathinfo = pathinfo($fname);
	$extension = (isset($pathinfo['extension'])) ? strtolower($pathinfo['extension']) : '';
	if (file_exists(THEME_PATH.'/images/files/'.$extension.'.png')) {
		return $extension;
	}
	return 'blank_16';
}

// Tooltip onmouseover
function ShowTip($name, $detail='') {
	$parts = explode(".", $name);
	$ext = strtolower(end($parts));
	if (strpos('.gif.jpg.jpeg.png.bmp.', $ext)) {
		return 'onmouseover="overlib(\'<img src=\\\''.$name.'\\\' maxwidth=\\\'200\\\' maxheight=\\\'200\\\'>\',VAUTO, WIDTH)" onmouseout="nd()" ';
	}
	return '';
}

// Human readable filesize
function fsize($size) {
	if($size == 0) return("0 Bytes");
	$filesizename = array(" bytes", " kB", " MB", " GB", " TB");
	return round($size/pow(1024, ($i = floor(log($size, 1024)))), 1) . $filesizename[$i];
}

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('media_browse.htt')));
$template->set_file('page', 'media_browse.htt');
$template->set_block('page', 'main_block', 'main');

// Get current dir (relative to media)
$currentHome = $admin->get_home_folder();
$directory = $admin->get_get('dir');
$directory = ($currentHome AND (!$directory)) ? $currentHome : $directory;
$directory = ($directory == '/' or $directory == '\\') ? '' : $directory;
$dirlink = 'browse.php?dir='.$directory;

// Ensure directory is inside WBCE media folder
if (!check_media_path($directory)) {
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], 'browse.php?dir=', false);
	die;
}

// Ensure directory exists
if(!file_exists(WB_PATH.MEDIA_DIRECTORY.$directory)) {
	$admin->print_error($MESSAGE['MEDIA_DIR_DOES_NOT_EXIST'], 'browse.php?dir=', false);
	die;
}

// Check to see if the user wanted to go up a directory into the parent folder
if($admin->get_get('up') == 1) {
	$parent_directory = dirname($directory);
	header("Location: browse.php?dir=$parent_directory");
	exit(0);
}

// Hide admin settings for non admins
if ($_SESSION['GROUP_ID'] != 1 && $pathsettings['global']['admin_only']) {
	$template->set_var('DISPLAY_SETTINGS', 'hide');
}

// Workout the parent dir link
$parent_dir_link = ADMIN_URL.'/media/browse.php?dir='.$directory.'&amp;up=1';

// Workout if the up arrow should be shown
if(($directory == '') or ($directory==$currentHome)) {
	$display_up_arrow = 'hide';
} else {
	$display_up_arrow = '';
}

// Insert values
$template->set_var(array(
					'THEME_URL' => THEME_URL,
					'CURRENT_DIR' => $directory,
					'PARENT_DIR_LINK' => $parent_dir_link,
					'DISPLAY_UP_ARROW' => $display_up_arrow,
					'INCLUDE_PATH' => WB_URL.'/include'
				)
			);

// Get home folder not to show
$home_folders = get_home_folders();

// Generate list
$template->set_block('main_block', 'list_block', 'list');

// Check for potentially malicious files
$forbidden_file_types  = preg_replace( '/\s*[,;\|#]\s*/','|',RENAME_FILES_ON_UPLOAD);

if($handle = opendir(WB_PATH.MEDIA_DIRECTORY.'/'.$directory)) {
	// Loop through the files and dirs an add to list
   while (false !== ($file = readdir($handle))) {
		$info = pathinfo($file);
		$ext = isset($info['extension']) ? $info['extension'] : '';
		if(substr($file, 0, 1) != '.' AND $file != '.svn' AND $file != 'index.php') {
			if( !preg_match('/'.$forbidden_file_types.'$/i', $ext) ) {
				if(is_dir(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$file)) {
					if(!isset($home_folders[$directory.'/'.$file])) {
						$DIR[] = $file;
					}
				} else {
					$FILE[] = $file;
				}
			}
		}
	}
	// Now parse these values to the template
	$temp_id = 0;
	$row_bg_color = 'FFF';
	if(isset($DIR)) {
		sort($DIR);
		foreach($DIR AS $name) {
			$link_name = str_replace(' ', '%20', $name);
			$temp_id++;
			$template->set_var(array(
								'NAME' => $name,
								'NAME_SLASHED' => addslashes($name),
								'TEMP_ID' => $admin->getIDKEY($temp_id),
								'LINK' => "browse.php?dir=$directory/$link_name",
								'LINK_TARGET' => '_self',
								'ROW_BG_COLOR' => $row_bg_color,
								'FT_ICON' => THEME_URL.'/images/folder_16.png',
								'FILETYPE_ICON' => THEME_URL.'/images/folder_16.png',
								'MOUSEOVER' => '',
								'IMAGEDETAIL' => '',
								'SIZE' => '',
								'DATE' => '',
								'PREVIEW' => '',
								'IMAGE_TITLE' => $name,
								'IMAGE_EXIST' => 'blank_16.gif'
							)
						);
			$template->parse('list', 'list_block', true);
			// Code to alternate row colors
			if($row_bg_color == 'FFF') {
				$row_bg_color = 'ECF1F3';
			} else {
				$row_bg_color = 'FFF';
			}
		}
	}
	if(isset($FILE)) {
		sort($FILE);
		$filepreview = array('jpg','gif','tif','tiff','png','txt','css','js','cfg','conf','pdf','zip','gz','doc');
		foreach($FILE AS $name) {
			$size = filesize('../../'.MEDIA_DIRECTORY.$directory.'/'.$name);
			$bytes = byte_convert($size);
			$fdate = filemtime('../../'.MEDIA_DIRECTORY.$directory.'/'.$name);
			$date = date(DATE_FORMAT.' '.TIME_FORMAT, $fdate);
			$filetypeicon = get_filetype_icon(WB_URL.MEDIA_DIRECTORY.$directory.'/'.$name);
			$filetype = get_filetype(WB_URL.MEDIA_DIRECTORY.$directory.'/'.$name);

			if (in_array($filetype, $filepreview)) {
				$preview = 'preview';
			} else {
				$preview = '';
			}
			$temp_id++;
			$imgdetail = '';
			// $icon = THEME_URL.'/images/blank_16.gif';
			$icon = '';
			$tooltip = '';

			if (!is_array($pathsettings)) {
				$pathsettings = array();
				$pathsettings['global']['show_thumbs'] = true;
			}
				if (!$pathsettings['global']['show_thumbs']) {
					$info = @getimagesize(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$name);
					if (!is_array($info)) {
						$info=array();
						$info[0]=false;
					}
					if ($info[0]) {
						$imgdetail = fsize(filesize(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$name)).'<br /> '.$info[0].' x '.$info[1].' px';
						$icon = 'thumb.php?t=1&amp;img='.$directory.'/'.$name;
						$tooltip = ShowTip('thumb.php?t=2&amp;img='.$directory.'/'.$name);
					} else {
						$imgdetail = fsize(filesize(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$name));
					}
				}
			

			$filetype_url = THEME_URL.'/images/files/'.$filetypeicon.'.png';
			$template->set_var(array(
								'NAME' => $name,
								'NAME_SLASHED' => addslashes($name),
								'TEMP_ID' => $admin->getIDKEY($temp_id),
								'LINK' => WB_URL.MEDIA_DIRECTORY.$directory.'/'.$name,
								'LINK_TARGET' => '_blank',
								'ROW_BG_COLOR' => $row_bg_color,
								'FT_ICON' => empty($icon) ? $filetype_url : $icon,
								'FILETYPE_ICON' => $filetype_url,
								'MOUSEOVER' => $tooltip,
								'IMAGEDETAIL' => $imgdetail,
								'SIZE' => $bytes,
								'DATE' => $date,
								'PREVIEW' => $preview,
								'IMAGE_TITLE' => $name,
								'IMAGE_EXIST' =>  'blank_16.gif'
							)
						);
			$template->parse('list', 'list_block', true);
			// Code to alternate row colors
			if($row_bg_color == 'FFF') {
				$row_bg_color = 'ECF1F3';
			} else {
				$row_bg_color = 'FFF';
			}
		}
	}
}

// If no files are in the media folder say so
if($temp_id == 0) {
	$template->set_var('DISPLAY_LIST_TABLE', 'hide');
} else {
	$template->set_var('DISPLAY_NONE_FOUND', 'hide');
}

// Insert permissions values
if($admin->get_permission('media_rename') != true) {
	$template->set_var('DISPLAY_RENAME', 'hide');
}
if($admin->get_permission('media_delete') != true) {
	$template->set_var('DISPLAY_DELETE', 'hide');
}

// Insert language text and messages
$template->set_var(array(
					'MEDIA_DIRECTORY' => MEDIA_DIRECTORY,
					'TEXT_CURRENT_FOLDER' => $TEXT['CURRENT_FOLDER'],
					'TEXT_RELOAD' => $TEXT['RELOAD'],
					'TEXT_RENAME' => $TEXT['RENAME'],
					'TEXT_DELETE' => $TEXT['DELETE'],
					'TEXT_SIZE' => $TEXT['SIZE'],
					'TEXT_DATE' => $TEXT['DATE'],
					'TEXT_NAME' => $TEXT['NAME'],
					'TEXT_TYPE' => $TEXT['TYPE'],
					'TEXT_UP' => $TEXT['UP'],
					'NONE_FOUND' => $MESSAGE['MEDIA_NONE_FOUND'],
					'CHANGE_SETTINGS' => $TEXT['MODIFY_SETTINGS'],
					'CONFIRM_DELETE' => $MESSAGE['MEDIA_CONFIRM_DELETE']
				)
			);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');
