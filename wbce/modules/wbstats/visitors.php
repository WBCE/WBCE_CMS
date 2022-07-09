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


defined('WB_PATH') OR die(header('Location: ../index.php'));
$top = 10;
$r = $stats->getVisitors(100);
//echo '<pre>';
//print_r($r);
//die();
?>
<div class="sysmenu small">
  <a href="#" class="pop" data-sec="pages"><?php echo $WS['PAGETOP']  ?></a>
  <a href="#" class="pop" data-sec="entry"><?php echo $WS['ENTRYTOP']  ?></a>
  <a href="#" class="pop" data-sec="exit"><?php echo $WS['EXITTOP']  ?></a>
  <a href="#" class="pop" data-sec="referer"><?php echo $WS['REFTOP'] ?></a>
  <a href="#" class="pop" data-sec="keys"><?php echo $WS['KEYSTOP']  ?></a>
  <a href="#" class="pop" data-sec="lang"><?php echo $WS['LANGTOP']  ?></a>
  <a href="#" class="pop" data-sec="location"><?php echo $WS['LOCTOP']  ?></a>
  <a href="#" class="pop" data-sec="browser"><?php echo $WS['BROWSERTOP']  ?></a>
  <a href="#" class="pop" data-sec="os"><?php echo $WS['OSTOP']  ?></a>
</div>

<div class="middle h265" id="referer">
	<h3><span><?php echo $WS['TOP'].' '.$top.' - '?></span><?php echo $WS['REFTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['REFERER'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['referer']) && is_array($r['referer'])) {
			$counter = 1;
			foreach($r['referer'] as $key => $data) { 
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['short'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
    </table>
</div>
  
<div class="middle h265" id="pages">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['PAGETOP'] ?></h3>
	<table width="100%" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['PAGES'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['pages']) && is_array($r['pages'])) {
			$counter = 1;
			foreach($r['pages'] as $key => $data) { 
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['short'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['REQUESTS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
    </table>
</div>

<div style="clear:both"></div>
<div class="middle h265" id="keys">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['KEYSTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['KEYWORDS'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['keyword']) && is_array($r['keyword'])) {
			$counter = 1;
			foreach($r['keyword'] as $key => $data) { 
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['short'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
			<?php }} ?>
	</table>
</div>

<div class="middle h265" id="entry">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['ENTRYTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['PAGES'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['entry']) && is_array($r['entry'])) {
			$counter = 1;
			foreach($r['entry'] as $key => $data) {
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['short'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
	</table>
</div>

<div style="clear:both"></div>

<div class="middle h265" id="lang">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['LANGTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['LANGUAGES'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['language']) && is_array($r['language'])) {
			$counter = 1;
			foreach($r['language'] as $key => $data) { 
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['short'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
	</table>
</div>
<div class="middle h265" id="exit">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['EXITTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['PAGES'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['exit']) && is_array($r['exit'])) {
			$counter = 1;
			foreach($r['exit'] as $key => $data) {
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['short'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
	</table>
</div>
<div class="third h265" id="location">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['LOCTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['LOCATION'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['location']) && is_array($r['location'])) {
			$counter = 1;
			foreach($r['location'] as $key => $data) {
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['name'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
	</table>
</div>

<div class="third h265" id="browser">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['BROWSERTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['BROWSER'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['browser']) && is_array($r['browser'])) {
			$counter = 1;
			foreach($r['browser'] as $key => $data) {
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['name'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
	</table>
</div>

<div class="third h265" id="os">
    <h3><span><?php echo $WS['TOP'].' '.$top.' - ' ?></span><?php echo $WS['OSTOP'] ?></h3>
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td class="fbar" width="30"><strong><?php echo $WS['NUMBER'] ?></strong></td>
			<td ><strong><?php echo $WS['OS'] ?></strong></td>
			<td width="70"><strong><?php echo $WS['PERCENT'] ?></strong></td>
			<td class="tbar" width="40"><strong>##</strong></td>
		</tr>
		<?php if(isset($r['os']) && is_array($r['os'])) {
			$counter = 1;
			foreach($r['os'] as $key => $data) {
				$display = $counter++ > $top ? ' class="hidden"':'';
				?>
		<tr<?=$display?>>
			<td class="fbar"><?php echo $key ?></td>
			<td><div class="expand" title="<?php echo htmlspecialchars($data['name']) ?>"><?php echo $data['name'] ?></div></td>
			<td nowrap><div class="vbar" style="width:<?php echo $data['width'] ?>px;" title="<?php echo $data['views'] ?> <?php echo $WS['VISITORS'] ?>" >&nbsp;<?php echo $data['percent'] ?>%</div></td>
			<td nowrap><div class="tbar"><?php echo $data['views'] ?></div></td>
		</tr>
		<?php }} ?>
	</table>
</div>

<div style="clear:both"></div>
<div class="middle h265">
    <h3><?php echo $WS['PAGES_CLOUD'] ?></h3>
	<div class="cloud-container">
		<?php 
		if(isset($r['pageviews']) && is_array($r['pageviews'])) {
			$tmp = $r['pageviews'];
			$tmp = $stats->shuffle_assoc($tmp);
			$maxval = max($tmp)+1; $minfont = 10; $maxfont = 28;
			if(log($maxval)>0){
				foreach ($tmp as $key => $data) { 
					$fontsize = round((log($data) / log($maxval)) * ($maxfont - $minfont) + $minfont);
					if($data) {
						echo '<span title="'.$data.' '.$WS['VISITORS'].'" style="font-size:'.$fontsize.'px" class="expand wordcloud">'.$pages_cloud[$key].'</span> ';
					}
				} 
			}
		} 
		?>
	</div>		
</div>		
<div class="middle h265">
    <h3><?php echo $WS['SECONDS_CLOUD'] ?></h3>
	<div class="cloud-container">
		<?php 
		if(isset($r['seconds']) && is_array($r['seconds'])) {
			$tmp = $r['seconds'];
			$tmp = $stats->shuffle_assoc($tmp);
			$maxval = max($tmp)+1; $minfont = 10; $maxfont = 28;
			if(log($maxval)>0){
				foreach ($tmp as $key => $data) { 
					$fontsize = round((log($data) / log($maxval)) * ($maxfont - $minfont) + $minfont);
					if($data) {
						echo '<span title="'.$data.' '.$WS['VISITORS'].'" style="font-size:'.$fontsize.'px" class="expand wordcloud">'.$second_cloud[$key].'</span> ';
					}
				}
			} 
		} 
		?>
	</div>
</div>
