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

 
defined('WB_PATH') OR die(header('Location: ../index.php'));
$c = $stats->getCampaigns();


?>
<?php 
foreach($c as $campaign => $data) {
	foreach ($data as $content => $val) {
	  if(stripos($content,"Not identified") === false) {
		print ( '<div class="full">');
		print ( '<h3>'.$campaign.' -> '.$content.'</h3>');
		
		print ( '<table class="res" width="100%" border="0" cellpadding="5" cellspacing="0">' );
		print ( '<tr>');	
		print ( '<th style="width:60px;">Source</td>');	
		print ( '<th style="width:190px;">Medium</td>');	
		print ( '<th>Content</td>');	
		print ( '<th style="text-align:center;width:110px">First date</td>');	
		print ( '<th style="text-align:center;width:110px">Last date</td>');	
		print ( '<th style="text-align:center;width:50px">Visits</td>');	
		print ( '<th style="text-align:center;width:50px">Bounces</td>');	
		print ( '<th style="text-align:center;width:50px">Pages</td>');	
		print ( '<th style="text-align:center;width:50px">Avg</td>');	
		print ( '<tr>');
		foreach ($val as $medium => $detail) {
			print ( '<tr>');	
			print ( '<td>'.$detail['source'].'</td>');	
			print ( '<td>'.$medium.'</td>');	
			print ( '<td style="white-space:nowrap;">'.$content.'</td>');	
			print ( '<td style="text-align:center;">'.fdate($detail['first']).'</td>');	
			print ( '<td style="text-align:center;">'.fdate($detail['last']).'</td>');	
			print ( '<td style="text-align:center;">'.$detail['totalcount'].'</td>');	
			print ( '<td style="text-align:center;">'.$detail['bounces'].' <small>('.$detail['bounce_perc'].'%)</small></td>');	
			print ( '<td style="text-align:center;">'.$detail['pages'].'</td>');	
			print ( '<td style="text-align:center;">'.$detail['pages_visit'].'</td>');	
			print ( '<tr>');	
		}
		print ( '</table>' );
		print ( '</div>' );
	  }
	}
}

function fdate($d) {
	$d = substr($d,0,4).'-'.substr($d,4,2).'-'.substr($d,6,2);
	
	return $d;
}
