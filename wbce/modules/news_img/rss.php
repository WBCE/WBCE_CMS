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

// Check that GET values have been supplied
if(isset($_GET['page_id']) AND is_numeric($_GET['page_id'])) {
	$page_id = intval($_GET['page_id']);
} else {
	header('Location: /');
	exit(0);
}
if(isset($_GET['group_id']) AND is_numeric($_GET['group_id'])) {
	$group_id = intval($_GET['group_id']);
	define('GROUP_ID', $group_id);
}

// Include WB files
require_once '../../config.php';
require_once WB_PATH.'/framework/class.frontend.php';
$database = new database();
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
		<generator>WBCE Content Management System</generator>
<?php
// Get news items from database
$t = TIME();
$time_check_str= "(`published_when` = '0' OR `published_when` <= ".$t.") AND (`published_until` = 0 OR `published_until` >= ".$t.")";
//Query
if(isset($group_id)) {
	$query = "SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `group_id`=".$group_id." AND `page_id` = ".$page_id." AND `active`=1 AND ".$time_check_str." ORDER BY `posted_when` DESC";
} else {
	$query = "SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `page_id`=".$page_id." AND `active`=1 AND ".$time_check_str." ORDER BY `posted_when` DESC";
}
$result = $database->query($query);

//Generating the news items
while($item = $result->fetchRow()){ ?>
		<item>
			<title><![CDATA[<?php echo stripslashes($item["title"]); ?>]]></title>
			<description><![CDATA[<?php echo stripslashes($item["content_short"]); ?>]]></description>
			<guid><?php echo WB_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?></guid>
			<link><?php echo WB_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?></link>
		</item>
<?php } ?>
	</channel>
</rss>
