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

require('../../config.php');
include_once('resize_img.php');

// Include WBCE functions file (legacy for WBCE 1.1.x)
require_once WB_PATH . '/framework/functions.php';

// Check if an image is specified
if (isset($_GET['img']) && isset($_GET['t'])) {
	$image = addslashes($_GET['img']);
	$type = (int) $_GET['t'];

	// Ensure image is inside WBCE media folder
	if (!check_media_path($image)) {
		$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL, false);
		die;
	}

	// Create a thumbnail for the specified image
	$img_path = WB_PATH . MEDIA_DIRECTORY .$image;
	$img = new RESIZEIMAGE($img_path);
	if ($img->imgWidth) {
		if ($type == 1) {
			$img->resize_limitwh(50,50);
		} elseif ($type == 2) {
			$img->resize_limitwh(200,200);
		}
		$img->close();
	} else {
		header ("Content-type: image/jpeg");
		readfile ( "nopreview.jpg" );
	}
}
