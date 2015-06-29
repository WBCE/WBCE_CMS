<?php
/**
 *
 * @category        modules
 * @package         news
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: rss.php 1485 2011-08-01 18:22:28Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/rss.php $
 * @lastmodified    $Date: 2011-08-01 20:22:28 +0200 (Mo, 01. Aug 2011) $
 *
 */

// Check that GET values have been supplied
if(isset($_GET['page_id']) AND is_numeric($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
} else {
	header('Location: /');
	exit(0);
}
if(isset($_GET['group_id']) AND is_numeric($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	define('GROUP_ID', $group_id);
}

// Include WB files
require_once('../../config.php');
require_once(WB_PATH.'/framework/class.frontend.php');
$wb = new frontend();
$wb->page_id = $page_id;
$wb->get_page_details();
$wb->get_website_settings();

//checkout if a charset is defined otherwise use UTF-8
if(defined('DEFAULT_CHARSET')) {
	$charset=DEFAULT_CHARSET;
} else {
	$charset='utf-8';
}

// Sending XML header
header("Content-type: text/xml; charset=$charset" );

// Header info
// Required by CSS 2.0
echo '<?xml version="1.0" encoding="'.$charset.'"?>';
?> 
<rss version="2.0">
	<channel>
		<title><?php echo PAGE_TITLE; ?></title>
		<link>http://<?php echo $_SERVER['SERVER_NAME']; ?></link>
		<description> <?php echo PAGE_DESCRIPTION; ?></description>
<?php
// Optional header info 
?>
		<language><?php echo strtolower(DEFAULT_LANGUAGE); ?></language>
		<copyright><?php $thedate = date('Y'); $websitetitle = WEBSITE_TITLE; echo "Copyright {$thedate}, {$websitetitle}"; ?></copyright>
		<managingEditor><?php echo SERVER_EMAIL; ?></managingEditor>
		<webMaster><?php echo SERVER_EMAIL; ?></webMaster>
		<category><?php echo WEBSITE_TITLE; ?></category>
		<generator>WebsiteBaker Content Management System</generator>
<?php
// Get news items from database
$t = TIME();
$time_check_str= "(published_when = '0' OR published_when <= ".$t.") AND (published_until = 0 OR published_until >= ".$t.")";
//Query
if(isset($group_id)) {
	$query = "SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE group_id=".$group_id." AND page_id = ".$page_id." AND active=1 AND ".$time_check_str." ORDER BY posted_when DESC";
} else {
	$query = "SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE page_id=".$page_id." AND active=1 AND ".$time_check_str." ORDER BY posted_when DESC";	
}
$result = $database->query($query);

//Generating the news items
while($item = $result->fetchRow()){
	$description = stripslashes($item["content_short"]);
	// wb->preprocess() -- replace all [wblink123] with real, internal links
	$wb->preprocess($description);
?>
	<item>
		<title><![CDATA[<?php echo stripslashes($item["title"]); ?>]]></title>
		<description><![CDATA[<?php echo $description; ?>]]></description>
		<link><?php echo WB_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?></link>
		<pubDate><?PHP echo date("D, d M Y", $item["published_when"]); ?></pubDate>
		<guid><?php echo WB_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?></guid>
	</item>
<?php } ?>
	</channel>
</rss>