<?php
/**
 *
 * @category        module
 * @package         Form
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: info.php 1575 2012-01-16 12:20:38Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/form/info.php $
 * @lastmodified    $Date: 2012-01-16 13:20:38 +0100 (Mo, 16. Jan 2012) $
 * @description     
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */
$module_directory = 'form';
$module_name = 'Form';
$module_function = 'page';
$module_version = '2.8.4';
$module_platform = '2.8.x';
$module_author = 'Ryan Djurovich & Rudolph Lartey - additions John Maats - PCWacht';
$module_license = 'GNU General Public License';
$module_description = 'This module allows you to create customised online forms, such as a feedback form. '.
'Thank-you to Rudolph Lartey who help enhance this module, providing code for extra field types, etc.';
