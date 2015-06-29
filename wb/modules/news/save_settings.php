<?php
/**
 *
 * @category        modules
 * @package         news
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: save_settings.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/save_settings.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

require('../../config.php');
/*
// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

if (!$admin->checkFTAN())
{
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	exit();
}
*/

$admin_header = false;
// Tells script to update when this page was last updated
$update_when_modified = true;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}
$admin->print_header();

// This code removes any <?php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');
$header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['header']));
$post_loop = $admin->add_slashes(str_replace($friendly, $raw, $_POST['post_loop']));
$footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['footer']));
$post_header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['post_header']));
$post_footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['post_footer']));
$comments_header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_header']));
$comments_loop = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_loop']));
$comments_footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_footer']));
$comments_page = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_page']));
$commenting = $admin->add_slashes($_POST['commenting']);
$posts_per_page = $admin->add_slashes($_POST['posts_per_page']);
$use_captcha = $admin->add_slashes($_POST['use_captcha']);
if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) {
	$resize = $_POST['resize'];
} else {
	$resize = '';
}

// Update settings
$database->query("UPDATE ".TABLE_PREFIX."mod_news_settings SET header = '$header', post_loop = '$post_loop', footer = '$footer',
				 posts_per_page = '$posts_per_page', post_header = '$post_header', post_footer = '$post_footer',
				 comments_header = '$comments_header', comments_loop = '$comments_loop', comments_footer = '$comments_footer',
				 comments_page = '$comments_page', commenting = '$commenting', resize = '$resize', use_captcha = '$use_captcha'
				 WHERE section_id = '$section_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();
