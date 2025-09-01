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
 * @version         0.2.5.6
 * @lastmodified    September 1, 2025
 *
 */

defined('WB_PATH') OR die(header('Location: ../index.php?foutje'));
if(isset($_POST['ips'])) {
	$tmp = $_POST['ips'];
	$tmp = str_replace(array(" ",",","|","\r\n"), '|', $tmp);
	$ip = explode("|" , $tmp);
	$ip = array_unique($ip);
	$stats->setIgnores($ip);
}

$iplist = $stats->getIgnores();
$myip = $stats->_getRealUserIp();

$testlist = '|'.implode("|",$iplist).'|';
$usecopy = strpos($testlist,'|'.$myip.'|') !== false ? 'isthere':'copyip';
?>
<div class="third" id="ignore" style="padding: 15px">
	<h3><?=$WS['IGNORES']?>:</h3>
	<form method="post">
		<input type="hidden" name="tool" value="wbstats">
		<p><textarea style="width:100%;height:200px;border:1px solid #aaa;padding:10px;background:#fff;" name="ips" id="ips"><?php echo implode("\n",$iplist) ?></textarea></p>
		<p><input type="submit" class="btn" value="<?php echo $TEXT['SAVE']; ?>" /></p>
	</form>
	<b><?=$WS['MYIP']?>: <a href="#" class="<?=$usecopy?>" data-ip="<?=$myip?>"><u><?=$myip?></u></a></b><br>
</div>
<!--
<div class="third" id="ignore" style="padding: 15px">
	<h3>Instellingen:</h3>
	<form method="post">
		<p>Bewaar history (7,30,60,90,365 dagen)</p>
		
		<p><input type="submit" class="btn" value="<?php echo $TEXT['SAVE']; ?>" /></p>

	</form>
</div>
-->
