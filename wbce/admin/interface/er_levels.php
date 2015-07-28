<?php
/**
 *
 * @category        admin
 * @package         interface
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: er_levels.php 1374 2011-01-10 12:21:47Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/interface/er_levels.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10. Jan 2011) $
 *
 * Error Reporting Level's list file
 * This file is used to generate a list of PHP
 * Error Reporting Level's for the user to select
 *
 */

if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

// Define that this file is loaded
if(!defined('ERROR_REPORTING_LEVELS_LOADED')) {
	define('ERROR_REPORTING_LEVELS_LOADED', true);
}

// Create array
$ER_LEVELS = array();

// Add values to list
if(isset($TEXT['SYSTEM_DEFAULT'])) {
	$ER_LEVELS[''] = $TEXT['SYSTEM_DEFAULT'];
} else {
	$ER_LEVELS[''] = 'System Default';
}
$ER_LEVELS['6135'] = 'E_ALL^E_NOTICE'; // standard: E_ALL without E_NOTICE
$ER_LEVELS['0'] = 'E_NONE';
$ER_LEVELS['6143'] = 'E_ALL';
$ER_LEVELS['8191'] = htmlentities('E_ALL&E_STRICT'); // for programmers

?>