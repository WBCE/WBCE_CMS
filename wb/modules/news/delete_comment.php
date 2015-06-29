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
 * @version         $Id: delete_comment.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/delete_comment.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

require('../../config.php');

$admin_header = true;
// Tells script to update when this page was last updated
$update_when_modified = true;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

$comment_id = intval($admin->checkIDKEY('comment_id', false, 'GET'));
$post_id = intval($admin->checkIDKEY('post_id', false, 'GET'));
if (!$post_id || !$comment_id) {
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL.'/modules/news/modify_post.php?page_id='.$page_id.'&section_id='.$section_id/*.'&post_id='.$post_id */);
} else {
	$post_id = $admin->getIDKEY($post_id);
}

// Update row
$database->query("DELETE FROM ".TABLE_PREFIX."mod_news_comments  WHERE comment_id = '$comment_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error())
{
	$admin->print_error($database->get_error(), WB_URL.'/modules/news/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='.$post_id );
}
else
{
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/news/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='.$post_id );
}

// Print admin footer
$admin->print_footer();
