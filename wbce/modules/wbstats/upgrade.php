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


// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: index.php')); 

$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_ips` MODIFY `ip` VARCHAR(32)");

