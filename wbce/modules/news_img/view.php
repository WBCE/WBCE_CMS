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
if (defined('WB_PATH') == false) {
    exit("Cannot access this file directly");
}

require_once __DIR__.'/functions.inc.php';
$usebrax = false;

// Nur temporaer, spaeter ueber Optionen
$variant = "default";

// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang);

//overwrite php.ini on Apache servers for valid SESSION ID Separator
if (function_exists('ini_set')) {
    ini_set('arg_separator.output', '&amp;');
}

// Check if there is a start point defined
if (isset($_GET['p']) and is_numeric($_GET['p']) and $_GET['p'] >= 0) {
    $position = $_GET['p'];
} else {
    $position = 0;
}

// Get user's username, display name, email, and id - needed for insertion into post info
$users = array();
$query_users = $database->query("SELECT `user_id`,`username`,`display_name`,`email` FROM `".TABLE_PREFIX."users`");
if ($query_users->numRows() > 0) {
    while (false != ($user = $query_users->fetchRow())) {
        // Insert user info into users array
        $user_id = $user['user_id'];
        $users[$user_id]['username'] = $user['username'];
        $users[$user_id]['display_name'] = $user['display_name'];
        $users[$user_id]['email'] = $user['email'];
    }
}
// Get groups (title, if they are active, and their image [if one has been uploaded])
if (isset($groups)) {
    unset($groups);
}

$groups[0]['title'] = '';
$groups[0]['active'] = true;
$groups[0]['image'] = '';

$query_users = $database->query("SELECT `group_id`,`title`,`active` FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `section_id` = '$section_id' ORDER BY `position` ASC");
if ($query_users->numRows() > 0) {
    while (false != ($group = $query_users->fetchRow())) {
        // Insert user info into users array
        $group_id = $group['group_id'];
        $groups[$group_id]['title'] = ($group['title']);
        $groups[$group_id]['active'] = $group['active'];
        if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/.news_img/image'.$group_id.'.jpg')) {
            $groups[$group_id]['image'] = WB_URL.MEDIA_DIRECTORY.'/.news_img/image'.$group_id.'.jpg';
        } else {
            $groups[$group_id]['image'] = '';
        }
    }
}

