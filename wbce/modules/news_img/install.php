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
    // DDL lives in install_struct.sql and goes through importSql() rather
    // than raw query() — it normalises MySQL-only syntax (AUTO_INCREMENT,
    // inline UNIQUE/KEY/INDEX, ENGINE=, UNSIGNED, ...) for SQLite the same
    // way the core installer's SQL files are handled. Default
    // preserveExisting=true is fine here — these statements are all
    // CREATE TABLE IF NOT EXISTS with no DROP TABLE to skip.
    foreach ($database->importSql(__DIR__ . '/install_struct.sql') as $r) {
        if (!$r['ok']) {
            echo "<h2>DB error creating " . h($r['statement']) . ": " . h($r['msg']) . "</h2>";
        }
    }

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
            // workaround for problem with global $module_directory overwritten
            // by functions.inc.php here
            $orig_module_dir = $module_directory;
             include WB_PATH.'/modules/droplets/functions.inc.php';
            make_dir(WB_PATH.'/temp/unzip');
            wbce_unpack_and_import(WB_PATH.'/modules/news_img/droplets/droplet_fetchNewsItems.zip', WB_PATH . '/temp/unzip/');
            rm_full_dir(WB_PATH.'/temp/unzip');
            $module_directory = $orig_module_dir;
        } catch ( \Exception $e ) {}
    } else {
        CAT_Helper_Droplet::installDroplet(WB_PATH.'/modules/news_img/droplets/droplet_fetchNewsItems.zip');
    }
    
};
