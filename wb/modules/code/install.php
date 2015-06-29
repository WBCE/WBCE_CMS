<?php
/**
 *
 * @category        modules
 * @package         code
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version      	$Id: install.php 1374 2011-01-10 12:21:47Z Luisehahne $
 * @filesource		$HeadURL: http://svn.websitebaker2.org/branches/2.8.x/wb/modules/code/install.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10. Jan 2011) $
 *
 */

/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

if(defined('WB_URL'))
{

	// Create table
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_code`");
	$mod_code = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_code` ('
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `content` TEXT NOT NULL,'
		. ' PRIMARY KEY ( `section_id` )'
		. ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	$database->query($mod_code);

    $mod_search = "SELECT * FROM ".TABLE_PREFIX."search  WHERE value = 'code'";
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
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'code', '$field_info')");
    	// Query start
    	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_code, [TP]pages WHERE ";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'code')");
    	// Query body
    	$query_body_code = " [TP]pages.page_id = [TP]mod_code.page_id AND [TP]mod_code.content [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'code')");
    	// Query end
    	$query_end_code = "";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'code')");

    	// Insert blank row (there needs to be at least on row for the search to work)
    	$database->query("INSERT INTO ".TABLE_PREFIX."mod_code (page_id,section_id) VALUES ('0','0')");

    }
}
