<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */
// Prevent this file from being access directly
defined('WB_PATH') or die('Cannot access this file directly');

$sTableName = "{TP}mod_menu_link";
$database->query("DROP TABLE IF EXISTS `$sTableName`");

$sSqlTable = "
	CREATE TABLE IF NOT EXISTS `$sTableName` (
		`section_id`     INT(11) NOT NULL       DEFAULT '0',
		`page_id`        INT(11) NOT NULL       DEFAULT '0',
		`target_page_id` INT(11) NOT            NULL DEFAULT '0',
		`redirect_type`  INT(3)  NOT NULL       DEFAULT '301',
		`anchor`         VARCHAR(255) NOT NULL  DEFAULT '0' ,
		`extern`         VARCHAR(255) NOT NULL  DEFAULT '' ,
		PRIMARY KEY (`section_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
";
$database->query($sSqlTable);