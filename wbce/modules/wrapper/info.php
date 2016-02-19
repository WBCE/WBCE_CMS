<?php
/**
 *
 * @category        modules
 * @package         wrapper
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version      	$Id: info.php 1540 2011-12-11 21:43:16Z Luisehahne $
 * @filesource		$HeadURL: http://svn.websitebaker2.org/branches/2.8.x/wb/modules/wrapper/install.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10 Jan 2011) $
 *
 */
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

$module_directory = 'wrapper';
$module_name = 'Wrapper';
$module_function = 'page';
$module_version = '2.8.3';
$module_platform = '2.7 | 2.8.x';
$module_author = 'Ryan Djurovich';
$module_license = 'GNU General Public License';
$module_description = 'This module allows you to wrap your site around another using an inline frame';

