<?php
/**
 *
 * @category        modules
 * @package         form
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version      	$Id: install.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/form/install.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

if(defined('WB_URL'))
{
		
	// $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_fields`");
	// $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_submissions`");
	// $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_settings`");

	// Create tables
	$mod_form = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_form_fields` ( `field_id` INT NOT NULL AUTO_INCREMENT,'
		. ' `section_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `position` INT NOT NULL DEFAULT \'0\' ,'
		. ' `title` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `type` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `required` INT NOT NULL DEFAULT \'0\' ,'
		. ' `value` TEXT NOT NULL ,'
		. ' `extra` TEXT NOT NULL ,'
		. ' PRIMARY KEY ( `field_id` ) '
		. ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	$database->query($mod_form);

	$mod_form = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_form_settings` ('
		. ' `section_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `header` TEXT NOT NULL ,'
		. ' `field_loop` TEXT NOT NULL ,'
		. ' `footer` TEXT NOT NULL ,'
		. ' `email_to` TEXT NOT NULL ,'
		. ' `email_from` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `email_fromname` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `email_subject` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `success_page` TEXT NOT NULL ,'
		. ' `success_email_to` TEXT NOT NULL ,'
		. ' `success_email_from` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `success_email_fromname` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `success_email_text` TEXT NOT NULL ,'
		. ' `success_email_subject` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `stored_submissions` INT NOT NULL DEFAULT \'0\' ,'
		. ' `max_submissions` INT NOT NULL DEFAULT \'0\' ,'
		. ' `use_captcha` INT NOT NULL DEFAULT \'0\' ,'
		. ' PRIMARY KEY ( `section_id` ) '
		. ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	$database->query($mod_form);
	
	$mod_form = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_form_submissions` ( `submission_id` INT NOT NULL AUTO_INCREMENT,'
		. ' `section_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `submitted_when` INT NOT NULL DEFAULT \'0\' ,'
		. ' `submitted_by` INT NOT NULL DEFAULT \'0\','
		. ' `body` TEXT NOT NULL,'
		. ' PRIMARY KEY ( `submission_id` ) '
		. ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	$database->query($mod_form);
		
    $mod_search = "SELECT * FROM ".TABLE_PREFIX."search  WHERE value = 'form'";
    $insert_search = $database->query($mod_search);
    if( $insert_search->numRows() == 0 )
    {
    	// Insert info into the search table
    	// Module query info
    	$field_info = array();
    	$field_info['page_id'] = 'page_id';
    	$field_info['title'] = 'page_title';
    	$field_info['link'] = 'link';
    	$field_info['description'] = 'description';
    	$field_info['modified_when'] = 'modified_when';
    	$field_info['modified_by'] = 'modified_by';
    	$field_info = serialize($field_info);
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'form', '$field_info')");
    	// Query start
    	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_form_fields, [TP]mod_form_settings, [TP]pages WHERE ";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'form')");
    	// Query body
    	$query_body_code = " [TP]pages.page_id = [TP]mod_form_settings.page_id AND [TP]mod_form_settings.header LIKE \'%[STRING]%\'
    	OR [TP]pages.page_id = [TP]mod_form_settings.page_id AND [TP]mod_form_settings.footer LIKE \'%[STRING]%\'
    	OR [TP]pages.page_id = [TP]mod_form_fields.page_id AND [TP]mod_form_fields.title LIKE \'%[STRING]%\' ";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'form')");
    	// Query end
    	$query_end_code = "";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'form')");

    	// Insert blank row (there needs to be at least on row for the search to work)
    	$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_fields (page_id,section_id) VALUES ('0','0')");
    	$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_settings (page_id,section_id) VALUES ('0','0')");

    }
}
