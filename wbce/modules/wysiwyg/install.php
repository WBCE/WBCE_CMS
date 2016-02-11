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

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


if(defined('WB_URL'))
{
	// Create table
	//$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_wysiwyg`");
	$mod_wysiwyg = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_wysiwyg` ( '
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `content` LONGTEXT NOT NULL ,'
		. ' `text` LONGTEXT NOT NULL ,'
		. ' PRIMARY KEY ( `section_id` ) '
		. ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	$database->query($mod_wysiwyg);

// remove old version of search (deprecated)
//    $mod_search = "SELECT * FROM ".TABLE_PREFIX."search  WHERE value = 'wysiwyg'";
//    $insert_search = $database->query($mod_search);
//    if( $insert_search->numRows() == 0 )
//    {
//    	// Insert info into the search table
//    	// Module query info
//    	$field_info = array();
//    	$field_info['page_id'] = 'page_id';
//    	$field_info['title'] = 'page_title';
//    	$field_info['link'] = 'link';
//    	$field_info['description'] = 'description';
//    	$field_info['modified_when'] = 'modified_when';
//    	$field_info['modified_by'] = 'modified_by';
//    	$field_info = serialize($field_info);
//    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'wysiwyg', '$field_info')");
//    	// Query start
//    	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_wysiwyg, [TP]pages WHERE ";
//    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'wysiwyg')");
//    	// Query body
//    	$query_body_code = " [TP]pages.page_id = [TP]mod_wysiwyg.page_id AND [TP]mod_wysiwyg.text [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";
//    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'wysiwyg')");
//    	// Query end
//    	$query_end_code = "";
//    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'wysiwyg')");
//
//    	// Insert blank row (there needs to be at least on row for the search to work)
//    	$database->query("INSERT INTO ".TABLE_PREFIX."mod_wysiwyg (page_id,section_id) VALUES ('0','0')");
//    }
}
