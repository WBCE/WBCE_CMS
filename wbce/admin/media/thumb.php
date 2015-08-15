<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * More Baking. Less Struggling.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require('../../config.php');
include_once('resize_img.php');
require_once(WB_PATH.'/framework/functions.php');

if (isset($_GET['img']) && isset($_GET['t'])) {
	$image = addslashes($_GET['img']);

	// Check to see if it contains ..
	if (!check_media_path($image)) {
		$admin->print_error($MESSAGE['MEDIA_DIR_DOT_DOT_SLASH'], WB_URL, false);
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
