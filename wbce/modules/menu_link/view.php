<?php
/**
 *
 * @category        modules
 * @package         Menu Link
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: view.php 1420 2011-01-26 17:43:56Z Luisehahne $
 * @filesource		$HeadURL: http://svn.websitebaker2.org/branches/2.8.x/wb/modules/wysiwyg/modify.php $
 * @lastmodified    $Date: 2011-01-11 20:29:52 +0100 (Di, 11 Jan 2011) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }


$this_page_id = PAGE_ID;

// get target_page_id
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_menu_link` WHERE `page_id` = '.(int)$this_page_id;
$query_tpid = $database->query($sql);
if($query_tpid->numRows() == 1)
{
	$res = $query_tpid->fetchRow();
	$target_page_id = $res['target_page_id'];
	$redirect_type = $res['redirect_type'];
	$anchor = ($res['anchor'] != '0' ? '#'.(string)$res['anchor'] : '');
	$extern = $res['extern'];
	// set redirect-type
	if($redirect_type == 301)
	{
		@header('HTTP/1.1 301 Moved Permanently');
	}
	if($target_page_id == -1)
	{
		if($extern != '')
		{
			$target_url = $extern.$anchor;
			header('Location: '.$target_url);
			exit;
		}
	}
	else
	{
		// get link of target-page
		$sql  = 'SELECT `link` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$target_page_id;
		$target_page_link = $database->get_one($sql);
		if($target_page_link != null)
		{
			$target_url = WB_URL.PAGES_DIRECTORY.$target_page_link.PAGE_EXTENSION.$anchor;
            if (OPF_SHORT_URL) {$target_url = WB_URL. $target_page_link. $anchor; }
			header('Location: '.$target_url);
			exit;
		}
	}
}
