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
require(WB_PATH.'/modules/admin.php');
$section_key = $admin->checkIDKEY('section_key', 0, 'GET');
if (!$section_key || $section_key != $section_id){
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (IDKEY) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
}

// Include the ordering class
require WB_PATH.'/framework/class.order.php';
// Get new order
$order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
$position = $order->get_new($section_id);

// Insert new row into database
$sql = "INSERT INTO `".TABLE_PREFIX."mod_news_img_posts` (`section_id`,`page_id`,`position`,`link`,`content_short`,`content_long`,`content_block2`,`active`) VALUES ('$section_id','$page_id','$position','','','','','1')";
$database->query($sql);

// Say that a new record has been added, then redirect to modify page
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
    // Get the id
    $post_id = $database->get_one("SELECT LAST_INSERT_ID()");
    $post_id_key = $admin->getIDKEY($post_id);
    if(defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) 
       $post_id_key = $post_id;

    $admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='. $post_id_key);
}

// Print admin footer
$admin->print_footer();
