<?php
/**
 *
 * @category        modules
 * @package         wrapper
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version      	$Id: install.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/wrapper/install.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

if(defined('WB_URL')) {
	
	// Create table
	// $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_wrapper`");
	$mod_wrapper = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_wrapper` ('
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `url` TEXT NOT NULL,'
		. ' `height` INT NOT NULL DEFAULT \'0\','
		. ' PRIMARY KEY ( `section_id` ) '
		. ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	$database->query($mod_wrapper);
}
