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
$admin = new admin('Media', 'media_upload', false);

// Include WBCE functions file and PclZip class (legacy for WBCE 1.1.x)
require_once WB_PATH . '/framework/functions.php';
require_once WB_PATH . '/include/pclzip/pclzip.lib.php';

// Check FTAN
if (!$admin->checkFTAN()) {
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Include required files
include_once('resize_img.php');
include_once('parameters.php');

// Get target dir (relative to media)
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$target = (isset(${$requestMethod}['target'])) ? ${$requestMethod}['target'] : '';
$target = ($target == '/' or $target == '\\') ? '' : $target;
$dirlink = 'index.php?dir=' . $target;

// Ensure directory is inside WBCE media folder
if (!check_media_path($target, false)) {
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], 'index.php', false);
	die;
}

// Create absolute path of the new dir name
$directory = WB_PATH . $target . '/';
$resizepath = str_replace(array('/', ' '), '_', $target);

// Find out whether we should replace files or give an error
$overwrite = ($admin->get_post('overwrite') != '') ? true : false;

// Check for potentially malicious files
$forbidden_file_types = preg_replace( '/\s*[,;\|#]\s*/','|', RENAME_FILES_ON_UPLOAD);

// Loop through the files
$good_uploads = 0;
$sum_dirs = 0;
$sum_files = 0;
for($count = 1; $count <= 10; $count++) {
	// If file was upload to tmp
	if(isset($_FILES["file$count"]['name'])) {
		// Remove bad characters
		$filename = trim(media_filename($_FILES["file$count"]['name']),'.') ;
		$info = pathinfo($filename);
		$ext = isset($info['extension']) ? $info['extension'] : '';

		// Check if file extension is not in forbidden list
		if(($filename != '') && !preg_match("/" . $forbidden_file_types . "$/i", $ext)) {
			// Move to relative path (in media folder)
			if(file_exists($directory.$filename) AND $overwrite == true) {
				if(move_uploaded_file($_FILES["file$count"]['tmp_name'], $directory.$filename)) {
					$good_uploads++;
					$sum_files++;
					// Chmod the uploaded file
					change_mode($directory.$filename);
				}
			} elseif(!file_exists($directory.$filename)) {
				if(move_uploaded_file($_FILES["file$count"]['tmp_name'], $directory.$filename)) {
					$good_uploads++;
					$sum_files++;
					// Chmod the uploaded file
					change_mode($directory.$filename);
				}
			}

			if(file_exists($directory.$filename) && isset($pathsettings[$resizepath])) {
				if ($pathsettings[$resizepath]['width'] || $pathsettings[$resizepath]['height'] ) {
					$rimg=new RESIZEIMAGE($directory.$filename);
					$rimg->resize_limitwh($pathsettings[$resizepath]['width'],$pathsettings[$resizepath]['height'],$directory.$filename);
					$rimg->close();
				}
			}

			// store file name of first file for possible unzip action
			if ($count == 1) {
				$filename1 = $directory . $filename;
			}
		}
	}
}
/*
 * Callback function to skip files in black-list
 */
function pclzipCheckValidFile($p_event, &$p_header) {
	// Check for potentially malicious files
	global $forbidden_file_types;
	$info = pathinfo($p_header['filename']);
	$ext = isset($info['extension']) ? $info['extension'] : '';
	$dots = (substr($info['basename'], 0, 1) == '.') || (substr($info['basename'], -1, 1) == '.');
	// Check if we should skip the file from beeing extracted
	$skip_file = ($dots || preg_match('/' . $forbidden_file_types . '$/i', $ext));
	// Return 1 to extract the file, 0 to skip it
	return ($skip_file) ? 0 : 1;
}

// If the user chose to unzip the first file, unzip into the current folder
if (isset($_POST['unzip']) && isset($filename1) && file_exists($filename1) ) {
	// Required to unzip file.
	$archive = new PclZip($filename1);
	$list = $archive->extract(PCLZIP_OPT_PATH, $directory,PCLZIP_CB_PRE_EXTRACT, 'pclzipCheckValidFile');

	if($list == 0) {
		// error while trying to extract the archive (most likely wrong format)
		$admin->print_error('UNABLE TO UNZIP FILE' . $archive -> errorInfo(true));
	}
	$sum_files = 0;
	// rename executable files!
	foreach ($list as $key => $val) {
		if( ($val['folder'] ) && change_mode($val['filename']) ) {
			$sum_dirs++;
		} elseif( is_writable($val['filename']) && ($val['status'] == 'ok') && change_mode($val['filename']) )  {
			$sum_files++;
		}
	}
	if (isset($_POST['delzip'])) {
		unlink($filename1);
	}
	$dir = dirname($filename1);
	if(file_exists($dir)) {
		$array = createFolderProtectFile($dir);
	}
}
unset($list);

if($sum_files == 1) {
	$admin->print_success($sum_files.' '.$MESSAGE['MEDIA_SINGLE_UPLOADED'] );
} elseif($sum_files > 1) {
	$admin->print_success($sum_files.' '.$MESSAGE['MEDIA_UPLOADED'] );
} else {
	$admin->print_error($MESSAGE['MEDIA_NO_FILE_UPLOADED'] );
}

// Print admin
$admin->print_footer();
