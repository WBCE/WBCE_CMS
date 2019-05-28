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

if(defined('WB_URL')) {

    function create_new_post($filename, $filetime=NULL, $content )
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

            create_new_post($target_dir.$file, $timestamp, $content);
        }

    }

    // 2014-04-10 by BlackBird Webprogrammierung:
    //            image position
    $database->query(sprintf(
        'ALTER TABLE `%smod_news_img_img` ADD `position` INT(11) NOT NULL DEFAULT \'0\' AFTER `post_id`',
        TABLE_PREFIX
    ));

    // 2018-11-28 by BlackBird Webprogrammierung:
    //            new image resize settings (leaving the old column untouched)
    $database->query(sprintf(
        'ALTER TABLE `%smod_news_img_settings` ADD `resize_preview` VARCHAR(50) NULL AFTER `resize`, ADD `crop_preview` CHAR(1) NOT NULL DEFAULT \'N\' AFTER `resize_preview`',
        TABLE_PREFIX
    ));

    // 2019-04-12 by BlackBird Webprogrammierung:
    //            custom markup for post content and image loop
    $database->query(sprintf(
        'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `post_content` TEXT NOT NULL AFTER `post_header`, ADD COLUMN `image_loop` TEXT NOT NULL AFTER `post_content`',
        TABLE_PREFIX
    ));

    // 2019-04-12 by BlackBird Webprogrammierung:
    //            custom markup for post content and image loop
    $database->query(sprintf(
        'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `gallery` TEXT NOT NULL AFTER `use_captcha`',
        TABLE_PREFIX
    ));

    // 2019-04-12 by Martin Hecht:
    //            add second block
    $database->query(sprintf(
        'ALTER TABLE `%smod_news_img_posts` ADD COLUMN `content_block2` TEXT NOT NULL AFTER `content_long`',
        TABLE_PREFIX
    ));
    // 2019-05-02 by Martin Hecht:
    //            add second block in settings
    $database->query(sprintf(
        'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `block2` TEXT NOT NULL AFTER `footer`',
        TABLE_PREFIX
    ));
    // 2019-04-13 by Martin Hecht:
    //            add view order
    $database->query(sprintf(
        'ALTER TABLE `%smod_news_img_settings` ADD COLUMN `view_order` INT NOT NULL DEFAULT \'0\' AFTER `post_loop`',
        TABLE_PREFIX
    ));

    // 2019-04-18 Bianka Martinovic
    //            remove all commenting settings and table
    $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_news_img_comments`");
    $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_posts` DROP COLUMN `commenting`");
    $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_settings` DROP COLUMN `comments_page`, DROP COLUMN `comments_header`, DROP COLUMN `comments_loop`, DROP COLUMN `comments_footer`, DROP COLUMN `commenting`, DROP COLUMN `use_captcha`, DROP COLUMN `resize`");
    $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_settings` ADD COLUMN `imgthumbsize` VARCHAR(50) NULL DEFAULT NULL AFTER `gallery`, ADD COLUMN `imgmaxwidth` VARCHAR(50) NULL DEFAULT NULL AFTER `imgthumbsize`, ADD COLUMN `imgmaxheight` VARCHAR(50) NULL DEFAULT NULL AFTER `imgmaxwidth`, ADD COLUMN `imgmaxsize` VARCHAR(50) NULL DEFAULT NULL AFTER `imgmaxheight`");

    // 2019-04-18 Bianka Martinovic
    //            rename columns (from German to neutral) in mod_news_img_img table
    //            add permalink column to mod_news_img_posts table
    $database->query("ALTER TABLE `".TABLE_PREFIX."mod_news_img_img` CHANGE COLUMN `bildname` `picname` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`, CHANGE COLUMN `bildbeschreibung` `picdesc` VARCHAR(255) NOT NULL DEFAULT '' AFTER `picname`");

    // 2019-04-18 Bianka Martinovic
    //            image directory
    if(!is_dir(WB_PATH.MEDIA_DIRECTORY.'/.news_img')) {
        require_once WB_PATH.'/framework/functions.php';
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

header('Location: ../../');
?>";
            $handle = fopen(WB_PATH.MEDIA_DIRECTORY.'/.news_img/thumb/index.php', 'w');
            fwrite($handle, $content);
            fclose($handle);
            change_mode(WB_PATH.MEDIA_DIRECTORY.'/.news_img/thumb/index.php', 'file');
        }
    }


    // 2019-05-08 Martin Hecht
    //            config file
    $nwi_config_file=__DIR__.'/config.php';
    if(!file_exists($nwi_config_file)) {
        $content = ''.
"<?php

if(!defined('NWI_USE_SECOND_BLOCK')){
    define('NWI_USE_SECOND_BLOCK',true);
}
";
        $handle = fopen($nwi_config_file, 'w');
        fwrite($handle, $content);
        fclose($handle);
        change_mode($nwi_config_file, 'file');
    }


    // 2019-05-11 Martin Hecht
    //            move pictures to new location during update
    $old_file_dir = WB_PATH.PAGES_DIRECTORY.'/beitragsbilder/';
    $old_thumb_dir = WB_PATH.PAGES_DIRECTORY.'/beitragsbilder/thumb/';

require_once __DIR__.'/functions.inc.php';

    // make sure the folder exists
    if(!is_dir($mod_nwi_file_dir)) {
        mod_nwi_img_makedir($mod_nwi_file_dir);
    }

    $query_img = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_img`");
    if ($query_img->numRows() > 0) {
	while ($row = $query_img->fetchRow()) {
            $post_id=$row['post_id'];
	    $picname=$row['picname'];
	    $dest_img=$mod_nwi_file_dir.'/'.$picname;
	    $source_img=$old_file_dir.'/'.$picname;
            if(!file_exists($dest_img) && file_exists($source_img)){
	    	rename($source_img, $dest_img);
	    }
	    $dest_img=$mod_nwi_thumb_dir.'/'.$picname;
	    $source_img=$old_thumb_dir.'/'.$picname;
            if(!file_exists($dest_img) && file_exists($source_img)){
	    	rename($source_img, $dest_img);
	    }
	}
    }

    // Print admin footer
    $admin->print_footer();
}
