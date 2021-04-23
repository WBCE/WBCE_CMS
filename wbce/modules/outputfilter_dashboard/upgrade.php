<?php

/*
upgrade.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.14
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2021 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            http://forum.websitebaker.org/index.php/topic,28926.0.html
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @link            http://addons.wbce.org/pages/addons.php?do=item&item=53
 * @license         GNU General Public License, Version 3
 * @platform        WebsiteBaker 2.8.x or WBCE
 * @requirements    PHP 5.4 and higher
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

// obtain module directory
$mod_dir = basename(dirname(__FILE__));
require(WB_PATH.'/modules/'.$mod_dir.'/info.php');

// include module.functions.php
include_once(WB_PATH . '/framework/module.functions.php');

// load outputfilter-functions
require_once(dirname(__FILE__)."/functions.php");

if(is_dir(WB_PATH.'/temp')){
    opf_io_mkdir(WB_PATH.'/temp/opf_plugins');
} else {
    opf_io_mkdir(WB_PATH.MEDIA_DIRECTORY.'/opf_plugins');
}

opf_io_unlink($mod_dir.'/debug_config.php');
opf_io_unlink($mod_dir.'/config_init.php');
opf_io_unlink($mod_dir.'/precheck.php');

if(file_exists(WB_PATH.'/modules/practical_module_functions/pmf.php')){
    // load Practical Module Functions
    include_once(WB_PATH.'/modules/practical_module_functions/pmf.php');
    $opf = pmf_init(0, basename(dirname(__FILE__)));

    // unregister this module since we do not use pmf anymore
    pmf_mod_unregister($opf, basename(dirname(__FILE__)));

}

opf_db_run_query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_outputfilter_dashboard_settings`");


opf_io_rmdir(dirname(__FILE__).'/naturaldocs_txt');


// run install scripts of plugin filters  - they should start upgrade if already installed
foreach( preg_grep('/\/plugin_install.php/', opf_io_filelist(dirname(__FILE__).'/plugins/')) as $installer){
    require($installer);
}



// Only block this if WBCE CMS installer is running, if this is an Upgrade or
// Module install, we need this. But the installer registers the filter-modules later.
if(!defined('WB_INSTALLER')){
    // run install scripts of module filters - they should start upgrade if already installed
    foreach( preg_grep('/\/install.php/', opf_io_filelist(WB_PATH.'/modules')) as $installer){
        if(strpos($installer,'outputfilter_dashboard')===FALSE){
            $contents = file_get_contents($installer);
            if(preg_match('/opf_register_filter/',$contents)){
                require($installer);
            }
        }
    }
}

// convert database entries to use generic path and url placeholders internally
$filters = opf_select_filters();
if(is_array($filters)) {
    foreach($filters as $filter) {
        $filter['modules'] = unserialize($filter['modules']);
        $filter['desc'] = unserialize($filter['desc']);
        $filter['helppath'] = unserialize($filter['helppath']);
        $filter['pages_parent'] = unserialize($filter['pages_parent']);
        $filter['pages'] = unserialize($filter['pages']);
        $filter['additional_values'] = unserialize($filter['additional_values']);
        $filter['additional_fields'] = unserialize($filter['additional_fields']);
        $filter['additional_fields_languages'] = unserialize($filter['additional_fields_languages']);
        $filter = opf_insert_sysvar($filter);
        $filter['helppath'] = opf_insert_sysvar($filter['helppath'],$filter['plugin']);
        $filter['modules'] = serialize($filter['modules']);
        $filter['desc'] = serialize($filter['desc']);
        $filter['helppath'] = serialize($filter['helppath']);
        $filter['pages_parent'] = serialize($filter['pages_parent']);
        $filter['pages'] = serialize($filter['pages']);
        $filter['additional_values'] = serialize($filter['additional_values']);
        $filter['additional_fields'] = serialize($filter['additional_fields']);
        $filter['additional_fields_languages'] = serialize($filter['additional_fields_languages']);
        $sSql = "UPDATE `".TABLE_PREFIX."mod_outputfilter_dashboard` SET "
              . "`userfunc`='".addslashes($filter['userfunc'])."', "
              . "`plugin`='".addslashes($filter['plugin'])."', "
              . "`file`='".addslashes($filter['file'])."', "
              . "`func`='".addslashes($filter['func'])."', "
              . "`desc`='".addslashes($filter['desc'])."', "
              . "`configurl`='".addslashes($filter['configurl'])."', "
              . "`csspath`='".addslashes($filter['csspath'])."', "
              . "`helppath`='".addslashes($filter['helppath'])."', "
              . "`additional_values`='".addslashes($filter['additional_values'])."', "
              . "`additional_fields`='".addslashes($filter['additional_fields'])."', "
              . "`additional_fields_languages`='".addslashes($filter['additional_fields_languages'])."'"
              . "WHERE `id`=".$filter['id'];
        if(!opf_db_run_query($sSql))
         echo "SQL statement failed: $sSql";
    }
}


