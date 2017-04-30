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
$database->query("CREATE TABLE `$table_day` (
  	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`user` int(10) NOT NULL default '0',
	`view` int(10) NOT NULL default '0',
	`bots` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	INDEX `day` (`day`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_ips`");
$database->query("CREATE TABLE `$table_ips` (
	`id` int(11) NOT NULL auto_increment,
	`ip` varchar(15) NOT NULL default '',
	`session` varchar(64) NOT NULL default '',
	`time` int(20) NOT NULL default '0',
	`online` int(20) NOT NULL default '0',
	`page` varchar(255) NOT NULL default '',
	`loggedin` int(1) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_pages`");
$database->query("CREATE TABLE `$table_pages` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`page` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_ref`");
$database->query("CREATE TABLE `$table_ref` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_key`");
$database->query("CREATE TABLE `$table_key` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`keyword` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);
$database->query("DROP TABLE IF EXISTS `$table_lang`");
$database->query("CREATE TABLE `$table_lang` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`language` varchar(2) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);

?>