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

// Include the ordering class
require_once WB_PATH.'/framework/class.order.php';
// Create new order object and reorder
$order = new order('{TP_OPFD}', 'position', 'id', 'type');
foreach(opf_get_types() as $type => $typename){
    $order->clean($type);
}

// Main help link — MarkdownWbce popup with one tab per document, replacing the
// old generated phpDocumentor pages under docs/files/. README.md is the short
// module overview; the three guides under documentation/ are the actual manual,
// split by audience (site admins vs. filter authors vs. the API itself).
// Only the base paths are passed: MdReaderHelper::findExistingDoc() swaps in the
// _<LANG> variant when one exists, so a DE backend gets README_DE.md and the
// three *_DE.md guides without this having to know which translations shipped.
// Labels are decoded first: this module's language files write umlauts as HTML
// entities, but a tab label travels as a JSON value inside a GET parameter and
// is rendered as text on the other side -- an '&Uuml;bersicht' would arrive
// verbatim instead of becoming 'Übersicht'. A no-op for entity-free strings.
$docLabel = static fn(string $key, string $fallback): string
    => html_entity_decode(Lang::get('L', $key, $fallback), ENT_QUOTES, 'UTF-8');

$helpLink = MdReaderLink::docs([
    ['path' => __DIR__ . '/README.md',                        'label' => $docLabel('TXT_DOC_OVERVIEW',  'Overview')],
    ['path' => __DIR__ . '/documentation/USER_GUIDE.md',      'label' => $docLabel('TXT_DOC_USER',      'User Guide')],
    ['path' => __DIR__ . '/documentation/DEVELOPER_GUIDE.md', 'label' => $docLabel('TXT_DOC_DEVELOPER', 'Developer Guide')],
    ['path' => __DIR__ . '/documentation/API_REFERENCE.md',   'label' => $docLabel('TXT_DOC_API',       'API Reference')],
])->title('OutputFilter Dashboard');

// get list of filters for template
$aFilters = array();
$types      = opf_get_types();
$filters    = opf_select_filters();
if (!is_array($filters))
    $filters = array();
$old_type = ''; // remember last type in foreach loop below,
                // to draw a separator in case type changed
// loop through fliters and process their values
foreach($filters as $filter) {

    $filter_id = $filter['id'];
    $sFilterUri = $ToolUrl.'&amp;id='.$filter_id.'&amp;';
    $filter['funcname']          = $filter['funcname'];
    $filter['desc']              = unserialize($filter['desc']);
    $filter['modules']           = unserialize($filter['modules']);
    $filter['pages']             = unserialize($filter['pages']);
    $filter['pages_parent']      = unserialize($filter['pages_parent']);
    $filter['additional_values'] = unserialize($filter['additional_values']);
    $filter                      = opf_replace_sysvar($filter);

    // we need the next str_replace to allow \ ' " in the name for use with javascript
    $filter['name_js_quoted'] = str_replace(
        array('\\','&#039;','&quot;'),
        array('\\\\','\\&#039;','\\&quot;'),
        $filter['name']
    );
    $sTmpDesc           =  opf_fetch_entry_language($filter['desc']);
    $filter['desc']     =  opf_correct_umlauts(htmlspecialchars($sTmpDesc));

    // mark last added filter
    $filter['last_touched'] = FALSE;
    if ($filter['id'] == $id){
        $filter['last_touched'] = TRUE;
    } 
    if (isset($_GET['last']) && $_GET['last'] == $filter['id']) {
        $filter['last_touched'] = TRUE;
    }
    // line to separate filter-types
    $filter['sep_line'] = FALSE;
    if ($old_type!=$filter['type']) {
        $old_type = $filter['type'];
        $filter['sep_line'] = TRUE;
    }
    $filter['active'] = opf_is_active($filter['name']) || ( ($id == $filter['id']) && $active );
    $filter['edit_link'] = $sFilterUri.'edit=1';

    // css link
    $filter['css_link'] = '';
    if ($filter['csspath']!='') {
        $filter['css_link'] = $sFilterUri.'csspath='.urlencode($filter['csspath']);
    }
   
    
    // ramaining dashboard links
    $filter['export_link'] = '';
    $filter['convert_link'] = '';
    if ($filter['userfunc'] || $filter['plugin']) {
        $filter['export_link'] = $sFilterUri.'export=1';
        $filter['convert_link'] = $sFilterUri.'convert=1';
    }

    $filter['type_id'] = $filter['type'];
    $filter['type'] = $types[$filter['type_id']];


    $aFilters[] = $filter;
}

