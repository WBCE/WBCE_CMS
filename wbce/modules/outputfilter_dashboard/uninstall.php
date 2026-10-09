<?php

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.7.0
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, 
 *                   Christian M. Stefan  (https://www.wbEasy.de), 
 *                   Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010-2023 Christian M. Stefan, 2016-2023 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            https://addons.wbce.org/pages/addons.php?do=item&item=53
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU General Public License, Version 3
 * @platform        WBCE 1.7.x
 * @requirements    PHP 8.1
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
$mod_dir = basename(__DIR__);
require(WB_PATH.'/modules/'.$mod_dir.'/info.php');

// include module.functions.php
include_once(WB_PATH . '/framework/module.functions.php');

// include the module language file depending on the backend language of the current user
if (!include(get_module_language_file($mod_dir))) return;

// load outputfilter-functions
require_once(__DIR__.'/functions.php');

// delete media-dir
opf_io_rmdir(WB_PATH.MEDIA_DIRECTORY.'/opf_plugins');
opf_io_rmdir(WB_PATH.'/temp/opf_plugins');

global $database;
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_outputfilter_dashboard`");
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_outputfilter_dashboard_settings`");


