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
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = false;
require WB_PATH.'/modules/admin.php';

if (!defined('CAT_PATH')) {
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

// change mode
if(isset($_POST['mode']) && in_array($_POST['mode'], array('default', 'advanced'))) {
    $database->query(
        "UPDATE `{TP}mod_news_img_settings` SET `mode` = ? WHERE `section_id` = ?",
        [$_POST['mode'], intval($section_id)]
    );
    if ($database->hasError()) {
        $admin->print_error($database->getError(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    } else {
        $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
    $admin->print_footer();
    exit;
}

// Strip <?php tags from user-supplied HTML fields (no escaping needed — PDO handles that)
$friendly = array('&lt;', '&gt;', '?php');
$raw      = array('<',    '>',    '');

// get current settings
$settings = mod_nwi_settings_get($section_id);

// always-present fields
$header       = str_replace($friendly, $raw, $_POST['header']    ?? '');
$post_loop    = str_replace($friendly, $raw, $_POST['post_loop'] ?? '');
$view_order   = intval($_POST['view_order']    ?? 0);
$footer       = str_replace($friendly, $raw, $_POST['footer']    ?? '');
$post_header  = str_replace($friendly, $raw, $_POST['post_header']  ?? '');
$post_content = str_replace($friendly, $raw, $_POST['post_content'] ?? '');
$post_footer  = str_replace($friendly, $raw, $_POST['post_footer']  ?? '');
$posts_per_page = intval($_POST['posts_per_page'] ?? 0);

// Security: only allow plain directory-name characters (prevents path traversal)
$gallery = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($_POST['gallery'] ?? ''));

$use_second_block          = (isset($_POST['use_second_block'])          && $_POST['use_second_block'] == 'Y') ? 'Y' : 'N';
$show_settings_only_admins = (isset($_POST['show_settings_only_admins']) && $_POST['show_settings_only_admins'] == 'Y') ? 'Y' : 'N';

// expert mode
if (isset($settings['mode']) && $settings['mode'] == 'advanced') {
    $image_loop          = str_replace($friendly, $raw, $_POST['image_loop'] ?? '');
    $gal_img_resize_width  = $_POST['gal_img_resize_width']  ?? 0;
    $gal_img_resize_height = $_POST['gal_img_resize_height'] ?? 0;
    $gal_img_max_size      = intval($_POST['gal_img_max_size'] ?? 0) * 1024;
    // Security: only allow plain directory-name characters (prevents path traversal)
    $view      = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($_POST['view'] ?? ''));
    $block2    = str_replace($friendly, $raw, $_POST['block2'] ?? '');
    $thumbwidth  = $_POST['thumb_width']  ?? 0;
    $thumbheight = $_POST['thumb_height'] ?? 0;
} else {
    $image_loop          = $settings['image_loop'];
    $gal_img_resize_width  = $settings['imgmaxwidth'];
    $gal_img_resize_height = $settings['imgmaxheight'];
    $gal_img_max_size      = $settings['imgmaxsize'];
    $view   = $settings['view'];
    $block2 = $settings['block2'];
    list($previewwidth, $previewheight, $thumbwidth, $thumbheight) = mod_nwi_get_sizes($section_id);
}

// if the user chooses a new view, the settings are overwritten by the defaults of this view
if (!empty($view) && $view != $settings['view']) {
    include __DIR__.'/views/'.$view.'/config.php';
}

$resize_preview = '';
$crop = 'N';

$width  = $_POST['resize_width']  ?? 0;
$height = $_POST['resize_height'] ?? 0;
$thumbsize = '100x100'; // default

$crop = (isset($_POST['crop_preview']) ? $_POST['crop_preview'] : 'N');
if (is_numeric($width) && is_numeric($height) && $height > 0 && $width > 0) {
    $resize_preview = intval($width).'x'.intval($height);
}
if (is_numeric($thumbwidth) && is_numeric($thumbheight) && $thumbheight > 0 && $thumbwidth > 0) {
    $thumbsize = intval($thumbwidth).'x'.intval($thumbheight);
}
if ($crop == 'on') {
    $crop = 'Y';
} else {
    $crop = 'N';
}

$gal_img_max_size      = intval($gal_img_max_size);
$gal_img_resize_width  = intval($gal_img_resize_width);
$gal_img_resize_height = intval($gal_img_resize_height);

// if the gallery setting changed, load its default settings
$current_gallery = $database->fetchValue(
    "SELECT `gallery` FROM `{TP}mod_news_img_settings` WHERE `section_id` = ?",
    [intval($section_id)]
);
if ($current_gallery !== false && $current_gallery != $gallery) {
    include WB_PATH.'/modules/news_img/js/galleries/'.$gallery.'/settings.php';
}

// if the "use block 2" setting changed from on to off: clear content
if ($settings['use_second_block'] != $use_second_block && $use_second_block == 'N') {
    $block2 = '';
}

// Update settings — PDO prepared statement, no string interpolation
$database->query(
    "UPDATE `{TP}mod_news_img_settings` SET
        `header`                  = ?,
        `post_loop`               = ?,
        `view_order`              = ?,
        `footer`                  = ?,
        `block2`                  = ?,
        `posts_per_page`          = ?,
        `post_header`             = ?,
        `post_content`            = ?,
        `image_loop`              = ?,
        `post_footer`             = ?,
        `resize_preview`          = ?,
        `crop_preview`            = ?,
        `gallery`                 = ?,
        `imgmaxsize`              = ?,
        `imgmaxwidth`             = ?,
        `imgmaxheight`            = ?,
        `imgthumbsize`            = ?,
        `use_second_block`        = ?,
        `show_settings_only_admins` = ?,
        `view`                    = ?
    WHERE `section_id` = ?",
    [
        $header,
        $post_loop,
        $view_order,
        $footer,
        $block2,
        $posts_per_page,
        $post_header,
        $post_content,
        $image_loop,
        $post_footer,
        $resize_preview,
        $crop,
        $gallery,
        $gal_img_max_size,
        $gal_img_resize_width,
        $gal_img_resize_height,
        $thumbsize,
        $use_second_block,
        $show_settings_only_admins,
        $view,
        intval($section_id),
    ]
);

// Check result
if ($database->hasError()) {
    $admin->print_error($database->getError(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
    $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();
