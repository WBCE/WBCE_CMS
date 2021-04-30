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

if(defined('WB_URL'))
{
    $mod_news = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_news_img_posts` ( '
                     . '`post_id` INT NOT NULL AUTO_INCREMENT,'
                     . '`section_id` INT NOT NULL DEFAULT \'0\','
                     . '`group_id` INT NOT NULL DEFAULT \'0\','
                     . '`active` INT NOT NULL DEFAULT \'0\','
                     . '`position` INT NOT NULL DEFAULT \'0\','
                     . '`title` VARCHAR(255) NOT NULL DEFAULT \'\','
                     . '`link` TEXT NOT NULL ,'
                     . '`image` VARCHAR(256) NOT NULL DEFAULT \'\','
                     . '`content_short` TEXT NOT NULL ,'
                     . '`content_long` TEXT NOT NULL ,'
                     . '`content_block2` TEXT NOT NULL ,'
                     . '`published_when` INT NOT NULL DEFAULT \'0\','
                     . '`published_until` INT NOT NULL DEFAULT \'0\','
                     . '`posted_when` INT NOT NULL DEFAULT \'0\','
                     . '`posted_by` INT NOT NULL DEFAULT \'0\','
                     . 'PRIMARY KEY (post_id)'
                     . ' )';
    $database->query($mod_news);
    
    $mod_news = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_news_img_groups` ( '
                     . '`group_id` INT NOT NULL AUTO_INCREMENT,'
                     . '`section_id` INT NOT NULL DEFAULT \'0\','
                     . '`active` INT NOT NULL DEFAULT \'0\','
                     . '`position` INT NOT NULL DEFAULT \'0\','
                     . '`title` VARCHAR(255) NOT NULL DEFAULT \'\','
                     . 'PRIMARY KEY (group_id)'
                . ' )';
    $database->query($mod_news);

    $mod_news = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_news_img_settings` ( '
                     . '`section_id` INT NOT NULL DEFAULT \'0\','
                     . '`header` TEXT NOT NULL ,'
                     . '`post_loop` TEXT NOT NULL ,'
                     . '`view_order` INT NOT NULL DEFAULT \'0\','
                     . '`footer` TEXT NOT NULL ,'
                     . '`block2` TEXT NOT NULL ,'
                     . '`posts_per_page` INT NOT NULL DEFAULT \'0\','
                     . '`post_header` TEXT NOT NULL,'
                     . '`post_content` TEXT NOT NULL,'
                     . '`image_loop` TEXT NOT NULL,'
                     . '`post_footer` TEXT NOT NULL,'
                     . '`resize_preview` VARCHAR(50) NULL, '
                     . '`crop_preview` CHAR(1) NOT NULL DEFAULT \'N\', '
                     . '`gallery` TEXT NOT NULL,'
                     . '`imgthumbsize` VARCHAR(50) NULL DEFAULT NULL, '
                     . '`imgmaxwidth` VARCHAR(50) NULL DEFAULT NULL, '
                     . '`imgmaxheight` VARCHAR(50) NULL DEFAULT NULL, '
                     . '`imgmaxsize` VARCHAR(50) NULL DEFAULT NULL, '
                     . '`use_second_block` CHAR(1) NOT NULL DEFAULT \'N\', '
                     . '`view` VARCHAR(50) NOT NULL DEFAULT \'default\', '
	                 . '`mode` VARCHAR(50) NULL DEFAULT \'default\', '
                     . 'PRIMARY KEY (section_id)'
                . ' )';
    $database->query($mod_news);
        
    $mod_news = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_news_img_img` ( '
                 . '`id` INT NOT NULL AUTO_INCREMENT,'
                 . '`picname` VARCHAR(255) NOT NULL DEFAULT \'\','
                 . '`picdesc` VARCHAR(255) NOT NULL DEFAULT \'\','
                 . '`post_id` INT NOT NULL DEFAULT \'0\','
                 . '`position` INT(11) NOT NULL DEFAULT \'0\','
                 . 'PRIMARY KEY (id)'
                 . ' )';
    $database->query($mod_news);

    $database->query(sprintf("CREATE TABLE IF NOT EXISTS `%smod_news_img_tags` (
          `tag_id` int(11) NOT NULL AUTO_INCREMENT,
          `tag` varchar(255) NOT NULL,
          `tag_color` VARCHAR(7) NULL DEFAULT NULL,
          PRIMARY KEY (`tag_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        TABLE_PREFIX
    ));

    $database->query(sprintf("CREATE TABLE IF NOT EXISTS `%smod_news_img_tags_posts` (
          `post_id` int(11) NOT NULL,
          `tag_id` int(11) NOT NULL,
          UNIQUE KEY `post_id_tag_id` (`post_id`,`tag_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        TABLE_PREFIX
    ));

    $database->query(sprintf("CREATE TABLE IF NOT EXISTS `%smod_news_img_tags_sections` (
    	`section_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
    	`tag_id` INT(11) UNSIGNED NOT NULL,
    	UNIQUE INDEX `section_id_tag_id` (`section_id`, `tag_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        TABLE_PREFIX
    ));
        
    $mod_search = "SELECT * FROM `".TABLE_PREFIX."search` WHERE `value` = 'news_img'";
    $insert_search = $database->query($mod_search);
    if( $insert_search->numRows() == 0 )
    {
        // Insert info into the search table
        // Module query info
        $field_info = array();
        $field_info['page_id'] = 'page_id';
        $field_info['title'] = 'page_title';
        $field_info['link'] = 'link';
        $field_info['description'] = 'description';
        $field_info['modified_when'] = 'modified_when';
        $field_info['modified_by'] = 'modified_by';
        $field_info = serialize($field_info);
        $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'news_img', '$field_info')");
        // Query start
        $query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title, [TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by    FROM [TP]mod_news_img_posts, [TP]mod_news_img_groups, [TP]mod_news_img_settings, [TP]pages WHERE ";
        $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'news_img')");
        // Query body
        $query_body_code = "
        [TP]pages.page_id = [TP]mod_news_img_posts.page_id AND [TP]mod_news_img_posts.title LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_news_img_posts.page_id AND [TP]mod_news_img_posts.content_short LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_news_img_posts.page_id AND [TP]mod_news_img_posts.content_long LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_news_img_posts.page_id AND [TP]mod_news_img_posts.content_block2 LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_news_img_settings.page_id AND [TP]mod_news_img_settings.header LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_news_img_settings.page_id AND [TP]mod_news_img_settings.footer LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_news_img_settings.page_id AND [TP]mod_news_img_settings.post_header LIKE \'%[STRING]%\'
        OR [TP]pages.page_id = [TP]mod_news_img_settings.page_id AND [TP]mod_news_img_settings.post_footer LIKE \'%[STRING]%\'";
        $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'news_img')");
        // Query end
        $query_end_code = "";
        $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'news_img')");

        // Insert blank row (there needs to be at least on row for the search to work)
        $database->query("INSERT INTO `".TABLE_PREFIX."mod_news_img_posts` (`section_id`) VALUES ('0')");
        $database->query("INSERT INTO `".TABLE_PREFIX."mod_news_img_groups` (`section_id`) VALUES ('0')");
        $database->query("INSERT INTO `".TABLE_PREFIX."mod_news_img_settings` (`section_id`) VALUES ('0')");
    }

        // Make news post img files dir
    require_once(WB_PATH.'/framework/functions.php');
    if(make_dir(WB_PATH.MEDIA_DIRECTORY.'/.news_img')) {
        // Add a index.php file to prevent directory spoofing
        $content = ''.
"<?php

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

header('Location: ../');
?>";
        $handle = fopen(WB_PATH.MEDIA_DIRECTORY.'/.news_img/index.php', 'w');
        fwrite($handle, $content);
        fclose($handle);
        change_mode(WB_PATH.MEDIA_DIRECTORY.'/.news_img/index.php', 'file');
    }
        
        // Make news post img thumb files dir
    require_once(WB_PATH.'/framework/functions.php');
    if(make_dir(WB_PATH.MEDIA_DIRECTORY.'/.news_img/thumb')) {
        // Add a index.php file to prevent directory spoofing
        $content = ''.
"<?php

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

header('Location: ../');
?>";
        $handle = fopen(WB_PATH.MEDIA_DIRECTORY.'/.news_img/thumb/index.php', 'w');
        fwrite($handle, $content);
        fclose($handle);
        change_mode(WB_PATH.MEDIA_DIRECTORY.'/.news_img/thumb/index.php', 'file');
    }

    // install the droplet(s)
    if(!defined('CAT_PATH')) {
        try {
            include __DIR__.'/droplets.functions.php';
			$sDropletFile = __DIR__.'/droplets/getNewsItems.php';
			if(is_readable($sDropletFile)){
				if(importDropletFromFile($sDropletFile)){
					echo 'Droplet <b>getNewsItems</b> installed successfully.<br>';
				}
			}
        } catch ( \Exception $e ) {}
    } else {
        CAT_Helper_Droplet::installDroplet(WB_PATH.'/modules/news_img/droplets/droplet_getNewsItems.zip');
    }
    
};
