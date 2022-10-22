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
 
 
defined('WB_PATH') OR die(header('Location: ../index.php'));

$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );
require_once('info.php');
require_once('class.stats.php');

$admintool_url = ADMIN_URL .'/admintools/index.php';
$module_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats';
$module_overview_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&overview';
$module_visitors_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&visitors';
$module_history_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&history';
$module_live_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&live';
$module_log_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&logbook';
$module_campaign_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&campaigns';
$module_cfg_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&config';
$module_help_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&help';



require_once ("head.php");
if (isset($_GET['overview'])) {
	require ("overview.php");
	return;
}
if (isset($_GET['visitors'])) {
	require ("visitors.php");
	return;
}
if (isset($_GET['history'])) {
	require ("history.php");
	return;
}
if (isset($_GET['live'])) {
	require ("live.php");
	return;
}
if (isset($_GET['logbook'])) {
	require ("logbook.php");
	return;
}
if (isset($_GET['campaigns'])) {
	require ("campaigns.php");
	return;
}
if (isset($_GET['config'])) {
	require ("setconfig.php");
	return;
}

if (isset($_GET['help'])) {
	require ("help.php");
	return;
}
if (!$check = $database->get_one("SELECT sum(user) visitors FROM ".$table_day)) {
	require ("help.php");
	return;
}
require_once ("overview.php");
