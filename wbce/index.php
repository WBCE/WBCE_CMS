<?php
/**
 *
 * @category        frontend
 * @package         page
 * @author          WebsiteBaker Project
 * @copyright       2009-, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: index.php 1626 2012-02-29 22:45:20Z darkviper $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/index.php $
 * @lastmodified    $Date: 2012-02-29 23:45:20 +0100 (Mi, 29. Feb 2012) $
 *
 */

$starttime = array_sum(explode(" ",microtime()));

define('DEBUG', false);
// Include config file
$config_file = dirname(__FILE__).'/config.php';
if(file_exists($config_file))
{
	require_once($config_file);
}

// Check if the config file has been set-up
if(!defined('TABLE_PREFIX'))
{
/*
 * Remark:  HTTP/1.1 requires a qualified URI incl. the scheme, name
 * of the host and absolute path as the argument of location. Some, but
 * not all clients will accept relative URIs also.
 */
	$host       = $_SERVER['HTTP_HOST'];
	$uri        = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	$file       = 'install/index.php';
	$target_url = 'http://'.$host.$uri.'/'.$file;
	$sResponse  = $_SERVER['SERVER_PROTOCOL'].' 307 Temporary Redirect';
	header($sResponse);
	header('Location: '.$target_url);
	exit;	// make sure that subsequent code will not be executed
}

require_once(WB_PATH.'/framework/class.frontend.php');
// Create new frontend object
$wb = new frontend();

// Figure out which page to display
// Stop processing if intro page was shown
$wb->page_select() or die();

// Collect info about the currently viewed page
// and check permissions
$wb->get_page_details();

// Collect general website settings
$wb->get_website_settings();

// Load functions available to templates, modules and code sections
// also, set some aliases for backward compatibility
require(WB_PATH.'/framework/frontend.functions.php');

// redirect menu-link
$this_page_id = PAGE_ID;

$php43 = version_compare(phpversion(), '4.3', '>=');

$sql  = 'SELECT `module`, `block` FROM `'.TABLE_PREFIX.'sections` ';
$sql .= 'WHERE `page_id` = '.(int)$this_page_id.' AND `module` = "menu_link"';
$query_this_module = $database->query($sql);
if($query_this_module->numRows() == 1)  // This is a menu_link. Get link of target-page and redirect
{
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
			if($php43)
			{
				@header('HTTP/1.1 301 Moved Permanently', TRUE, 301);
			}
			else
			{
				@header('HTTP/1.1 301 Moved Permanently');
			}
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
				header('Location: '.$target_url);
				exit;
			}
		}
	}
}
//Get pagecontent in buffer for Droplets and/or Filter operations
ob_start();
require(WB_PATH.'/templates/'.TEMPLATE.'/index.php');
$output = ob_get_contents();
if(ob_get_length() > 0) { ob_end_clean(); }
// execute frontend output filters
	if(file_exists(WB_PATH .'/modules/output_filter/index.php')) {
		include_once(WB_PATH .'/modules/output_filter/index.php');
		if(function_exists('executeFrontendOutputFilter')) {
			$output = executeFrontendOutputFilter($output);
		}
	}
// now send complete page to the browser
echo $output;
// end of wb-script
exit;
