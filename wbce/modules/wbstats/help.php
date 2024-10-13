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
?>
<div class="full" style="height:auto;">
	<h3><?php echo $help['installhead'] ?></h3>
	<?php echo $help['installbody'] ?>
	<br/><pre>include (WB_PATH.'/modules/wbstats/count.php');</pre><br/>
	<?php if (!defined('ORG_REFERER') ) { ?>
	<?php if (defined('WB_VERSION') && version_compare(WB_VERSION,'2.10.0','<')) { ?>
	<br/>
	<h3><?php echo $help['refererhead'] ?></h3>
	<?php echo $help['refererbody'] ?>
	<br/><pre>$referer = $_SERVER['HTTP_REFERER'];</pre><br/>
	<?php } ?>
	<?php } ?>
	<?php if (!defined('WB_VERSION') || version_compare(WB_VERSION,'2.8.2','<')) { ?>
	<br/>
	<h3><?php echo $help['jqueryhead'] ?></h3>
	<?php echo $help['jquerybody'] ?><br/>
	<?php } ?>
	<br/>
	<h3>Dev4me</h3>
	<div style="float: right"><a href="http://www.dev4me.nl" target="_blank"><img src="<?php echo WB_URL ?>/modules/wbstats/logo.png" alt="" /></a></div>
	<?php echo $help['donate'] ?><br/>
	<br/>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="FNER9H6GSUN5L">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
	</form>
	
	
	<div style="clear:both"></div>
</div>

