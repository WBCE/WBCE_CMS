<?php
/**
 *
 * @category        admin
 * @package         media
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: upload.php 1601 2012-02-07 22:48:27Z Luisehahne $
 * @filesource		$HeadURL:  $
 * @lastmodified    $Date:  $
 *
 */

// Print admin header
require('../../config.php');
include_once('resize_img.php');
include_once('parameters.php');

require_once(WB_PATH.'/framework/class.admin.php');
// require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');	// Required to unzip file.
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Media', 'media_upload', false);

if( !$admin->checkFTAN() )
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'] );
}
// After check print the header
$admin->print_header();

// Target location
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$target = (isset(${$requestMethod}['target'])) ? ${$requestMethod}['target'] : '';

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

$directory = ($target == '/') ?  '' : $target;
$dirlink = 'index.php?dir='.$directory;
$rootlink = 'index.php?dir=';

// Check to see if target contains ../
if (!check_media_path($target, false))
{
	$admin->print_error($MESSAGE['MEDIA']['TARGET_DOT_DOT_SLASH'] );
}

// Create relative path of the target location for the file
$relative = WB_PATH.$target.'/';
$resizepath = str_replace(array('/',' '),'_',$target);

// Find out whether we should replace files or give an error
$overwrite = ($admin->get_post('overwrite') != '') ? true : false;

// Get list of file types to which we're supposed to append 'txt'
$get_result=$database->query("SELECT value FROM ".TABLE_PREFIX."settings WHERE name='rename_files_on_upload' LIMIT 1");
$file_extension_string='';
if ($get_result->numRows()>0) {
	$fetch_result=$get_result->fetchRow();
	$file_extension_string=$fetch_result['value'];
}

$file_extensions=explode(",",$file_extension_string);
// get from settings and add to forbidden list
$forbidden_file_types  = preg_replace( '/\s*[,;\|#]\s*/','|',RENAME_FILES_ON_UPLOAD);
// Loop through the files
$good_uploads = 0;
$sum_dirs = 0;
$sum_files = 0;

for($count = 1; $count <= 10; $count++)
{
	// If file was upload to tmp
	if(isset($_FILES["file$count"]['name']))
	{
		// Remove bad characters
		$filename = trim(media_filename($_FILES["file$count"]['name']),'.') ;
		// Check if there is still a filename left
		// if($filename != '') {
		$info = pathinfo($filename);
		$ext = isset($info['extension']) ? $info['extension'] : '';

		if ( ($filename != '') && !preg_match("/" . $forbidden_file_types . "$/i", $ext) )
		{
			// Move to relative path (in media folder)
			if(file_exists($relative.$filename) AND $overwrite == true) {
				if(move_uploaded_file($_FILES["file$count"]['tmp_name'], $relative.$filename)) {
					$good_uploads++;
					$sum_files++;
					// Chmod the uploaded file
					change_mode($relative.$filename);
				}
			} elseif(!file_exists($relative.$filename)) {
				if(move_uploaded_file($_FILES["file$count"]['tmp_name'], $relative.$filename)) {
					$good_uploads++;
					$sum_files++;
					// Chmod the uploaded file
					change_mode($relative.$filename);
				}
			}

			if(file_exists($relative.$filename)) {
				if ($pathsettings[$resizepath]['width'] || $pathsettings[$resizepath]['height'] ) {
					$rimg=new RESIZEIMAGE($relative.$filename);
					$rimg->resize_limitwh($pathsettings[$resizepath]['width'],$pathsettings[$resizepath]['height'],$relative.$filename);
					$rimg->close();
				}
			}

			// store file name of first file for possible unzip action
			if ($count == 1) {
				$filename1 = $relative . $filename;
			}
		}
	}
}
/*
 * Callback function to skip files in black-list
 */
function pclzipCheckValidFile($p_event, &$p_header)
{
    //  return 1;
// Check for potentially malicious files
	$forbidden_file_types  = preg_replace( '/\s*[,;\|#]\s*/','|',RENAME_FILES_ON_UPLOAD);
	$info = pathinfo($p_header['filename']);
	$ext = isset($info['extension']) ? $info['extension'] : '';
	$dots = (substr($info['basename'], 0, 1) == '.') || (substr($info['basename'], -1, 1) == '.');
	if( !preg_match('/'.$forbidden_file_types.'$/i', $ext) && $dots != '.' )
	{	// ----- allowed file types are extracted
	  return 1;
	}else
	{	// ----- all other files are skiped
	  return 0;
	}
}
/* ********************************* */

// If the user chose to unzip the first file, unzip into the current folder
if (isset($_POST['unzip']) && isset($filename1) && file_exists($filename1) ) {
	// Required to unzip file.
	require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
	$archive = new PclZip($filename1);
	$list = $archive->extract(PCLZIP_OPT_PATH, $relative,PCLZIP_CB_PRE_EXTRACT, 'pclzipCheckValidFile');

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
	if (isset($_POST['delzip'])) { unlink($filename1); }
	$dir = dirname($filename1);
    if(file_exists($dir)) {
		$array = createFolderProtectFile($dir);
    }
}
unset($list);

if($sum_files == 1) {
	$admin->print_success($sum_files.' '.$MESSAGE['MEDIA']['SINGLE_UPLOADED'] );
} elseif($sum_files > 1) {
	$admin->print_success($sum_files.' '.$MESSAGE['MEDIA']['UPLOADED'] );
} else {
	$admin->print_error($MESSAGE['MEDIA_NO_FILE_UPLOADED'] );
}

// Print admin
$admin->print_footer();
