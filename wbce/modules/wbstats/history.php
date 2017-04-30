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

$time=time();
if (isset($_GET["m"]) && is_numeric($_GET["m"]) && $_GET["m"] >= 1 && $_GET["m"] <= 12 ) {$show_month = $_GET["m"];} 
else {$show_month=date("n",$time);}
if (isset($_GET["y"]) && is_numeric($_GET["y"]) && $_GET["y"] >= 2010 && $_GET["y"] <= 2100 ) {$show_year = $_GET["y"];} 
else {$show_year=date("Y",$time);}


$stats = new stats();
$r = $stats->getHistory($show_month,$show_year);
//print_r($r);
?>

  <div class="middle">
    <h3><?php echo $WS['HISTORY'] ?></h3>

	<table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr valign="top">
	  <td colspan="4"><strong><?php echo $WS['TOTALSINCE'] ?> <?PHP echo $r['since'];?></strong></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%"><?php echo $WS['VISITORS'] ?></td><td width="20%"><?PHP echo $r['visitors']; ?></td>
	  <td width="30%"><?php echo $WS['PAGES'] ?></td><td width="20%"><?PHP echo $r['visits']; ?></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%"><?php echo $WS['AVGDAY'] ?></td><td width="20%"><?PHP echo $r['average']; ?></td>
	  <td width="30%">&nbsp;</td><td width="20%">&nbsp;</td>
	  </tr>
	</table>
	<br />
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	  <tr valign="top">
		<td colspan="4"><strong><?php echo $WS['SELECTED'] ?>: <?PHP echo date("Y-m",mktime(0, 0, 0, $show_month, 1, $show_year)); ?></strong></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%"><?php echo $WS['VISITORS'] ?></td><td width="20%"><?PHP echo $r['mvisitors']; ?></td>
	  <td width="30%"><?php echo $WS['PAGES'] ?></td><td width="20%"><?PHP echo $r['mvisits']; ?></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%"><?php echo $WS['AVGDAY'] ?></td><td width="20%"><?PHP echo $r['maverage']; ?></td>
	  <td width="30%">&nbsp;</td><td width="20%">&nbsp;</td>
    </table>
  </div>
  <div class="middle">
    <h3>
	<?php 
	echo $WS['YEAR']." ".date("Y",mktime(0, 0, 0, $show_month, 1, $show_year)); 
	
	$back_month=date("n",mktime(0, 0, 0, $show_month, 1, $show_year-1));
	$back_year=date("Y",mktime(0, 0, 0, $show_month, 1, $show_year-1));
	$next_month=date("n",mktime(0, 0, 0, $show_month, 1, $show_year+1));
	$next_year=date("Y",mktime(0, 0, 0, $show_month, 1, $show_year+1));
	
	echo "<span><a href=\"$module_history_link&m=$back_month&y=$back_year\"><</a>&nbsp;<a href=\"$module_history_link&m=$next_month&y=$next_year\">></a></span>";
	?>
	</h3>
	<table height="200" width="100%" cellpadding="0" cellspacing="0" align="right">
	<tr valign="bottom" height="180">

	<?php
	foreach($r['month'] as $key => $data) {
		$value = (int)$data['data'];
		if ($r['max_month'] > 0) {$bar_height=round((175/$r['max_month'])*$value+5);} else $bar_height = 5;

		echo "<td width=\"38\">";
		echo "<a href=\"$module_history_link&m=$key&y=$show_year\">";
		echo "<div class=\"bar\" style=\"height:".$bar_height."px;\" title=\"".$data['title']." - $value ".$WS['VISITORS']."\"></div>";
		echo "</a></td>\n";
		}
	?>
    </tr><tr height="20">
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 1, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 4, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 7, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 10, 1, $show_year)); ?></td>
	</tr></table>
  </div>
  
  <div style="clear:both"></div>
  
  <div class="full">
    <h3>
	<?php 
	echo date("F Y",mktime(0, 0, 0, $show_month, 1, $show_year)); 
	$back_month=date("n",mktime(0, 0, 0, $show_month-1, 1, $show_year));
	$back_year=date("Y",mktime(0, 0, 0, $show_month-1, 1, $show_year));
	$next_month=date("n",mktime(0, 0, 0, $show_month+1, 1, $show_year));
	$next_year=date("Y",mktime(0, 0, 0, $show_month+1, 1, $show_year));
	echo "<span><a href=\"$module_history_link&m=$back_month&y=$back_year\"><</a>&nbsp;<a href=\"$module_history_link&m=$next_month&y=$next_year\">></a></span>";
	?>
	</h3>
	<table height="230" width="100%" cellpadding="0" cellspacing="0" align="right">
	<tr valign="bottom" height="210">
	<?php
	$max = 1;
	foreach($r['days'] as $data) {
		if($data['data']>$max) $max = $data['data'];
	}
	foreach($r['days'] as $key => $data) {
		$value = (int)$data['data'];
		if ($max > 0) {$bar_height=round((195/$max)*$value+5);} else $bar_height = 5;

		echo "<td width=\"30\">";
		echo "<div class=\"bar\" style=\"height:".$bar_height."px;\" title=\"".$data['title'].$data['tooltip']."\"></div>";
		echo "</td>\n";
		}
	?>
	
	
    </tr><tr height="20">
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 1, $show_year)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 7, $show_year)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 13, $show_year)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 19, $show_year)); ?></td>
	<td colspan="7" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 25, $show_year)); ?></td>
	</tr></table>
  </div>
  <div style="clear:both"></div>
</div>
