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

require '../../config.php';
include_once 'resize_img.php'; // ResizeImage Class

// Check if an image is specified
if (isset($_GET['img']) && isset($_GET['t'])) {
    $sImage = addslashes($_GET['img']);
    $iType = (int) $_GET['t'];
    
    // Ensure image is inside WBCE media folder
    if (!check_media_path($sImage)) {
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL, false);
        die;
    }
    
    // Create a thumbnail for the specified image
    $sImgPath = WB_PATH . MEDIA_DIRECTORY .$sImage;
    $oImgResizer = new ResizeImage($sImgPath);
    if ($oImgResizer->imgWidth) {
        if ($iType == 1) {
            $oImgResizer->resize_limitwh(50,50);
        } elseif ($iType == 2) {
            $oImgResizer->resize_limitwh(300,300);
        }
        $oImgResizer->close();
    } else {
        header ("Content-type: image/jpeg");
        readfile ( "nopreview.jpg" );
    }
}