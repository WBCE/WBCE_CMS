<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
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
    $value = $database->fetchValue(
        "SELECT `value` FROM `{TP}mod_jsadmin` WHERE `name` = ?",
        [$name]
    );
    return ($value !== false && $value !== null) ? $value : $default;
}

function save_setting($name, $value) {
    global $database;
    $database->upsertRow(
        '{TP}mod_jsadmin',
        ['name'  => $name],
        ['name'  => $name, 'value' => $value]
    );
}

