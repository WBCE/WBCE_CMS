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
 * @version         0.2.5
 * @lastmodified    July 7, 2022
 *
 */

 
require('../../config.php');

require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin();
if (!($admin->is_authenticated())) {
	die("Go away");
}
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

if(isAjax()) {
	require_once (__DIR__ . '/class.stats.php');
	$stats = new stats();
	$r = $stats->getLive();

	echo json_encode($r);
}


function isAjax() {
	$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	return $isAjax;
}
	