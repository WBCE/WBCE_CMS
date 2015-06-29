<?php
/**
 *
 * @category        modules
 * @package         modules_news
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: view.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/view.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if (! defined('WB_PATH')) { die('Cannot access this file directly'); }
/* -------------------------------------------------------- */
global $post_id, $post_section, $TEXT, $MESSAGE, $MOD_NEWS;
// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );
//overwrite php.ini on Apache servers for valid SESSION ID Separator
if (function_exists('ini_set')) {
    ini_set('arg_separator.output', '&amp;');
}
// Check if there is a start point defined
$position = (isset($_GET['p']) ? intval($_GET['p']) : 0);
// Get user's username, display name, email, and id - needed for insertion into post info
$users = array();
$sql = 'SELECT `user_id`,`username`,`display_name`,`email` FROM `'.TABLE_PREFIX.'users`';
if (($resUsers = $database->query($sql))) {
    while ($recUser = $resUsers->fetchRow()) {
        $users[$recUser['user_id']] = $recUser;
    }
}
// Get all groups (id, title, active, image)
$groups = array(
    0 => array(
        'group_id'  => 0,
        'title'     => '',
        'active'    => true,
        'image'     => ''
    )
);
$sql = 'SELECT `group_id`, `title`, `active` FROM `'.TABLE_PREFIX.'mod_news_groups` '
     . 'WHERE `section_id`='.(int)$section_id.' '
     . 'ORDER BY `position` ASC';
