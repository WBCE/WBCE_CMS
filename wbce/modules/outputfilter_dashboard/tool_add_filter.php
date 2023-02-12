<?php

/*
tool_add_filter.php
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


///////////////////////////////////////////////
// This file will be included from tool.php  //
///////////////////////////////////////////////
//
// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));

// Authorization: check if user is allowed to use Admin-Tools
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

$id = 0;
// get a short unique id to add to function name
$sUnique = substr(uniqid(), 0, -8);
$function_name = 'opff_function_name_' . $sUnique;
$func = htmlspecialchars("<?php\nfunction $function_name(&\$content, \$page_id, \$section_id, \$module, \$wb) {\n  // filter code\n  \n  \n  return(TRUE);\n}\n?>");

// fill template vars
$aToTwig = array(
    'add_filter'        => true, // tell template we come from tool.add_filter.php
    'filter_id'         => opf_quotes($id),
    'filter_name'       => opf_quotes($L['TXT_INSERT_NAME']),
    'filter_desc'       => opf_quotes($L['TXT_INSERT_DESCRIPTION']),
    'func'              => $func,
    'funcname'          => opf_quotes($function_name),
    'file'              => '',
    'helppath'          => '',
    'disabled_readonly' => '',
    'active_checked'    => ' checked',
    'filter_file_loc'   => '',
    'filter_config_url' => '',
    'readOnly'          => false,
    'extra_fields'      => [],
    'module_tree'       => opf_make_modules_checktree([], $type='tree', TRUE),
    'page_tree'         => opf_make_pages_parent_checktree([], ['all'], $type='tree'),
    'filter_type_options' => opf_get_types_select(),
);

$oTemplate = $oTwig->load('tool_add_edit_filter.twig');
$oTemplate->display($aToTwig);
