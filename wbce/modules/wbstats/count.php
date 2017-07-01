<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.1.11
 * @lastmodified    June 29, 2017 
 *
 */

// Add to the top of your template (within the <?php)
// include ( WB_PATH.'/modules/wbstats/count.php');

// Add to the /config.php, just before the initialize line
// $referer = $_SERVER['HTTP_REFERER'];

require_once ( 'class.count.php');
new counter();
?>