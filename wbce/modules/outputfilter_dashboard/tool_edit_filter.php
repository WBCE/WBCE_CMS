<?php

/*
tool_edit_filter.php
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

// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));

// Authorization: check if user is allowed to use Admin-Tools
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

if (!$filter = $database->get_array(
        "SELECT * FROM `{TP_OPFD}` WHERE `id`=". $id
        )[0]
    ){
    return;
}

$aToTwig = [];
$filter = opf_replace_sysvar($filter);

$type = (array_key_exists($filter['type'], opf_get_types())
        ? $filter['type']
        : key($types));
$aToTwig['filter_type_options'] = opf_get_types_select($type);

// checkbox-trees: contains the whole HTML-output.
$pages_parent    = unserialize($filter['pages_parent']);
$pages           = unserialize($filter['pages']);
$modules         = unserialize($filter['modules']);
$allowedit       = $filter['allowedit']       == 1 ? 1 : 0;
$allowedittarget = $filter['allowedittarget'] == 1 ? 1 : 0;

$aModuleTree  = '';
$aPageTree = '';
opf_preload_filter_definitions();
if ($allowedit == 0 && $allowedittarget == 0) {
    // We can't use disabled or readonly with checkbox-tree, so just list the modules
    $aModuleTree = opf_make_modules_checktree($modules, 'flat');
    // pages_parent
    $aPageTree = opf_make_pages_parent_checktree($pages_parent, $pages, 'flat');
    // pages
} else {
    $aModuleTree = opf_make_modules_checktree($modules, 'tree');
    $aPageTree  = opf_make_pages_parent_checktree($pages_parent, $pages, 'tree');
}
$aToTwig['module_tree'] = $aModuleTree;
$aToTwig['page_tree'] = $aPageTree;

// collect template vars
$sTmpReadOnly = str_replace(['/', WB_PATH, '\\'], ['\\', '[WB_PATH]', '/'], $filter['file']);
$aToTwig['file_loc_readonly'] = str_replace('[WB_PATH]/modules/outputfilter_dashboard/plugins','[OPF_PLUGINS]', $sTmpReadOnly);

$userfunc = $filter['userfunc'] == 1 ? 1 : 0;
$aToTwig += [
    'edit_filter'  => true, // tell template we come from tool.edit_filter.php
    'filter_id'    => $id,
    'filter_name'  => $filter['name'],
    'filter_desc'  => opf_fetch_entry_language(unserialize($filter['desc'])),
    'plugin'       => $filter['plugin'],
    'file'         => $filter['file'],
    'func'         => quote_chars($filter['func']),
    'funcname'     => $filter['funcname'],
    'helppath'     => opf_get_helppath($id),

    // only inline-filters and filters with 'allowedit' are editable
    'disabled_readonly' => ($userfunc||$allowedit)? '' :' readonly="readonly"',
    'active_checked'    => (opf_is_active($filter['name'])) ? ' checked' : '',
    'filter_file_loc'   => opf_insert_sysvar($filter['file'], $filter['plugin']),
    'filter_config_url' => opf_quotes($filter['configurl']),
    'readOnly'          => ($userfunc ? false : true),
];

$aToTwig['extra_fields'] = opf_get_extrafields_array($id);
$oTemplate = $oTwig->load('tool_add_edit_filter.twig');
$oTemplate->display($aToTwig);
