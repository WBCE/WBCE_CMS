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
$r = $stats->getVisitors();
//print_r($r);
?>

<div class="middle h265">
	<h3><?php echo $WS['REFTOP10'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td width="280"><strong><?php echo $WS['REFERER'] ?></strong></td>
			<td width="120"><strong><?php echo $WS['PERCENT'] ?></strong></td>
		</tr>
		<?php if(isset($r['referer'])) 
			foreach($r['referer'] as $key => $data) { ?>
		<tr>
			<td><?php echo $key ?></td>
			<td><span class="expand" title="<?php echo $data['name'] ?>"><?php echo $data['short'] ?></span></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
		</tr>
		<?php } ?>
    </table>
</div>
  
<div class="middle h265">
    <h3><?php echo $WS['PAGETOP10'] ?></h3>
	<table width="100%" cellpadding="3" cellspacing="0">
		<tr>
			<td width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td width="280"><strong><?php echo $WS['PAGES'] ?></strong></td>
			<td width="120"><strong><?php echo $WS['PERCENT'] ?></strong></td>
		</tr>
		<?php if(isset($r['pages'])) foreach($r['pages'] as $key => $data) { ?>
		<tr>
			<td><?php echo $key ?></td>
			<td><span class="expand" title="<?php echo $data['name'] ?>"><?php echo $data['short'] ?></span></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['REQUESTS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
		</tr>
		<?php } ?>
    </table>
</div>

<div style="clear:both"></div>
<div class="middle h265">
    <h3><?php echo $WS['KEYSTOP10'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td width="280"><strong><?php echo $WS['KEYWORDS'] ?></strong></td>
			<td width="120"><strong><?php echo $WS['PERCENT'] ?></strong></td>
		</tr>
		<?php if(isset($r['keyword'])) foreach($r['keyword'] as $key => $data) { ?>
		<tr>
			<td><?php echo $key ?></td>
			<td><span class="expand" title="<?php echo $data['name'] ?>"><?php echo $data['short'] ?></span></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
		</tr>
		<?php } ?>
	</table>
</div>

<div class="middle h265">
    <h3><?php echo $WS['LANGTOP10'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td width="280"><strong><?php echo $WS['LANGUAGES'] ?></strong></td>
			<td width="120"><strong><?php echo $WS['PERCENT'] ?></strong></td>
		</tr>
		<?php if(isset($r['language'])) foreach($r['language'] as $key => $data) { ?>
		<tr>
			<td><?php echo $key ?></td>
			<td><span class="expand" title="<?php echo $data['name'] ?>"><?php echo $data['short'] ?></span></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
		</tr>
		<?php } ?>
	</table>
</div>
<div style="clear:both"></div>
</div>