if (($query_users = $database->query($sql))) {
    while (($group = $query_users->fetchRow())) {
        // Insert user info into users array
        $groups[$group['group_id']] = $group;
        $sImageUrl = WB_PATH.MEDIA_DIRECTORY.'/.news/image'.$group['group_id'].'.jpg';
        $groups[$group['group_id']]['image'] = (is_readable($sImageUrl) ? $sImageUrl : '');
    }
}
// Check if we should show the main page or a post itself
// if(!defined('POST_ID') OR !is_numeric(POST_ID))
if (!isset($post_id) || !is_numeric($post_id)) {
    // Check if we should only list posts from a certain group
    if (isset($_GET['g']) AND is_numeric($_GET['g'])) {
        $query_extra = 'AND `group_id`='.(int)$_GET['g'].' ';
    } else {
        $query_extra = '';
    }
    // Get settings
    $setting_header = $setting_post_loop = $setting_footer = $setting_posts_per_page = '';
    $sql = 'SELECT `header`, `post_loop`, `footer`, `posts_per_page` '
         . 'FROM `'.TABLE_PREFIX.'mod_news_settings` '
         . 'WHERE `section_id`='.(int)$section_id;
    if (($resSettings = $database->query($sql))) {
        if (($recSettings = $resSettings->fetchRow(MYSQL_ASSOC))) {
            foreach ($recSettings as $key=>$val) {
                ${'setting_'.$key} = $val;
            }
        }
    }
    // Get total number of posts relatet to now
    $t = time();
    $sql = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'mod_news_posts` '
         . 'WHERE `section_id`='.(int)$section_id.' AND `active`=1 '
         .        'AND `title`!=\'\' '
         .        'AND (`published_when`=0 OR `published_when`<='.$t.') '
         .        'AND (`published_until`=0 OR `published_until`>='.$t.') '
         .        $query_extra;
    $total_num = intval($database->get_one($sql));
    // Work-out if we need to add limit code to sql
    if ($setting_posts_per_page != 0) {
        $limit_sql = ' LIMIT '.$position.', '.$setting_posts_per_page;
    } else {
        $limit_sql = '';
    }
    // Query posts (for this page)
    $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_news_posts` '
         . 'WHERE `section_id`='.$section_id.' '
         .        'AND `active`=1 '
         .        'AND `title`!=\'\' '
         .        'AND (`published_when`=0 OR `published_when`<='.$t.') '
         .        'AND (`published_until`=0 OR `published_until`>='.$t.') '
         .        $query_extra
         . 'ORDER BY `position` DESC'.$limit_sql;
    $query_posts = $database->query($sql);         
    $num_posts = $query_posts->numRows();
    // Create previous and next links
    if ($setting_posts_per_page != 0) {
        if ($position > 0) {
            if (isset($_GET['g']) AND is_numeric($_GET['g'])) {
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
            if (isset($_GET['g']) AND is_numeric($_GET['g'])) {
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
// Print header
    $aPlaceHolders = makePhExp(
        'DISPLAY_PREVIOUS_NEXT_LINKS',
        'NEXT_PAGE_LINK',
        'NEXT_LINK',
        'PREVIOUS_PAGE_LINK',
        'PREVIOUS_LINK',
        'OUT_OF',
        'OF'
    );
    if ($display_previous_next_links == 'none') {
        $aReplacements = array(
            $display_previous_next_links
        );
    } else {
        $aReplacements = array(
            $display_previous_next_links,
            $next_page_link,
            $next_link,
            $previous_page_link,
            $previous_link,
            $out_of,
            $of
        );
    }
    print (preg_replace($aPlaceHolders, $aReplacements, $setting_header));
    if ($num_posts > 0)
    {
        if ($query_extra != '') {
            echo ('<div class="selected-group-title">'
                 .'<a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME']))
                 .'">'.PAGE_TITLE.'</a> &gt;&gt; '.$groups[$_GET['g']]['title']
                 .'</div>'.PHP_EOL
            );
        }
        $aPlaceHolders = makePhExp(
            'PAGE_TITLE',
            'GROUP_ID',
            'GROUP_TITLE',
            'GROUP_IMAGE',
            'DISPLAY_GROUP',
            'DISPLAY_IMAGE',
            'TITLE',
            'SHORT',
            'MODI_DATE',
            'MODI_TIME',
            'CREATED_DATE',
            'CREATED_TIME',
            'PUBLISHED_DATE',
            'PUBLISHED_TIME',
            'LINK',
            'SHOW_READ_MORE',
            'TEXT_READ_MORE',
            'USER_ID',
            'USERNAME',
            'DISPLAY_NAME',
            'EMAIL'
        );
        while (($post = $query_posts->fetchRow()))
        {
            if (
                isset($groups[$post['group_id']]['active']) AND
                $groups[$post['group_id']]['active'] != false
            ) { // Make sure parent group is active
                $uid = $post['posted_by']; // User who last modified the post
                // Workout date and time of last modified post
                if ($post['published_when'] === '0') {
                    $post['published_when'] = time();
                }
                if ($post['published_when'] > $post['posted_when']) {
                    $post_date = date(DATE_FORMAT, $post['published_when']+TIMEZONE);
                    $post_time = date(TIME_FORMAT, $post['published_when']+TIMEZONE);
                } else {
                    $post_date = date(DATE_FORMAT, $post['posted_when']+TIMEZONE);
                    $post_time = date(TIME_FORMAT, $post['posted_when']+TIMEZONE);
                }
                $publ_date      = date(DATE_FORMAT,$post['published_when']);
                $publ_time      = date(TIME_FORMAT,$post['published_when']);
                // Work-out the post link
                $post_link      = page_link($post['link']);
                $post_link_path = str_replace(WB_URL, WB_PATH,$post_link);
                $create_date    = date(DATE_FORMAT, $post['created_when']);
                $create_time    = date(TIME_FORMAT, $post['created_when']);
                if (isset($_GET['p']) AND $position > 0) {
                    $post_link .= '?p='.$position;
                }
                if (isset($_GET['g']) AND is_numeric($_GET['g'])) {
                    if (isset($_GET['p']) AND $position > 0) {
                        $post_link .= '&amp;';
                    } else {
                        $post_link .= '?';
                    }
                    $post_link .= 'g='.$_GET['g'];
                }
                // Get group id, title, and image
                $group_id      = $post['group_id'];
                $group_title   = $groups[$group_id]['title'];
                $group_image   = $groups[$group_id]['image'];
                $display_image = ($group_image == '') ? "none" : "inherit";
                $display_group = ($group_id == 0) ? 'none' : 'inherit';

                if ($group_image != "") {
                    $group_image= "<img src='".$group_image."' alt='".$group_title."' />";
                }
                // Replace [wblink--PAGE_ID--] with real link
                $short = ($post['content_short']);
                // Replace vars with values
                $post_long_len = strlen($post['content_long']);
                // set replacements for exchange
                $aReplacements = array(
                    PAGE_TITLE,
                    $group_id,
                    $group_title,
                    $group_image,
                    $display_group,
                    $display_image,
                    $post['title'],
                    $short,
                    $post_date,
                    $post_time,
                    $create_date,
                    $create_time,
                    $publ_date,
                    $publ_time
                );
                if (isset($users[$uid]['username']) AND $users[$uid]['username'] != '') {
                    if ($post_long_len < 9) {
                        $aReplacements[] = '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;';
                        $aReplacements[] = 'hidden';
                        $aReplacements[] = '';
                        $aReplacements[] = $uid;
                        $aReplacements[] = $users[$uid]['username'];
                        $aReplacements[] = $users[$uid]['display_name'];
                        $aReplacements[] = $users[$uid]['email'];
                    } else {
                        $aReplacements[] = $post_link;
                        $aReplacements[] = 'visible';
                        $aReplacements[] = $MOD_NEWS['TEXT_READ_MORE'];
                        $aReplacements[] = $uid;
                        $aReplacements[] = $users[$uid]['username'];
                        $aReplacements[] = $users[$uid]['display_name'];
                        $aReplacements[] = $users[$uid]['email'];
                    }
                } else {
                    if ($post_long_len < 9) {
                        $aReplacements[] = '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;';
                        $aReplacements[] = 'hidden';
                    } else {
                        $aReplacements[] = $post_link;
                        $aReplacements[] = 'visible';
                        $aReplacements[] = $MOD_NEWS['TEXT_READ_MORE'];
                    }
                }
                print (preg_replace($aPlaceHolders, $aReplacements, $setting_post_loop));
            }
        }
    }
    // Print footer
    $aPlaceHolders = makePhExp(
        'DISPLAY_PREVIOUS_NEXT_LINKS',
        'NEXT_PAGE_LINK',
        'NEXT_LINK',
        'PREVIOUS_PAGE_LINK',
        'PREVIOUS_LINK',
        'OUT_OF',
        'OF'
    );
    if ($display_previous_next_links == 'none') {
        $aReplacements = array(
            $display_previous_next_links
        );
    } else {
        $aReplacements = array(
            $display_previous_next_links,
            $next_page_link,
            $next_link,
            $previous_page_link,
            $previous_link,
            $out_of,
            $of
        );
    }
    print (preg_replace($aPlaceHolders, $aReplacements, $setting_footer));

} elseif(isset($post_id) && is_numeric($post_id)) {
    if (isset($post_section) && ($post_section == $section_id)) {
        // Get settings
        $setting_post_header = $setting_post_footer = $setting_comments_header
                             = $setting_comments_loop = $setting_comments_footer = '';
        $sql = 'SELECT `post_header`, `post_footer`, `comments_header`, `comments_loop`, `comments_footer` '
             . 'FROM `'.TABLE_PREFIX.'mod_news_settings` '
             . 'WHERE `section_id`='.(int)$section_id;
        if (($resSettings = $database->query($sql)) ) {
            if (($recSettings = $resSettings->fetchRow())) {
                foreach ($recSettings as $key=>$val) {
                    ${'setting_'.$key} = $val;
                }
            }
        }
        // Get page info
        $sql = 'SELECT `link` FROM `'.TABLE_PREFIX.'pages` '
             . 'WHERE `page_id`='.PAGE_ID;
        $query_page = $database->query($sql);
        if ($query_page->numRows() > 0) {
            $page = $query_page->fetchRow();
            $page_link = page_link($page['link']);
            if (isset($_GET['p']) AND $position > 0) {
                $page_link .= '?p='.$_GET['p'];
            }
            if (isset($_GET['g']) AND is_numeric($_GET['g'])) {
                if (isset($_GET['p']) AND $position > 0) {
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
        $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_news_posts` '
             . 'WHERE `post_id`='.$post_id.' AND active=1 '
             .        'AND (`published_when`=0 OR `published_when`<='.$t.') '
             .        'AND (`published_until`=0 OR `published_until`>='.$t.')';
        $query_post = $database->query($sql);
        if ($post = $query_post->fetchRow()) {
            if (isset($groups[$post['group_id']]['active']) 
                AND $groups[$post['group_id']]['active'] != false
            ) { // Make sure parent group is active
                $uid = $post['posted_by']; // User who last modified the post
                // Workout date and time of last modified post
                if ($post['published_when'] === '0') {
                    $post['published_when'] = time();
                }
                if ($post['published_when'] > $post['posted_when']) {
                    $post_date = date(DATE_FORMAT, $post['published_when']+TIMEZONE);
                    $post_time = date(TIME_FORMAT, $post['published_when']+TIMEZONE);
                } else {
                    $post_date = date(DATE_FORMAT, $post['posted_when']+TIMEZONE);
                    $post_time = date(TIME_FORMAT, $post['posted_when']+TIMEZONE);
                }
                $publ_date      = date(DATE_FORMAT,$post['published_when']);
                $publ_time      = date(TIME_FORMAT,$post['published_when']);
                // Work-out the post link
                $post_link      = page_link($post['link']);
                $post_link_path = str_replace(WB_URL, WB_PATH,$post_link);
                $create_date    = date(DATE_FORMAT, $post['created_when']);
                $create_time    = date(TIME_FORMAT, $post['created_when']);
                // Get group id, title, and image
                $group_id       = $post['group_id'];
                $group_title    = $groups[$group_id]['title'];
                $group_image    = $groups[$group_id]['image'];
                $display_image  = ($group_image == '') ? "none" : "inherit";
                $display_group  = ($group_id == 0) ? 'none' : 'inherit';
                $post_short     =$post['content_short'];
                if ($group_image != "") $group_image= "<img src='".$group_image."' alt='".$group_title."' />";

                $aPlaceHolders = makePhExp(
                    'PAGE_TITLE',
                    'GROUP_ID',
                    'GROUP_TITL',
                    'GROUP_IMAGE',
                    'DISPLAY_GROUP',
                    'DISPLAY_IMAGE',
                    'TITLE',
                    'SHORT',
                    'BACK',
                    'TEXT_BACK',
                    'TEXT_LAST_CHANGED',
                    'MODI_DATE',
                    'TEXT_AT',
                    'MODI_TIME',
                    'CREATED_DATE',
                    'CREATED_TIME',
                    'PUBLISHED_DATE',
                    'PUBLISHED_TIME',
                    'TEXT_POSTED_BY',
                    'TEXT_ON',
                    'USER_ID',
                    'USERNAME',
                    'DISPLAY_NAME',
                    'EMAIL'
                );
                $aReplacements = array(
                    PAGE_TITLE,
                    $group_id,
                    $group_title,
                    $group_image,
                    $display_group,
                    $display_image,
                    $post['title'],
                    $post_short,
                    $page_link,
                    $MOD_NEWS['TEXT_BACK'],
                    $MOD_NEWS['TEXT_LAST_CHANGED'],
                    $post_date,
                    $MOD_NEWS['TEXT_AT'],
                    $post_time,
                    $create_date,
                    $create_time,
                    $publ_date,
                    $publ_time,
                    $MOD_NEWS['TEXT_POSTED_BY'],
                    $MOD_NEWS['TEXT_ON']
                );
                if (isset($users[$uid]['username']) AND $users[$uid]['username'] != '') {
                    $aReplacements[] = $uid;
                    $aReplacements[] = $users[$uid]['username'];
                    $aReplacements[] = $users[$uid]['display_name'];
                    $aReplacements[] = $users[$uid]['email'];
                }
                $post_long = ($post['content_long'] != '') ? $post['content_long'] : $post['content_short'];
                print (preg_replace($aPlaceHolders, $aReplacements, $setting_post_header));
                print $post_long;
                print (preg_replace($aPlaceHolders, $aReplacements, $setting_post_footer));
            }
        } else {
                $wb->print_error($MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'], 'view.php', false);
        }
        // Show comments section if we have to
        if (($post['commenting'] == 'private' AND isset($wb) AND $wb->is_authenticated() == true)
            OR $post['commenting'] == 'public'
        ) {
            // Print comments header
            $aPlaceHolders = makePhExp(
                'ADD_COMMENT_URL',
                'TEXT_COMMENTS'
            );
            $aReplacements = array(
                WB_URL.'/modules/news/comment.php?post_id='.$post_id.'&amp;section_id='.$section_id,
                $MOD_NEWS['TEXT_COMMENTS']
            );
            print (preg_replace($aPlaceHolders, $aReplacements, $setting_comments_header));
            // Query for comments
            $iNumberOfComments = 0;
            $aPlaceHolders = makePhExp(
                'COMMENT',
                'TITLE',
                'TEXT_ON',
                'DATE',
                'TEXT_AT',
                'TIME',
                'TEXT_BY',
                'USER_ID',
                'USERNAME',
                'DISPLAY_NAME',
                'EMAIL'
            );
            $sql = 'SELECT `title`, `comment`, `commented_when`, `commented_by` '
                 . 'FROM `'.TABLE_PREFIX.'mod_news_comments` '
                 . 'WHERE `post_id`='.$post_id.' '
                 . 'ORDER BY `commented_when` ASC';
            if (($query_comments = $database->query($sql))) {
                while (($comment = $query_comments->fetchRow())) {
                    $iNumberOfComments++;
                    // Display Comments without slashes, but with new-line characters
                    $comment['comment'] = nl2br($wb->strip_slashes($comment['comment']));
                    $comment['title'] = $wb->strip_slashes($comment['title']);
                    // Print comments loop
                    $commented_date = date(DATE_FORMAT, $comment['commented_when']+TIMEZONE);
                    $commented_time = date(TIME_FORMAT, $comment['commented_when']+TIMEZONE);
                    $uid = $comment['commented_by'];
                    $aReplacements = array(
                        $comment['comment'],
                        $comment['title'],
                        $MOD_NEWS['TEXT_ON'],
                        $commented_date,
                        $MOD_NEWS['TEXT_AT'],
                        $commented_time,
                        $MOD_NEWS['TEXT_BY']
                    );
                    if (isset($users[$uid]['username']) AND $users[$uid]['username'] != '') {
                        $aReplacements[] = $uid;
                        $aReplacements[] = $users[$uid]['username'];
                        $aReplacements[] = $users[$uid]['display_name'];
                        $aReplacements[] = $users[$uid]['email'];
                    } else {
                        $aReplacements[] = '0';
                        $aReplacements[] = strtolower($TEXT['UNKNOWN']);
                        $aReplacements[] = $TEXT['UNKNOWN'];
                    }
                    print (preg_replace($aPlaceHolders, $aReplacements, $setting_comments_loop));
                }
            }
            if (! $iNumberOfComments) {
                // Say no comments found
                $content = '';
                $aReplacements = array(
                    $MOD_NEWS['NO_COMMENT_FOUND']
                );
                print (preg_replace($aPlaceHolders, $aReplacements, $setting_comments_loop));
            }
            // Print comments footer
            $aPlaceHolders = makePhExp(
                'ADD_COMMENT_URL',
                'TEXT_ADD_COMMENT',
                'TEXT_COMMENTS'
            );
            $aReplacements = array(
                WB_URL.'/modules/news/comment.php?post_id='.$post_id.'&amp;section_id='.$section_id,
                $MOD_NEWS['TEXT_ADD_COMMENT'],
                $MOD_NEWS['TEXT_COMMENTS']
            );
            print (preg_replace($aPlaceHolders, $aReplacements, $setting_comments_footer));
        }
        if (ENABLED_ASP) {
            $_SESSION['comes_from_view'] = $post_id;
            $_SESSION['comes_from_view_time'] = time();
        }
    }
}
