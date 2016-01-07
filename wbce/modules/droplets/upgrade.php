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

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$table_name = TABLE_PREFIX .'mod_droplets';
$description = 'INT NOT NULL default 0 ';
$database->field_add($table_name,'show_wysiwyg',$description.'AFTER `active`' );
$database->field_add($table_name,'admin_view',$description.'AFTER `active`' );
$database->field_add($table_name,'admin_edit',$description.'AFTER `active`' );
