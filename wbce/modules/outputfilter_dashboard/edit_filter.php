<?php

/*
edit_filter.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.4
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2017 Martin Hecht (mrbaseman)
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

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../index.php'));
// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) die(header('Location: ../../index.php'));


// get filter-data

if(!$filters = opf_db_query( "SELECT * FROM `".TABLE_PREFIX."mod_outputfilter_dashboard` WHERE `id`=$id"))
    return;
$filter = $filters[0];
$types = opf_get_types();
$name = $filter['name'];
// update active/inactive state from Settings
$filter['active']=opf_is_active($filter['name']);
$active = ($filter['active']==1?1:0);
$userfunc = ($filter['userfunc']==1?1:0);
$plugin = $filter['plugin'];
$type = (array_key_exists($filter['type'],$types)?$filter['type']:key($types));
$file = $filter['file'];
$modules = unserialize($filter['modules']);
$desc = opf_fetch_entry_language(unserialize($filter['desc']));
$helppath = opf_fetch_entry_language(unserialize($filter['helppath']));
$helppath = str_replace('{SYSVAR:WB_URL}', WB_URL, $helppath);
$helppath = str_replace('{OPF:PLUGIN_URL}', OPF_PLUGINS_URL.$filter['plugin'], $helppath);
$func = $filter['func'];
$funcname = $filter['funcname'];
$pages_parent = unserialize($filter['pages_parent']);
$pages = unserialize($filter['pages']);
$allowedit = ($filter['allowedit']==1?1:0);
$allowedittarget = ($filter['allowedittarget']==1?1:0);
$additional_values = unserialize($filter['additional_values']);
$additional_fields = unserialize($filter['additional_fields']);
$additional_fields_languages = unserialize($filter['additional_fields_languages']);

$filter_type_options='';

foreach($types as $value=>$text){
    $filter_type_options .= "<option value=\"$value\" ";
    if($type==$value) $filter_type_options .= 'selected="selected"';
    $filter_type_options .= ">".opf_quotes($text)."</option>";
}


if($helppath) {
    $helppath_onclick = "javascript: return opf_popup('$helppath');";
} else {
    $helppath_onclick = '';
}

// fill target checkbox-trees.
$mlist = $plist1 = $plist2 = '';
opf_preload_filter_definitions();
if($allowedit==0 && $allowedittarget==0) {
    // We can't use disabled or readonly with checkbox-tree, so just list the modules
    $mlist = opf_make_modules_checktree($modules, 'flat');
    // pages_parent
    $plist1 = opf_make_pages_parent_checktree($pages_parent, $pages, 'flat');
    // pages
} else {
    $mlist = opf_make_modules_checktree($modules, 'tree');
    $plist1  = opf_make_pages_parent_checktree($pages_parent, $pages, 'tree');
}

// do we have to display additional_fields?
$list_growfield = array();
$list_editarea = array();
$extra_fields = array();
if(!empty($additional_fields)) {
    if(empty($additional_fields_languages))
        $lang = array();
    else { // get language-strings
        if(isset($additional_fields_languages[LANGUAGE]))
            $lang = $additional_fields_languages[LANGUAGE];
        elseif(isset($additional_fields_languages['EN']))
            $lang = $additional_fields_languages['EN'];
        else
            $lang = reset($additional_fields_languages); // get first language-strings
    }
    foreach($additional_fields as $field) {
        // we use 'label' since v1.3.2, but keep 'text' for compatibility reasons
        if(isset($field['label'])) $field['text'] = $field['label'];
        if(is_string($field['text']))
            $field['text'] = ($field['text']{0}=='['?$lang[trim($field['text'],'[]')]:$field['text']);
        elseif(is_array($field['text'])) {
            if(isset($field['text'][LANGUAGE])) $field['text'] = $field['text'][LANGUAGE];
            else $field['text'] = $field['text']['EN'];
        }
        if(isset($field['style'])) $field['style'] = 'style="'.$field['style'].'"'; else $field['style'] = '';
        switch ($field['type']) {
        case 'text':
            if(isset($additional_values[$field['variable']]))
                $field['value'] = htmlspecialchars($additional_values[$field['variable']], ENT_QUOTES);
            break 1;
        case 'textarea': // supports use of array (simple one), too. See Docu.
        case 'editarea':
            $field['id'] = uniqid('ea');
            $field['value'] = htmlspecialchars($additional_values[$field['variable']], ENT_QUOTES);
            if($field['type']=='textarea')
                $list_growfield[] = $field['id'];
            if($field['type']=='editarea')
                $list_editarea[] = $field['id'];
            break 1;
        case 'checkbox':
            if(isset($additional_values[$field['variable']]) && $additional_values[$field['variable']])
                $field['checked'] = 'checked="checked"';
            else $field['checked'] = '';
            break 1;
        case 'radio':
            if(isset($additional_values[$field['variable']]) && $additional_values[$field['variable']]==$field['value'])
                $field['checked'] = 'checked="checked"';
            else $field['checked'] = '';
            break 1;
        case 'select':
            $field['options'] = '';
            foreach($field['value'] as $v=>$str) {
                if($additional_values[$field['variable']]==$v)
                    $selected = 'selected="selected"';
                else $selected = '';
                if(is_string($str))
                    $str = ($str{0}=='['?$lang[trim($str,'[]')]:$str);
                elseif(is_array($str)) {
                    if(isset($str[LANGUAGE])) $str = $str[LANGUAGE];
                    else $str = $str['EN'];
                }
                $field['options'] .= "<option value=\"".htmlspecialchars($v, ENT_QUOTES)."\" $selected>".htmlspecialchars($str, ENT_QUOTES)."</option>";
            }
            break 1;
        case 'array':
            foreach($additional_values[$field['variable']] as $k=>$v) {
                $field['values'][htmlspecialchars($k, ENT_QUOTES)] = htmlspecialchars($v, ENT_QUOTES);
            }
            break 1;
        }
        $extra_fields[] = $field;
    }
}

// init template
$tpl = new Template(WB_PATH.'/modules/outputfilter_dashboard');
$tpl->set_file('page', 'templates/add_edit.htt');

// fill template vars
$tpl->set_var(
array_merge($LANG['MOD_OPF'],
    array(
    // only inline-filters and filters with 'allowedit' are editable
    'tpl_filter_readonly' => ($userfunc||$allowedit)?'':'readonly="readonly"',
    'tpl_filter_disabled' => ($userfunc||$allowedit)?'':'disabled="disabled"',
    // filter active?
    'tpl_filter_active' => ($active)?'checked="checked"':'',
    'tpl_filter_type' => $type,
    // checkbox-trees: contains the whole HTML-output. Just use echo
    'tpl_module_tree' => $mlist,
    'tpl_pages_list1' => $plist1,
    'tpl_save_url' => opf_quotes(ADMIN_URL."/admintools/tool.php?tool=".basename(dirname(__FILE__))),
    'FTAN' => $ftan,
    'tpl_id' => $id,
    'tpl_filter_name' => $name,
    'tpl_filter_funcname' => $funcname,
    'tpl_filter_file' => $file,
    'tpl_filter_description' => $desc,
    'tpl_filter_helppath_onclick' => $helppath_onclick,
    'tpl_funcname' => $funcname,
    'tpl_func' => htmlspecialchars($func,ENT_QUOTES),
    'tpl_cancel_onclick' => 'javascript: window.location = \''.ADMIN_URL.'/admintools/tool.php?tool='.basename(dirname(__FILE__)).'\';',
    'tpl_allowedit' => (($funcname <> "")?("var opf_editarea = ".($allowedit?'"editable"':'""').";"):""),
    'tpl_list_editarea' => "",
    'tpl_list_growfield' => "",
    'tpl_filter_type_options' => $filter_type_options,
    'WB_URL' => WB_URL,
    'MOD_URL' => WB_URL.'/modules/'.$module_directory,
    'IMAGE_URL' => WB_URL.'/modules/'.$module_directory.'/templates/images'
)));


    // if helppath_onclick is present parse the help block and store the result in TPL_HELP_BLOCK
    if($helppath_onclick){
        $tpl->set_block('page', 'help_block', 'help');
        $tpl->parse('TPL_HELP_BLOCK', 'help_block', false);
    } else {
        $tpl->set_var('TPL_HELP_BLOCK', "");
    }

    // if file is present not empty parse the file_area_block and store the result in TPL_FILE_AREA_BLOCK
    if(!empty($file)){
        $tpl->set_block('page', 'file_area_block', 'file_area');
        $tpl->parse('TPL_FILE_AREA_BLOCK', 'file_area_block', false);
    } else {
        $tpl->set_var('TPL_FILE_AREA_BLOCK', "");
    }

    // if func is present not empty parse the func_area_block and store the result in TPL_FUNC_AREA_BLOCK
    if(!empty($func)){
        $tpl->set_block('page', 'func_area_block', 'func_area');
        $tpl->parse('TPL_FUNC_AREA_BLOCK', 'func_area_block', false);
    } else {
        $tpl->set_var('TPL_FUNC_AREA_BLOCK', "");
    }

    // if extra_fileds is not empty parse the extra_fields_block and store the result in TPL_EXTRA_fields_AREA_BLOCK
    if(!empty($extra_fields)){
        $TPL_EXTRA_FIELDS_BLOCK="";
        foreach($extra_fields as $field){
            $template=$field['type'];
        if($field['type']=='editarea')$template='textarea';
        $tpl_field_text=opf_quotes($field['text']);
        $tpl->set_var('tpl_field_text', $tpl_field_text);
        $tpl_field_name=opf_quotes($field['name']);
        $tpl->set_var('tpl_field_name', $tpl_field_name);
        $tpl_field_value=$field['value'];
        $tpl_field_id='';
        if($template=='textarea'){
            $tpl_field_id=opf_quotes($field['id']);
        } else {
            $tpl_field_value=opf_quotes($tpl_field_value);
        }
        $tpl->set_var('tpl_field_value', $tpl_field_value);
        $tpl->set_var('tpl_field_id', $tpl_field_id);
        $tpl_field_style=$field['style'];
        $tpl->set_var('tpl_field_style', $tpl_field_style);
        if(isset($field['checked']))
            $tpl_field_checked=$field['checked'];
            else $tpl_field_checked="";
        $tpl->set_var('tpl_field_checked', $tpl_field_checked);
        if(isset($field['options']))
            $tpl_field_options=$field['options'];
            else $tpl_field_options="";
        $tpl->set_var('tpl_field_options', $tpl_field_options);

        if($field['type']=='array'){
            $tpl->set_var('tpl_field_value', serialize($tpl_field_value));
            $tpl->set_block('page', 'array_row_block', 'extra_field');
            foreach($field['value'] as $key=>$value){
            $tpl->set_var('tpl_key', $key);
            $tpl->set_var('tpl_value', $value);
            $keyid=uniqid();
            $list_growfield[]=$keyid;
            $tpl->set_var('tpl_keyid', $keyid);
            $valid=uniqid();
            $list_growfield[]=$valid;
            $tpl->set_var('tpl_valid', $valid);
            // first parse the block specific to this field type
               $TPL_EXTRA_FIELDS_BLOCK .= $tpl->parse('TPL_FIELD_BLOCK', 'array_row_block', false);
            }
        } else {
           // in short, pretty much the same, but just do it only once
           $tpl->set_block('page', $template.'_block', 'extra_field');
           $TPL_EXTRA_FIELDS_BLOCK .= $tpl->parse('TPL_FIELD_BLOCK', $template.'_block', false);
        }
        }
        $tpl->set_var('TPL_EXTRA_FIELDS_BLOCK', $TPL_EXTRA_FIELDS_BLOCK);
    } else {
        $tpl->set_var('TPL_EXTRA_FIELDS_BLOCK', "");
    }

    // if list_editarea is present update tpl_list_editarea
    if(!empty($list_editarea)){
        $tpl_list_editarea = 'var opf_editarea_list = new Array();';
        $i = 0;
        foreach($list_editarea as $lid) {
            $tpl_list_editarea .= 'opf_editarea_list['.$i++.'] = '."'$lid';";
        }
        $tpl->set_var('tpl_list_editarea', $tpl_list_editarea);
    }

    // if list_growfield is present update tpl_list_growfield
    if(!empty($list_growfield)){
        $tpl_list_growfield = 'var opf_growfield_list = new Array();';
        $i = 0;
        foreach($list_growfield as $lid) {
            $tpl_list_growfield .= 'opf_growfield_list['.$i++.'] = '."'$lid';";
        }
        $tpl->set_var('tpl_list_growfield', $tpl_list_growfield);
    }


// show page
$tpl->set_unknowns('keep');
$tpl->set_block('page', 'main_block', 'main');
$tpl->parse('main', 'main_block', false);
print opf_filter_Comments($tpl->parse('output', 'main', false));