// collect template vars
$aToTwig += array(
    'tpl_add_onclick'         => $ToolUrl.'&amp;add=1',
    'tpl_help_onclick'        => opf_quotes($helpLink->popupOnclick()),
    'tpl_help_url'            => $helpLink->url(),
    // Panel starts closed, except right after a failed upload attempt so the
    // error stays visible next to the retry form instead of being hidden again.
    'tpl_show_upload'         => ($upload_message != '' && $upload_ok !== TRUE),
    'tpl_tool_url'            => opf_quotes($ToolUrl)
);

$arr_allways_active = include __DIR__ . '/allways_active_array.php';
$aAllFilters = [];
foreach($aFilters as $filter){
    $aSingleFilter = array(
        'filter_id'        => $filter['id'],
        'helppath_onclick' => opf_get_helppath($filter['id']),
        'filter_name'      => opf_quotes($filter['name']),
        'filter_desc'      => opf_quotes($filter['desc']),
        'funcname'         => $filter['funcname'],
        'type_id'          => $filter['type_id'],
        'type'             => ($filter['plugin']!='') ? "plugin" :(($filter['userfunc']) ? "inline" : "extension"),
        'type_sep_line'    => ($filter['sep_line']),
        'type_title'       => ($filter['plugin']) ? $L["TXT_PLUGIN_FILTER"] : (($filter['userfunc']) ? $L["TXT_INLINE_FILTER"] : $L["TXT_MODULE_EXTENSION_FILTER"]),
        'type_name'        => opf_quotes($filter['type']),
        'editlink'         => opf_quotes($filter['edit_link']),
        'additional_class' => ($filter['last_touched'])? ' last-modified' : '',
        'filter_active'    => ($filter['active'] ? '' : 'in').'active',
        'config_url'       => opf_quotes($filter['configurl']),
        'css_link'         => opf_quotes($filter['css_link']),
        'deletable'        => ($filter['userfunc'] || $filter['plugin']), // 1 : 0
        'filter_export_link'=> opf_quotes($filter['export_link']),
        'check_disabled'   => (in_array($filter['funcname'], $arr_allways_active)) ? 'disabled' : '',
        'convert_link'     => $filter['convert_link'],
        // Confirmation is rendered inline in the row (see .convert-item in
        // ajax.js), same UX as the delete confirmation -- no more popup dialog.
        'convert_question' => opf_quotes(sprintf(
                (($filter['plugin']=='') ? $LANG['MOD_OPF']['TXT_SURE_TO_CONVERT'] : $LANG['MOD_OPF']['TXT_SURE_TO_INLINE']),
                $filter['name']
            )),
        'convert_confirm'  => opf_quotes($LANG['MOD_OPF']["TXT_OK"]),
        'convert_cancel'   => opf_quotes($LANG['MOD_OPF']["TXT_CANCEL"]),


    );
    $aAllFilters[] = $aSingleFilter;
}
$aToTwig['filters'] = $aAllFilters;
$aToTwig['hilite']  = (isset($_GET['hilite'])) ? $_GET['hilite'] : '';

// render the output in Twig template
$oTemplate = $oTwig->load('tool_dashboard.twig');
$oTemplate->display($aToTwig);
