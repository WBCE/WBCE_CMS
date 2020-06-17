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

// Must include code to stop this file being access directly
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

$database->query("DELETE FROM `".TABLE_PREFIX."search` WHERE `name` = 'module' AND `value` = 'news_img'");
$database->query("DELETE FROM `".TABLE_PREFIX."search` WHERE `extra` = 'news_img'");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_tags_posts`");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_tags_sections`");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_tags`");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_posts`");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_groups`");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_settings`");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_posts_img`");
$database->query("DROP TABLE `".TABLE_PREFIX."mod_news_img_img`");

require_once WB_PATH.'/framework/functions.php';
//rm_full_dir(WB_PATH.PAGES_DIRECTORY.'/posts');
rm_full_dir(WB_PATH.MEDIA_DIRECTORY.'/.news_img');
