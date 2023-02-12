<?php

/*
export.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.6.3
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010-2023 Christian M. Stefan (Stefek), 2016-2023 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            https://addons.wbce.org/pages/addons.php?do=item&item=53
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU General Public License, Version 3
 * @platform        WBCE 1.x
 * @requirements    PHP 7.4 - 8.2
 *
 * This file is part of OutputFilter-Dashboard, a module for WBCE and Website Baker CMS.
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

/*
    This file allows to export a single Filter to a installable zip-archive.
*/

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


// This file will be included from tool.php

// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) die(header('Location: ../../index.php'));

require_once(WB_PATH.'/framework/functions.php');
if (file_exists (WB_PATH.'/include/pclzip/pclzip.lib.php'))
   require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');


global $LANG;
$export_ok = FALSE;
$temp_dir = WB_PATH.MEDIA_DIRECTORY.'/opf_plugins/';
$temp_link = WB_URL.MEDIA_DIRECTORY.'/opf_plugins/';
if(is_dir(WB_PATH.'/temp')){
    $temp_dir = WB_PATH.'/temp/opf_plugins/';
    $temp_link = WB_URL.'/temp/opf_plugins/';
}
if(!is_dir($temp_dir)) opf_io_mkdir($temp_dir);
$plugin_dir = dirname(__FILE__).'/plugins/';
$temp_name = uniqid(mt_rand(1000,9999));

$text_failed = $LANG['MOD_OPF']['TXT_EXPORT_FAILED_PLUGIN'];

// check write permissions
if(!is_writable($temp_dir)) {
    $export_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_WRITE_DENIED'], $temp_dir);
    return(FALSE);
}

// get filter-data
if(!$filter = opf_get_data($id)) {
    $export_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NO_FILTER']);
    return(FALSE);
}
$filter['desc'] = unserialize($filter['desc']);
$filter['modules'] = unserialize($filter['modules']);
$filter['additional_values'] = unserialize($filter['additional_values']);
$filter['additional_fields'] = unserialize($filter['additional_fields']);
$filter['additional_fields_languages'] = unserialize($filter['additional_fields_languages']);
// update additional_fields: copy data from additional_values to additional_fields
if(is_array($filter['additional_fields'])) {
    foreach($filter['additional_fields'] as $i=>$f) {
        $filter['additional_fields'][$i]['value']
            = $filter['additional_values'][$filter['additional_fields'][$i]['variable']];
    }
}
unset($filter['additional_values'],
      $filter['id'],
      $filter['position'],
      $filter['pages'],
      $filter['pages_parent']);

$filter = opf_insert_sysvar($filter);

if($filter['plugin']=='' && $filter['userfunc']==0) {
    $export_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NO_EXPORT']);
    return(FALSE);
}
if($filter['plugin']!='' && !file_exists($plugin_dir.$filter['plugin'])) {
    $export_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NO_SUCH_DIR']);
    return(FALSE);
}

// get name for zip-archive
if($filter['plugin']!='')
    $temp_file = 'opf_export_'.$filter['plugin'].'.zip';
else
    $temp_file = 'opf_export_'.$temp_name.'.zip';

opf_io_mkdir($temp_dir.$temp_name);

// Setup PclZip
$archive = new PclZip($temp_dir.$temp_file);

