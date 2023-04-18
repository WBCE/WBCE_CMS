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

require_once __DIR__.'/functions.inc.php';

if (
       ! isset($_POST['action'])
    || ! isset($_POST['section_id'])
    || !isset($_POST['page_id'])
) {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
}

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

// validate action
$action = '';
$known_actions = array(
    'copy',     'copy_with_tags', 'move', 'move_with_tags', 'delete',
    'activate', 'deactivate',     'tags', 'group'
);
if (in_array($_POST['action'], $known_actions)) {
    $action = $_POST['action'];
} else {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
}

if(isset($_POST['exec']) && isset($_POST['manage_posts'])) {
    switch($action) {
        case "copy":
            $result = mod_nwi_post_copy($section_id,$page_id);
            break;
        case "copy_with_tags":
            $result = mod_nwi_post_copy($section_id,$page_id,true);
            break;
        case "delete":
            $result = mod_nwi_post_delete($_POST['manage_posts']);
            break;
        case "activate":
            $result = mod_nwi_post_activate(1);
            break;
        case "deactivate":
            $result = mod_nwi_post_activate(0);
            break;
        case "move":
            $result = mod_nwi_post_move($section_id, $page_id);
            break;
        case "move_with_tags":
            $result = mod_nwi_post_move($section_id, $page_id, true);
            break;
        case "group":
            $result = false;
            $posts = array();
            $group = null;
            // get post IDs
            if(isset($_POST['manage_posts']) && is_array($_POST['manage_posts'])) {
                $posts = $_POST['manage_posts'];
            }
            // get group
            if(isset($_POST['group']) && !empty($_POST['group'])) {
                $gid_value = urldecode($_POST['group']);
                $values = unserialize($gid_value);
                if (!isset($values['s']) or  !isset($values['g']) or  !isset($values['p'])) {
                    header("Location: ".ADMIN_URL."/pages/index.php");
                    exit(0);
                }
                if (intval($values['p'])!=0) {
                    $group_id = intval($values['g']);
                    $section_id = intval($values['s']);
                    $page_id = intval($values['p']);
                    $group = mod_nwi_get_group($group_id);
                }
            }
						
            if(!empty($posts)) {
                foreach($posts as $post_id) {
                    // Update row
					if (empty($group)) {$group_id = 0; }
                    $database->query(sprintf(
                        "UPDATE `%smod_news_img_posts` ".
                        "SET `group_id`=%d WHERE `post_id`=%d",
                        TABLE_PREFIX,$group_id,intval($post_id)
                    ));
                }
                $result = true;
            }
            break;
        case "tags":
            $result = false;
            $posts = array();
            // get post IDs
            if(isset($_POST['manage_posts']) && is_array($_POST['manage_posts'])) {
                $posts = $_POST['manage_posts'];
            } else {
                return false;
            }
            $tags = mod_nwi_get_tags($section_id);
            // validate tag IDs
            $assign = $_POST['tags'];
            if(is_array($assign) && count($assign)>0) {
                for($i=count($assign)-1; $i>=0; $i--) {
                    if(!array_key_exists($assign[$i],$tags)) {
                        unset($assign[$i]);
                    }
                }
            }
            // save
            foreach($posts as $post_id) {
                foreach($assign as $tag_id) {
                    // Update row
                    $database->query(sprintf(
                        "REPLACE INTO `%smod_news_img_tags_posts`"
                        . " (`post_id`, `tag_id`) VALUES "
                        . " ($post_id, $tag_id)",
                        TABLE_PREFIX
                    ));
                }
            }
            $result = true;
            break;
    }
    unset($_POST['action']);
    unset($_POST['manage_posts']);
    if($result==true) {
        $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    } else {
        $admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
    return;
}

$section_id = intval($_POST['section_id']);
$page_id = intval($_POST['page_id']);
$FTAN = $admin->getFTAN();

$posts=array();
if (isset($_POST['manage_posts'])&&is_array($_POST['manage_posts'])) {
    $post_ids = $_POST['manage_posts'];
    if(count($post_ids)>0) {
        foreach($post_ids as $pid) {
            $post_data = mod_nwi_post_get($pid);
            $posts[$pid] = $post_data['title'];
        }
    }
}

list($groups,$pages) = mod_nwi_get_all_groups($section_id, $page_id);
$tags = mod_nwi_get_tags($section_id);

include __DIR__.'/templates/default/manage_posts.phtml';

// Print admin footer
$admin->print_footer();
