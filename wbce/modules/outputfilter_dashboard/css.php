<?php

/*
css.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.6
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2018 Martin Hecht (mrbaseman)
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

// get content of csspath
$css = '';
if(file_exists($csspath) && is_readable($csspath)) // csspath has to be local, file_exists() and is_readable() can't handle remote files
    $css = file_get_contents($csspath);

// template
$tpl = new Template(WB_PATH.'/modules/outputfilter_dashboard');
$tpl->set_file('page', "templates/css.htt");


// fill template vars
$tpl->set_var(
array_merge($LANG['MOD_OPF'],
    array(
    'tpl_save_url' => opf_quotes("$ToolUrl&amp;id=$id&amp;css_save=1"),
    'FTAN' => $ftan,
    'tpl_id' => opf_quotes($id),
    'tpl_csspath' => opf_quotes($csspath),
    'tpl_css' => opf_quotes($css),
    'tpl_cancel_onclick' => opf_quotes("javascript: window.location = '$ToolUrl'"),
    'WB_URL' => WB_URL,
    'MOD_URL' => WB_URL.'/modules/'.$module_directory,
    'IMAGE_URL' => WB_URL.'/modules/'.$module_directory.'/templates/images'

)));

// output template
$tpl->set_unknowns('keep');
$tpl->set_block('page', 'main_block', 'main');
$tpl->parse('main', 'main_block', false);
print opf_filter_Comments($tpl->parse('output', 'main', false));