// plugin-filter
if($filter['plugin']!='') {
    // get human readable dump
    $filter_dump = opf_revert_type_consts(opf_dump_var($filter),$OPF_TYPE_ASSIGNMENTS);
    // get filter-data serialised
    $filter_ser = serialize($filter);
    $filter_ser = opf_escape_string($filter_ser);

    $file_install = <<<EOD
<?php
if(!defined('WB_PATH')) die(header('Location: index.php'));
// experimental feature, export human-readable:
opf_register_filter($filter_dump)
// if this fails to import, try the serialized version:
or opf_register_filter('$filter_ser', TRUE);

EOD;
    if($fh = fopen($temp_dir.$temp_name.'/plugin_install.php', 'wb')) {
        fputs($fh, $file_install);
        fclose($fh);
    } else {
        $export_message = $export_message = sprintf($text_failed,
           $LANG['MOD_OPF']['TXT_WRITE_FAILED'], $temp_dir.$temp_name.'/plugin_install.php');
        rm_full_dir($temp_dir.$temp_name);
        return(FALSE);
    }

    if(!$archive->create($plugin_dir.$filter['plugin'],
                 PCLZIP_OPT_REMOVE_PATH, $plugin_dir.$filter['plugin'])) {
        $export_message = sprintf($text_failed, $archive->errorInfo(true));
        rm_full_dir($temp_dir.$temp_name);
        return(FALSE);
    }
    if(!$archive->delete(PCLZIP_OPT_BY_NAME, 'plugin_install.php')) {
        $export_message = sprintf($text_failed, $archive->errorInfo(true));
        rm_full_dir($temp_dir.$temp_name);
        return(FALSE);
    }
    if(!$archive->add($temp_dir.$temp_name.'/plugin_install.php',
                      PCLZIP_OPT_REMOVE_PATH, $temp_dir.$temp_name)) {
        $export_message = sprintf($text_failed, $archive->errorInfo(true));
        rm_full_dir($temp_dir.$temp_name);
        return(FALSE);
    }


} else {


// inline-filter
    // create a plugin-filter

    $filter['plugin'] = $temp_name;
    $filter_func = $filter['func'];
    $filter['func'] = '';
    $filter['file'] = '{OPF:PLUGIN_PATH}/filter.php';
    // get human readable dump
    $filter_dump = opf_revert_type_consts(opf_dump_var($filter),$OPF_TYPE_ASSIGNMENTS);
    // get filter-data serialised
    $filter_ser = serialize($filter);
    $filter_ser = opf_escape_string($filter_ser);

    $file_info = <<<EOD
<?php
\$plugin_directory   = '$temp_name';
\$plugin_name        = '{$filter['name']}';
\$plugin_version     = '';
\$plugin_status      = '';
\$plugin_platform    = '';
\$plugin_author      = '';
\$plugin_license     = '';
\$plugin_description = '';
EOD;
    $file_index = <<<EOD
<?php
@header('HTTP/1.1 301 Moved Permanently',TRUE,301);
exit(header('Location: ../index.php'));
EOD;
    $file_install = <<<EOD
<?php
if(!defined('WB_PATH')) die(header('Location: index.php'));
// experimental feature, export human-readable:
opf_register_filter($filter_dump)
// if this fails to import, try the serialized version:
or opf_register_filter('$filter_ser', TRUE);

EOD;
    $file_contents = array('plugin_info.php'=>$file_info,
                   'index.php'=>$file_index,
                   'plugin_install.php'=>$file_install,
                   'filter.php'=>$filter_func);
    foreach($file_contents as $file=>$contents) {
        if($fh = fopen($temp_dir.$temp_name.'/'.$file, 'wb')) {
            fputs($fh, $contents);
            fclose($fh);
        } else {
            $export_message = sprintf($text_failed,
                $LANG['MOD_OPF']['TXT_WRITE_FAILED'], $temp_dir.$temp_name.'/'.$file);
            rm_full_dir($temp_dir.$temp_name);
            return(FALSE);
        }
    }

    // zip it
    if(!$archive->create($temp_dir.$temp_name,
                     PCLZIP_OPT_REMOVE_PATH, $temp_dir.$temp_name)) {
    $export_message = sprintf($text_failed, $archive->errorInfo(true));
        rm_full_dir($temp_dir.$temp_name);
        return(FALSE);
  }
}

rm_full_dir($temp_dir.$temp_name);

$link = $temp_link.$temp_file;
$export_message = $LANG['MOD_OPF']['TXT_PLUGIN_EXPORTED'];
$export_ok = TRUE;
return($link);

// the created zip still remains in media/opf_plugins/ and should be deleted manually

