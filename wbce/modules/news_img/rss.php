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
if(isset($_GET['section_id']) && is_numeric($_GET['section_id'])) {
	$section_id = intval($_GET['section_id']);
} else {
	die('section_id missing');
}

if(isset($_GET['page_id']) && is_numeric($_GET['page_id'])) {
	$page_id = intval($_GET['page_id']);
} else {
	die('page_id missing');
}

if(isset($_GET['group_id']) && is_numeric($_GET['group_id'])) {
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
$t = TIME();
echo '<?xml version="1.0" encoding="'.$charset.'"?>';
?> 
<rss version="2.0">
	<channel>
		<title><?php echo PAGE_TITLE; ?></title>
		<link><?php echo WB_URL; ?></link>
		<description> <?php echo PAGE_DESCRIPTION; ?></description>
<?php
// Optional header info 
?>
		<language><?php echo strtolower(DEFAULT_LANGUAGE); ?></language>
		<copyright><?php $thedate = date('Y'); $websitetitle = WEBSITE_TITLE; echo "Copyright {$thedate}, {$websitetitle}"; ?></copyright>
		<category><?php echo WEBSITE_TITLE; ?></category>		
<?php
$time_check_str= "(`published_when` = '0' OR `published_when` <= ".$t.") && (`published_until` = 0 OR `published_until` >= ".$t.")";
//Query
if(isset($group_id)) {
	$query = "SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `group_id`=".$group_id." && `section_id` = ".$section_id." && `active`=1 && ".$time_check_str." ORDER BY `posted_when` DESC";
} else {
	$query = "SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `section_id`=".$section_id." && `active`=1 && ".$time_check_str." ORDER BY `posted_when` DESC";
}

$result = $database->query($query);

//Generating the news items
while($item = $result->fetchRow()){ 

$pattern = '/\[wblink([0-9]+)\]/isU';
if (preg_match_all($pattern, $item["content_short"], $aMatches, PREG_SET_ORDER))
{
	$aSearchReplaceList = array();
	foreach ($aMatches as $aMatch) {
		 // collect matches formatted like '[wblink123]' => 123
		$aSearchReplaceList[strtolower($aMatch[0])] = $aMatch[1];
	}
	// build list of PageIds for SQL query
	$sPageIdList = implode(',', $aSearchReplaceList); // '123,124,125'
	// replace all PageIds with '#' (stay on page death link)
	array_walk($aSearchReplaceList, function(&$value, $index){ $value = '#'; });
	$sql = 'SELECT `page_id`, `link` FROM `'.TABLE_PREFIX.'pages` '
		 . 'WHERE `page_id` IN('.$sPageIdList.')';
	if (($oPages = $database->query($sql))) {
		while (($aPage = $oPages->fetchRow(MYSQLI_ASSOC))) {
			$aPage['link'] = ($aPage['link']
							 ? PAGES_DIRECTORY.$aPage['link'].PAGE_EXTENSION
							 : '#');
			// collect all search-replace pairs with valid links
			if (is_readable(WB_PATH.$aPage['link'])) {
				// replace death link with found and valide link
				$aSearchReplaceList['[wblink'.$aPage['page_id'].']'] =
					WB_URL.$aPage['link'];
			}
		}
	}
	// replace all found [wblink**] tags with their urls
	$item["content_short"] = str_ireplace(
		array_keys($aSearchReplaceList),
		$aSearchReplaceList,
		$item["content_short"]
	);
}

?>
		<item>
			<title><![CDATA[<?php echo stripslashes($item["title"]); ?>]]></title>
			<description><![CDATA[<?php echo stripslashes($item["content_short"]); ?>]]></description>
			<guid><?php echo WB_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?></guid>
			<link><?php echo WB_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?></link>
		</item>
<?php } ?>
	</channel>
</rss>
