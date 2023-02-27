<?php

/*
tool_dashboard.php
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

// Include the ordering class
require_once WB_PATH.'/framework/class.order.php';
// Create new order object and reorder
$order = new order('{TP_OPFD}', 'position', 'id', 'type');
foreach(opf_get_types() as $type => $typename){
    $order->clean($type);
}

// set language for help-browser
$help_lang = LANGUAGE;
if (!file_exists(__DIR__."/docs/files/".$help_lang."/intro-txt.html")){
    $help_lang = 'EN';
}

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
    'tpl_help_onclick'        => opf_quotes("javascript: return opf_popup('$ModUrl/docs/files/$help_lang/intro-txt.html');"),
    'tpl_help_url'            => $ModUrl.'/docs/files/'.$help_lang.'/intro-txt.html',
    'tpl_upload_message'      => opf_quotes($upload_message),
    'tpl_upload_ok'           => $upload_ok,
    'tpl_upload_success'      => ($upload_ok == FALSE) ? $L['TXT_UPLOAD_FAILED'] : $L['TXT_UPLOAD_SUCCESS'],
    'tpl_upload_message_type' => ($upload_ok == FALSE)?'error':'success',
    'tpl_hide_upload'         => ($upload_message == '' || $upload_ok == TRUE) ? 'class="hideupload"' : '',
    'tpl_export_message'      => opf_quotes($export_message),
    'tpl_export_success'      => $export_success,
    'tpl_export_message_type' => ($export_ok==FALSE) ? 'error' : 'success',
    'tpl_export_button1'      => ($export_ok==FALSE) ? $L['TXT_OK'] : $L['TXT_CANCEL'],
    'tpl_export_button2'      => ($export_ok==FALSE) ? 'null' : "'".$L['TXT_DOWNLOAD']."'",
    'tpl_export_action2'      => ($export_ok==FALSE) ? '0' : opf_quotes($export_url),
    'tpl_export_ok'           => $export_ok,
    'tpl_export_url'          => opf_quotes($export_url),
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
        'filter_convert_query' => opf_quotes(
                "opf_message('"
                .(($filter['plugin']=='')
                    ?$LANG['MOD_OPF']["TXT_CONVERT_FILTER"]
                    :$LANG['MOD_OPF']["TXT_CONVERT_PLUGIN"])
                ."', '"
                .sprintf((($filter['plugin']=='')
                    ?$LANG['MOD_OPF']['TXT_SURE_TO_CONVERT']
                    :$LANG['MOD_OPF']['TXT_SURE_TO_INLINE']),$filter['name_js_quoted'])
                ."', 'query', '"
                .$LANG['MOD_OPF']["TXT_CANCEL"]
                ."', '"
                .$LANG['MOD_OPF']["TXT_OK"]
                ."', '"
                .opf_quotes($filter['convert_link'])
                ."'); return false;"
            ),


    );    
    $aAllFilters[] = $aSingleFilter;
}
$aToTwig['filters'] = $aAllFilters;
$aToTwig['hilite']  = (isset($_GET['hilite'])) ? $_GET['hilite'] : '';

// render the output in Twig template
$oTemplate = $oTwig->load('tool_dashboard.twig');
$oTemplate->display($aToTwig);
