<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.1
 * @lastmodified    November 15, 2019
 *
 */
 
defined('WB_PATH') or die(header('Location: ../index.php'));  ?>
<script type="text/javascript" src="<?php echo WB_URL ?>/modules/wbstats/js/jquery.poshytip.js"></script>
<div id="loading" class="box" style="display:none;"><?php echo $WS['PLEASEWAIT'] ?></div>
<div id="container">
<div class="sysmenu">
  <a href="<?php echo $module_overview_link  ?>"><?php echo $WS['MENU1'] ?></a>
  <a href="<?php echo $module_visitors_link  ?>"><?php echo $WS['MENU2'] ?></a>
  <a href="<?php echo $module_history_link  ?>"><?php echo $WS['MENU3'] ?></a>
  <a style="float:right" href="<?php echo $module_help_link  ?>"><?php echo $WS['MENU4'] ?></a>
</div>