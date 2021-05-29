<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.1
 * @lastmodified    November 15, 2019
 *
 */

$module_directory = 'wbstats';
$module_name = 'Visitor statistics - WBstats';
$module_version = '0.2.2';
$module_function = 'tool';
$module_platform = '2.8';
if(defined('WBCE_VERSION')) {
	$module_function = 'tool,initialize,page';
	$module_platform = '1.3.0';
}
$module_author = 'Dev4me - Ruud Eisinga - www.dev4me.nl, Norbert Heimsath(heimsath.org)';
$module_license = 'GNU General Public License';
$module_description = 'Displays website statistics as an admintool';
$module_icon = 'fa fa-bar-chart';

/**
 * Version history
 * 
 * 0.2.2 - fixed Versioning
 *       - cs fixed files
 * 
 * 0.2.1 - Fix for wbstats database cleanup
 *       - fixed updgrade.php for MySQL-Strict / Doctrine
 *       - added frontend view for WBCE
 *
 * 0.2.0 - added entrypages, exitpages, time/pages cloud
 *
 **/
