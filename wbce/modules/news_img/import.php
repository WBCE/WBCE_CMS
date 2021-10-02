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

// Get id
if (!isset($_POST['source_id']) or !isset($_POST['section_id']) or !isset($_POST['page_id'])) {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
}

require_once __DIR__.'/functions.inc.php';

$mod_nwi_file_base=$mod_nwi_file_dir;

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = false;
// Include WB admin wrapper script
require WB_PATH.'/modules/admin.php';
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error(
        $MESSAGE['GENERIC_SECURITY_ACCESS']
     .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php'
    );
    $admin->print_footer();
    exit();
} else {
    if(!defined('CAT_PATH')) {
        $admin->print_header();
    }
}

$section_id = intval($_POST['section_id']);
$page_id = intval($_POST['page_id']);
$source_id = intval($_POST['source_id']);

// find out which type we have to import
$query_module = $database->query("SELECT `module` FROM `".TABLE_PREFIX."sections` WHERE `section_id` = '$source_id'");
$module_type = "";
if ($query_module->numRows()==1) {
    $module_result = $query_module->fetchRow();
    $module_type = $module_result['module'];
}

// handle topics names
$topics_name = "topics";
$query_tables = $database->query("SHOW TABLES");
while ($table_info = $query_tables->fetchRow()) {
    $table_name = $table_info[0];
    $topics_name=preg_replace('/'.TABLE_PREFIX.'mod_/', '', $table_name);
    $res = $database->query("SHOW COLUMNS FROM `$table_name` LIKE 'topic_id'");
    if (($res->numRows() > 0) && ($module_type == $topics_name)) {
        $module_type = "topics";
        break;
    }
}

$query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");
$original_settings = $query_settings->fetchRow();
foreach ($original_settings as $k => $v) {
    if ($v==null) {
        $original_settings[$k]='';
    }
}


