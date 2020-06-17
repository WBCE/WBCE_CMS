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
    function nwi_create_new_post($filename, $filetime=NULL, $content )
    {
        global $page_id, $section_id, $post_id;
    	// The depth of the page directory in the directory hierarchy
    	// '/pages' is at depth 1
    	$pages_dir_depth = count(explode('/',PAGES_DIRECTORY))-1;
    	// Work-out how many ../'s we need to get to the index page
    	$index_location = '../';
    	for($i = 0; $i < $pages_dir_depth; $i++)
        {
    		$index_location .= '../';
    	}

    	// Write to the filename
    	$content .='
define("POST_SECTION", $section_id);
define("POST_ID", $post_id);
require("'.$index_location.'config.php");
require(WB_PATH."/index.php");
?>';
    	if($handle = fopen($filename, 'w+'))
        {
        	fwrite($handle, $content);
        	fclose($handle);
            if($filetime)
            {
                touch($filename, $filetime);
            }
        	change_mode($filename);
        }
    }   // end function create_new_post()

    // read files from /pages/posts/
    if( !function_exists('scandir') )
    {
        function scandir($directory, $sorting_order = 0)
        {
            $dh  = opendir($directory);
            while( false !== ($filename = readdir($dh)) )
            {
                $files[] = $filename;
            }
            if( $sorting_order == 0 )
            {
                sort($files);
            } else
            {
                rsort($files);
            }
            return($files);
        }
    }   // end function scandir()

    $target_dir = WB_PATH . PAGES_DIRECTORY.'/posts/';
    if(is_dir($target_dir)) {
    	$files = scandir($target_dir);
    	natcasesort($files);

    	// All files in /pages/posts/
    	foreach( $files as $file )
        {
            if( file_exists($target_dir.$file)
                AND ($file != '.')
                    AND ($file != '..')
                        AND ($file != 'index.php') )
            {
                clearstatcache();
                $timestamp = filemtime ( $target_dir.$file );
                $lines = file($target_dir.$file);
                $content = '';
                // read lines until first define
                foreach ($lines as $line_num => $line) {
                    if(strstr($line,'define'))
                    {
                      break;
                    }
                    $content .= $line;
                }
                nwi_create_new_post($target_dir.$file, $timestamp, $content);
            }
        }
    }

    // ----- update database ---------------------------------------------------

    // 2014-04-10 by BlackBird Webprogrammierung:
    //            image position
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_img` ADD `position` INT(11) NOT NULL DEFAULT \'0\' AFTER `post_id`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2018-11-28 by BlackBird Webprogrammierung:
    //            new image resize settings (leaving the old column untouched)
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_settings` ADD `resize_preview` VARCHAR(50) NULL AFTER `resize`, ADD `crop_preview` CHAR(1) NOT NULL DEFAULT \'N\' AFTER `resize_preview`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2019-04-12 by BlackBird Webprogrammierung:
    //            custom markup for post content and image loop
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `post_content` TEXT NOT NULL AFTER `post_header`, ADD COLUMN `image_loop` TEXT NOT NULL AFTER `post_content`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2019-04-12 by BlackBird Webprogrammierung:
    //            custom markup for post content and image loop
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `gallery` TEXT NOT NULL AFTER `use_captcha`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2019-04-12 by Martin Hecht:
    //            add second block
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_posts` ADD COLUMN `content_block2` TEXT NOT NULL AFTER `content_long`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2019-05-02 by Martin Hecht:
    //            add second block in settings
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `block2` TEXT NOT NULL AFTER `footer`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2019-04-13 by Martin Hecht:
    //            add view order
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `view_order` INT NOT NULL DEFAULT \'0\' AFTER `post_loop`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2019-04-18 Bianka Martinovic
    //            remove all commenting settings and table
    $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_news_img_comments`");

    try {
        $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_posts` DROP COLUMN `commenting`");
    } catch(\PDOException $e) {}

    try {
        $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_settings` DROP COLUMN `comments_page`, DROP COLUMN `comments_header`, DROP COLUMN `comments_loop`, DROP COLUMN `comments_footer`, DROP COLUMN `commenting`, DROP COLUMN `use_captcha`, DROP COLUMN `resize`");
    } catch(\PDOException $e) {}

    try {
        $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_settings` ADD COLUMN `imgthumbsize` VARCHAR(50) NULL DEFAULT NULL AFTER `gallery`, ADD COLUMN `imgmaxwidth` VARCHAR(50) NULL DEFAULT NULL AFTER `imgthumbsize`, ADD COLUMN `imgmaxheight` VARCHAR(50) NULL DEFAULT NULL AFTER `imgmaxwidth`, ADD COLUMN `imgmaxsize` VARCHAR(50) NULL DEFAULT NULL AFTER `imgmaxheight`");
    } catch(\PDOException $e) {}

    // 2019-04-18 Bianka Martinovic
    //            rename columns (from German to neutral) in mod_news_img_img table
    //            add permalink column to mod_news_img_posts table
    try {
        $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_img` CHANGE COLUMN `bildname` `picname` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`, CHANGE COLUMN `bildbeschreibung` `picdesc` VARCHAR(255) NOT NULL DEFAULT '' AFTER `picname`");
    } catch(\PDOException $e) {}

    // ----- v5.0 --------------------------------------------------------------

    try {
        $database->query(sprintf("ALTER TABLE `%smod_news_img_posts` DROP COLUMN `page_id`",TABLE_PREFIX));
    } catch(\PDOException $e) {}

    try {
        $database->query(sprintf("ALTER TABLE `%smod_news_img_groups` DROP COLUMN `page_id`",TABLE_PREFIX));
    } catch(\PDOException $e) {}

    try {
        $database->query(sprintf("ALTER TABLE `%smod_news_img_settings` DROP COLUMN `page_id`",TABLE_PREFIX));
    } catch(\PDOException $e) {}

    try {
        $database->query(sprintf("ALTER TABLE `%smod_news_img_settings` ADD COLUMN `use_second_block` CHAR(1) NOT NULL DEFAULT 'N' AFTER `imgmaxsize`",TABLE_PREFIX));
    } catch(\PDOException $e) {}

    try {
        $database->query(sprintf("ALTER TABLE `%smod_news_img_settings` ADD COLUMN `view` VARCHAR(50) NOT NULL DEFAULT 'default' AFTER `use_second_block`",TABLE_PREFIX));
    } catch(\PDOException $e) {}

    try {
        $database->query(sprintf("ALTER TABLE `%smod_news_img_settings` ADD COLUMN `mode` VARCHAR(50) NULL DEFAULT 'default' AFTER `view`", TABLE_PREFIX));
    } catch(\PDOException $e) {}

    // 2019-07-05 Bianka Martinovic
    //            add new table that links images to posts
    $database->query(sprintf(
        "CREATE TABLE IF NOT EXISTS `%smod_news_img_posts_img` (
          `post_id` int(11) NOT NULL,
          `pic_id` int(11) NOT NULL,
          `position` int(11) NOT NULL,
          UNIQUE KEY `post_id_pic_id` (`post_id`,`pic_id`),
          KEY `FK_%smod_news_img_posts_img_%smod_news_img_img` (`pic_id`),
          CONSTRAINT `FK_%smod_news_img_posts_img_%smod_news_img_img` FOREIGN KEY (`pic_id`) REFERENCES `%smod_news_img_img` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
          CONSTRAINT `FK_%smod_news_img_posts_img_%smod_news_img_posts` FOREIGN KEY (`post_id`) REFERENCES `%smod_news_img_posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX
    ));

    // 2019-07-05 Bianka Martinovic
    //            retrieve old data from img-table
    $q = $database->query(sprintf(
        'SELECT `id`, `post_id`, `position` FROM `%smod_news_img_img`',
        TABLE_PREFIX
    ));
    $old_data = array();
    if (!empty($q) && $q->numRows() > 0) {
        while($row = $q->fetchRow()) {
            $old_data[$row['id']] = $row;
        }
    }

    // 2019-07-05 Bianka Martinovic
    //            insert data into new table
    if(is_array($old_data) && count($old_data)>0) {
        foreach($old_data as $pic_id => $item) {
            $database->query(sprintf(
                "INSERT IGNORE INTO `%smod_news_img_posts_img` ".
                "( `post_id`, `pic_id`, `position` ) ".
                "VALUES( %d, %d, %d )",
                TABLE_PREFIX, intval($item['post_id']), intval($pic_id), intval($item['position'])
            ));
        }
    }

    // 2019-07-05 Bianka Martinovic
    //            remove (moved) columns from img-table
    try {
        $database->query(sprintf(
            'ALTER TABLE `%smod_news_img_img DROP COLUMN `post_id`, DROP COLUMN `position`',
            TABLE_PREFIX
        ));
    } catch(\PDOException $e) {}

    // 2019-07-05 Bianka Martinovic
    //            add database tables for tags
    $database->query(sprintf("CREATE TABLE IF NOT EXISTS `%smod_news_img_tags` (
          `tag_id` int(11) NOT NULL AUTO_INCREMENT,
          `tag` varchar(255) NOT NULL,
          `tag_color` VARCHAR(7) NULL DEFAULT NULL,
          PRIMARY KEY (`tag_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX
    ));

    $database->query(sprintf("CREATE TABLE IF NOT EXISTS `%smod_news_img_tags_posts` (
          `post_id` int(11) NOT NULL,
          `tag_id` int(11) NOT NULL,
          UNIQUE KEY `post_id_tag_id` (`post_id`,`tag_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        , TABLE_PREFIX
    ));

    $database->query(sprintf("CREATE TABLE IF NOT EXISTS `%smod_news_img_tags_sections` (
    	`section_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
    	`tag_id` INT(11) UNSIGNED NOT NULL,
    	UNIQUE INDEX `section_id_tag_id` (`section_id`, `tag_id`)
        ) ENGINE=InnoDB"
        , TABLE_PREFIX
    ));

    // 
    // 2019-10-14 Bianka Martinovic
    //            move post images
    $q = $database->query(sprintf(
        'SELECT `post_id`,`image` FROM `%smod_news_img_posts`',
        TABLE_PREFIX
    ));
    if (!empty($q) && $q->numRows() > 0) {
        while($row = $q->fetchRow()) {
            if(!empty($row['image'])) {
                $file = WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$row['post_id'].'/'.$row['image'];
                if(file_exists($file)) {
                    rename($file,WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$row['image']);
                }
            }
        }
    }

    // find second block setting in config.php and delete that file then
    if(file_exists(__DIR__.'/config.php')) {
        // 'grep' define('NWI_USE_SECOND_BLOCK',true);
        require __DIR__.'/config.php';
        if(defined('NWI_USE_SECOND_BLOCK') && NWI_USE_SECOND_BLOCK === true) {
            $database->query(sprintf(
                'UPDATE `%smod_news_img_settings` SET `use_second_block`="Y"',
                TABLE_PREFIX
            ));
        }
        @unlink(__DIR__.'/config.php');
    }


    // install the droplet(s)
    if(!defined('CAT_PATH')) {
        try {
        include WB_PATH.'/modules/droplets/functions.inc.php';
        make_dir(WB_PATH.'/temp/unzip');
        wbce_unpack_and_import(WB_PATH.'/modules/news_img/droplets/droplet_getNewsItems.zip', WB_PATH . '/temp/unzip/');
        rm_full_dir(WB_PATH.'/temp/unzip');
        } catch ( \Exception $e ) {}
    } else {
        CAT_Helper_Droplet::installDroplet(WB_PATH.'/modules/news_img/droplets/droplet_getNewsItems.zip');
    }

}