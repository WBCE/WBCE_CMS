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

global $page_id, $section_id, $post_id, $allowed_suffixes;

require_once __DIR__.'/functions.inc.php';

// Get id
if (!isset($_POST['post_id']) or !is_numeric($_POST['post_id'])) {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
} else {
    $id = intval($_POST['post_id']);
    $post_id = $id;
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = FALSE;
// Include WB admin wrapper script
require WB_PATH.'/modules/admin.php';
if(!defined('CAT_PATH')) {
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
        $admin->print_header();
    }
}

if(!defined('CAT_PATH')) {
    include __DIR__.'/config/wbce_config.php';
} else {
    include __DIR__.'/config/bc_config.php';
}

$group = '';
$block2 = '';

// Validate all fields
if ($admin->get_post('title') == '' and $admin->get_post('url') == '') {
    $post_id_key = $admin->getIDKEY($id);
    if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
    	    $post_id_key = $id;
    }
    $admin->print_error($MESSAGE['GENERIC_FILL_IN_ALL'], WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='.$post_id_key);
} else {
    $settings = mod_nwi_settings_get($section_id);
    $title = mod_nwi_escapeString($admin->get_post('title'));
    $link = mod_nwi_escapeString($admin->get_post('link'));
    $short = mod_nwi_escapeString($admin->get_post('short'));
    $long = mod_nwi_escapeString($admin->get_post('long'));
    if ($settings['use_second_block']=='Y') {
        $block2 = mod_nwi_escapeString($admin->get_post('block2'));
    }
    $image = mod_nwi_escapeString($admin->get_post('image'));
    $active = mod_nwi_escapeString($admin->get_post('active'));
    $group = mod_nwi_escapeString($admin->get_post('group'));

    $tags = $admin->get_post('tags');
}

if ($link=='') {
	$link = page_filename($title);
}


$group_id = 0;
$old_section_id = $section_id;
$old_page_id = $page_id;

if (!empty($group)) {
    $gid_value = urldecode($group);
    $values = unserialize($gid_value);
    if (!isset($values['s']) or  !isset($values['g']) or  !isset($values['p'])) {
        header("Location: ".ADMIN_URL."/pages/index.php");
        exit(0);
    }
    if (intval($values['p'])!=0) {
        $group_id = intval($values['g']);
        $section_id = intval($values['s']);
        $page_id = intval($values['p']);
    }
}

// Get page link URL
$query_page = $database->query("SELECT `level`,`link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '$page_id'");
$page = $query_page->fetchRow();
$page_level = $page['level'];
$page_link = $page['link'];

// get old link
$query_post = $database->query("SELECT `link` FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `post_id`='$post_id'");
$post = $query_post->fetchRow();
$old_link = $post['link'];

// potential new link
$post_link = '/posts/'.page_filename($link);
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
} elseif (($old_link != $post_link) or !file_exists(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION) or $page_id != $old_page_id or $section_id != $old_section_id) {
    // We need to create a new file
    // First, delete old file if it exists
    if (file_exists(WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION)) {
        $file_create_time = filemtime(WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION);
        unlink(WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION);
    }
    if ($page_id != $old_page_id or $section_id != $old_section_id) {
        $file_create_time = '';
    }
    // Specify the filename
    $filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
    mod_nwi_create_file($filename, $file_create_time, null, null);
}

list($publishedwhen, $publisheduntil) = mod_nwi_get_dates();

// post images (gallery images)
$imageErrorMessage = '';
if (isset($_FILES["foto"])) {
    $imageErrorMessage = mod_nwi_img_upload($post_id);
}

// ----- post (preview) picture; shown on overview page ----------------------------------
if (isset($_FILES["postfoto"]) && $_FILES["postfoto"]["name"] != "") {
    $imageErrorMessage .= mod_nwi_img_upload($post_id, true);
} 
  
// strip HTML from title
$title = strip_tags($title);

