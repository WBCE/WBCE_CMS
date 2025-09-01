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


defined('WB_PATH') OR die(header('Location: ../index.php'));

$table_day   = TABLE_PREFIX .'mod_wbstats_day';
$table_ips   = TABLE_PREFIX .'mod_wbstats_ips';
$table_pages = TABLE_PREFIX .'mod_wbstats_pages';
$table_ref   = TABLE_PREFIX .'mod_wbstats_ref';
$table_key   = TABLE_PREFIX .'mod_wbstats_keywords';
$table_lang  = TABLE_PREFIX .'mod_wbstats_lang';
$table_brw   = TABLE_PREFIX .'mod_wbstats_browser';
$table_hist  = TABLE_PREFIX .'mod_wbstats_hist';
$table_cfg 	 = TABLE_PREFIX .'mod_wbstats_cfg';


$database->query("DROP TABLE IF EXISTS `$table_day`");
$database->query("DROP TABLE IF EXISTS `$table_ips`");
$database->query("DROP TABLE IF EXISTS `$table_pages`");
$database->query("DROP TABLE IF EXISTS `$table_ref`");
$database->query("DROP TABLE IF EXISTS `$table_key`");
$database->query("DROP TABLE IF EXISTS `$table_lang`");
$database->query("DROP TABLE IF EXISTS `$table_brw`");
$database->query("DROP TABLE IF EXISTS `$table_hist`");
$database->query("DROP TABLE IF EXISTS `$table_cfg`");


?>