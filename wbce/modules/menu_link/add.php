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

// insert a new row into `{TP}mod_menu_link` DB table
$database->insertRow("{TP}mod_menu_link", array(
		'page_id' => $page_id,
		'section_id' => $section_id,
		'target_page_id' => '0',
		'anchor' => '0',
		'extern' => ''
	)
);

