<?php
/**
 *
 * @category        admin
 * @package         admintools
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: thumb.php 5 2015-04-27 08:02:19Z luisehahne $
 * @filesource      $HeadURL: https://localhost:8443/svn/wb283Sp4/SP4/branches/wb/admin/media/thumb.php $
 * @lastmodified    $Date: 2015-04-27 10:02:19 +0200 (Mo, 27. Apr 2015) $
 *
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
