<?php
/**
 *
 * @category        admintool
 * @package         wbstats
 * @author          Ruud Eisinga - dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.5.3
 * @lastmodified    October17, 2022
 *
 */

$module_directory = 'wbstats';
$module_name = 'Visitor statistics - WBstats - by Dev4me';
$module_version = '0.2.5.3';
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
 * 0.2.5.3
 * - some more fixes in install.php and upgrade.php for the older MySQL 5.7
 *
 * 0.2.5.2
 * - fixes in install.php and upgrade.php for the older MySQL 5.7
 *
 * 0.2.5.1
 * - fixed security issue
 *
 * 0.2.5
 * - showing 404 status when recent 404 module is used
 * - better bot detection (calls without browser language are fake and not counted)
 * - improved logbook history
 * - added UTM campaign recording
 * - added GCLID and FBCLID detection (logged as campaign data)
 * - fixed issue with viewing searchkeys
 * - fixed issue mysql 5.7+ (setting ONLY_FULL_GROUP_BY)
 * - fixed several php 8.1 "deprecated" issues
 *
 * 0.2.4
 * - fixed updgrade.php for MySQL-Strict / Doctrine
 * - fixed table cleanup routines
 * - added  logbook visitory history
 *
 * 0.2.3
 * - added the option to ignore specified IP adressess
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
