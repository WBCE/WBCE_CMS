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

$stats = new stats();
$r = $stats->getStats();
?>
<script type="text/javascript">
function AutoRefresh( t ) {
	setTimeout("location.reload(true);", t);
}
AutoRefresh(5*60000);
$('.visitors').live("click", function(e) { 
	e.preventDefault();
	$('tr#pags').hide(); 
	$('tr#visits').show(); 
});
$('.pags').live("click",function(e) {
	e.preventDefault();
	$('tr#visits').hide(); 
	$('tr#pags').show(); 
});

</script>

<div class="middle h600">
	<h3><?php echo $WS['GENERAL'] ?></h3>
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr valign="top">
			<td colspan="2"><h4><?php echo $WS['TOTALS'] ?></h4></td>
		</tr>
		<tr valign="top">
			<td ><strong><?php echo $WS['TOTALVISITORS'] ?></strong></td><td width="10%"><?php echo $r['visitors'] ?></td>
		</tr>
		<tr valign="top">
			<td ><strong><?php echo $WS['TOTALPAGES'] ?></strong></td><td width="10%"><?php echo $r['visits'] ?></td>
		</tr>
		<tr valign="top">
			<td colspan="2"><h4><?php echo $WS['LIVE'] ?></h4></td>
		</tr>
		<tr valign="top">
			<td><span<?php if($r['online_title']) { echo ' class="expandunder underline" title="'.$r['online_title'].'"'; } ?>><strong><?php echo $WS['CURRENTONLINE'] ?></span></strong></td><td><?php echo $r['online'] ?></td>
		</tr>
		<tr valign="top">
			<td colspan="2"><h4><?php echo $WS['TODAY'] ?></h4></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['TODAYVISITORS'] ?></strong></td><td><?php echo $r['today'] ?></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['TODAYPAGES'] ?></strong></td><td><?php echo $r['ptoday'] ?></td>
		</tr>	
		<tr valign="top">
			<td><strong><?php echo $WS['TODAYBOTS'] ?></strong></td><td><?php echo $r['btoday'] ?></td>
		</tr>
		<tr valign="top">
			<td colspan="2"><h4><?php echo $WS['YESTERDAY'] ?></h4></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['YESTERVISITORS'] ?></strong></td><td><?php echo $r['yesterday'] ?></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['YESTERPAGES'] ?></strong></td><td><?php echo $r['pyesterday'] ?></td>
		</tr>	
		<tr valign="top">
			<td><strong><?php echo $WS['YESTERDAYBOTS'] ?></strong></td><td><?php echo $r['byesterday'] ?></td>
		</tr>
		<tr valign="top">
			<td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr valign="top">
			<td colspan="2"><h4><?php echo $WS['MISC'] ?></h4></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['BOUNCES'] ?></strong></td><td><?php echo $r['bounced'] ?>%</td>
		</tr>
		<tr valign="top">
			<td colspan="2"><h4><?php echo $WS['AVERAGES'] ?></h4></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['AVGPAGESVISIT'] ?></strong></td><td><?php echo $r['page_user'] ?></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['AVG7VISITS'] ?></strong></td><td><?php echo $r['avg_7'] ?></td>
		</tr>
		<tr valign="top">
			<td><strong><?php echo $WS['AVG30VISITS'] ?></strong></td><td><?php echo $r['avg_30'] ?></td>
		</tr>
    </table>
</div>

<div class="middle h250">
	<h3><?php echo $WS['LAST24'] ?></h3>
	<table height="230" width="100%" cellpadding="0" cellspacing="0" align="right">
		<tr valign="bottom" height="210">
		<?php
			$max = 1;
			foreach($r['bar'] as $bar) {
				if($bar['data']>$max) $max = $bar['data'];
			}
			foreach($r['bar'] as $bar) {
				$value = $bar['data'];
				$bar_height=round((205/$max)*$value+5);
				if ($bar_height == 0) $bar_height = 1;	
				echo "\t\t\t<td width=\"19\">";
				echo "<div class=\"bar\" style=\"height:".$bar_height."px;\" title=\"".$bar['title']." - $value ".$WS['VISITORS']."\"></div></td>\n";
			}	
		?>
		</tr>
		<tr height="20">
			<td colspan="6" width="25%" class="timeline"><?PHP echo date("H:i",mktime(date("H")-23, 0, 0, date("n"), date("j"), date("Y"))+TIMEZONE); ?></td>
			<td colspan="6" width="25%" class="timeline"><?PHP echo date("H:i",mktime(date("H")-17, 0, 0, date("n"), date("j"), date("Y"))+TIMEZONE); ?></td>
			<td colspan="6" width="25%" class="timeline"><?PHP echo date("H:i",mktime(date("H")-11, 0, 0, date("n"), date("j"), date("Y"))+TIMEZONE); ?></td>
			<td colspan="6" width="25%" class="timeline"><?PHP echo date("H:i",mktime(date("H")-5, 0, 0, date("n"), date("j"), date("Y"))+TIMEZONE); ?></td>
		</tr>
	</table>
</div>

<div class="middle  h250">
	<h3><?php echo $WS['LAST30'] ?>
		<span>
		<a href="" class="visitors"><?php echo $WS['VISITORS'] ?></a> | 
		<a href="" class="pags"><?php echo $WS['PAGES'] ?></a>
	</span>
	</h3>
	<table  height="230" width="100%" cellpadding="0" cellspacing="0" align="right">
		<tr id="visits" valign="bottom" height="210">
		<?php
			$max = 1;
			foreach($r['days'] as $days) {
				if($days['data']>$max) $max = $days['data'];
			}
			foreach($r['days'] as $days) {
				$value = $days['data'];
				$bar_height=round((195/$max)*$value+5);
				if ($bar_height == 0) $bar_height = 1;	
				echo "\t\t\t<td width=\"19\">";
				echo "<div class=\"bar\" style=\"height:".$bar_height."px;\" title=\"".$days['title'].$days['tooltip']."\"></div></td>\n";
			}	
		?>
		</tr>
		<tr style="display:none" id="pags" valign="bottom" height="210">
		<?php
			$max = 1;
			foreach($r['days'] as $days) {
				if($days['views']>$max) $max = $days['views'];
			}
			foreach($r['days'] as $days) {
				$value = $days['views'];
				$bar_height=round((195/$max)*$value+5);
				if ($bar_height == 0) $bar_height = 1;	
				echo "\t\t\t<td width=\"19\">";
				echo "<div class=\"bar\" style=\"height:".$bar_height."px;\" title=\"".$days['title'].$days['tooltip']."\"></div></td>\n";
			}	
		?>
		</tr>
		<tr height="20">
			<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-29, date("Y"))); ?></td>
			<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-23, date("Y"))); ?></td>
			<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-17, date("Y"))); ?></td>
			<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-11, date("Y"))); ?></td>
			<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, date("n"), date("j")-5, date("Y"))); ?></td>
		</tr>
	</table>
</div>

<div style="clear:both"></div>
</div>