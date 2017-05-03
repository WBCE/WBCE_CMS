<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.1.9
 * @lastmodified    Februari 20, 2015
 *
 */

defined('WB_PATH') OR die(header('Location: ../index.php'));

$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );
require_once('class.stats.php');

$admintool_url = ADMIN_URL .'/admintools/index.php';
$module_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats';
$module_overview_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&overview';
$module_visitors_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&visitors';
$module_history_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&history';
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
if (isset($_GET['help'])) {
	require ("help.php");
	return;
}
if (!$check = $database->get_one("SELECT sum(user) visitors FROM ".$table_day)) {
	require ("help.php");
	return;
}
require_once ("overview.php");
