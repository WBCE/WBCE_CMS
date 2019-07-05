<?php

/*
upload.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.9
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2019 Martin Hecht (mrbaseman)
 * @link            https://github.com/WebsiteBaker-modules/outputfilter_dashboard
 * @link            http://forum.websitebaker.org/index.php/topic,28926.0.html
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @link            http://addons.wbce.org/pages/addons.php?do=item&item=53
 * @license         GNU General Public License, Version 3
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.4 and higher
 *
 * This file is part of OutputFilter-Dashboard, a module for Website Baker CMS.
 *
 * OutputFilter-Dashboard is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OutputFilter-Dashboard is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OutputFilter-Dashboard. If not, see <http://www.gnu.org/licenses/>.
 *
 **/

// This file will be included from tool.php


// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../index.php'));

// obtain module directory
$mod_dir = basename(dirname(__FILE__));
require(WB_PATH.'/modules/'.$mod_dir.'/info.php');

// include module.functions.php
include_once(WB_PATH . '/framework/module.functions.php');

// include the module language file depending on the backend language of the current user
if (!include(get_module_language_file($mod_dir))) return;

// load outputfilter-functions
require_once(dirname(__FILE__).'/functions.php');

// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) die(header('Location: ../../index.php'));

$upload_ok = FALSE;

// check for uploaded plugin
$upload_result = opf_upload_check('filterplugin', '.zip', 'zip');
if(!is_array($upload_result)) {
    $upload_message = 'upload failed';
    return;
}
if(!$upload_result['status']) {
    $upload_message = $upload_result['result'];
    return;
}
$upload_id = $upload_result['result'];

require_once(WB_PATH.'/framework/functions.php');
if (file_exists (WB_PATH.'/include/pclzip/pclzip.lib.php'))
   require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');

$temp_dir = WB_PATH.MEDIA_DIRECTORY.'/opf_plugins/';
if(is_dir(WB_PATH.'/temp')){
    $temp_dir = WB_PATH.'/temp/opf_plugins/';
}
if(!is_dir($temp_dir)) opf_io_mkdir($temp_dir);
$temp_file = uniqid();
$temp_unzip = $temp_dir.'opf_unzip/';
$install_file = 'plugin_install.php';
$info_file = 'plugin_info.php';
$install_dir = dirname(__FILE__).'/plugins/';

$text_failed = $LANG['MOD_OPF']['TXT_FAILED_TO_UPLOAD'];

// check write permissions
if(!is_writable($install_dir)) {
    $upload_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_DIR_WRITE_FAILED']);
    return;
}

// Try to move uploaded plugin to temp
if(!opf_upload_move($upload_id, $temp_dir, $temp_file)) {
    $upload_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_UPLOAD_FAILED']);
    return;
}

// make temp dir
opf_io_mkdir($temp_unzip);

// Setup PclZip and check if zip-file contains a plugin
$archive = new PclZip($temp_dir.$temp_file);
$list = $archive->extract(PCLZIP_OPT_PATH, $temp_unzip);
if(!$list || !file_exists($temp_unzip.$info_file)) {
    $upload_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NOT_A_FILTER']);
    if (file_exists($temp_unzip.'install.php')
        && file_exists($temp_unzip.'uninstall.php')
        && file_exists($temp_unzip.'info.php')) {
        $upload_message .= sprintf($LANG['MOD_OPF']['TXT_LOOKS_LIKE_MODULE'],
            '../modules/index.php'
        );
    }
    @unlink($temp_dir.$temp_file);
    // Cleanup temp
    opf_io_rmdir($temp_unzip);
    return;
}
$plugin_directory = opf_plugin_info_read($temp_unzip.$info_file);

if(!$plugin_directory) {
    $upload_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NOT_A_FILTER']);
    @unlink($temp_dir.$temp_file);
    return;
}

// Check version
if(file_exists($install_dir.$plugin_directory)) {
    $old_version =  opf_plugin_info_read($install_dir.$plugin_directory.'/'.$info_file, 'plugin_version');
    $new_version =  opf_plugin_info_read($temp_unzip.$info_file, 'plugin_version');
    if(version_compare($old_version, $new_version, '>')) {
        $upload_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_ALREADY_INSTALLED']);
        opf_io_rmdir($temp_unzip);
        @unlink($temp_dir.$temp_file);
        return;
    }
}

// Cleanup temp
opf_io_rmdir($temp_unzip);

$plugin_dir = $install_dir.$plugin_directory.'/';
opf_io_mkdir($plugin_dir);

// unzip plugin directly to $plugin_dir
$list = $archive->extract(PCLZIP_OPT_PATH, $plugin_dir);
if(!$list) {
    $upload_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_UNZIP_FAILED']);
    @unlink($temp_dir.$temp_file);
    return;
}

// delete archive
@unlink($temp_dir.$temp_file);

// chmod new files
foreach(opf_io_filelist($plugin_dir) as $file)
    opf_io_chmod($file);

// run install-script
if(file_exists($plugin_dir.$install_file)) {
    require($plugin_dir.$install_file);
}

$upload_message = $LANG['MOD_OPF']['TXT_PLUGIN_UPLOAD_SUCCESS'];
$upload_ok = TRUE;
return;
