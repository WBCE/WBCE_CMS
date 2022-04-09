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

/**
*	Must include code to stop this file being accessed directly
*/
if(defined('WB_PATH') == false) exit("Cannot access this file directly");
global $table_day,$table_ips,$table_pages,$table_ref,$table_key,$table_lang, $code2lang,$WS;
$mpath = WB_PATH.'/modules/wbstats/';
$lang = $mpath . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? $mpath . '/languages/EN.php' : $lang );
$module_overview_link = '?page_id='.$page_id.'&overview';
$module_visitors_link = '?page_id='.$page_id.'&visitors';
$module_history_link = '?page_id='.$page_id.'&history';
?>
<script type="text/javascript" src="<?php echo WB_URL ?>/modules/wbstats/js/jquery.poshytip.js"></script>
<div id="container">
<div class="sysmenu">
  <a href="<?php echo $module_overview_link  ?>"><?php echo $WS['MENU1'] ?></a>
  <a href="<?php echo $module_visitors_link  ?>"><?php echo $WS['MENU2'] ?></a>
  <a href="<?php echo $module_history_link  ?>"><?php echo $WS['MENU3'] ?></a>
</div>
<?php 
require_once($mpath.'class.stats.php');
if (isset($_GET['overview'])) {
	require ($mpath."overview.php");
	return;
}
if (isset($_GET['visitors'])) {
	require ($mpath."visitors.php");
	return;
}
if (isset($_GET['history'])) {
	require ($mpath."history.php");
	return;
}
require_once ($mpath."overview.php");
