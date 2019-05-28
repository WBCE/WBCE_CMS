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

if (!isset($_POST['section_id']) or !isset($_POST['page_id']) or !is_numeric($_POST['section_id']) or !is_numeric($_POST['page_id'])) {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
} 

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = FALSE;
// Include WB admin wrapper script
require WB_PATH.'/modules/admin.php';
if (!$admin->checkFTAN()){
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
} else $admin->print_header();

global $page_id, $section_id, $post_id;

// Include the ordering class
require WB_PATH.'/framework/class.order.php';

$section_id = intval($_POST['section_id']);
$page_id = intval($_POST['page_id']);


$group_id = 0;
$old_section_id = $section_id;
$old_page_id = $page_id;

$group = $admin->get_post_escaped('group');


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

//store this one for later use
$mod_nwi_file_base=$mod_nwi_file_dir; 

$posts=array();
if(isset($_POST['manage_posts'])&&is_array($_POST['manage_posts'])) $posts=$_POST['manage_posts'];
foreach($posts as $idx=>$pid) {
    $post_id = intval($pid);

    if($post_id != 0){
    
	// Update row
	$database->query("UPDATE `".TABLE_PREFIX."mod_news_img_posts` SET `page_id` = '$page_id', `section_id` = '$section_id', `group_id` = '$group_id'  WHERE `post_id` = '$post_id'");

    }

    // Clean up ordering (e.g. if we were moving posts across section borders
    $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
    $order->clean($old_section_id);
    $order->clean($section_id);

    //   exit;
    // Check if there is a db error, otherwise say successful
    if($database->is_error())
    {
	$admin->print_error($database->get_error(),  ADMIN_URL.'/pages/modify.php?page_id='.$old_page_id);
    }  
    
    // get post link
    $query_post = $database->query("SELECT `link` FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `post_id`='$post_id'");
    $post = $query_post->fetchRow();
    $post_link = $post['link'];
    // We need to create a new file
    // First, delete old file if it exists
    if (file_exists(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION)) {
        $file_create_time = filemtime(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION);
        unlink(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION);
    }

    // Specify the filename
    $filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
    mod_nwi_create_file($filename, '');
    
}

$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);

// Print admin footer
$admin->print_footer();
