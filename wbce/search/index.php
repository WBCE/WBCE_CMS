<?php
/**
 *
 * @category        frontend
 * @package         search
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: index.php 1386 2011-01-16 09:56:53Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/search/index.php $
 * @lastmodified    $Date: 2011-01-16 10:56:53 +0100 (So, 16. Jan 2011) $
 *
 */

// Include the config file
require('../config.php');

// Required page details
$page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', 0);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['SEARCH']);
define('MENU_TITLE', $TEXT['SEARCH']);
define('MODULE', '');
define('VISIBILITY', 'public');
define('PAGE_CONTENT', 'search.php');

// Find out what the search template is
// $database = new database();
$query_template = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'template' LIMIT 1");
$fetch_template = $query_template->fetchRow();
$template = $fetch_template['value'];
if($template != '') {
	define('TEMPLATE', $template);
}
unset($template);

//Get the referrer page ID if it exists
if(isset($_REQUEST['referrer']) && is_numeric($_REQUEST['referrer']) && intval($_REQUEST['referrer']) > 0) {
	define('REFERRER_ID', intval($_REQUEST['referrer']));
} else {
	define('REFERRER_ID', 0);
}

// Include index (wrapper) file
require(WB_PATH.'/index.php');

?>