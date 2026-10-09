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

///////////////////////////////////////////////
// This file will be included from tool.php  //
///////////////////////////////////////////////

// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));

// Authorization: check if user is allowed to use Admin-Tools
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

$filter = $database->fetchRow("SELECT * FROM `{TP_OPFD}` WHERE `id` = ?", [$id]);
if (!$filter) {
    return;
}

$aToTwig = [];
$filter = opf_replace_sysvar($filter);

// A save just failed CodeVet's syntax/security check -- tool.php stashed the
// admin's unsaved edit here instead of discarding it (see its docblock and
// modules/droplets/save_droplet.php's identical pattern). Overlay it onto
// the freshly-loaded DB row so the broken code (not the last-saved-good
// version) shows up, and record the flagged line for the editor to jump to.
// Read once, then cleared immediately so it never leaks into a later,
// unrelated visit to this filter.
$errorLine = 0;
$draftKey  = 'opf_' . $id;
if (isset($_SESSION['codevet_draft'][$draftKey])) {
    $draft = $_SESSION['codevet_draft'][$draftKey];
    unset($_SESSION['codevet_draft'][$draftKey]);
    if (isset($draft['name']))     $filter['name']     = $draft['name'];
    if (isset($draft['func']))     $filter['func']      = $draft['func'];
    if (isset($draft['funcname'])) $filter['funcname'] = $draft['funcname'];
    $errorLine = max(0, (int) ($draft['line'] ?? 0));
}

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

$userfunc   = $filter['userfunc']   == 1 ? 1 : 0;
$isEditable = ($userfunc || $allowedit);

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
    'disabled_readonly' => $isEditable ? '' : ' readonly="readonly"',
    'active_checked'    => (opf_is_active($filter['name'])) ? ' checked' : '',
    'filter_file_loc'   => opf_insert_sysvar($filter['file'], $filter['plugin']),
    'filter_config_url' => opf_quotes($filter['configurl']),
    'readOnly'          => !$isEditable,

    // AJAX save — only for editable filters with an existing DB record
    'idKey'    => $isEditable ? $admin->getIDKEY($id) : '',
    'ajax_url' => $isEditable ? WB_URL . '/modules/outputfilter_dashboard/ajax_save_filter.php' : '',

    // Line CodeVet flagged on the save that just bounced back here (0 = none).
    'error_line' => $errorLine,
];

$aToTwig['extra_fields'] = opf_get_extrafields_array($id);
$oTemplate = $oTwig->load('tool_add_edit_filter.twig');
$oTemplate->display($aToTwig);