// Check if we should show the main page or a post itself
if (!defined('POST_ID') or !is_numeric(POST_ID)) {
    // -------------------------|   show main page    |-------------------------

     // Check if we should only list posts from a certain group
     if (isset($_GET['g']) and is_numeric($_GET['g'])) {
        $query_extra = " AND group_id = '".$_GET['g']."'";
     } else {
         $query_extra = '';
     }
 

    if (isset($_GET['m']) and is_numeric($_GET['m']) and isset($_GET['y']) and is_numeric($_GET['y']) and isset($_GET['method']) and is_numeric($_GET['method'])) {
        $startdate = mktime(0, 0, 0, $_GET['m'], 1, $_GET['y']);
        $enddate = mktime(0, 0, 0, $_GET['m']+1, 1, $_GET['y']);
        switch ($_GET['method']) {
        case 0:
            $date_option = "posted_when";
            break;
        case 1:
            $date_option = "published_when";
            break;
        }
        $query_extra .= " AND ".$date_option." >= '$startdate' AND ".$date_option." < '$enddate'";
    } 

    // Get settings
    $query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");
    if ($query_settings->numRows() > 0) {
        $fetch_settings = $query_settings->fetchRow();
        $setting_header = ($fetch_settings['header']);
        $setting_post_loop = ($fetch_settings['post_loop']);
        $setting_view_order = ($fetch_settings['view_order']);
        $setting_footer = ($fetch_settings['footer']);
        $setting_block2 = ($fetch_settings['block2']);
        $setting_posts_per_page = $fetch_settings['posts_per_page'];
    } else {
        $setting_header = '';
        $setting_post_loop = '';
        $setting_view_order = 0;
        $setting_footer = '';
        $setting_block2 = '';
        $setting_posts_per_page = '';
    }
    
    $order_by = "position";
    if ($setting_view_order==1) {
        $order_by = "published_when";
    }
    if ($setting_view_order==2) {
        $order_by = "published_until";
    }
    if ($setting_view_order==3) {
        $order_by = "posted_when";
    }
    if ($setting_view_order==4) {
        $order_by = "post_id";
    }

    $t = time();
    $group_id = 0;
    
    // Get total number of posts
    $query_total_num = $database->query("SELECT `post_id`, `section_id` FROM `".TABLE_PREFIX."mod_news_img_posts`
        WHERE `section_id` = '$section_id' AND `active` = '1' AND `title` != '' $query_extra
        AND (`published_when` = '0' OR `published_when` <= $t) AND (`published_until` = 0 OR `published_until` >= $t)");
    $total_num = $query_total_num->numRows();

    // Work-out if we need to add limit code to sql
    if ($setting_posts_per_page != 0) {
        $limit_sql = " LIMIT $position, $setting_posts_per_page";
    } else {
        $limit_sql = "";
    }
    
    // Query posts (for this page)
    $query_posts = $database->query("SELECT p.*,g.active,g.position FROM `".TABLE_PREFIX."mod_news_img_posts` AS p
        LEFT JOIN `".TABLE_PREFIX."mod_news_img_groups` AS g ON (g.group_id = p.group_id OR p.group_id = 0 AND g.group_id = NULL)
        WHERE p.section_id = '$section_id' AND (g.active = 1 OR p.group_id = 0) AND p.active = 1 AND p.title != ''"
	.str_replace(array('group_id','posted_when','published_when'),array('p.group_id','p.posted_when','p.published_when'),$query_extra)
        ."AND (p.published_when = 0 OR p.published_when <= $t) AND (p.published_until = 0 OR p.published_until >= $t)
        ORDER BY g.position,p.$order_by DESC".$limit_sql);
    $num_posts = $query_posts->numRows();
    // Create previous and next links
    if ($setting_posts_per_page != 0) {
        if ($position > 0) {
            if (isset($_GET['g']) and is_numeric($_GET['g'])) {
                $pl_prepend = '<a href="?p='.($position-$setting_posts_per_page).'&amp;g='.$_GET['g'].'">&lt;&lt; ';
            } else {
                $pl_prepend = '<a href="?p='.($position-$setting_posts_per_page).'">&lt;&lt; ';
            }
            $pl_append = '</a>';
            $previous_link = $pl_prepend.$TEXT['PREVIOUS'].$pl_append;
            $previous_page_link = $pl_prepend.$TEXT['PREVIOUS_PAGE'].$pl_append;
        } else {
            $previous_link = '';
            $previous_page_link = '';
        }
        if ($position + $setting_posts_per_page >= $total_num) {
            $next_link = '';
            $next_page_link = '';
        } else {
            if (isset($_GET['g']) and is_numeric($_GET['g'])) {
                $nl_prepend = '<a href="?p='.($position+$setting_posts_per_page).'&amp;g='.$_GET['g'].'"> ';
            } else {
                $nl_prepend = '<a href="?p='.($position+$setting_posts_per_page).'"> ';
            }
            $nl_append = ' &gt;&gt;</a>';
            $next_link = $nl_prepend.$TEXT['NEXT'].$nl_append;
            $next_page_link = $nl_prepend.$TEXT['NEXT_PAGE'].$nl_append;
        }
        if ($position+$setting_posts_per_page > $total_num) {
            $num_of = $position+$num_posts;
        } else {
            $num_of = $position+$setting_posts_per_page;
        }

        $out_of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OUT_OF']).' '.$total_num;
        $of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OF']).' '.$total_num;
        $display_previous_next_links = '';
    } else {
        $display_previous_next_links = 'none';
    }

    if ($num_posts === 0) {
        $setting_header = '';
        $setting_post_loop = '';
        $setting_footer = '';
        $setting_posts_per_page = '';
    }

    echo "<div class=\"mod_nwi_$variant\">";

    // echo header
    if ($display_previous_next_links == 'none') {
        echo  str_replace(
            array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]'),
            array('','','','','','', $display_previous_next_links),
            $setting_header
        );
    } else {
        echo str_replace(
            array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]'),
            array($next_page_link, $next_link, $previous_page_link, $previous_link, $out_of, $of, $display_previous_next_links),
            $setting_header
        );
    }
    if ($num_posts > 0) {
        if ($query_extra != '' && isset($_GET['g'])) {
            ?>
            <div class="selected-group-title">
                <?php echo '<a href="'.htmlspecialchars(strip_tags($_SERVER['PHP_SELF'])).'">'.PAGE_TITLE.'</a> &gt;&gt; '.$groups[$_GET['g']]['title']; ?>
            </div>
<?php
        }
        while (false != ($post = $query_posts->fetchRow())) {
            if (isset($groups[$post['group_id']]['active']) and $groups[$post['group_id']]['active'] != false) { // Make sure parent group is active
                $uid = $post['posted_by']; // User who last modified the post
                // Workout date and time of last modified post
                if ($post['published_when'] === '0') {
                    $post['published_when'] = time();
                }
                if ($post['published_when'] > $post['posted_when']) {
                    $post_date = gmdate(DATE_FORMAT, $post['published_when']+TIMEZONE);
                    $post_time = gmdate(TIME_FORMAT, $post['published_when']+TIMEZONE);
                } else {
                    $post_date = gmdate(DATE_FORMAT, $post['posted_when']+TIMEZONE);
                    $post_time = gmdate(TIME_FORMAT, $post['posted_when']+TIMEZONE);
                }

                $publ_date = gmdate(DATE_FORMAT, $post['published_when']+TIMEZONE);
                $publ_time = gmdate(TIME_FORMAT, $post['published_when']+TIMEZONE);

                // Work-out the post link
                $post_link = page_link($post['link']);
                $post_link_path = str_replace(WB_URL, WB_PATH, $post_link);
                if (file_exists($post_link_path)) {
                    $create_date = gmdate(DATE_FORMAT, filemtime($post_link_path)+TIMEZONE);
                    $create_time = gmdate(TIME_FORMAT, filemtime($post_link_path)+TIMEZONE);
                } else {
                    $create_date = $publ_date;
                    $create_time = $publ_time;
                }

                if (isset($_GET['p']) and $position > 0) {
                    $post_link .= '?p='.$position;
                }
                if (isset($_GET['g']) and is_numeric($_GET['g'])) {
                    if (isset($_GET['p']) and $position > 0) {
                        $post_link .= '&amp;';
                    } else {
                        $post_link .= '?';
                    }
                    {
                    $post_link .= 'g='.$_GET['g'];
                    }
                }

                // Get group id, title, and image
                $group_id      = $post['group_id'];
                $group_title   = $groups[$group_id]['title'];
                $group_image   = $groups[$group_id]['image'];
                $group_image_url = WB_URL."/modules/news_img/images/nopic.png";
                $display_image = ($group_image == '') ? "none" : "inherit";
                $display_group = ($group_id == 0) ? 'none' : 'inherit';

                if ($group_image != "") {
                    $group_image_url = $group_image;
                    $group_image = "<img src='".$group_image."' alt='".$group_title."' />";
                }

                // Replace [wblink--PAGE_ID--] with real link
                $short = ($post['content_short']);
                $wb->preprocess($short);
                
                if ($post['image'] != "") {
                    $post_img = "<img src='".WB_URL.MEDIA_DIRECTORY.'/.news_img/'.$post['post_id'].'/'.$post['image']."' alt='".$post['title']."' />";
                } else {
                    $post_img = "<img src='".WB_URL."/modules/news_img/images/nopic.png' alt='empty placeholder' />";
                }

                // anzahl der post images  - wichtig für link "weiterlesen"  SCHOW_READ_MORE
                $sql_result = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_news_img_img WHERE post_id = ".$post['post_id']);
                $anz_post_img = $sql_result->numRows();

                // Replace vars with values
                $post_long_len = strlen($post['content_long']);

                // Placeholders
                $vars = array(
                    '[PAGE_TITLE]',
                    '[GROUP_ID]',
                    '[GROUP_TITLE]',
                    '[GROUP_IMAGE]',
                    '[DISPLAY_GROUP]',
                    '[DISPLAY_IMAGE]',
                    '[TITLE]',
                    '[IMAGE]',
                    '[SHORT]',
                    '[LINK]',
                    '[MODI_DATE]',
                    '[MODI_TIME]',
                    '[CREATED_DATE]',
                    '[CREATED_TIME]',
                    '[PUBLISHED_DATE]',
                    '[PUBLISHED_TIME]',
                    '[USER_ID]',
                    '[USERNAME]',
                    '[DISPLAY_NAME]',
                    '[EMAIL]',
                    '[TEXT_READ_MORE]',
                    '[SHOW_READ_MORE]',
                    '[GROUP_IMAGE_URL]',
                );

                if (isset($users[$uid]['username']) and $users[$uid]['username'] != '') {
                    if (($post_long_len < 9) && ($anz_post_img < 1)) {
                        $values = array(PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'], $post_img, $short, '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;', $post_date, $post_time, $create_date, $create_time, $publ_date, $publ_time, $uid, $users[$uid]['username'], $users[$uid]['display_name'], $users[$uid]['email'], '', 'hidden',$group_image_url);
                    } else {
                        $values = array(PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'], $post_img, $short, $post_link, $post_date, $post_time, $create_date, $create_time, $publ_date, $publ_time, $uid, $users[$uid]['username'], $users[$uid]['display_name'], $users[$uid]['email'], $MOD_NEWS_IMG['TEXT_READ_MORE'], 'visible',$group_image_url);
                    }
                } else {
                    if (($post_long_len < 9) && ($anz_post_img < 1)) {
                        $values = array(PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'], $post_img, $short, '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;', $post_date, $post_time, $create_date, $create_time, $publ_date, $publ_time, '', '', '', '', '','hidden',$group_image_url);
                    } else {
                        $values = array(PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'], $post_img, $short, $post_link, $post_date, $post_time, $create_date, $create_time, $publ_date, $publ_time, '', '', '', '', $MOD_NEWS_IMG['TEXT_READ_MORE'],'visible',$group_image_url);
                    }
                }
                echo str_replace($vars, $values, $setting_post_loop);
            }
        }
    }
    // echo footer
    if ($display_previous_next_links == 'none') {
        echo  str_replace(array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]'), array('','','','','','', $display_previous_next_links), $setting_footer);
    } else {
        echo str_replace(array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]'), array($next_page_link, $next_link, $previous_page_link, $previous_link, $out_of, $of, $display_previous_next_links), $setting_footer);
    }

    echo "</div>";
} elseif (defined('POST_ID') and is_numeric(POST_ID)) {
    // -------------------------|   show post page    |-------------------------

    if (defined('POST_SECTION') and POST_SECTION == $section_id) {
        // Get settings
        $query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");
        if ($query_settings->numRows() > 0) {
            $fetch_settings = $query_settings->fetchRow();
            $setting_post_header = ($fetch_settings['post_header']);
            $setting_post_footer = ($fetch_settings['post_footer']);
            $setting_post_content = ($fetch_settings['post_content']);
            $setting_image_loop = ($fetch_settings['image_loop']);
            $setting_gallery = ($fetch_settings['gallery']);
            $setting_block2 = ($fetch_settings['block2']);
        } else {
            $setting_post_header = '';
            $setting_post_footer = '';
            $setting_image_loop = '<img src="[IMAGE]" alt="[DESCRIPTION]" />';
            $setting_gallery = '';
            $setting_block2 = '';
        }

        if (strlen($setting_gallery)) {
            include __DIR__.'/js/'.$setting_gallery.'/include.tpl';
        }

        // Get page info
        $query_page = $database->query("SELECT `link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '".PAGE_ID."'");
        if ($query_page->numRows() > 0) {
            $page = $query_page->fetchRow();
            $page_link = page_link($page['link']);
            if (isset($_GET['p']) and $position > 0) {
                $page_link .= '?p='.$_GET['p'];
            }
            if (isset($_GET['g']) and is_numeric($_GET['g'])) {
                if (isset($_GET['p']) and $position > 0) {
                    $page_link .= '&amp;';
                } else {
                    $page_link .= '?';
                }
                $page_link .= 'g='.$_GET['g'];
            }
        } else {
            exit($MESSAGE['PAGES']['NOT_FOUND']);
        }

        // Get post info
        $t = time();
        $query_post = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts`
            WHERE `post_id` = '".POST_ID."' AND `active` = '1'
            AND (`published_when` = '0' OR `published_when` <= $t) AND (`published_until` = 0 OR `published_until` >= $t)");

        if ($query_post->numRows() > 0) {
            $post = $query_post->fetchRow();
            if (isset($groups[$post['group_id']]['active']) and $groups[$post['group_id']]['active'] != false) { // Make sure parent group is active
                $uid = $post['posted_by']; // User who last modified the post
                // Workout date and time of last modified post
                if ($post['published_when'] === '0') {
                    $post['published_when'] = time();
                }
                if ($post['published_when'] > $post['posted_when']) {
                    $post_date = gmdate(DATE_FORMAT, $post['published_when']+TIMEZONE);
                    $post_time = gmdate(TIME_FORMAT, $post['published_when']+TIMEZONE);
                } else {
                    $post_date = gmdate(DATE_FORMAT, $post['posted_when']+TIMEZONE);
                    $post_time = gmdate(TIME_FORMAT, $post['posted_when']+TIMEZONE);
                }

                if ($post['image'] != "") {
                    $post_img = "<img src='".WB_URL.MEDIA_DIRECTORY.'/.news_img/'.$post['post_id'].'/'.$post['image']."' alt='".$post['title']."' />";
                } else {
                    $post_img = "";
                }


                $publ_date = gmdate(DATE_FORMAT, $post['published_when']+TIMEZONE);
                $publ_time = gmdate(TIME_FORMAT, $post['published_when']+TIMEZONE);

                // Work-out the post link
                $post_link = page_link($post['link']);

                $post_link_path = str_replace(WB_URL, WB_PATH, $post_link);
                if (file_exists($post_link_path)) {
                    $create_date = gmdate(DATE_FORMAT, filemtime($post_link_path)+TIMEZONE);
                    $create_time = gmdate(TIME_FORMAT, filemtime($post_link_path)+TIMEZONE);
                } else {
                    $create_date = $publ_date;
                    $create_time = $publ_time;
                }
                // Get group id, title, and image
                $group_id = $post['group_id'];
                $group_title = $groups[$group_id]['title'];
                $group_image = $groups[$group_id]['image'];
                $display_image = ($group_image == '') ? "none" : "inherit";
                $display_group = ($group_id == 0) ? 'none' : 'inherit';

                if ($group_image != "") {
                    $group_image= "<img src='".$group_image."' alt='".$group_title."' />";
                }

                $vars = array('[PAGE_TITLE]', '[GROUP_ID]', '[GROUP_TITLE]', '[GROUP_IMAGE]', '[DISPLAY_GROUP]', '[DISPLAY_IMAGE]', '[TITLE]', '[IMAGE]', '[SHORT]', '[BACK]', '[TEXT_BACK]', '[TEXT_LAST_CHANGED]', '[MODI_DATE]', '[TEXT_AT]', '[MODI_TIME]', '[CREATED_DATE]', '[CREATED_TIME]', '[PUBLISHED_DATE]', '[PUBLISHED_TIME]', '[TEXT_POSTED_BY]', '[TEXT_ON]', '[USER_ID]', '[USERNAME]', '[DISPLAY_NAME]', '[EMAIL]');
                $post_short=$post['content_short'];
                $wb->preprocess($post_short);
                if (isset($users[$uid]['username']) and $users[$uid]['username'] != '') {
                    $values = array(PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'], $post_img, $post_short, $page_link, $MOD_NEWS_IMG['TEXT_BACK'], $MOD_NEWS_IMG['TEXT_LAST_CHANGED'],$post_date, $MOD_NEWS_IMG['TEXT_AT'], $post_time, $create_date, $create_time, $publ_date, $publ_time, $MOD_NEWS_IMG['TEXT_POSTED_BY'], $MOD_NEWS_IMG['TEXT_ON'], $uid, $users[$uid]['username'], $users[$uid]['display_name'], $users[$uid]['email']);
                } else {
                    $values = array(PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'], $post_img, $post_short, $page_link, $MOD_NEWS_IMG['TEXT_BACK'], $MOD_NEWS_IMG['TEXT_LAST_CHANGED'], $post_date, $MOD_NEWS_IMG['TEXT_AT'], $post_time, $create_date, $create_time, $publ_date, $publ_time, $MOD_NEWS_IMG['TEXT_POSTED_BY'], $MOD_NEWS_IMG['TEXT_ON'], '', '', '', '');
                }

                $post_long = ($post['content_long']);
            }
        } else {
            $wb->print_error($MESSAGE['FRONTEND']['SORRY_NO_ACTIVE_SECTIONS'], "javascript: history.go(-1);", false);
            exit(0);
        }

        // echo post header
        echo str_replace($vars, $values, $setting_post_header);

        // post images
        // 2014-04-10 by BlackBird Webprogrammierung:
        //            added image sort order
        $sql_result = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_img` WHERE `post_id` = '".POST_ID."' ORDER BY `position`, `id` ASC");

        $images = array();
        if ($sql_result->numRows() > 0) {
            while ($row = $sql_result->fetchRow()) {
                $images[] = str_replace(array('[IMAGE]','[DESCRIPTION]'), array(WB_URL.MEDIA_DIRECTORY.'/.news_img/'.POST_ID.'/'.$row['picname'],$row['picdesc']), $setting_image_loop);
            }
        }

        // Replace [wblink--PAGE_ID--] with real link
        $wb->preprocess($post_long);
        // echo post
        echo str_replace(
            array('[IMAGES]','[CONTENT]'),
            array(implode("", $images),$post_short.$post_long),
            $setting_post_content
        );

        // echo post footer
        echo str_replace($vars, $values, $setting_post_footer);

        // Block2
	if(NWI_USE_SECOND_BLOCK){
            $post_block2 = ($post['content_block2']);
	    if(empty($post_block2) AND $setting_block2 != NULL) $post_block2 = $setting_block2;
	    $post_block2 = str_replace($vars, $values, $post_block2);
            define("NEWS_BLOCK2", $post_block2);
            define("TOPIC_BLOCK2", $post_block2); // re-use the constant from topics for backwards compatibility
            if (!defined("MODULES_BLOCK2")) {
        	define("MODULES_BLOCK2", $post_block2);
            }
	}
    }
}
