<?php

/*
tool_edit_css.php
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


// This file will be included from tool.php

// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));
// Authorization: check if user is allowed to use Admin-Tools
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

// get content of csspath
$sCssCode = '';
if(file_exists($csspath) && is_readable($csspath)) {
    $sCssCode = file_get_contents($csspath);
}

$aToTwig = array(
    'tpl_css'            => opf_quotes($sCssCode),
    'tpl_id'             => opf_quotes($id),
    'tpl_csspath'        => opf_quotes($csspath),
    'tpl_cancel_onclick' => opf_quotes("javascript: window.location = '$ToolUrl'"),
    'tpl_save_url'       => opf_quotes("$ToolUrl&amp;id=$id&amp;css_save=1"),

);

$oTemplate = $oTwig->load('tool_edit_css.twig');
$oTemplate->display($aToTwig);
