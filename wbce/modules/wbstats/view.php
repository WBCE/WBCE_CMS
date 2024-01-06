<?php
/**
 *
 * @category        admintool
 * @package         wbstats
 * @author          Ruud Eisinga - dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 7 and higher
 * @version         0.2.5.5
 * @lastmodified    December 16, 2023
 *
 */


 
/**
*	Must include code to stop this file being access directly
*/
if(defined('WB_PATH') == false) die("Cannot access this file directly");
//include (dirname(__FILE__).'/login.php');

global $table_day,$table_ips,$table_pages,$table_ref,$table_key,$table_lang, $table_brwsr, $table_hist, $code2lang,$WS;
$mpath = WB_PATH.'/modules/wbstats/';
$lang = $mpath . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? $mpath . '/languages/EN.php' : $lang );
require_once( $mpath .'/info.php');
require_once($mpath . '/class.stats.php');
$stats = new stats();


$module_overview_link = '?overview';
$module_visitors_link = '?visitors';
$module_history_link = '?history';
$module_live_link = '?live';
$module_log_link = '?logbook';
?>
<script type="text/javascript" src="<?php echo WB_URL ?>/modules/wbstats/backend.js"></script>
<script type="text/javascript" src="<?php echo WB_URL ?>/modules/wbstats/js/jquery.poshytip.js"></script>
<div id="container" class="frontend">
<div class="sysmenu">
  <a href="<?php echo $module_overview_link  ?>"><?php echo $WS['MENU1'] ?></a>
  <a href="<?php echo $module_live_link  ?>"><?php echo $WS['MENU4'] ?></a>
  <a href="<?php echo $module_log_link  ?>"><?php echo $WS['MENU7'] ?></a>
  <a href="<?php echo $module_visitors_link  ?>"><?php echo $WS['MENU2'] ?></a>
  <a href="<?php echo $module_history_link  ?>"><?php echo $WS['MENU3'] ?></a>
</div>
<?php 
require_once($mpath.'class.stats.php');
if (isset($_GET['overview'])) {
	require ($mpath."overview.php");
} elseif (isset($_GET['visitors'])) {
	require ($mpath."visitors.php");
} elseif (isset($_GET['history'])) {
	require ($mpath."history.php");
} elseif (isset($_GET['live'])) {
	require ($mpath."live.php");
} elseif (isset($_GET['logbook'])) {
	require ($mpath."logbook.php");
} else {
	require_once ($mpath."overview.php");
}
?>
</div>