$position="";
// if we are moving posts across section borders we have to update the order of the posts
if ($old_section_id!=$section_id) {
    // Get new order
    $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
    $position = "`position` = '".$order->get_new($section_id)."',";
}

// Update row
$database->query(
    "UPDATE `".TABLE_PREFIX."mod_news_img_posts`"
    . " SET `section_id` = '$section_id',"
    . " $position"
    . " `group_id` = '$group_id',"
    . " `title` = '$title',"
    . " `link` = '$post_link',"
    . " `content_short` = '$short',"
    . " `content_long` = '$long',"
    . " `content_block2` = '$block2',"
    . " `active` = '$active',"
    . " `published_when` = '$publishedwhen',"
    . " `published_until` = '$publisheduntil',"
    . " `posted_when` = '".time()."',"
    . " `posted_by` = '".$admin->get_user_id()."'"
    . " WHERE `post_id` = '$post_id'"
);

// when no error has occurred go ahead and update the image descriptions
if (!($database->is_error())) {
    //update Bildbeschreibungen der tabelle mod_news_img_img
    $images = mod_nwi_img_get_by_post($post_id,false);
    if (count($images) > 0) {
        foreach ($images as $row) {
            $row_id = $row['id'];
            $picdesc = isset($_POST['picdesc'][$row_id])
                     ? mod_nwi_escapeString(strip_tags($_POST['picdesc'][$row_id]))
                     : '';
            $database->query("UPDATE `".TABLE_PREFIX."mod_news_img_img` SET `picdesc` = '$picdesc' WHERE id = '$row_id'");
        }
    }
}


$pageQuery = "SELECT * from `".TABLE_PREFIX."sections` WHERE `section_id`=".$section_id;	
$query_page = $database->query($pageQuery);
if ($query_page->numRows() > 0) {
$page      = $query_page->fetchRow();
}
if (!isset($active) || $active != 1) { $active=0; }


if ($publishedwhen != 0 && $publishedwhen > time()) {$active = 0;}
if ($publisheduntil != 0 && $publisheduntil < time()) {$active = 0;}


if ($active != 1 ) {		
	if(is_writable(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION)) {
			unlink(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION);
	}
	
} else{	
	$filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
	mod_nwi_create_file($filename, '', $post_id, $section_id, $page['page_id']);
}

// if this went fine so far and we are moving posts across section borders we still have to reorder
if ((!($database->is_error()))&&($old_section_id!=$section_id)) {
    // Clean up ordering
    $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
    $order->clean($old_section_id);
}

// remove current tags
$database->query(sprintf(
    "DELETE FROM `%smod_news_img_tags_posts` WHERE `post_id`=$post_id",
    TABLE_PREFIX
));
// re-add marked tags
if (is_array($tags) && count($tags)>0) {
    $existing = mod_nwi_get_tags($section_id);
    foreach (array_values($tags) as $t) {
        $t = intval($t);
        if (array_key_exists($t, $existing)) {
            $database->query(sprintf(
                "INSERT IGNORE INTO `%smod_news_img_tags_posts` VALUES('$post_id','$t')",
                TABLE_PREFIX
            ));
        }
    }
}

// Check result
if ($database->is_error()) {
    $post_id_key = $admin->getIDKEY($id);
    if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
        $post_id_key = $id;
    }
    $admin->print_error($database->get_error(), WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='.$post_id_key);
} else {
    if ($imageErrorMessage!='') {
        $post_id_key = $admin->getIDKEY($id);
        if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
            $post_id_key = $id;
        }
        $admin->print_error($MOD_NEWS_IMG['GENERIC_IMAGE_ERROR'].'<br />'.$imageErrorMessage, WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='.$post_id_key);
    } else {
        if (isset($_POST['savegoback']) && $_POST['savegoback']=='1') {
            $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
        } else {
	    $post_id_key = $admin->getIDKEY($id);
            if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
        	$post_id_key = $id;
            }
            $admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='.$post_id_key);
        }
    }
}

// Print admin footer
$admin->print_footer();
