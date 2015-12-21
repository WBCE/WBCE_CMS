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

$table_day   = TABLE_PREFIX .'mod_wbstats_day';
$table_ips   = TABLE_PREFIX .'mod_wbstats_ips';
$table_pages = TABLE_PREFIX .'mod_wbstats_pages';
$table_ref   = TABLE_PREFIX .'mod_wbstats_ref';
$table_key   = TABLE_PREFIX .'mod_wbstats_keywords';
$table_lang  = TABLE_PREFIX .'mod_wbstats_lang';

$database->query("DROP TABLE IF EXISTS `$table_day`");
$database->query("DROP TABLE IF EXISTS `$table_ips`");
$database->query("DROP TABLE IF EXISTS `$table_pages`");
$database->query("DROP TABLE IF EXISTS `$table_ref`");
$database->query("DROP TABLE IF EXISTS `$table_key`");
$database->query("DROP TABLE IF EXISTS `$table_lang`");


?>