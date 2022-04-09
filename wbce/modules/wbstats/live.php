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
 
defined('WB_PATH') OR die(header('Location: ../index.php'));

$stats = new stats();
$r = $stats->getLive();
?>
<script type="text/javascript">
function getLive() {
	var url = "<?= WB_URL ?>/modules/wbstats/ajax.php";
	$.getJSON(url , function(data) {
		var tbl_body = "";
		var odd_even = false;
		$.each(data, function() {
			var tbl_row = "";
			$.each(this, function(k , v) {
				tbl_row += "<td class=\""+k+"\">"+v+"</td>";
			});
			tbl_body += "<tr class=\""+( odd_even ? "odd" : "even")+"\">"+tbl_row+"</tr>";
			odd_even = !odd_even;               
		});
		$(".res").html(tbl_body);
	});
}
var refreshId = setInterval(getLive, 5000);
getLive();
</script>
<div id="live"><table class="res"></table></div>

