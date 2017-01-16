<?php

/*
convert.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.1
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2017 Martin Hecht (mrbaseman)
 * @link            https://github.com/WebsiteBaker-modules/outpufilter_dashboard
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

/*
    This file allows to convert an inline filter into a plugin filter.
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

global $LANG;
$convert_ok = FALSE;
$plugin_dir = dirname(__FILE__).'/plugins/';

$text_failed = $LANG['MOD_OPF']['TXT_CONVERT_FAILED_PLUGIN'];

// get filter-data
if(!$filter = opf_get_data($id)) {
    $convert_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NO_FILTER']);
    return(FALSE);
}

if($filter['plugin']=='' && $filter['userfunc']==0) {
    $convert_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NO_EXPORT']);
    return(FALSE);
}

$plugin_name = opf_create_dirname($filter['name']);


$filter['desc'] = unserialize($filter['desc']);
$filter['modules'] = unserialize($filter['modules']);
$filter['additional_values'] = unserialize($filter['additional_values']);
$filter['additional_fields'] = unserialize($filter['additional_fields']);
$filter['additional_fields_languages'] = unserialize($filter['additional_fields_languages']);
$filter['helppath'] = unserialize($filter['helppath']);
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

    $filter = str_replace(OPF_PLUGINS_PATH.$filter['plugin'], '{OPF:PLUGIN_PATH}', $filter);
    $filter = str_replace(WB_PATH, '{SYSVAR:WB_PATH}', $filter);
    $filter = str_replace(OPF_PLUGINS_URL.$filter['plugin'], '{OPF:PLUGIN_URL}', $filter);
    $filter = str_replace(WB_URL, '{SYSVAR:WB_URL}', $filter);



if($filter['plugin']!='' && !file_exists($plugin_dir.$filter['plugin'])) {
    $convert_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NO_SUCH_DIR']);
    return(FALSE);
}


if($filter['plugin']!='') {
    $filter_file=$filter['file'];
    $filter_file = str_replace('{OPF:PLUGIN_PATH}', OPF_PLUGINS_PATH.$filter['plugin'], $filter_file);
    $filter_file = str_replace('{SYSVAR:WB_PATH}', WB_PATH, $filter_file);        
    if(file_exists($filter_file)){
        $filter['func']=file_get_contents($filter_file);
        $filter['file'] = ''; 
        rm_full_dir($plugin_dir.$filter['plugin']); 
        $filter['plugin'] = '';
    } else {
        $convert_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_NO_SUCH_DIR']);
        return(FALSE);
    }
} else {

    if( file_exists($plugin_dir.$plugin_name) 
        || !opf_io_mkdir($plugin_dir.$plugin_name)
        || !is_writable($plugin_dir.$plugin_name)) {
        $convert_message = sprintf($text_failed, $LANG['MOD_OPF']['TXT_WRITE_DENIED'], $plugin_dir.$plugin_name);
        return(FALSE);
    }

    // create a plugin-filter
    $filter['plugin'] = $plugin_name;
    // get human readable dump
    $filter_func = $filter['func'];
    $filter['func'] = '';
    $filter['file'] = '{OPF:PLUGIN_PATH}/filter.php';
    $filter_dump = opf_dump_var($filter);
    // get filter-data serialised
    $filter_ser = serialize($filter);
    $filter_ser = opf_escape_string($filter_ser);
    
    $file_info = <<<EOD
<?php
\$plugin_directory   = '$plugin_name';
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
opf_register_filter($filter_dump)
// if this fails to import, try the serialized version:
else opf_register_filter('$filter_ser', TRUE);

EOD;
    $file_contents = array('plugin_info.php'=>$file_info,
                   'index.php'=>$file_index,
                   'plugin_install.php'=>$file_install,
                   'filter.php'=>$filter_func);
    foreach($file_contents as $file=>$contents) {
        if($fh = fopen($plugin_dir.$plugin_name.'/'.$file, 'wb')) {
            fputs($fh, $contents);
            fclose($fh);
        } else {
            $convert_message = sprintf($text_failed,
                $LANG['MOD_OPF']['TXT_WRITE_FAILED'], $plugin_dir.$plugin_name.'/'.$file);
            rm_full_dir($plugin_dir.$plugin_name);
            return(FALSE);
        }
    }

}
if (opf_register_filter($filter)){
    $convert_ok=TRUE;
    return(TRUE);
} else return FALSE;
