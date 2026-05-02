<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

if (defined('WB_URL')) {
    // Create table
    // $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_wysiwyg`");
    $mod_wysiwyg = 'CREATE TABLE IF NOT EXISTS `' . TABLE_PREFIX . 'mod_wysiwyg` ( '
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `content` LONGTEXT NOT NULL ,'
		. ' `text` LONGTEXT NOT NULL ,'
		. ' PRIMARY KEY ( `section_id` ) '
		. ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
    $database->query($mod_wysiwyg);
}