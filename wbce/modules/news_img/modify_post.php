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
require WB_PATH.'/modules/admin.php';

$post_id = $admin->checkIDKEY('post_id', 0, 'GET', true);
if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
    $post_id = intval($_GET['post_id']);
}
if (!$post_id) {
    $admin->print_error(
        $MESSAGE['GENERIC_SECURITY_ACCESS']
     .' (IDKEY) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php'
    );
    $admin->print_footer();
    exit();
}

$FTAN = $admin->getFTAN();
$post_id_key = $admin->getIDKEY($post_id);
if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
    $post_id_key = intval($_GET['post_id']);
}

// get post
$post_data = mod_nwi_post_get($post_id);

// ----- delete previewimage ---------------------------------------------------
if (isset($_GET['post_img'])) {
    $post_img = $post_data['image'];
    $database->query(sprintf(
        "UPDATE `%smod_news_img_posts` SET `image`='' WHERE `post_id`=%d",
        TABLE_PREFIX, intval($post_id)
    ));
    @unlink($mod_nwi_file_dir.$post_img);
    $post_data['image'] = null;
}   //end delete preview image

$mod_nwi_file_dir .= "$post_id/";
$mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";

// ----- delete gallery image --------------------------------------------------
if (isset($_GET['img_id'])) {
    $img_id = $admin->checkIDKEY('img_id', 0, 'GET');
    if (!$img_id) {
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
             .' (IDKEY) '.__FILE__.':'.__LINE__, ADMIN_URL.'/pages/index.php');
        $admin->print_footer();
        exit();
    }
    
    $img_id = intval($img_id);
    $row = mod_nwi_img_get($img_id);
 
    if (!$row) {
        echo "Datei existiert nicht!";
    } else {
        unlink($mod_nwi_file_dir.$row['picname']);
        unlink($mod_nwi_thumb_dir.$row['picname']);
    }
    $database->query(sprintf(
        "DELETE FROM `%smod_news_img_img` WHERE `id` = '%d'",
        TABLE_PREFIX, $img_id
    ));
}   //end delete gallery image

// re-order images
if (isset($_GET['id']) && (isset($_GET['up']) || isset($_GET['down']))) {
    $order = new order(TABLE_PREFIX.'mod_news_img_img', 'position', 'id', 'post_id');
    $id = $admin->checkIDKEY('id', 0, 'GET');
    if (!$id) {
        $admin->print_error(
            $MESSAGE['GENERIC_SECURITY_ACCESS']
         .' (IDKEY) '.__FILE__.':'.__LINE__,
                 ADMIN_URL.'/pages/index.php'
        );
        $admin->print_footer();
        exit();
    }
    if (isset($_GET['up'])) {
        $order->move_up(intval($id));
    } else {
        $order->move_down(intval($id));
    }
}

$settings = mod_nwi_settings_get($section_id);

// ----- make sure we have a WYSIWYG editor ------------------------------------
if (!defined('WYSIWYG_EDITOR') or WYSIWYG_EDITOR=="none" or !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
    function show_wysiwyg_editor($name, $id, $content, $width, $height)
    {
        echo '<textarea name="'.$name.'" id="'.$id.'" rows="10" cols="1" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
    }
} else {
    $id_list=array("short","long");
    if ($settings['use_second_block']=='Y') {
        $id_list[]="block2";
    }
    require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

// split link
$link = $post_data['link'];
$parts = explode('/', $link);
$link = array_pop($parts);
$linkbase = implode('/', $parts);
if(strlen(PAGE_SPACER)) {
    $parts = explode(PAGE_SPACER, $link);
    array_pop($parts);
    $link = implode(PAGE_SPACER, $parts);
}
$assigned = array();
$tags = mod_nwi_get_tags($section_id);
$assigned_tags = $database->query(sprintf(
    "SELECT * FROM `%smod_news_img_tags_posts` WHERE `post_id`=%d",
    TABLE_PREFIX, $post_id
));

while ($a=$assigned_tags->fetchRow()) {
    $assigned[$a['tag_id']] = 1;
}

// Create new order object and reorder
$order = new order(TABLE_PREFIX.'mod_news_img_img', 'position', 'id', 'post_{id');
$order->clean($post_id);

// get images
$postimg = mod_nwi_img_get_by_post($post_id,false);
$images = array();
$seenimg = array();

if (count($postimg)>0) {
    $i=1;
    foreach ($postimg as $row) {
        $row['id_key'] = $admin->getIDKEY($row['id']);
        if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
            $row['id_key'] = $row['id'];
        }
        $row['up'] = '<span style="display:inline-block;width:20px;"></span>';
        $row['down'] = $row['up'];
        if ($i>1) { // not first
            $row['up'] = '<a href="'.WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='. $post_id_key.'&id='.$row['id_key'] .'&up=1">'
                . '<img src="'.THEME_URL.'/images/up_16.png"  class="mod_news_img_arrow" /></a>';
        }
        if ($i != (count($postimg)-1)) { // not last
            $row['down'] = '<a href="'.WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='. $post_id_key.'&id='.$row['id_key'] .'&down=1">'
                  . '<img src="'.THEME_URL.'/images/down_16.png"  class="mod_news_img_arrow" /></a>';
        }
        $i++;
        $images[] = $row;
        $seenimg[$row['picname']]=1;
    }
}

$settings = mod_nwi_settings_get($section_id);
$imgmaxsize = $settings['imgmaxsize'];

list($groups,$pages) = mod_nwi_get_all_groups($section_id, $page_id);

if(!defined('CAT_PATH')) {
    include __DIR__.'/config/wbce_config.php';
} else {
    include __DIR__.'/config/bc_config.php';
}

include __DIR__.'/js/datetimepickers/'.$datetimepicker.'/include.phtml';
include __DIR__.'/templates/default/modify_post.phtml';

// Print admin footer
$admin->print_footer();
