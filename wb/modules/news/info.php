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
 * @version         $Id: info.php 1540 2011-12-11 21:43:16Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/info.php $
 * @lastmodified    $Date: 2011-12-11 22:43:16 +0100 (So, 11. Dez 2011) $
 *
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */
$module_directory   = 'news';
$module_name        = 'News';
$module_function    = 'page';
$module_version     = '3.5.6';
$module_platform    = '2.8.2';
$module_author      = 'Ryan Djurovich, Rob Smith, Werner v.d.Decken';
$module_license     = 'GNU General Public License';
$module_description = 'This page type is designed for making a news page.';
