<?php
/**
 *
 * @category        admin
 * @package         admintools
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: thumb.php 1400 2011-01-21 19:42:51Z FrankH $
 * @filesource		$HeadURL:  $
 * @lastmodified    $Date:  $
 *
 */

require('../../config.php');
include_once('resize_img.php');
require_once(WB_PATH.'/framework/functions.php');

if (isset($_GET['img']) && isset($_GET['t'])) {
	$image = addslashes($_GET['img']);

	// Check to see if it contains ..
	if (!check_media_path($image)) {
		$admin->print_error($MESSAGE['MEDIA']['DIR_DOT_DOT_SLASH'], WB_URL, false);
	}

	$type = addslashes($_GET['t']);
	$media = WB_PATH.MEDIA_DIRECTORY;
	$img=new RESIZEIMAGE($media.$image);
	if ($img->imgWidth) {
		if ($type == 1) {
			$img->resize_limitwh(50,50);
		} else if ($type == 2) {
			$img->resize_limitwh(200,200);
		} 
		$img->close();
	} else {
		header ("Content-type: image/jpeg");
		readfile ( "nopreview.jpg" );
	}
}
?>