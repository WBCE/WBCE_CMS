<?php
/**
 *
 * @category        modules
 * @package         news
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: add_post.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/add_post.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');
// Get new order
$order = new order(TABLE_PREFIX.'mod_news_posts', 'position', 'post_id', 'section_id');
$position = $order->get_new($section_id);

// Get default commenting
$sql = 'SELECT `commenting` FROM `'.TABLE_PREFIX.'mod_news_settings` '
     . 'WHERE `section_id`='.(int)$section_id;
$commenting = $database->get_one($sql);
/*
$query_settings = $database->query("SELECT commenting FROM ".TABLE_PREFIX."mod_news_settings WHERE section_id = '$section_id'");
$fetch_settings = $query_settings->fetchRow();
$commenting = $fetch_settings['commenting'];

 Insert new row into database
$created_when = time();
$created_by = $admin->get_user_id();
*/
$sUrl = WB_URL.'/modules/news/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id=';
$sql = 'INSERT INTO `'.TABLE_PREFIX.'mod_news_posts` '
     . 'SET `section_id`='.$section_id.', '
     .     '`page_id`='.$page_id.', '
     .     '`position`='.$position.', '
     .     '`commenting`=\''.$commenting.'\', '
     .     '`active`=1, '
     .     '`title`=\'\', '
     .     '`link`=\'\', '
     .     '`content_short`=\'\', '
     .     '`content_long`=\'\', '
     .     '`created_when`='.time().', '
     .     '`created_by`='.$admin->get_user_id();
if (($database->query($sql))) {
    $post_id = $admin->getIDKEY($database->getLastInsertId());
    $admin->print_success($TEXT['SUCCESS'], $sUrl.$post_id);
} else {
    $post_id = $admin->getIDKEY(0);
    $admin->print_error($database->get_error(), $sUrl.$post_id);
}
$admin->print_footer();
