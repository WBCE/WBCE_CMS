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
 * @version         $Id: comment.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/comment.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

// Include config file
require('../../config.php');
require_once(WB_PATH.'/framework/class.wb.php');
$wb = new wb;

// Check if there is a post id
// $post_id = $wb->checkIDKEY('post_id', false, 'GET');

$post_id = (int)$_GET['post_id'];
$section_id = (int)$_GET['section_id'];

if (!$post_id OR !isset($_GET['section_id']) OR !is_numeric($_GET['section_id'])) {
	$wb->print_error('ABORT::'.$MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL.PAGES_DIRECTORY );
	exit();
}

// Query post for page id
$query_post = $database->query("SELECT post_id,title,section_id,page_id FROM ".TABLE_PREFIX."mod_news_posts WHERE post_id = '$post_id'");
if($query_post->numRows() == 0)
{
    header("Location: ".WB_URL.PAGES_DIRECTORY."");
	exit( 0 );
}
else
{
	$fetch_post = $query_post->fetchRow();
	$page_id = $fetch_post['page_id'];
	$section_id = $fetch_post['section_id'];
	$post_id = $fetch_post['post_id'];
	$post_title = $fetch_post['title'];
	define('SECTION_ID', $section_id);
	define('POST_ID', $post_id);
	define('POST_TITLE', $post_title);

	// don't allow commenting if its disabled, or if post or group is inactive
	$t = time();
	$table_posts = TABLE_PREFIX."mod_news_posts";
	$table_groups = TABLE_PREFIX."mod_news_groups";
	$query = $database->query("
		SELECT p.post_id
		FROM $table_posts AS p LEFT OUTER JOIN $table_groups AS g ON p.group_id = g.group_id
		WHERE p.post_id='$post_id' AND p.commenting != 'none' AND p.active = '1' AND ( g.active IS NULL OR g.active = '1' )
		AND (p.published_when = '0' OR p.published_when <= $t) AND (p.published_until = 0 OR p.published_until >= $t)
	");
	if($query->numRows() == 0)
    {
		header("Location: ".WB_URL.PAGES_DIRECTORY."");
	    exit( 0 );
	}

	// don't allow commenting if ASP enabled and user doesn't comes from the right view.php
	if(ENABLED_ASP && (!isset($_SESSION['comes_from_view']) OR $_SESSION['comes_from_view']!=POST_ID))
    {
		header("Location: ".WB_URL.PAGES_DIRECTORY."");
	    exit( 0 );
	}

	// Get page details
	$query_page = $database->query("SELECT parent,page_title,menu_title,keywords,description,visibility FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
	if($query_page->numRows() == 0)
    {
		header("Location: ".WB_URL.PAGES_DIRECTORY."");
	    exit( 0 );
	}
    else
    {
		$page = $query_page->fetchRow();
		// Required page details
		define('PAGE_CONTENT', WB_PATH.'/modules/news/comment_page.php');
		// Include index (wrapper) file
		require(WB_PATH.'/index.php');
	}
}
