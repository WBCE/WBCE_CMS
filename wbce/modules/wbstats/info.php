<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.2
 * @lastmodified    December 9, 2020
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
 * 0.2.2
 * - fixed small bug in upgrade script when used in complete CMS upgrades
 * - fixed bug showing current online
 * - extra page with live visitor view
 * - Detect local search keywords
 *
 * 0.2.1
 *	- added frontend view for WBCE
 *
 * 0.2.0
 *	- added entrypages, exitpages, time/pages cloud
 *
 * 0.1.12.3
 *	- Update module_platform 
 *
 * 0.1.12.2
 *	- Add Admintool Icon
 *
 * 0.1.12.1
 *	- Initialize.php for wbstats
 *
 **/
