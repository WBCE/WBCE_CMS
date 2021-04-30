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

require_once __DIR__.'/functions.inc.php';

//get and remove all php files created for the news_img section
$query_details = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `section_id` = '$section_id'");
if($query_details->numRows() > 0) {
    while($query_result = $query_details->fetchRow()) {
        if(is_writable(WB_PATH.PAGES_DIRECTORY.$query_result['link'].PAGE_EXTENSION)) {
            unlink(WB_PATH.PAGES_DIRECTORY.$query_result['link'].PAGE_EXTENSION);
        }
                
        //get and remove all images created by posts in section
        $query_img = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_img` WHERE `post_id` = ".$query_result['post_id']);
        if($query_img->numRows() > 0) {
            while($result = $query_img->fetchRow()) {
                if(is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/'.$result['picname'])) 
                    unlink(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/'.$result['picname']);
                if(is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/'.$result['picname'])) 
                    unlink(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/'.$result['picname']);
                $database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_img` WHERE `post_id` = ".$query_result['post_id']);
            }
        }
        if($query_result['image']!='' && is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/'.$query_result['image']))
            unlink(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/'.$query_result['image']);
	if(is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/index.php'))
            unlink(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/index.php');
	if(is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/'))
            rmdir(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/thumb/');
        if($query_result['image']!='' && is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/'.$query_result['image']))
            unlink(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/'.$query_result['image']);
	if(is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/index.php'))
            unlink(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id'].'/index.php');
	if(is_writable(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id']))
            rmdir(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$query_result['post_id']);
        }
}
//check to see if any other sections are part of the news page, if only 1 news is there delete it
$query_details = $database->query("SELECT * FROM `".TABLE_PREFIX."sections` WHERE `page_id` = '$page_id'");
if($query_details->numRows() == 1) {
    $query_details2 = $database->query("SELECT * FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '$page_id'");
    $query_result2 = $query_details2->fetchRow();
    if(is_writable(WB_PATH.PAGES_DIRECTORY.$query_result2['link'].PAGE_EXTENSION)) {
        unlink(WB_PATH.PAGES_DIRECTORY.$query_result2['link'].PAGE_EXTENSION);
    }
}

$database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `section_id` = '$section_id'");
$database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `section_id` = '$section_id'");
$database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");

// TODO: remove tags