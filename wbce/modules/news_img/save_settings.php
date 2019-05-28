<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

require_once __DIR__.'/functions.inc.php';

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = FALSE;
require WB_PATH.'/modules/admin.php';
if (!$admin->checkFTAN()){
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
} else $admin->print_header();

$block2='';
// This code removes any <?php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');
$header = $database->escapeString(str_replace($friendly, $raw, $_POST['header']));
$post_loop = $database->escapeString(str_replace($friendly, $raw, $_POST['post_loop']));
$view_order = intval($_POST['view_order']);
$footer = $database->escapeString(str_replace($friendly, $raw, $_POST['footer']));
if(NWI_USE_SECOND_BLOCK){
    $block2 = $database->escapeString(str_replace($friendly, $raw, $_POST['block2']));
}
$post_header = $database->escapeString(str_replace($friendly, $raw, $_POST['post_header']));
$post_content = $database->escapeString(str_replace($friendly, $raw, $_POST['post_content']));
$image_loop = $database->escapeString(str_replace($friendly, $raw, $_POST['image_loop']));
$post_footer = $database->escapeString(str_replace($friendly, $raw, $_POST['post_footer']));
$posts_per_page = $database->escapeString($_POST['posts_per_page']);
$gallery = $database->escapeString($_POST['gallery']);
$gal_img_resize_width = $database->escapeString($_POST['gal_img_resize_width']);
$gal_img_resize_height = $database->escapeString($_POST['gal_img_resize_height']);
$gal_img_max_size = intval($_POST['gal_img_max_size'])*1024;

$resize_preview = '';
$crop = 'N';

$width = $_POST['resize_width'];
$height = $_POST['resize_height'];
$thumbwidth = $_POST['thumb_width'];
$thumbheight = $_POST['thumb_height'];
$thumbsize = "100x100"; // default

$crop = (isset($_POST['crop_preview']) ? $_POST['crop_preview'] : 'N');
if(is_numeric($width) && is_numeric($height)) {
    if($height>0 && $width>0) {
        $resize_preview = $width.'x'.$height;
    }
}
if(is_numeric($thumbwidth) && is_numeric($thumbheight)) {
    if($thumbheight>0 && $thumbwidth>0) {
        $thumbsize = $thumbwidth.'x'.$thumbheight;
    }
}
if($crop=='on') {
    $crop = 'Y';
} else {
    $crop = 'N';
}

if($posts_per_page=='') {
    $posts_per_page = 0; // unlimited
}

// if the gallery setting changed, load default settings
$query_content = $database->query("SELECT `gallery` FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");
$fetch_content = $query_content->fetchRow();
if($fetch_content['gallery'] != $gallery) {
    include WB_PATH.'/modules/news_img/js/'.$gallery.'/settings.php';
}

$gal_img_max_size = intval($gal_img_max_size);
$gal_img_resize_width = intval($gal_img_resize_width);
$gal_img_resize_height = intval($gal_img_resize_height);

// Update settings
$database->query(
    "UPDATE `".TABLE_PREFIX."mod_news_img_settings`"
    . " SET"
    . " `header` = '$header',"
    . " `post_loop` = '$post_loop',"
    . " `view_order` = '$view_order',"
    . " `footer` = '$footer',"
    . " `block2` = '$block2',"
    . " `posts_per_page` = '$posts_per_page',"
    . " `post_header` = '$post_header',"
    . " `post_content` = '$post_content',"
    . " `image_loop` = '$image_loop',"
    . " `post_footer` = '$post_footer',"
    . " `resize_preview` = '$resize_preview',"
    . " `crop_preview` = '$crop',"
    . " `gallery` = '$gallery',"
    . " `imgmaxsize`='$gal_img_max_size',"
    . " `imgmaxwidth`='$gal_img_resize_width',"
    . " `imgmaxheight`='$gal_img_resize_height',"
    . " `imgthumbsize`='$thumbsize'"
    . " WHERE `section_id` = '$section_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();
