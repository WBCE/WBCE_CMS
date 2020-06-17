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

// Include WB admin wrapper script
$update_when_modified = false; // Tells script to update when this page was last updated
$admin_header = FALSE;
require WB_PATH.'/modules/admin.php';
if (!$admin->checkFTAN()){
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
} else {
    if(!defined('CAT_PATH')) {
        $admin->print_header();
    }
}

// Validate all fields
if($admin->get_post('new_tag') == '' && $admin->get_post('tag_id') == '')
{
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&tab=s');
    $admin->print_footer();
    exit();
}
else
{
    if($admin->get_post('tag_id') != '') {
        $tag_id = intval($admin->get_post('tag_id'));
        $tag = $admin->get_post('tag');
    } else {
        $tag_id = null;
        $tag = $admin->get_post('new_tag');
    }
	$tag = mod_nwi_escapeString($tag);
	$tag = strip_tags($tag);
    $tag_color = mod_nwi_escapeString($admin->get_post('tag_color'));
}

// make global
$tag_section_id = $section_id;
if($admin->get_post('global_tag') == 'on') {
    $tag_section_id = 0;
}
// Update row
if(empty($tag_id)) {
    if(mod_nwi_tag_exists($tag_section_id,$tag)) {
        $admin->print_error($MOD_NEWS_IMG['TAG_EXISTS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id.'&tab=s');
    }
    $database->query("INSERT INTO `".TABLE_PREFIX."mod_news_img_tags` ( `tag`, `tag_color` ) VALUES ('$tag','$tag_color')");
    if($database->is_error()) {
    	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&tab=s');
    } else {
        $tag_id = $database->getLastInsertId();
        if(!empty($tag_id)) {
            $database->query(sprintf(
                "INSERT INTO `%smod_news_img_tags_sections` (`section_id`,`tag_id`) VALUES ($tag_section_id, $tag_id);",
                TABLE_PREFIX
            ));
        }
    }
} else {
    $database->query("UPDATE `".TABLE_PREFIX."mod_news_img_tags` SET `tag`='$tag', `tag_color`='$tag_color' WHERE `tag_id`=$tag_id");
}

if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&tab=s');
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&tab=s');
}

// Print admin footer
$admin->print_footer();

