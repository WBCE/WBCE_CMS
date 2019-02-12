<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @category   modules
 * @package    JsAdmin
 * @author     WebsiteBaker Project, modified by Swen Uth for Website Baker 2.7
 * @author     WBCE Project, modified by Christian M. Stefan to implement Insert methods
 * @copyright  Ryan Djurovich (2004-2009)
 * @copyright  WebsiteBaker Org. e.V. (2009-2015)
 * @copyright  WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */
 
// prevent this file from being accessed directly
defined('WB_PATH')or die('Access denied');

function get_setting($name, $default = '') {
	global $database;
	$rs = $database->query("SELECT `value` FROM `{TP}mod_jsadmin` WHERE `name` = '".$name."'");
	return ($row = $rs->fetchRow()) ? $row['value'] : $default;
}

function save_setting($name, $value) {
	global $database;
	$prev_value = get_setting($name, false);
	if($prev_value === false) {
		$database->query("INSERT INTO `{TP}mod_jsadmin` (name,value) VALUES ('$name','$value')");
	} else {
		$database->query("UPDATE `{TP}mod_jsadmin` SET value = '$value' WHERE name = '$name'");
	}
}

// the follwing variables to use and check existing the YUI
$YUI_PATH = '/include/yui';
$js_yui_min = "-min";  // option for smaller code so faster
$js_yui_scripts = array();
$js_yui_scripts[] = $YUI_PATH.'/yahoo/yahoo'.$js_yui_min.'.js';
$js_yui_scripts[] = $YUI_PATH.'/event/event'.$js_yui_min.'.js';
$js_yui_scripts[] = $YUI_PATH.'/dom/dom'.$js_yui_min.'.js';
$js_yui_scripts[] = $YUI_PATH.'/connection/connection'.$js_yui_min.'.js';
$js_yui_scripts[] = $YUI_PATH.'/dragdrop/dragdrop'.$js_yui_min.'.js';