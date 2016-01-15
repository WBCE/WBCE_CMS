<?php
/**
*	Must include code to stop this file being access directly
*/
if(defined('WB_PATH') == false) die("Cannot access this file directly");
global $table_day,$table_ips,$table_pages,$table_ref,$table_key,$table_lang, $code2lang,$WS;
$mpath = WB_PATH.'/modules/wbstats/';
$lang = $mpath . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? $mpath . '/languages/EN.php' : $lang );
$plink = WB_URL.PAGES_DIRECTORY.get_page_link(PAGE_ID).PAGE_EXTENSION;
$module_overview_link = $plink.'?overview';
$module_visitors_link = $plink.'?visitors';
$module_history_link = $plink.'?history';
?>
<script type="text/javascript" src="<?php echo WB_URL ?>/modules/wbstats/js/jquery.poshytip.js"></script>
<div id="wbstats_container">
<div class="wbstats_sysmenu">
  <a href="<?php echo $module_overview_link  ?>"><?php echo $WS['MENU1'] ?></a>
  <a href="<?php echo $module_visitors_link  ?>"><?php echo $WS['MENU2'] ?></a>
  <a href="<?php echo $module_history_link  ?>"><?php echo $WS['MENU3'] ?></a>
</div>
<?php 
require_once($mpath.'class.stats.php');
$viewmode = true;

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
?>
