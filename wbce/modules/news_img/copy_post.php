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

// Get id
if (!isset($_POST['section_id']) or !isset($_POST['page_id']) or !is_numeric($_POST['section_id']) or !is_numeric($_POST['page_id'])) {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
} 

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = FALSE;
require WB_PATH.'/modules/admin.php';
if (!$admin->checkFTAN()){
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
} else $admin->print_header();


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
    $original_post_id = intval($pid);

    if($original_post_id != 0){
        //trigger_error("copying $original_post_id");

	// Get new order
	$order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
	$position = $order->get_new($section_id);

	// Insert new row into database
	$sql = "INSERT INTO `".TABLE_PREFIX."mod_news_img_posts` (`section_id`,`page_id`,`group_id`,`position`,`link`,`content_short`,`content_long`,`content_block2`,`active`) VALUES ('$section_id','$page_id','$group_id','$position','','','','','0')";
	$database->query($sql);

	$post_id = $database->get_one("SELECT LAST_INSERT_ID()");

	$mod_nwi_file_dir = "$mod_nwi_file_base/$post_id/";
	$mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";

	$query_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `post_id` = '$original_post_id'");
	$fetch_content = $query_content->fetchRow();

	$title = $database->escapeString($fetch_content['title']);
	$link = $database->escapeString($fetch_content['link']);
	$short = $database->escapeString($fetch_content['content_short']);
	$long = $database->escapeString($fetch_content['content_long']);
	$block2 = $database->escapeString($fetch_content['content_block2']);
	$image = $database->escapeString($fetch_content['image']);
	$active = 0;
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
	if(substr_compare($post_link,$post_id,-(strlen($post_id)),strlen($post_id))!=0) {
	    $post_link .= PAGE_SPACER.$post_id;
	}

	// Make sure the post link is set and exists
	// Make news post access files dir
	make_dir(WB_PATH.PAGES_DIRECTORY.'/posts/');
	$file_create_time = '';
	if (!is_writable(WB_PATH.PAGES_DIRECTORY.'/posts/')) {
	    $admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
	} else {
	    // Specify the filename
	    $filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
	    mod_nwi_create_file($filename, $file_create_time);
	}


	if(!is_dir($mod_nwi_file_dir)) {
	    mod_nwi_img_makedir($mod_nwi_file_dir);
	}
	mod_nwi_img_copy(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$original_post_id,$mod_nwi_file_dir);

	// Update row
	$database->query(
	    "UPDATE `".TABLE_PREFIX."mod_news_img_posts`"
	        . " SET `page_id` = '$page_id',"
	        . " `section_id` = '$section_id',"
	        . " `group_id` = '$group_id',"
	        . " `title` = '$title',"
	        . " `link` = '$post_link',"
	        . " `content_short` = '$short',"
	        . " `content_long` = '$long',"
	        . " `content_block2` = '$block2',"
	        . " `image` = '$image',"
	        . " `active` = '$active',"
	        . " `published_when` = '$publishedwhen',"
	        . " `published_until` = '$publisheduntil',"
	        . " `posted_when` = '".time()."',"
	        . " `posted_by` = '".$admin->get_user_id()."'"
	        . " WHERE `post_id` = '$post_id'");
	if(!($database->is_error())){
	    //update table images
	   $database->query("INSERT INTO `".TABLE_PREFIX."mod_news_img_img` (`picname`, `picdesc`, `post_id`, `position`) SELECT `picname`, `picdesc`, '".$post_id."', `position` FROM `".TABLE_PREFIX."mod_news_img_img` WHERE `post_id` = '".$original_post_id."'");
	}
    }

    // Clean up ordering (e.g. if we were moving posts across section borders
    $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
    $order->clean($old_section_id);

    //   exit;
    // Check if there is a db error, otherwise say successful
    if($database->is_error())
    {
	$admin->print_error($database->get_error(),  ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    } 
}

$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);

// Print admin footer
$admin->print_footer();