if ($module_type == "news_img") {

// =========================================== News with images ======================================

    $query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$source_id'");
    $fetch_settings = $query_settings->fetchRow();

    // import settings
    $database->query("UPDATE `".TABLE_PREFIX."mod_news_img_settings` SET ".
        "`header` = '".mod_nwi_escapeString($fetch_settings['header'])."', ".
        "`post_loop` = '".mod_nwi_escapeString($fetch_settings['post_loop'])."', ".
        "`view_order` = '".$fetch_settings['view_order']."', ".
        "`footer` = '".mod_nwi_escapeString($fetch_settings['footer'])."', ".
        "`block2` = '".mod_nwi_escapeString($fetch_settings['block2'])."', ".
        "`posts_per_page` = '".$fetch_settings['posts_per_page']."', ".
        "`post_header` = '".mod_nwi_escapeString($fetch_settings['post_header'])."', ".
        "`post_content` = '".mod_nwi_escapeString($fetch_settings['post_content'])."', ".
        "`image_loop` = '".mod_nwi_escapeString($fetch_settings['image_loop'])."', ".
        "`post_footer` = '".mod_nwi_escapeString($fetch_settings['post_footer'])."', ".
        "`resize_preview` = '".mod_nwi_escapeString($fetch_settings['resize_preview'])."', ".
        "`crop_preview` = '".mod_nwi_escapeString($fetch_settings['crop_preview'])."', ".
        "`gallery` = '".mod_nwi_escapeString($fetch_settings['gallery'])."', ".
        "`imgmaxsize` = '".mod_nwi_escapeString($fetch_settings['imgmaxsize'])."', ".
        "`imgmaxwidth` = '".mod_nwi_escapeString($fetch_settings['imgmaxwidth'])."', ".
        "`imgmaxheight` = '".mod_nwi_escapeString($fetch_settings['imgmaxheight'])."', ".
        "`imgthumbsize` = '".mod_nwi_escapeString($fetch_settings['imgthumbsize'])."' ".
        "WHERE `section_id` = '$section_id'"
    );

    // Check if there is a db error
    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
        // Print admin footer
        $admin->print_footer();
        exit();
    }

    // import groups
    $database->query(sprintf(
        "INSERT INTO `%smod_news_img_groups` " .
        "(`section_id`,`active`,`position`,`title`) ".
        "SELECT '%d', `active`,`position`,`title` " .
        "FROM `%smod_news_img_groups` " .
        "WHERE `section_id` = '%d'",
        TABLE_PREFIX,$section_id,TABLE_PREFIX,$source_id
    ));

    // Check if there is a db error
    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
        // Print admin footer
        $admin->print_footer();
        exit();
    }

    // import tags
    $tags = mod_nwi_get_tags($source_id);
    if(is_array($tags) && count($tags))
    {
        foreach($tags as $tag)
        {
            if($tag['section_id']==0) { continue; } // do not copy global tags
            $tag_id = $tag['tag_id'];
            // no dups
            if(!mod_nwi_tag_exists($section_id,$tag['tag'])) {
                $database->query(sprintf(
                    "INSERT INTO `%smod_news_img_tags` " .
                    "(`tag`,`tag_color`) " .
                    "SELECT `tag`,`tag_color` " .
                    "FROM `%smod_news_img_tags` " .
                    "WHERE `tag_id`='%d'",
                    TABLE_PREFIX,TABLE_PREFIX,$tag_id
                ));
                $dup_tag_id = $database->get_one("SELECT LAST_INSERT_ID()");
            } else {
                $query_tags = $database->query(sprintf(
                    "SELECT * FROM `%smod_news_img_tags` AS t1 " .
                    "JOIN `%smod_news_img_tags_sections` AS t2 " .
                    "ON t1.tag_id=t2.tag_id ".
                    "WHERE t2.`section_id`=%d",
                    TABLE_PREFIX, TABLE_PREFIX, intval($section_id)
                ));
                if (!empty($query_tags) && $query_tags->numRows() > 0) {
                    $t = $query_tags->fetchRow();
                    $dup_tag_id = $t['tag_id'];
                }
            }
            $database->query(sprintf(
                "INSERT INTO `%smod_news_img_tags_sections` " .
                "(`section_id`,`tag_id`) " .
                "VALUES ('%d','%s');",
                TABLE_PREFIX,$section_id,$dup_tag_id
            ));
        }
    }

    $copied_tags = mod_nwi_get_tags($section_id);

    // Check if there is a db error
    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
        // Print admin footer
        $admin->print_footer();
        exit();
    }

    // Create new order object and reorder
    $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
    $order->clean($source_id);

    $query_posts = $database->query(sprintf(
        "SELECT * FROM `%smod_news_img_posts` " .
        "WHERE `section_id`='%s' ORDER BY `post_id`",
        TABLE_PREFIX,$source_id
    ));

    if ($query_posts->numRows() > 0)
    {
        $num_posts = $query_posts->numRows();
        while ($post = $query_posts->fetchRow())
        {
            // Get new order
            $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
            $position = $order->get_new($section_id);

            // Insert new row into database
            $sql = sprintf(
                "INSERT INTO `%smod_news_img_posts` " .
                "(`section_id`,`group_id`,`position`,`link`,`content_short`,`content_long`,`content_block2`,`active`) " .
                "VALUES ('$section_id','0','$position','','','','','0')",
                TABLE_PREFIX
            );
            $database->query($sql);

            // get new post ID
            $post_id = $database->get_one("SELECT LAST_INSERT_ID()");

            // set dir names
            $mod_nwi_file_dir = "$mod_nwi_file_base/$post_id/";
            $mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";

            // read original post
            $query_content = $database->query(sprintf(
                "SELECT * FROM `%smod_news_img_posts` " .
                "WHERE `post_id` = '".$post['post_id']."'",
                TABLE_PREFIX
            ));
            $fetch_content = $query_content->fetchRow();

            $title = mod_nwi_escapeString($fetch_content['title']);
            $link = mod_nwi_escapeString($fetch_content['link']);
            $group_id = $fetch_content['group_id'];

            if ($group_id!=0) {
                // figure out new group
                $query_groups = $database->query(sprintf(
                    "SELECT `title` FROM `%smod_news_img_groups` " .
                    "WHERE `section_id` = '$source_id' " .
                    "AND `group_id` = '$group_id' " .
                    "ORDER BY `position` ASC",
                    TABLE_PREFIX
                ));
                if ($query_groups->numRows()==0) {
                    $group_id=0;
                } else {
                    $group_result=$query_groups->fetchRow();
                    $group_title=mod_nwi_escapeString($group_result['title']);
                    $query_groups = $database->query(sprintf(
                        "SELECT `group_id` FROM `%smod_news_img_groups` " .
                        "WHERE `section_id` = '$section_id' " .
                        "AND `title` = '$group_title' " .
                        "ORDER BY `group_id` DESC",
                        TABLE_PREFIX
                    ));
                    if ($query_groups->numRows()==0) {
                        $group_id=0;
                    } else {
                        $group_result=$query_groups->fetchRow();
                        $group_id = $group_result['group_id'];
                    }
                }
            }
            $posted_by = $fetch_content['posted_by'];
            $short = mod_nwi_escapeString($fetch_content['content_short']);
            $long = mod_nwi_escapeString($fetch_content['content_long']);
            $block2 = mod_nwi_escapeString($fetch_content['content_block2']);
            $image = mod_nwi_escapeString($fetch_content['image']);
            $active = $fetch_content['active'];
            $publishedwhen =  $fetch_content['published_when'];
            $publisheduntil =  $fetch_content['published_until'];

            // Get page link URL
            $query_page = $database->query(sprintf(
                "SELECT `level`,`link` " .
                "FROM `%spages` " .
                "WHERE `page_id` = '$page_id'",
                TABLE_PREFIX
            ));
            $page = $query_page->fetchRow();
            $page_level = $page['level'];
            $page_link = $page['link'];

            // get old link
            $old_link = $link;

            // new link
            $post_link = '/posts/'.page_filename(preg_replace('/^\/?posts\/?/s', '', preg_replace('/-[0-9]*$/s', '', $link, 1)));
            // make sure to have the post_id as suffix; this will make the link unique (hopefully...)
            if (substr_compare($post_link, $post_id, -(strlen($post_id)), strlen($post_id))!=0) {
                $post_link .= PAGE_SPACER.$post_id;
            }

            // Make sure the post link is set and exists
            // Make news post access files dir
            make_dir(WB_PATH.PAGES_DIRECTORY.'/posts/');
            $file_create_time = '';
            if (!is_writable(WB_PATH.PAGES_DIRECTORY.'/posts/')) {
                $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
            } else {
                // Specify the filename
                $filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
                mod_nwi_create_file($filename, $file_create_time, null, null);
            }


            if (!is_dir($mod_nwi_file_dir)) {
                mod_nwi_img_makedir($mod_nwi_file_dir);
            }
            mod_nwi_img_copy(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$post['post_id'], $mod_nwi_file_dir);

            // Update row
            $database->query(
                "UPDATE `".TABLE_PREFIX."mod_news_img_posts` "
                . "SET `section_id` = '$section_id', "
                . "`group_id` = '$group_id', "
                . "`title` = '$title', "
                . "`link` = '$post_link', "
                . "`content_short` = '$short', "
                . "`content_long` = '$long', "
                . "`content_block2` = '$block2', "
                . "`image` = '$image', "
                . "`active` = '$active', "
                . "`published_when` = '$publishedwhen', "
                . "`published_until` = '$publisheduntil', "
                . "`posted_when` = '".time()."', "
                . "`posted_by` = '".$posted_by."' "
                . "WHERE `post_id` = '$post_id'"
            );

            // Check if there is a db error
            if ($database->is_error()) {
                $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
                // Print admin footer
                $admin->print_footer();
                exit();
            }

            // update tags
            $assigned_tags = mod_nwi_get_tags_for_post($post['post_id']);

            if(is_array($assigned_tags) && count($assigned_tags)>0) {
                foreach($assigned_tags as $id => $t) {
                    foreach($copied_tags as $cid => $ct) {
                        if($ct['tag']==$t['tag']) {
                            $database->query(sprintf(
                                "INSERT IGNORE INTO `%smod_news_img_tags_posts` ".
                                "(`post_id`,`tag_id`) VALUES ".
                                "(%d,%d) ",
                                TABLE_PREFIX, intval($post_id), intval($ct['tag_id'])
                            ));
                        }
                    }
                }
            }

            // update table images
            $database->query(sprintf(
                "INSERT INTO `%smod_news_img_img` " .
                "(`picname`, `picdesc`, `post_id`, `position`) " .
                "SELECT `picname`, `picdesc`, '".$post_id."', `position` " .
                "FROM `%smod_news_img_img` " .
                "WHERE `post_id` = '".$post['post_id']."'",
                TABLE_PREFIX,TABLE_PREFIX
            ));

            // Check if there is a db error
            if ($database->is_error()) {
                $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
                // Print admin footer
                $admin->print_footer();
                exit();
            }

        }
    }

    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    } else {
        $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
    
    // Print admin footer
    $admin->print_footer();
} elseif ($module_type == "news") {

// =========================================== Classic News ======================================

    $query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_settings` WHERE `section_id` = '$source_id'");
    $fetch_settings = $query_settings->fetchRow();


    // Update settings
    $database->query("UPDATE `".TABLE_PREFIX."mod_news_img_settings` SET ".
    "`header` = '".mod_nwi_escapeString($fetch_settings['header'])."', ".
    "`post_loop` = '".mod_nwi_escapeString($fetch_settings['post_loop'])."', ".
    "`view_order` = '".$original_settings['view_order']."', ".
    "`footer` = '".mod_nwi_escapeString($fetch_settings['footer'])."', ".
    "`block2` = '".mod_nwi_escapeString($original_settings['block2'])."', ".
    "`posts_per_page` = '".$fetch_settings['posts_per_page']."', ".
    "`post_header` = '".mod_nwi_escapeString($fetch_settings['post_header'])."', ".
    "`post_content` = '".mod_nwi_escapeString($original_settings['post_content'])."', ".
    "`image_loop` = '".mod_nwi_escapeString($original_settings['image_loop'])."', ".
    "`post_footer` = '".mod_nwi_escapeString($fetch_settings['post_footer'])."', ".
    "`resize_preview` = '".mod_nwi_escapeString($original_settings['resize_preview'])."', ".
    "`crop_preview` = '".mod_nwi_escapeString($original_settings['crop_preview'])."', ".
    "`gallery` = '".mod_nwi_escapeString($original_settings['gallery'])."', ".
    "`imgmaxsize` = '".mod_nwi_escapeString($original_settings['imgmaxsize'])."', ".
    "`imgmaxwidth` = '".mod_nwi_escapeString($original_settings['imgmaxwidth'])."', ".
    "`imgmaxheight` = '".mod_nwi_escapeString($original_settings['imgmaxheight'])."', ".
    "`imgthumbsize` = '".mod_nwi_escapeString($original_settings['imgthumbsize'])."' ".
    "WHERE `section_id` = '$section_id'");

    // Check if there is a db error, otherwise say successful
    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
        // Print admin footer
        $admin->print_footer();
        exit();
    }

    if (!($database->is_error())) {

        // Update row
        $database->query("INSERT INTO `".TABLE_PREFIX."mod_news_img_groups` (`section_id`,`active`,`position`,`title`) SELECT '".$section_id."', `active`,`position`,`title` FROM `".TABLE_PREFIX."mod_news_groups` WHERE `section_id` = '".$source_id."'");

        if (!($database->is_error())) {

            // Include the ordering class
            require_once(WB_PATH.'/framework/class.order.php');
            // Create new order object and reorder
            $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
            $order->clean($source_id);
    
            $query_posts = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_posts` WHERE `section_id` = '$source_id' ORDER BY `post_id`");
            if ($query_posts->numRows() > 0) {
                $num_posts = $query_posts->numRows();
                while ($post = $query_posts->fetchRow()) {
                    // Get new order
                    $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
                    $position = $order->get_new($section_id);

                    // Insert new row into database
                    $sql = "INSERT INTO `".TABLE_PREFIX."mod_news_img_posts` (`section_id`,`group_id`,`position`,`link`,`content_short`,`content_long`,`content_block2`,`active`) VALUES ('$section_id','0','$position','','','','','0')";
                    $database->query($sql);

                    $post_id = $database->get_one("SELECT LAST_INSERT_ID()");

                    $mod_nwi_file_dir = "$mod_nwi_file_base/$post_id/";
                    $mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";

                    $query_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_posts` WHERE `post_id` = '".$post['post_id']."'");
                    $fetch_content = $query_content->fetchRow();

                    $title = mod_nwi_escapeString($fetch_content['title']);
                    $link = mod_nwi_escapeString($fetch_content['link']);
                    $group_id = $fetch_content['group_id'];
                    if ($group_id!=0) {
                        // find out new group
                        $query_groups = $database->query("SELECT `title` FROM `".TABLE_PREFIX."mod_news_groups` WHERE `section_id` = '$source_id' AND `group_id` = '$group_id' ORDER BY `position` ASC");
                        if ($query_groups->numRows()==0) {
                            $group_id=0;
                        } else {
                            $group_result=$query_groups->fetchRow();
                            $group_title=$group_result['title'];
                            $query_groups = $database->query("SELECT `group_id` FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `section_id` = '$section_id' AND `title` = '$group_title' ORDER BY `group_id` DESC");
                            if ($query_groups->numRows()==0) {
                                $group_id=0;
                            } else {
                                $group_result=$query_groups->fetchRow();
                                $group_id = $group_result['group_id'];
                            }
                        }
                    }
                    $posted_by = $fetch_content['posted_by'];
                    $short = mod_nwi_escapeString($fetch_content['content_short']);
                    $long = mod_nwi_escapeString($fetch_content['content_long']);
                    $block2 = '';
                    $image = '';
                    $active = $fetch_content['active'];
                    $publishedwhen =  $fetch_content['published_when'];
                    $publisheduntil =  $fetch_content['published_until'];

                    // Get page link URL
                    $query_page = $database->query("SELECT `level`,`link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '$page_id'");
                    $page = $query_page->fetchRow();
                    $page_level = $page['level'];
                    $page_link = $page['link'];

                    // get old link
                    $old_link = $link;

                    // new link
                    $post_link = '/posts/'.page_filename(preg_replace('/^\/?posts\/?/s', '', preg_replace('/-[0-9]*$/s', '', $link, 1)));
                    // make sure to have the post_id as suffix; this will make the link unique (hopefully...)
                    if (substr_compare($post_link, $post_id, -(strlen($post_id)), strlen($post_id))!=0) {
                        $post_link .= PAGE_SPACER.$post_id;
                    }

                    // Make sure the post link is set and exists
                    // Make news post access files dir
                    make_dir(WB_PATH.PAGES_DIRECTORY.'/posts/');
                    $file_create_time = '';
                    if (!is_writable(WB_PATH.PAGES_DIRECTORY.'/posts/')) {
                        $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
                    } else {
                        // Specify the filename
                        $filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
                        mod_nwi_create_file($filename, $file_create_time, null, null);
                    }

                    if (!is_dir($mod_nwi_file_dir)) {
                        mod_nwi_img_makedir($mod_nwi_file_dir);
                    }
                    if (!is_dir($mod_nwi_thumb_dir)) {
                        mod_nwi_img_makedir($mod_nwi_thumb_dir);
                    }
                    $mod_nwi_file_dir = "$mod_nwi_file_base/";
                    $mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";
                    if (!is_dir($mod_nwi_file_dir)) {
                        mod_nwi_img_makedir($mod_nwi_file_dir);
                    }
                    if (!is_dir($mod_nwi_thumb_dir)) {
                        mod_nwi_img_makedir($mod_nwi_thumb_dir);
                    }

                    mod_nwi_img_copy(
                        WB_PATH.MEDIA_DIRECTORY.'/.news/image'.$fetch_content['group_id'].'.jpg',
                        $mod_nwi_file_dir.'/image'.$group_id.'.jpg'
                    );
                    mod_nwi_img_copy(
                        WB_PATH.MEDIA_DIRECTORY.'/.news/thumb'.$fetch_content['group_id'].'.jpg',
                        $mod_nwi_thumb_dir.'/image'.$group_id.'.jpg'
                    );
        
                    // Update row
                    $database->query(
                        "UPDATE `".TABLE_PREFIX."mod_news_img_posts` "
                        . "SET `section_id` = '$section_id', "
                        . "`group_id` = '$group_id', "
                        . "`title` = '$title', "
                        . "`link` = '$post_link', "
                        . "`content_short` = '$short', "
                        . "`content_long` = '$long', "
                        . "`content_block2` = '$block2', "
                        . "`image` = '$image', "
                        . "`active` = '$active', "
                        . "`published_when` = '$publishedwhen', "
                        . "`published_until` = '$publisheduntil', "
                        . "`posted_when` = '".time()."', "
                        . "`posted_by` = '".$posted_by."' "
                        . "WHERE `post_id` = '$post_id'"
                    );
                }
            }
        }
    }

    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    } else {
        $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
    
    // Print admin footer
    $admin->print_footer();

} elseif ($module_type == "topics") {

    // ==================================================== topics ===============================================

    $query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$topics_name."_settings` WHERE `section_id` = '$source_id'");
    $fetch_settings = $query_settings->fetchRow();

    $view_order = 0;
    if (($fetch_settings['sort_topics']==1)||($fetch_settings['sort_topics']==3)) {
        $view_order=$fetch_settings['sort_topics'];
    }

    // Placeholders
    $vars = array(
     '[ACTIVE]'                        =>  '',
     '[ADDITIONAL_PICTURES]'           =>  "",
     '[ALLCOMMENTSLIST]'               =>  '',
     '[CLASSES]'                       =>  '',
     '[COMMENTFRAME]'                  =>  '',
     '[COMMENT_ID]'                    =>  '',
     '[COMMENTSCLASS]'                 =>  '',
     '[COMMENTSCOUNT]'                 =>   '',
     '[CONTENT_EXTRA]'                 =>  '',
     '[CONTENT_LONG]'                  =>  '[CONTENT]',
     '[CONTENT_LONG_FIRST]'            =>  '[CONTENT]',
     '[COUNTER]'                       =>  '',
     '[COUNTER2]'                      =>  '',
     '[COUNTER3]'                      =>  '',
     '[EDITLINK]'                      =>  '',
     '[EVENT_START_DATE]'              =>  '',
     '[EVENT_START_DAY]'               =>  '',
     '[EVENT_START_DAYNAME]'           =>  '',
     '[EVENT_START_MONTH]'             =>  '',
     '[EVENT_START_MONTHNAME]'         =>  '',
     '[EVENT_START_TIME]'              =>  '',
     '[EVENT_START_YEAR]'              =>  '',
     '[EVENT_STOP_DATE]'               =>  '',
     '[EVENT_STOP_DAY]'                =>  '',
     '[EVENT_STOP_DAYNAME]'            =>  '',
     '[EVENT_STOP_MONTH]'              =>  '',
     '[EVENT_STOP_MONTHNAME]'          =>  '',
     '[EVENT_STOP_TIME]'               =>  '',
     '[EVENT_STOP_YEAR]'               =>  '',
     '{FULL_TOPICS_LIST}'              => "",
     '{JUMP_LINKS_LIST}'               => "",
     '[HREF]'                          =>  '',
     '[META_DESCRIPTION]'              => "",
     '[META_KEYWORDS]'                 => "",
     '{NAME}'                          => "",
     '[PICTURE]'                       => "[IMAGE]",
     '{PICTURE}'                       => "[IMAGE]",
     '{PREV_NEXT_PAGES}'               =>
'<table class="page-header" style="display: [DISPLAY_PREVIOUS_NEXT_LINKS]">
<tr>
<td class="page-left">[PREVIOUS_PAGE_LINK]</td>
<td class="page-center">[OF]</td>
<td class="page-right">[NEXT_PAGE_LINK]</td>
</tr>
</table>',
     '[PICTURE_DIR]'                   => "",
     '[PUBL_DATE]'                     => "[PUBLISHED_DATE]",
     '[PUBL_TIME]'                     => "[PUBLISHED_TIME]",
     '[SECTION_DESCRIPTION]'           => "",
     '[SECTION_ID]'                    => $section_id,
     '[SECTION_TITLE]'                 => "",
     '[READ_MORE]'                     =>
'<span style="visibility:[SHOW_READ_MORE];"><a href="[LINK]">[TEXT_READ_MORE]</a></span>',
     '{SEE_ALSO}'                      => "",
     '{SEE_PREVNEXT}'                  => "",
     '[SHORT_DESCRIPTION]'             => "",
     '{THUMB}'                         =>
'<div class="mod_nwi_teaserpic">
    <a href="[LINK]">[IMAGE]</a>
</div>
',
     '[THUMB]'                         =>
'<div class="mod_nwi_teaserpic">[IMAGE]</div>
',
     '{TITLE}'                         => '<a href="[LINK]">[TITLE]</a>',
     '[TOPIC_ID]'                      => "",
     '[TOPIC_EXTRA]'                   => '',
     '[TOPIC_SCORE]'                   => '',
     '[TOPIC_SHORT]'                   => "[SHORT]",
     '[TOTALNUM]'                      =>  '',
     '[USER_EMAIL]'                    => '[EMAIL]',
     '[USER_NAME]'                     => '[USERNAME]',
     '[USER_DISPLAY_NAME]'             => '[DISPLAY_NAME]',
     '[USER_MODIFIEDINFO]'             => '',
     '[XTRA1]'                         => '',
     '[XTRA2]'                         => '',
     '[XTRA3]'                         => '',
);
    
    $fetch_settings=str_replace(array_keys($vars), array_values($vars), $fetch_settings);

    // Update settings

    $database->query("UPDATE `".TABLE_PREFIX."mod_news_img_settings` SET ".
    "`header` = '".mod_nwi_escapeString($fetch_settings['header'])."', ".
    "`post_loop` = '".mod_nwi_escapeString($fetch_settings['topics_loop'])."', ".
    "`view_order` = '".$view_order."', ".
    "`footer` = '".mod_nwi_escapeString($fetch_settings['footer'])."', ".
    "`block2` = '".mod_nwi_escapeString($fetch_settings['topic_block2'])."', ".
    "`posts_per_page` = '".$fetch_settings['topics_per_page']."', ".
    "`post_header` = '".mod_nwi_escapeString($fetch_settings['topic_header'])."', ".
    "`post_content` = '".mod_nwi_escapeString($original_settings['post_content'])."', ".
    "`post_footer` = '".mod_nwi_escapeString($fetch_settings['topic_footer'])."', ".
    "`resize_preview` = '".mod_nwi_escapeString($original_settings['resize_preview'])."', ".
    "`crop_preview` = '".mod_nwi_escapeString($original_settings['crop_preview'])."', ".
    "`gallery` = '".mod_nwi_escapeString($original_settings['gallery'])."', ".
    "`imgmaxsize` = '".mod_nwi_escapeString($original_settings['imgmaxsize'])."', ".
    "`imgmaxwidth` = '".mod_nwi_escapeString($original_settings['imgmaxwidth'])."', ".
    "`imgmaxheight` = '".mod_nwi_escapeString($original_settings['imgmaxheight'])."', ".
    "`imgthumbsize` = '".mod_nwi_escapeString($original_settings['imgthumbsize'])."' ".
    "WHERE `section_id` = '$section_id'");


    // Check if there is a db error
    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
        // Print admin footer
        $admin->print_footer();
        exit();
    }

    // Create new order object and reorder
    $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
    $order->clean($source_id);
    $query_posts = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$topics_name."` WHERE `section_id` = $source_id ORDER BY `topic_id`");
    if ($query_posts->numRows() > 0) {
        $num_posts = $query_posts->numRows();
        while ($post = $query_posts->fetchRow()) {
            // Get new order
            $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
            $position = $order->get_new($section_id);

            // Insert new row into database
            $sql = "INSERT INTO `".TABLE_PREFIX."mod_news_img_posts` (`section_id`,`group_id`,`position`,`link`,`content_short`,`content_long`,`content_block2`,`active`) VALUES ('$section_id','0','$position','','','','','0')";
            $database->query($sql);

            $post_id = $database->get_one("SELECT LAST_INSERT_ID()");

            $mod_nwi_file_dir = "$mod_nwi_file_base/$post_id/";
            $mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";

            $fetch_content = $post;

            $title = $fetch_content['title'];
            $link = $fetch_content['link'];
            $group_id = 0;
            $posted_by = $fetch_content['posted_by'];
            $short = $fetch_content['content_short'];
            $long = $fetch_content['content_long'];
            $block2 = '';
            $image = $fetch_content['picture'];
            $active = ($fetch_content['active']>3)?1:0;
            $publishedwhen =  $fetch_content['published_when'];
            $publisheduntil = $fetch_content['published_until'];

            // Get page link URL
            $query_page = $database->query("SELECT `level`,`link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '$page_id'");
            $page = $query_page->fetchRow();
            $page_level = $page['level'];
            $page_link = $page['link'];

            // get old link
            $old_link = $link;

            // new link
            $post_link = '/posts/'.page_filename(preg_replace('/^\/?posts\/?/s', '', preg_replace('/-[0-9]*$/s', '', $link, 1)));
            // make sure to have the post_id as suffix; this will make the link unique (hopefully...)
            if (substr_compare($post_link, $post_id, -(strlen($post_id)), strlen($post_id))!=0) {
                $post_link .= PAGE_SPACER.$post_id;
            }

            // Make sure the post link is set and exists
            // Make news post access files dir
            make_dir(WB_PATH.PAGES_DIRECTORY.'/posts/');
            $file_create_time = '';
            if (!is_writable(WB_PATH.PAGES_DIRECTORY.'/posts/')) {
                $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
            } else {
                // Specify the filename
                $filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
                mod_nwi_create_file($filename, $file_create_time, null, null);
            }

            if (!is_dir($mod_nwi_file_dir)) {
                mod_nwi_img_makedir($mod_nwi_file_dir);
            }
            if (!is_dir($mod_nwi_thumb_dir)) {
                mod_nwi_img_makedir($mod_nwi_thumb_dir);
            }
            $mod_nwi_file_dir = "$mod_nwi_file_base/$post_id/";
            $mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";
            if (!is_dir($mod_nwi_file_dir)) {
                mod_nwi_img_makedir($mod_nwi_file_dir);
            }
            if (!is_dir($mod_nwi_thumb_dir)) {
                mod_nwi_img_makedir($mod_nwi_thumb_dir);
            }

            mod_nwi_img_copy(
                WB_PATH.$fetch_settings['picture_dir'].'/'.$fetch_content['picture'],
                $mod_nwi_file_dir.'/'.$fetch_content['picture']
            );
            mod_nwi_img_copy(
                WB_PATH.$fetch_settings['picture_dir'].'/thumbs/'.$fetch_content['picture'],
                $mod_nwi_thumb_dir.'/'.$fetch_content['picture']
            );
        
            // Update row
            $database->query(sprintf(
                "UPDATE `%smod_news_img_posts` SET "
                . "`section_id` = '$section_id', "
                 . "`group_id` = '$group_id', "
                 . "`title` = '".mod_nwi_escapeString($title)."', "
                 . "`link` = '".mod_nwi_escapeString($post_link)."', "
                 . "`content_short` = '".mod_nwi_escapeString($short)."', "
                 . "`content_long` = '".mod_nwi_escapeString($long)."', "
                 . "`content_block2` = '".mod_nwi_escapeString($block2)."', "
                 . "`image` = '".mod_nwi_escapeString($image)."', "
                 . "`active` = '$active', "
                 . "`published_when` = '$publishedwhen', "
                 . "`published_until` = '$publisheduntil', "
                 . "`posted_when` = '".time()."', "
                 . "`posted_by` = '".$posted_by."' "
                 . "WHERE `post_id` = '$post_id'",
                 TABLE_PREFIX
            ));
            $additional_picture_path = WB_PATH.$fetch_settings['picture_dir'].'/topic'.$fetch_content['topic_id'];
            if (is_dir($additional_picture_path)) {
                $order = new order(TABLE_PREFIX.'mod_news_img_img', 'position', 'post_id', 'section_id');
                $position = $order->get_new($section_id);

                $additional_picture_url = WB_URL.$settings_fetch['picture_dir'].'/topic'.TOPIC_ID.'/';

                $dir = $additional_picture_path . '/';
                $extensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp');

                $pictures=scandir($additional_picture_path);
                $directory = new DirectoryIterator($dir);
                foreach ($pictures as $currfile) {
                    $currfilepath=$dir.'/'.$currfile;
                    if (is_file($currfilepath)) {
                        $extension = strtolower(pathinfo($currfilepath, PATHINFO_EXTENSION));
                        if (in_array($extension, $extensions)) {
                            mod_nwi_img_copy(
                                WB_PATH.$fetch_settings['picture_dir'].'/'.$currfile,
                                $mod_nwi_file_dir.'/'.$currfile
                            );
                            mod_nwi_img_copy(
                                WB_PATH.$fetch_settings['picture_dir'].'/thumbs/'.$currfile,
                                $mod_nwi_thumb_dir.'/'.$currfile
                            );
                            // Get new order
                            $order = new order(TABLE_PREFIX.'mod_news_img_img', 'position', 'post_id', 'section_id');
                            $position = $order->get_new($section_id);
                            $database->query("INSERT INTO `".TABLE_PREFIX."mod_news_img_img` (`picname`, `picdesc`, `post_id`, `position`) VALUES ($currfile, '', '$post_id','$position'");
                        }
                    }
                }
            }
        }
    }

    if ($database->is_error()) {
        $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    } else {
        $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
    
    // Print admin footer
    $admin->print_footer();
} else {

    // =========================================== unsupported section type ======================================

    $admin->print_error("unsupported section type", ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    
    // Print admin footer
    $admin->print_footer();
}
