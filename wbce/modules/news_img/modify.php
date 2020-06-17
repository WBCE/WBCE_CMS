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
if (!defined('WB_PATH')) {
    exit("Cannot access this file directly");
}

require_once __DIR__.'/functions.inc.php';

// cleanup database (orphaned)
$database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `section_id` = '$section_id' and `title`=''");
$database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `section_id` = '$section_id' and `title`=''");

// overwrite php.ini on Apache servers for valid SESSION ID Separator
if (function_exists('ini_set')) {
    ini_set('arg_separator.output', '&amp;');
}
$section_key = $admin->getIDKEY($section_id);

// map order to lang string
$lang_map = array(
    0 => $TEXT['CUSTOM'],
    1 => $TEXT['PUBL_START_DATE'].', '.$MOD_NEWS_IMG['DESCENDING'],
    2 => $TEXT['PUBL_END_DATE'].', '.$MOD_NEWS_IMG['DESCENDING'],
    3 => $TEXT['SUBMITTED'].', '.$MOD_NEWS_IMG['DESCENDING'],
    4 => $TEXT['SUBMISSION_ID'].', '.$MOD_NEWS_IMG['DESCENDING']
);

$FTAN = $admin->getFTAN();

// Create new order object and reorder
$order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
$order->clean($section_id);

$posts =  mod_nwi_posts_getall($section_id, true, '');
$num_posts = count($posts);
$importable_sections = 0;
$num_groups = 0;
    
// if there are already some posts, list them
if (!is_array($posts) || count($posts)<1) {
    // count groups
    $query_groups = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `section_id` = '$section_id'");
    $num_groups = $query_groups->numRows();
    if ($num_groups == 0) {
        // news with images
        $query_nwi = $database->query(sprintf(
            "SELECT `section_id` FROM `%ssections`" .
            " WHERE `module` = 'news_img' AND `section_id` != '$section_id' ORDER BY `section_id` ASC",
            TABLE_PREFIX
        ));
        $importable_sections = $query_nwi->numRows();
        // classical news
        $query_news = $database->query(sprintf(
            "SELECT `section_id` FROM `%ssections`" .
            " WHERE `module` = 'news' ORDER BY `section_id` ASC",
            TABLE_PREFIX
        ));
        $importable_sections += $query_news->numRows();
        // topics
        $topics_names = array();
        $query_tables = $database->query("SHOW TABLES");
        while ($table_info = $query_tables->fetchRow(MYSQLI_BOTH)) {
            $table_name = $table_info[0];
            $topics_name=preg_replace('/'.TABLE_PREFIX.'mod_/', '', $table_name);
            $res = $database->query("SHOW COLUMNS FROM `$table_name` LIKE 'topic_id'");
            if (!empty($res) && $res->numRows() > 0) {
                $topics_names[] = $topics_name;
                $query_topics = $database->query(sprintf(
                    "SELECT `section_id` FROM `".TABLE_PREFIX."sections`" .
                    " WHERE `module` = '$topics_name' ORDER BY `section_id` ASC",
                    TABLE_PREFIX
                ));
                $importable_sections += $query_topics->numRows();
            }
        }
        
        $nwi_sections = array();
        $news_sections = array();
        $topics_sections = array();

        if ($query_nwi->numRows() > 0) {
            // Loop through possible sections
            while ($source = $query_nwi->fetchRow()) {
                $nwi_sections[] = $source;
            }
        }
        if ($query_news->numRows() > 0) {
            // Loop through possible sections
            while ($source = $query_news->fetchRow()) {
                $news_sections[] = $source;
            }
        }

        foreach ($topics_names as $topics_name) {
            $topics_sections[$topics_name] = array();
            $query_topics = $database->query(sprintf(
                "SELECT `section_id` FROM `%ssections`" .
                " WHERE `module` = '$topics_name' ORDER BY `section_id` ASC",
                TABLE_PREFIX
            ));
            if ($query_topics->numRows() > 0) {
                #echo '<option disabled value="0">[--- '.$topics_name.' ---]</option>';
                // Loop through possible sections
                while ($source = $query_topics->fetchRow()) {
                    #echo '<option value="'.$source['section_id'].'">'.$TEXT['SECTION'].' '.$source['section_id'].'</option>';
                    $topics_sections[$topics_name][] = $source;
                }
            }
        }
    }
}

// groups
$order = new order(TABLE_PREFIX.'mod_news_img_groups', 'position', 'group_id', 'section_id');
$order->clean($section_id);

// Loop through existing groups
$groups = mod_nwi_get_groups(intval($section_id));

// tab to activate
$curr_tab = 'p';
if (isset($_REQUEST['tab']) && in_array($_REQUEST['tab'], array('g','s'))) {
    $curr_tab = $_REQUEST['tab'];
}

// existing tags
$tags = mod_nwi_get_tags($section_id);

// settings
$settings = mod_nwi_settings_get($section_id);

include __DIR__.'/templates/default/modify.phtml';
