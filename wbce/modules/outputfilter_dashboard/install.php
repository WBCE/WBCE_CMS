<?php

/*
install.php
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

// create media-dir
if(is_dir(WB_PATH.'/temp')){
    opf_io_mkdir(WB_PATH.'/temp/opf_plugins');
} else {
    opf_io_mkdir(WB_PATH.MEDIA_DIRECTORY.'/opf_plugins');
}
opf_db_run_query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_outputfilter_dashboard`");
opf_db_run_query("CREATE TABLE `".TABLE_PREFIX."mod_outputfilter_dashboard` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `userfunc` TINYINT NOT NULL DEFAULT '0',
    `position` INT NOT NULL DEFAULT '0',
    `active` TINYINT NOT NULL DEFAULT '1',
    `allowedit` TINYINT NOT NULL DEFAULT '0',
    `allowedittarget` TINYINT NOT NULL DEFAULT '0',
    `name` VARCHAR(249) NOT NULL,
    `func` TEXT NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `file` VARCHAR(255) NOT NULL,
    `csspath` VARCHAR(255) NOT NULL,
    `funcname` VARCHAR(255) NOT NULL,
    `configurl` VARCHAR( 255 ) NOT NULL,
    `plugin` VARCHAR( 255 ) NOT NULL,
    `helppath` TEXT NOT NULL,
    `modules` TEXT NOT NULL,
    `desc` LONGTEXT NOT NULL,
    `pages` TEXT NOT NULL,
    `pages_parent` TEXT NOT NULL,
    `additional_values` LONGTEXT NOT NULL,
    `additional_fields` TEXT NOT NULL,
    `additional_fields_languages` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`name`),
    INDEX (`type`)
) ENGINE = MYISAM");

opf_io_rmdir(dirname(__FILE__).'/naturaldocs_txt');

// run install scripts of plugin filters  - they should start upgrade if already installed
foreach( preg_grep('/\/plugin_install.php/', opf_io_filelist(dirname(__FILE__).'/plugins/')) as $installer){
    require($installer);
}

// Only block this if WBCE CMS installer is running, if this is an Upgrade or
// Module install, we need this. But the installer registers the filter-modules later.
if(!defined('WB_INSTALLER')){
    // run install scripts of already present module filters
    foreach( preg_grep('/\/install.php/', opf_io_filelist(WB_PATH.'/modules')) as $installer){
        if(strpos($installer,'outputfilter_dashboard')===FALSE){
            $contents = file_get_contents($installer);
            if(preg_match('/opf_register_filter/',$contents)){
                require($installer);
            }
        }
    }
}
