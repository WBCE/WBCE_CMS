<?php

/*
naturaldocs_txt/functions_outputfilter.php
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

/*
    File: Filter functions
*/


// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../index.php'));

// obtain module directory
$mod_dir = basename(dirname(__FILE__));
require(WB_PATH.'/modules/'.$mod_dir.'/info.php');

// include module.functions.php
include_once(WB_PATH . '/framework/module.functions.php');

// include the module language file depending on the backend language of the current user
if (!include(get_module_language_file($mod_dir))) return;

require_once(dirname(__FILE__).'/functions.php');


/*
    Topic: The Filter-Function itself
        The Filter function must have an unique name, and should have a "opff_"-prefix

    Prototype:
        %bool% opff_unique_name( %string% &$content, %int% $page_id, %int% $section_id, %string% $module, %object% $wb )

    Parameters:
        &$content - %(string)% The page's or section's Content, per reference!
        $page_id - %(int)% Actual Page ID.
        $section_id - %(int)% Actual Section ID. For Filters of type !OPF_TYPE_PAGE_FIRST!, !OPF_TYPE_PAGE! or !OPF_TYPE_PAGE_LAST! or !OPF_TYPE_PAGE_FINAL! this is always !FALSE!.
        $module - %(string)% Name of actual module (== !$module_directory! ). For Filters of type !OPF_TYPE_PAGE_FIRST!, !OPF_TYPE_PAGE! or !OPF_TYPE_PAGE_LAST!  or !OPF_TYPE_PAGE_FINAL! this is always !FALSE!.
        $wb - %(object)% Instance of Class wb.

    Returns:
        Should return always !TRUE!. Only in case the content may be damaged or undefined the function *must* return !FALSE!.

    Examples:
        (start code)
        // simple filter which will convert all U to X in $content
        function opff_x_as_u(&$content, $page_id, $section_id, $module, $wb) {
            $content = str_replace('U', 'X', $content);
            return(TRUE);
        }
        (end)

        (start code)
        function opff_prettify(&$content, $page_id, $section_id, $module, $wb) {
            $PATH = WB_URL.'/modules/opf_prettify/prettify';
            if(opf_find_class($content, 'prettyprint', '(pre|code)')) {
                opf_register_frontend_files($PATH.'/prettify.css','css');
                opf_register_frontend_files($PATH.'/prettify.js','js');
                if(opf_find_class($content, 'lang-sql', '(pre|code)'))
                    opf_register_frontend_files($PATH.'/lang-sql.js','js');
                opf_register_onload_event('prettyPrint');
            }
            return(TRUE);
        }
        (end)
*/


/*
    Function: opf_register_filter
        Register a new Filter.

        This function is used for plugin- and module-filters to install the filter.

        *You don't need this function for inline-filters!*

    Prototype:
        %bool% opf_register_filter( %array% $filter, %bool% $serialized=FALSE )

    Parameters:
        $filter - %(array)% An array that contains all filter data.
        $serialized - %(bool)% Whether the filter-data is passed serialized.

    Returns:
        !TRUE! on success, !FALSE! otherwise.
        Additionaly it will emit an error-message (level: E_USER_WARNING) in case of failure.

    Examples:
        (start code)
        $filter = array(
            'name'       => 'PrettyPrint: Google-Code-Prettify',
            'type'       => OPF_TYPE_SECTION,
            'file'       => WB_PATH.'/modules/opf_prettify/filter.php',
            'funcname'   => 'opff_prettify',
            'csspath'    => WB_PATH.'/modules/opf_prettify/prettify/prettify.css',
            'modules'     => 'download_gallery,manual,news,wysiwyg'
        );
        opf_register_filter($filter);
        (end)

    $filter-Array:
        name - %(string)% - Unique name of Filter, e.g. !Correct Date filter!.
        type - %(string)% - Type of Filter (see below).
        funcname - %(string)% - Unique name of Filter-Function. This function is called to apply the filter.
               It has to be an unique name and should begin with "opff_", e.g. !opff_correct_date()!.
        func - %(string)% - "Inline-Function" (see below).
        file - %(string)% - Name and absolute path of file containing the Filter-Function.
               Use either !func! or !file!.
        active - %(int)% - Set Filter active (!1!), or inactive (!0!) after installation. Defaults to !1!.
        allowedit - %(int)% - Set Filter-Settings editable (!1!), or not editable (!0!) (see below). Defaults to !0!.
        allowedittarget - %(int)% - Set additional Filter-Settings editable (see below). Defaults to !0!.
        desc - %(string/array)% - Description of Filter (see below).
        configurl - %(string)% - URL to Filter-Settings (e.g. to an Admin-Tool).
                E.g.: !'configurl' => ADMIN_URL.'/admintools/tool.php?tool=output_filter'!. Default: "".
        csspath - %(string)% - Name and absolute path of CSS-file.
              E.g.: !'csspath' => '{SYSVAR:WB_PATH}/modules/opf_prettify/prettify/prettify.css'!. Default: "".
        helppath - %(array)% - Array of name and absolute path to Help-files.
              E.g.: !'helppath' => array('DE'=>'{SYSVAR:WB_URL}/modules/opf_prettify/help_de.html',
                             'EN'=>'{SYSVAR:WB_URL}/modules/opf_prettify/help_en.html')!. Default: "".
        modules - %(string/array)% - List of modules (see below).
        pages - %(string/array)%  - List of pages (see below).
        pages_parent - %(string/array)%  - List of pages (see below).
        additional_fields - %(array)%  - List of additional Settings-Fields (see below).

    type:
        Determines on which type of content the filter is applied to.

    OPF_TYPE_SECTION_FIRST - Apply filter on section-content, but *before* all filters of type OPF_TYPE_SECTION.
    OPF_TYPE_SECTION - Apply filter on section-content.
    OPF_TYPE_SECTION_LAST - Like OPF_TYPE_SECTION, but applied *after* all filters of type OPF_TYPE_SECTION.
    OPF_TYPE_PAGE_FIRST - Like OPF_TYPE_PAGE, but applied *before* all filters of type OPF_TYPE_PAGE.
    OPF_TYPE_PAGE - Apply filter on page-content, i.e. on all sections, snippets on that page, and the entire template, too.
    OPF_TYPE_PAGE_LAST - Like OPF_TYPE_PAGE, but applied *after* all filters of type OPF_TYPE_PAGE.
    OPF_TYPE_PAGE_FINAL - Like OPF_TYPE_PAGE_LAST, but applied even *after* all filters of type OPF_TYPE_PAGE_LAST.

        Filter of type OPF_TYPE_SECTION are applied on section-content before the content gets inserted into the template.

        Filter of type OPF_TYPE_PAGE are applied on the finished page just before the page is sent to the browser.

    func:
        A string containing the filter-function. Convenient for very short functions.
        Use !file! for larger filter-functions.
        (start code)
        $function = '
            function opff_search_highlight(&$content, $page_id, $section_id, $module, $wb) {
                if(isset($_GET["searchresult"]) && is_numeric($_GET["searchresult"])
                     && !isset($_GET["nohighlight"]) && isset($_GET["sstring"])
                     && !empty($_GET["sstring"])) {
                    $arr_string = explode(" ", $_GET["sstring"]);
                    if($_GET["searchresult"]==2) // exact match
                        $arr_string[0] = str_replace("_", " ", $arr_string[0]);
                    $content = search_highlight($content, $arr_string);
                }
                return(TRUE);
            }
        ';
        $filter = array(
            'name'       => 'Searchresult Highlighting',
            'type'       => OPF_TYPE_SECTION_LAST,
            'funcname'   => 'opff_search_highlight',
            'func'       => $function,
            'modules'     => 'all'
        );
        opf_register_filter($filter);
        (end)

    file:
        Name and absolute path of file containing the Filter-Function.
        (start code)
        $filter = array(
            'name'       => 'Searchresult Highlighting',
            'type'       => OPF_TYPE_SECTION_LAST,
            'funcname'   => 'opff_search_highlight',
            'file'       => '{SYSVAR:WB_PATH}/modules/myfilter/filter.php',
            'modules'    => 'all'
        );
        opf_register_filter($filter);
        (end)
        With filter.php
        (start code)
        <?php
        // content of filter.php
        function opff_search_highlight(&$content, $page_id, $section_id, $module, $wb) {
            if(isset($_GET["searchresult"]) && is_numeric($_GET["searchresult"])
                 && !isset($_GET["nohighlight"]) && isset($_GET["sstring"])
                 && !empty($_GET["sstring"])) {
                $arr_string = explode(" ", $_GET["sstring"]);
                if($_GET["searchresult"]==2) // exact match
                    $arr_string[0] = str_replace("_", " ", $arr_string[0]);
                $content = search_highlight($content, $arr_string);
            }
            return(TRUE);
        }
        ?>
        (end)


    allowedit:
        If set to !1! following settings are changeable through Admin-Tool

        * Name of filter
        * Type of Filter
        * Name of filter-function
        * Filter-function itself (in case it's a "inline-filter")
        * List of modules, and
        * List of pages the filter is applied to.

        Suggestion: use !'allowedit' => 0! always (default).

    allowedittarget:
        Extension for !allowedit!: If !allowedit! is set to !0! and allowedittarget set to !1!
        the user is allowed to change only

        * List of modules, and
        * List of pages the filter is applied to.

        Suggestion: use !'allowedittarget' => 1! always (default).

        In case !allowedit! is set to !1! !allowedittarget! is meaningless.

    desc:
        Description of filter. Use either
        > 'desc' => "Description follows here.\nText..."
        or
        > 'desc' => array(
        >   'EN' => "Description\n...",
        >   'DE' => "Beschreibung\n..."
        > )
        Using the latter form, there has to be at least an English ( !'EN'! ) entry.

    modules:
        List of modules the filter (of type OPF_TYPE_SECTION_FIRST, OPF_TYPE_SECTION or OPF_TYPE_SECTION_LAST ) is applied to.
        In the following two examples, the filter will be applied to all WYSIWYG- and News-sections.
        > 'modules' => 'wysiwyg,news'
        or
        > 'modules' => array('wysiwyg', 'news')

        There are some aliases defined

        'all' - all installed modules
        'all_page_types' - all page-type modules: wysiwyg, news, guestbook, download_gallery, manual, mapbaker, newsarc
        'all_form_types' - form, formx, mpform, miniform
        'all_gallery_types' - Auto_Gallery, fancy_box, flickrgallery, foldergallery,  gallery, gdpics, imageflow, imagegallery, lightbox2, lightbox, minigallery, minigal2, panoramic_image, pickle, picturebox, tiltviewer, smoothgallery, swift, slideshow
        'all_wrapper_types' - curl, inlinewrapper, wrapper
        'all_calendar_types' - calendar, concert, event, event_calendar, extcal, procalendar
        'all_shop_types' - bakery, gocart, anyitems, lastitems
        'all_code_types' - code, code2, show_code, show_code_geshi, color4code
        'all_poll_types' - doodler, polls
        'all_listing_types' - accordion, addressbok, aggregator, bookings_v2, bookmarks, cabin, dirlist, enhanced_aggregator, faqbaker, faqmaker, glossary, jqCollapse, members, mfz, sitemap
        'all_various_types' - audioplayer, feedback, forum, newsreader, shoutbox, simpleviewer, small_ads, wb-forum, zitate

        See <opf_modules_categories> for the actual module -> category relation.

    pages:
        List of pages (PAGE_IDs) the filter is applied to. Alias 'all' for all present pages.
        > // apply filter on page 12, 121 and 16 only
        > 'pages' => '21,121,16' // written as list
        > 'pages' => array('21', '121', '16') // written as array
        > // apply filter to all pages
        > 'pages' => 'all'

    pages_parent:
        List of pages (PAGE_IDs) the filter is applied to. Additionaly the filter is applied to
        all sub-pages, too.
        Alias 'all' for all present pages.
        > // apply filter on page 12 and 21, and all sub-pages of page 12 and 21
        > 'pages_parent' => '12,21' // written as list
        > 'pages_parent' => array('12', '21') // written as array

    additional_fields:
        Arrays of additional configuration elements.

        In case the filter needs some additional config-elements but it doesn't have its own Settings-Page / Admin-Tool,
        you can add some fields using !additional_fields!. Possible field-types are

        text - normal HTML text-field
        textarea - normal HTML textarea
        editarea - textarea with activated editarea (Editor)
        radio - normal HTML radio-fields
        checkbox - normal HTML checkbox-fields
        select - normal HTML select-field
        array - simple array broken up into several text-fields

        !allowedit! has no affect on these fields.

        See <opf_filter_get_additional_values> on how to retrieve values from these fields.

        Example: Adding various fields
        (start code)
        'additional_fields' => array(
            array( // add text-field "Name"
                'type' => 'text',    // type of field
                'label' => 'Name',       // Label for the text-field
                'variable' => 'name',    // name of variable
                'name' => 'af_name',     // name for HTML name-attribute
                'value' => '',       // default-value
                'style' => 'width: 98%;' // style (only for text, textarea and editarea for width and height)
            ),
            array( // add textarea "Long-text"
                'type' => 'textarea',
                'label' => 'Long-Text',
                'variable' => 'text',
                'name' => 'af_long_text',
                'value' => 'Default text',
                'style' => 'width: 98%;'
            ),
            array( // add select-field "Colour"
                'type' => 'select',
                'label' => 'Colour',
                'variable' => 'colour',
                'name' => 'af_colour',
                'value' => array(    // options
                    'red'   => 'Red',
                    'blue'  => 'Blue',
                    'green' => 'Green'
                ),
                'checked'=> 'blue'       // selected option
                )
            ),
            array( // add checkbox
                'type' => 'checkbox',
                'label' => 'Use ECMA-variant',
                'variable' => 'use_ecma',
                'name' => 'af_use_ecma',
                'value' => 'use_ecma',
                'checked' => 1
            )
        )
        (end)

        Example: Adding fields with language-support
        (start code)
        'additional_fields' => array(
            array(
                'type' => 'text',         // type of field
                'label' => array(          // Language-dependent string
                    'EN'=>'Locale',
                    'DE'=>'Spracheinstellung'
                ),
                'variable' => 'locale',       // name of variable
                'name' => 'af_locale',    // name for name-attribute
                'value' => 'de_DE.utf-8',     // default-value
                'style' => 'width: 98%;'      // style (only for text, textarea and editarea for width and height)
            ),
            array(
                'type' => 'array',
                'label' => array(
                    'EN'=>'Date format',
                    'DE'=>'Datumsformat'
                ),
                'variable' => 'date_formats',
                'name' => 'af_date_formats',
                'value' => array(
                    'D M d, Y'  => '%a %b %d %Y',
                    'M d Y'     => '%b %d. %Y',
                    'd M Y'     => '%d. %b %Y',
                    'jS F, Y'   => '%e. %B %Y',
                    'l, jS F, Y'=> '%A, %e. %B %Y')
            ),
            array(
                'type' => 'select',
                'label' => array('EN'=>'Colour','DE'=>'Farbe'),
                'variable' => 'colour',
                'name' => 'af_colour',
                'value' => array(    // options
                    'red'   => array('EN'=>'Red','DE'=>'Rot'),
                    'blue'  => array('EN'=>'Blue','DE'=>'Blau'),
                    'green' => array('EN'=>'Green','DE'=>'Gr&uuml;n')
                ),
                'checked'=> 'blue'       // selected option
                )
            )
        )
        (end)

    label - Label of field. !label! can be an ordinary string "!Enter Date!", or a language-specific array.
    variable - Name of variable to be used for this field. Radio-buttons uses the same name.
    type - Type of field: '!text!', '!textarea!', '!editarea!', '!radio!', '!checkbox!', '!select!' or '!array!'.
    name - Value for the HTML name-attribute. Radio-buttons uses the same name.
    value - Default-value. 'select' and '!array!' requires an array.
    checked - For '!radio!' or '!checkbox!': use !'checked' => 1! to mark this field checked.
            For '!select!': use !'checked' => 'value'! i.e. repeat the selected value.
    style - (optional) For '!text!', '!textarea!', '!editarea!': add !width:! and/or !height:! attributes.
*/
function opf_register_filter($filter, $serialized=FALSE) {

    $now = time();
    $sql_where = '';
    // get variables
    if($serialized)
        $filter = unserialize($filter);
    $filter = opf_insert_sysvar($filter);
    if(isset($filter['id'])) $id = opf_fetch_clean( $filter['id'], 0, 'int'); else $id = 0; // id is set from opf_edit_filter() only
    if(isset($filter['type'])) $type = opf_fetch_clean( $filter['type'], '', 'string'); else $type = '';
    if(isset($filter['name'])) $name = opf_fetch_clean( $filter['name'], '', 'string'); else $name = '';
    if(isset($filter['file'])) $file = opf_fetch_clean( $filter['file'], '', 'string'); else $file = '';
    if(isset($filter['func'])) $func = opf_fetch_clean( $filter['func'], '', 'unchanged'); else $func = '';
    if(isset($filter['funcname']))  $funcname = opf_fetch_clean( $filter['funcname'], '', 'string'); else $funcname = '';
    if(isset($filter['userfunc'])) $userfunc = opf_fetch_clean( $filter['userfunc'], 0, 'int'); else $userfunc = 0;
    if(isset($filter['plugin'])) $plugin = opf_fetch_clean( $filter['plugin'], '', 'string'); else $plugin = '';
    if(isset($filter['active'])) $active = opf_fetch_clean( $filter['active'], 0, 'int'); else $active = 1;
    if(isset($filter['allowedit'])) $allowedit = opf_fetch_clean( $filter['allowedit'], 0, 'int'); else $allowedit = 0;
    if(isset($filter['allowedittarget'])) $allowedittarget = opf_fetch_clean( $filter['allowedittarget'], 0, 'int'); else $allowedittarget = 1;
    if(isset($filter['configurl'])) $configurl = opf_fetch_clean( $filter['configurl'], '', 'string'); else $configurl = '';
    if(isset($filter['csspath'])) $csspath = opf_fetch_clean( $filter['csspath'], '', 'string'); else $csspath = '';
    if(isset($filter['force']) && $filter['force']) $force = TRUE; else $force = FALSE;
    if(isset($filter['filter_installed']) && !$filter['filter_installed']) $filter_installed = FALSE; else $filter_installed = TRUE;
    if(isset($filter['modules'])) $modules = $filter['modules']; else $modules = array();
    if(isset($filter['targets'])) $targets = $filter['targets']; else $targets = array(); // allow "targets" as alias for "modules"
    if(isset($filter['pages'])) $pages = $filter['pages']; else $pages = array('all');
    if(isset($filter['pages_parent'])) $pages_parent = $filter['pages_parent']; else $pages_parent = array('all');
    if(isset($filter['additional_fields_languages'])) $additional_fields_languages = $filter['additional_fields_languages']; // else unset
    if(isset($filter['additional_fields'])) $additional_fields = $filter['additional_fields']; // else unset
    if(isset($filter['additional_values'])) $additional_values = $filter['additional_values']; else $additional_values = serialize('');

    // description may be an array of different languages
    if(!isset($filter['desc']) || empty($filter['desc'])) $desc = array();
    elseif(is_string($filter['desc'])) $desc = array('EN'=>$filter['desc']); // use EN as default
    else $desc = $filter['desc'];
    $desc = serialize($desc);

    if(!isset($filter['helppath']) || empty($filter['helppath'])) $helppath = array();
    elseif(is_string($filter['helppath'])) $helppath = array('EN'=>$filter['helppath']);
    else $helppath = $filter['helppath'];
    $helppath = serialize($helppath);

    // prepare modules-list
    if(!opf_type_uses_sections($type)) // not a section-filter - no need to check for modules
        $modules = array();
    else { // section-filter
        if(!isset($modules) && isset($targets))
            $modules = $targets;
        elseif(!isset($modules))
            $modules = array();
        if(!is_array($modules)) $modules = explode(',', $modules);
        $tmp = array();
        foreach($modules as $module)
            $tmp[] = trim($module);
        $modules = $tmp;
        if(in_array('all', $modules)) $all_modules = TRUE; else $all_modules = FALSE;
        foreach(opf_modules_categories('relations') as $m_type => $m_list) {
            if($all_modules || in_array($m_type, $modules)) {
                    $modules = array_merge($modules, $m_list);
            }
        }
        $modules = array_unique($modules);
    }

    // fix some values
    if($userfunc) $allowedit = 1;
    $modules = serialize($modules);
    if(!is_array($pages)) $pages = explode(',', $pages);
    $pages_parent = str_replace('search', '0', $pages_parent);
    if(!is_array($pages_parent)) $pages_parent = explode(',', $pages_parent);
    if(empty($pages) && empty($pages_parent)) {
        $pages = array('9999999');
        $pages_parent = array('9999999');
    }
    $tmp = array();
    foreach($pages as $page)
        $tmp[] = trim($page);
    $pages = $tmp;
    $tmp = array();
    foreach($pages_parent as $page)
        $tmp[] = trim($page);
    $pages_pages = $tmp;
    $pages = serialize($pages);
    $pages_parent = serialize($pages_parent);
    if(isset($additional_fields_languages) && !empty($additional_fields_languages)) {
            $additional_fields_languages = serialize($additional_fields_languages);
    } else
        $additional_fields_languages = serialize('');
    if($filter_installed) {
        if(isset($additional_fields) && !empty($additional_fields)) {
            $additional_values = array();
            foreach($additional_fields as $field) {
                switch ($field['type']) {
                case 'text':
                case 'textarea':
                case 'editarea':
                case 'array':
                    $additional_values[$field['variable']] = $field['value'];
                break;
                case 'checkbox':
                case 'radio':
                    if(isset($field['checked']) && $field['checked']) $additional_values[$field['variable']] = $field['value'];
                    else {
                        if(!isset($additional_values[$field['variable']]) || !($additional_values[$field['variable']]))
                            $additional_values[$field['variable']] = FALSE;
                    }
                break;
                case 'select':
                    if(isset($field['checked']) && isset($field['value'][$field['checked']]))
                        $additional_values[$field['variable']] = $field['checked'];
                    else
                        $additional_values[$field['variable']] = FALSE;
                break;
                }
            }
        } else
            $additional_values = '';
    }
    if(isset($additional_fields) && !empty($additional_fields)) {
            $additional_fields = serialize($additional_fields);
    } else
        $additional_fields = serialize('');
    $additional_values = serialize($additional_values);
    // check some variables
    if($type=='' || $name=='' || $funcname=='') {
        trigger_error('Needed Argument missing or empty', E_USER_WARNING);
        if($force) { // store it nevertheless, but set it inactive
            $active = 0;
            if($name=='') $name = uniqid('filter_');
            if($funcname=='') $funcname = uniqid('opff_');
        } else
            return(FALSE);
    }

    $fileCheck = opf_replace_sysvar($file, $plugin);
    if(is_array($fileCheck) && empty($fileCheck)) $fileCheck = '';
    
    if(($fileCheck=='' && $func=='') or ($fileCheck != '' && $func != '')) {
        trigger_error(
            'Function (: '.$func.') OR File (: '.$file.') needed', 
            E_USER_WARNING
        );
        if($force) { // store it nevertheless, but set it inactive
            $active = 0;
            if($func=='') $func = '// Please enter a function.';
        } else {
            return(FALSE);
        }
    }
    
    
    if($fileCheck && (!file_exists($fileCheck) || !is_file($fileCheck) || !is_readable($fileCheck))) {
        trigger_error("Can\'t read file ($file)", E_USER_WARNING);
        return(FALSE);
    }

    if($func!='' && !preg_match("~$funcname\s*\(~", $func)) {
        trigger_error('wrong funcname', E_USER_WARNING);
        if($force) { // store it nevertheless, but set it inactive
            $active = 0;
            $func = preg_replace('/\?>\s*<\?php/','',"<?php // ATTN: given funcname and used name differ.\n?>".$func);
        } else
            return(FALSE);
    }

    if($func!='' && preg_match("/$funcname\s*\(/", $func)) {
        // remove warning again when funcname is corrected
        $func = preg_replace('/\s*\/\/\s*ATTN: given funcname and used name differ.\s*/',"\n",$func);
    }

    // insert values into DB

    // get next free position for type
    $position =    opf_db_query_vars( "SELECT MAX(`position`) FROM `{TP}mod_outputfilter_dashboard` WHERE `type`='%s'", $type);
    if($position===NULL) $position = 0;  // NULL -> no entries
    else ++$position;

    // new entry or update?
    if($id>0)
        $update = opf_is_registered($id);
    else
        $update = opf_is_registered($name);
    if($update) { // update, fetch some old values from db
        $sql_action = 'UPDATE';
        if($id>0) $sql_where = "WHERE `id`=".(int)$id;
        else $sql_where = "WHERE `name`='".addslashes($name)."'"; // keep this addslashes()-call!
        $old = opf_db_query( "SELECT * FROM `{TP}mod_outputfilter_dashboard` $sql_where");
        if($old===FALSE)return(FALSE);
        $old = $old[0];
        $old_type = $old['type'];
        $old_pos = $old['position'];
        if($type==$old_type) { // type unchanged, so keep position
            $position = $old_pos;
        } else { // change of type
            // correct positions for old type
            opf_db_run_query( "UPDATE `{TP}mod_outputfilter_dashboard` SET `position`=`position`-1 WHERE `type`='%s' AND `position`>%d", $old_type, $old_pos);
        }
        if($force==FALSE) {
            // update - keep some old values
            $active = (int)$old['active'];
            $modules = $old['modules'];
            $pages = $old['pages'];
            $pages_parent = $old['pages_parent'];
        }
        if(!$filter_installed) { // filter from edit. Fetch old values from DB
            $additional_fields_languages = $old['additional_fields_languages'];
            $additional_fields = $old['additional_fields'];
        } else $additional_values = $old['additional_values'];
     } else {
        $sql_action = 'INSERT INTO';
    }
    $res = opf_db_run_query( "$sql_action `{TP}mod_outputfilter_dashboard` SET
                                             `userfunc`=%d,
                                             `plugin`='%s',
                                             `position`=%d,
                                             `active`=%d,
                                             `type`='%s',
                                             `name`='%s',
                                             `file`='%s',
                                             `func`='%s',
                                             `funcname`='%s',
                                             `modules`='%s',
                                             `desc`='%s',
                                             `pages`='%s',
                                             `pages_parent`='%s',
                                             `allowedit`=%d,
                                             `allowedittarget`=%d,
                                             `configurl`='%s',
                                             `csspath`='%s',
                                             `helppath`='%s',
                                             `additional_values`='%s',
                                             `additional_fields`='%s',
                                             `additional_fields_languages`='%s'
                                             $sql_where",
                                            $userfunc,$plugin,$position,$active,$type,$name,$file,$func,$funcname,
                                            $modules,$desc,$pages,$pages_parent,$allowedit,$allowedittarget,
                                            $configurl,$csspath,$helppath,$additional_values,$additional_fields,
                                            $additional_fields_languages);

    if(class_exists('Settings') && defined('WBCE_VERSION')){
        // force refresh the filter definitions
        global $opf_FILTERS;
        unset($opf_FILTERS);
        opf_set_active($name, $active);
    }

    return($res);
}


/*
    Function: opf_move_up_before
        Upon registration move a filter up to a position before another one.
        This function can be used after <opf_register_filter()> to adjust its position
        in the filter list. By default, freshly installed filters are appended to the
        end of the list of filters of the same type. Use this function inside the
        <install.php> to move the filter up to a target position denoted by the
        name of another filter. You can repeat this with different names of filters
        from which you know that they have to be applied after the one you are installing.
        Alternatively, if !$ref_name! is an array, the filter denoted by !$name!
        is moved upwards to the position of the upper-most entry of the whole list.
        If !$ref_name! is ommited, the filter !$name! is moved up to the
        top of the whole list. In this case the return value is the new position of
        the filter !$name!

    Prototype:
        %bool% opf_move_up_before( %string% $name, %string% $ref_name )

        %array% opf_move_up_before( %string% $name, %array% $ref_name )

        %int% opf_move_up_before( %string% $name )

    Parameters:
        $name - %(string)% the name of the filter to move up in the list
        $ref_name - %(string)% name of the filter at the target position or
        $ref_name - %(array)% names of the filters at the target positions

    Returns:
        !TRUE! on success, !FALSE! if the types don't match or the filters were not found
        or an array of bools, corresponding to the return values for each filter
        or the position of !$name! if !$ref_name! is ommitted

    Example:
        > opf_move_up_before('opf CSS to head', 'Searchengine Highlighter');
*/

function opf_move_up_before($name, $ref_name=""){
    if(is_array($ref_name)){
        $ret=array();
        foreach($ref_name as $rn){
            $ret[]=opf_move_up_before($name, $rn);
        }
        return($ret);
    }
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    if($ref_name==""){
       $pos=0;
       while(opf_move_up_one($name,FALSE)){
           $pos = opf_get_position($name,FALSE);
       }
       return($pos);
    }
    $pos = opf_get_position($name,FALSE);
    $type = opf_get_type($name,FALSE);
    if($pos!==FALSE && $type!==FALSE && $pos>0) {
        $ref_name = opf_check_name($ref_name);
        if(!$ref_name) return(FALSE);
        $ref_pos = opf_get_position($ref_name, FALSE);
        $ref_type = opf_get_type($ref_name,FALSE);
        while($ref_pos!==FALSE && $pos!==FALSE && $ref_type==$type && $ref_pos>0 && $pos>$ref_pos) {
            if(opf_move_up_one($name,FALSE)===FALSE)
                return(FALSE);
            $pos = opf_get_position($name,FALSE);
        }
     }
     return(TRUE);
}


/*
    Function: opf_unregister_filter
        Un-Register a Filter.

        This function is used to remove a filter.
        Use this for Module-Filters in the module's !uninstall.php!-file.

    Prototype:
        %bool% opf_unregister_filter( %string% $name )

    Parameters:
        $name - %(string)% Name of filter to un-register.

    Returns:
        !TRUE! on success, !FALSE! otherwise.

    Example:
        > opf_unregister_filter('PrettyPrint: Google-Code-Prettify');
*/
function opf_unregister_filter($name) {
    if(!function_exists('opf_unregister_call_uninstall')) {
        function opf_unregister_call_uninstall($file) {
            include $file;
        }
    }
    static $old_name = FALSE;
    $now = time();
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    if($old_name==$name) return(FALSE); // prevent usage of opf_unregister_filter() in plugin_uninstall.php
    $old_name = $name;
    // check whether $name is in DB
    if(opf_is_registered($name)) {
        $pos = opf_get_position($name);
        $type = opf_get_type($name);
        // delete plugin-dir if present
        if($plugin_dir = opf_db_query_vars( "SELECT `plugin` FROM `{TP}mod_outputfilter_dashboard` WHERE `name`='%s'", $name)) {
            if($plugin_dir && file_exists(WB_PATH.'/modules/outputfilter_dashboard/plugins/'.$plugin_dir)) {
                // uninstall.php present? include it
                if(file_exists(WB_PATH.'/modules/outputfilter_dashboard/plugins/'.$plugin_dir.'/plugin_uninstall.php'))
                    opf_unregister_call_uninstall(WB_PATH.'/modules/outputfilter_dashboard/plugins/'.$plugin_dir.'/plugin_uninstall.php');
                opf_io_rmdir(WB_PATH.'/modules/outputfilter_dashboard/plugins/'.$plugin_dir);
            }
        }
        $res = opf_db_run_query( "DELETE FROM `{TP}mod_outputfilter_dashboard` WHERE `name`='%s'", $name);
        if($res) {
            if(class_exists('Settings') && defined('WBCE_VERSION')){
                Settings::Del( opf_filter_name_to_setting($name));
                Settings::Del( opf_filter_name_to_setting($name).'_be');
            }

            if(opf_db_run_query( "UPDATE `{TP}mod_outputfilter_dashboard` SET `position`=`position`-1
                      WHERE `type`='%s' AND `position`>%d", $type, $pos))
            return(TRUE);
        }
    }
    return(FALSE);
}


/*
    Function: opf_register_frontend_files
        Register JS- or CSS-files to be loaded into the page's <head>-section.

    Prototype:
        %bool% opf_register_frontend_files( %string% $file, %string% $type, %string% $target='head', %string% $media='screen', %string% $iehack )

    Parameters:
        $file - %(string)% URL of file to register, or "inline" JS-script or CSS-style.
        $type - %(string)% Type: '!js!' or '!css!'.
        $target - %(string)% Where to insert the file: '!head!' or '!body!'.
        $media - %(string)% media, for stylesheets only, e.g.: '!screen!' or '!screen,print!'. Use '' (empty string) otherwise.
        $iehack - %(string)% Special IE-Hack (for type !js! only).

    Returns:
        Always !TRUE!.

    Examples:
        > // register JS-script
        > opf_register_frontend_files(WB_URL.'modules/opf_prettify/prettify.js', 'js');
        > // register JS-script in <body>
        > opf_register_frontend_files(WB_URL.'modules/opf_prettify/prettify.js', 'js', 'body');
        > // register CSS-Stylesheet
        > opf_register_frontend_files(WB_URL.'/modules/opf_pcdtr/pcdtr/styles.css', 'css');
        > // IE-Hack
        > opf_register_frontend_files(WB_URL.'/modules/opf_fix_png_ie6/sl.js', 'js', 'head', '', '[if lte IE 6]');
        > // output: <!--[if lte IE 6]><script ...></script><![endif]-->

        > // usage of "inline"-script
        > opf_register_frontend_files('
        >   <script type="text/javascript">function do_highlight() { highlighter.highlight(); }</script>
        >   ','js');
*/
function opf_register_frontend_files($file, $type, $target='head', $media='screen', $iehack='') {
    global $opf_HEADER; // global storage for all entries
    global $opf_BODY; // global storage for all entries
    if(!isset($opf_HEADER) || !is_array($opf_HEADER)) $opf_HEADER = array();
    if(!isset($opf_BODY) || !is_array($opf_BODY)) $opf_BODY = array();
    $str = '';
    // file?
    if(($type=='js' && !preg_match('~\s*<script~',$file)) || ($type=='css' && !preg_match('~\s*<style~',$file))) {
        if($type=='js') {
            $str = '<script type="text/javascript" src="'.$file.'"></script>'."\n";
            if($iehack)
                $str = "<!--$iehack>\n".$str."\n<![endif]-->\n";
        }
        else
            $str = '<link rel="stylesheet" href="'.$file.'" type="text/css" media="'.$media.'" />'."\n";
    } else { // script
        if($type=='js' || $type=='css')
            $str = $file;
    }
    if($target=='head' && !in_array($str, $opf_HEADER) && !in_array($str, $opf_BODY))
        $opf_HEADER[] = $str;
    if($target=='body' && !in_array($str, $opf_BODY) && !in_array($str, $opf_HEADER))
        $opf_BODY[] = $str;
    return(TRUE);
}


/*
    Function: opf_register_onload_event
        Register an Javascript onload-function inside
        page's <head>-section, using window.attachEvent() or window.addEventListener().

    Prototype:
        %bool% opf_register_onload_event( %string% $function_name )

    Parameters:
        $function_name - %(string)% Name of JS-function to register.

    Returns:
        Always !TRUE!.

    Example:
        > opf_register_onload_event('prettyPrint');

    Notes:
        There is no way to supply arguments to the function, yet. Use <opf_register_onload> in
        case the function call needs arguments.
*/
function opf_register_onload_event($function_name) {
    global $opf_HEADER; // global storage for all entries
    if(!isset($opf_HEADER) || !is_array($opf_HEADER))
    $opf_HEADER = array();
    $str = '';
    if($function_name) {
        $str = "<script type=\"text/javascript\">if(window.attachEvent) window.attachEvent('onload',$function_name); else window.addEventListener('DOMContentLoaded',$function_name,false);</script>";
        if(!in_array($str, $opf_HEADER))
            $opf_HEADER[] = $str;
    }
    return(TRUE);
}


/*
    Function: opf_register_onload
        Register an Javascript script onload-function inside page's <body>-section.

    Prototype:
        %bool% opf_register_onload( %string% $script )

    Parameters:
        $script - %(string)% JS-script to register.

    Returns:
        Always !TRUE!.

    Example:
        > opf_register_onload('prettyPrint();');
        > opf_register_onload("supersleight.run('".WB_URL."/modules/opf_fix_png_ie6/x.gif');");
        > opf_register_onload('$(\'#tagSphere\').tagSphere();');
*/
function opf_register_onload($script) {
    global $opf_BODY; // global storage for all entries
    if(!isset($opf_BODY) || !is_array($opf_BODY))
    $opf_BODY = array();
    $str = '';
    if($script) {
        $str = "<script type=\"text/javascript\">$script</script>";
        if(!in_array($str, $opf_BODY))
            $opf_BODY[] = $str;
    }
    return(TRUE);
}

/*
    Function: opf_register_document_ready
        Register an Javascript onload-event inside
        page's <head>-section, using jquery's !jQuery(document).ready()! method.

    Prototype:
        %bool% opf_register_document_ready( %string% $js )

    Parameters:
        $js - %(string)% The Javascript-Code to use inside !$(document).ready()!.

    Returns:
        Always !TRUE!.

    Requires:
        This function requires that jQuery is loaded in the page's <head>-section.

    Example:
        >opf_register_document_ready('alert("OK!");');
        >opf_register_document_ready('$(\'#tagSphere\').tagSphere();');
*/
function opf_register_document_ready($js) {
    global $opf_HEADER; // global storage for all entries
    if(!isset($opf_HEADER) || !is_array($opf_HEADER))
    $opf_HEADER = array();
    $str = '';
    if($js) {
        $str = '<script type="text/javascript">jQuery(document).ready(function() {'.$js.'});</script>';
        if(!in_array($str, $opf_HEADER))
            $opf_HEADER[] = $str;
    }
    return(TRUE);
}


/*
    Function: opf_find_class
        Check whether a class (or any other attribute) is present in content.

    Prototype:
        %bool% opf_find_class( %string% $content, %string% $match, %string% $tag='', %string% $attr='class' )

    Parameters:
        $content - %(string)% Content.
        $match - %(string/regex)% String to search for.
        $tag - %(string/regex)% HTML-tag. Defaults to '' (nothing).
        $attr - %(string/regex)% Attribute to check. Defaults to '!class!'.

    Returns:
        !1! if !$match! is present, !0! otherwise. In case of error, this function returns !FALSE!.

    Example:
        (start code)
        // apply filter only if class 'special_class' is present somewhere in $content
        if(opf_find_class($content, 'special_class')) {
            // ... apply filter
            return(TRUE);
        } else {
            return(TRUE); // quit
        }
        (end)

        (start code)
        // apply filter only if class 'php_highlighter' is present inside a <pre>-tag
        // <pre class="class1 php_highlighter">...</pre>
        if(opf_find_class($content, 'php_highlighter', 'pre')) {
            // ... apply filter
        (end)

        (start code)
        // apply filter only if id 'tagSphere' is present inside a <div>-tag
        // <div class="class2" id="tagSphere">...</div>
        if(opf_find_class($content, 'tagSphere', 'div', 'id')) {
            // ... apply filter
        (end)

        Regular expressions are allowed, too. Those regexs are performed ungreedy (PCRE_UNGREEDY).
        (start code)
        // search for class "cpp", "c" or "c++" in HTML-tag <pre> or <textarea>
        if(opf_find_class($content, '(cpp|c|c\+\+)', '(pre|textarea)')) {
            // ... apply filter
        (end)

*/
function opf_find_class($content, $match, $tag='', $attr='class') {
    if(!is_string($content)) { trigger_error('opf_find_class(): content is not a string', E_USER_WARNING); return(FALSE); }
    if($tag!='') $tag = $tag.' ';
    // do not use preg_quote, to allow e.g. $tag="(pre|code)"
    return(preg_match("~<{$tag}[^<>]*$attr\s*=\s*\"([^\"<>\s]*\s+)*$match(\s+[^\"<>]*)*\"~iU", $content));
}


/*
    Function: opf_add_class
        Add a class to a present HTML-tag, i.e. !<pre>!. The class will be added to all appearance of the given HTML-tag.

    Prototype:
        %bool% opf_add_class( %string% $content, %string% $class, %string% $tag )

    Parameters:
        $content - %(string)% Content (by reference).
        $class - %(string)% Name of class to add.
        $tag - %(string/regex)% HTML-tag, e.g. '!pre!'.

    Returns:
        !TRUE! if !$class! was added, !FALSE! otherwise.

    Example:
        (start code)
        // adds class "prettyPrint" to all <pre> tags
        opf_add_class($content, 'prettyPrint', 'pre');
        (end)

        (start code)
        // adds class "prettyPrint" to all <pre> and <code> tags
        opf_add_class($content, 'prettyPrint', '(?:pre|code)');
        (end)

    Note:
        !$tag! can be a regular expression (PCRE), too. But keep in mind that no "capturing groups" are allowed,
        i.e. use always !?:! e.g.: '!(?:pre|code)!'. Per default those regexs are performed ungreedy (PCRE_UNGREEDY).

*/
function opf_add_class(&$content, $class, $tag) {
    if(!is_string($content)) { trigger_error('opf_add_class(): content is not a string', E_USER_WARNING); return(FALSE); }
    // if the html-tag has no class-attribute given, this function will add a class-attribut like this: class=" name" (note the space, this can't be fixed)
    // if a class is present, the additional class will be added, e.g. class="old new"
    $res = preg_replace("~(<$tag )(?:([^<>]*)class\s*=\s*\"([^\"<>]*)\"([^<>]*)|([^<>]*))(\s*>)~iU",'$1 class="$3 '.$class.'" $2$4$5$6',$content);
    if($res===NULL)
        return(FALSE);
    $content = $res;
    return(TRUE);
}


/*
    Function: opf_add_class_to_class
        Add a class to an already present class.
        The new class will be added to all appearance of the given class.

    Prototype:
        %bool% opf_add_class_to_class( %string% $content, %string% $class, %string% $present_class, %string% $tag='' )

    Parameters:
        $content -       %(string)%       Content (by reference).
        $class -     %(string)%       Name of class to add.
        $present_class - %(string/regex)% Name of class to add $class to.
        $tag -       %(string/regex)% HTML-tag. Defaults to ''.

    Returns:
        !TRUE! if !$class! was added, !FALSE! otherwise.

    Example:
        (start code)
        // add class "php" to present class "prettify"
        opf_add_class_to_class($content, 'php', 'prettify')
        // <pre class="prettify"> ... </pre>
        // will become
        // <pre class="prettify php"> ... </pre>
        (end)

        (start code)
        // add class "php" to present class "prettify" but only for <code>-tags
        opf_add_class_to_class($content, 'php', 'prettify', 'code')
        // <pre class="prettify"> ... </pre>
        // <code class="prettify">...</code>
        // will become
        // <pre class="prettify"> ... </pre>
        // <code class="prettify php">...</code>
        (end)

    Note:
        !$present_class! and !$tag! can be a regular expression (PCRE), too. But keep in mind that no "capturing groups" are allowed,
        i.e. use always !?:! e.g.: '!(?:pre|code)!'. Per default those regexs are performed ungreedy (PCRE_UNGREEDY).

*/
function opf_add_class_to_class(&$content, $class, $present_class, $tag='') {
    if(!is_string($content)) { trigger_error('opf_add_class_to_class(): content is not a string', E_USER_WARNING); return(FALSE); }
    if($tag!='') $tag .= ' ';
    $res = preg_replace("~(<{$tag}[^<>]*class\s*=\s*\"[^\"<>]*$present_class)([^\"<>]*\")~iU",'$1 '.$class.'$2',$content);
    if($res===NULL)
        return(FALSE);
    $content = $res;
    return(TRUE);
}


/*
    Function: opf_add_class_to_attr
        Add a class to all HTML-tags that has a given attribute.

    Prototype:
        %bool% opf_add_class_to_attr( %string% $content, %string% $class, %string% $attr, %string% $value, %string% $tag='' )

    Parameters:
        $content - %(string)%       Content (by reference).
        $class -   %(string)%       Name of class to add.
        $attr -    %(string/regex)% Name of attribute that has to be present.
        $value -   %(string/regex)% Content of that attribute.
        $tag -     %(string/regex)% HTML-tag. Defaults to ''.

    Returns:
        !TRUE! if !$class! was added, !FALSE! otherwise.

    Examples:


    Note:
        !$attr!, !$value! and !$tag! can be a regular expression (PCRE), too. But keep in mind that no "capturing groups" are allowed,
        i.e. use always !?:! e.g.: '!(?:pre|code)!'. Per default those regexs are performed ungreedy (PCRE_UNGREEDY).
*/
function opf_add_class_to_attr(&$content, $class, $attr, $value, $tag='') {
    if($attr=='class')
        return(opf_add_class_to_class($content, $class, $value, $tag));
    if(!is_string($content)) { trigger_error('opf_add_class_to_attr(): content is not a string', E_USER_WARNING); return(FALSE); }
    $res = preg_replace("~<({$tag}[^ ]*?)(?:([^<>]*)(?:class\s*=\s*\"([^\"]*)\")?([^<>]*$attr\s*=\s*\"\s*$value\s*\"[^<>]*)(?(3)|class\s*=\s*\"([^\"]*)\")([^<>]*)|([^<>]*$attr\s*=\s*\"\s*$value\s*\"[^<>]*))>~iU", '<$1 class="$3$5 '.$class.'" $4 $2$6$7>', $content);
    if($res===NULL)
        return(FALSE);
    $content = $res;
    return(TRUE);
}


/*
    Function: opf_cut_extract
        Cuts pieces out of content using a regular expression and replace them by placeholders.

    Prototype:
        %array% opf_cut_extract( %string% &$content, %string% $regex, %string% $subpattern=0, %string% $modifers='iU', %string% $delimiter='~', %string% $extracts='' )

    Parameters:
        $content -    %(string)% Content (by reference).
        $regex -      %(string)% Regular expression (PCRE), without delimiters and modifers, e.g.: '!<input[^>]+/>!'. The regex is applied UNGREEDY per default.
        $subpattern - %(string)% Use the #.subpattern. Defaults to !0!.
        $modifers -   %(string)% PCRE-modifiers to use, Defaults to '!iU!'.
        $delimiter -  %(string)% Delimiter to use. Defaults to '!~!'.
        $extracts -   %(array)%  An array returned by a former call to this function (optional).

    Returns:
        Array containing the extracts. The array will be empty if $regex didn't match.

        In case of error, this function returns !FALSE! and emits a error-message (level: E_USER_WARNING)

    Example:
        Assume this filter-function:
        (start code)
        function opff_work_on_links(&$content, $page_id, $section_id, $module, $wb) {
            $extracts = opf_cut_extract($content, '<a href=[^>]+>.*</a>'); // cut $extracts out of content
            var_dump($extracts);
            var_dump($content);
            opf_glue_extract($content, $extracts); // put $extracts back into $content
            return(TRUE);
        }
        (end)
        Running this filter-function, !$extracts! may contain:
        (start code)
        array
            '@@@OPF_EXTRACT_494e430fc8cf1@@@' => string '<a href="http://localhost/wbtest" target="_top"  class="menu_current"> start#22 </a>' (length=84)
            '@@@OPF_EXTRACT_494e430fc8d06@@@' => string '<a href="http://localhost/wbtest/pages/test-mit-umbruch.php" target="_top"  class="menu_default"> Test! mit! Umbruch </a>' (length=121)
            '@@@OPF_EXTRACT_494e430fc8d15@@@' => string '<a href="http://localhost/wbtest/pages/news.php" target="_top"  class="menu_default"> News </a>' (length=95)
            ...
        (end)
        and !$content!:
        (start code)
        ...
        <tr>
            <td width="170">
                <ul>
                <li><span class="menu_current">@@@OPF_EXTRACT_494e430fc8cf1@@@</span></li>
                <li><span class="menu_default">@@@OPF_EXTRACT_494e430fc8d06@@@</span></li>
                <li><span class="menu_default">@@@OPF_EXTRACT_494e430fc8d15@@@</span></li>
        ...
        (end)

        Example:
        (start code)
        // PAGE_LAST-Filter: all '#' in menu-title will be converted to '<br />',
        // to achieve an explicitly line-break in menu.
        // Use menu-title e.g.: "entry# with linebreak", which will be converted to entry<br /> with linebreak
        // Notice the use of $subpattern to fetch the (.*)-subpattern only.
        // This example assumes that all menu-links will have a class="menu..." set.
        function opff_menu_linebreak(&$content, $page_id, $section_id, $module, $wb) {
            // cut pieces out of $content
            $extracts = opf_cut_extract($content, '<a href=[^>]*class="menu[^>]*>(.*)</a>', 1, 'iUs');
            // check if opf_cut_extract() returned without error
            if($extracts) {
                foreach($extracts as $key => $str) {
                    // modify
                    $extracts[$key] = str_replace('#','<br />', $str);
                }
                // write $extracts back to $content
                opf_glue_extract($content, $extracts);
                return(TRUE);
            } else {  // opf_cut_extract() failed. $content may be damaged, so return FALSE
                return(FALSE);
            }
        }
        (end)
*/
function opf_cut_extract(&$content, $regex, $subpattern=0, $modifers='iU', $delimiter='~', $extracts='') {
    if(!is_string($content)) { trigger_error('opf_cut_extract(): content is not a string', E_USER_WARNING); return(FALSE); }
    if(!is_array($extracts)) $extracts = array();
    // cuts pieces out of $content, adds placeholders to content
    // regex has to be a perl-like regular-expression without delimiter and modifers. e.g. '<textarea.*</textarea>'
    $extracts_new = array();
    $matches = opf_fetch_extract($content, $regex, $subpattern, $modifers, $delimiter);
    if($matches===FALSE) return(FALSE);
    if(count($matches)==0) // no matches
        return($extracts);
    $content = str_replace($matches, array_keys($matches), $content);
    $extracts = array_merge($extracts, $matches);
    return($extracts);
}


// used internal only. Called by opf_cut_extract()
/*
    Private Function: opf_fetch_extract
        Description

    Prototype:
        %array% opf_fetch_extract( %string% &$content, %string% $regex, %string% $subpattern=0, %string% $modifers='iU', %string% $delimiter='~' )

*/
function opf_fetch_extract($content, $regex, $subpattern=0, $modifers='iU', $delimiter='~') {
    if(!is_string($content)) { trigger_error('opf_fetch_extract(): content is not a string', E_USER_WARNING); return(FALSE); }
    // copy pieces out of $content. Does not add placeholders to content, as code is just copied
    // regex has to be a perl-like regular-expression without delimiter and modifers. e.g. '<textarea.*</textarea>'
    $regex = $delimiter.$regex.$delimiter.$modifers;
    $res = preg_match_all($regex, $content, $matches);
    if($res===FALSE) { // syntax error in regex
        trigger_error('opf_fetch_extract(): syntax error in regex', E_USER_WARNING);
        return(FALSE);
    }
    $extracts = array();
    foreach($matches[$subpattern] as $block) {
        $u = uniqid('OPF_EXTRACT_');
        $extracts['@@@'.$u.'@@@'] = $block;
    }
    return($extracts);
}


/*
    Function: opf_glue_extract


    Prototype:
        %bool% opf_glue_extract( %string% &$content, %string% $extracts )

    Parameters:
        $content -    %(string)% Content (by reference).
        $extracts -   %(array)%  An array returned by <opf_cut_extract()>.

    Notes:
        See <opf_cut_extract()>.

    Returns:
        !TRUE!.
*/
function opf_glue_extract(&$content, $extracts) {
    if(!is_string($content)) { trigger_error('opf_glue_extract(): content is not a string', E_USER_WARNING);
        return(FALSE); }
    // puts extracts back to content, replaces placeholders with actual values
    if(is_array($extracts) && $extracts!=array())
        $content = str_replace(array_keys($extracts), $extracts, $content);
    return(TRUE);
}


// used internal only by opf_filter_get_data()
function opf_filter_get_name_current() {
    global $opf_FILTERS;
    if(!(isset($opf_FILTERS) && is_array($opf_FILTERS))) {
        opf_preload_filter_definitions();
    }
    foreach($opf_FILTERS as $key => $filter) {
        if($opf_FILTERS[$key]['current']==TRUE)
            return($key);
    }
    return(FALSE);
}


/*
    Function: opf_filter_get_data
        Fetch data from filter.

    Prototype:
        %array% opf_filter_get_data( %string% $name='' )

    Parameters:
        $name - %string% Name of Filter. Use '' (empty string) for current filter.

    Returns:
        Data of current filter, or !FALSE! in case of error.

    Example:
        (start code)
        $data = opf_filter_get_data();
        var_dump($data);
        (end)
        will output:
        (start code)
        array
            'id' => string '12' (length=2)
            'userfunc' => string '0' (length=1)
            'position' => string '1' (length=1)
            'active' => string '1' (length=1)
            'allowedit' => string '0' (length=1)
            'allowedittarget' => string '1' (length=1)
            'name' => string 'PrettyPrint: Google-Code-Prettify' (length=33)
            'func' => string '' (length=0)
            'type' => string '3section' (length=8)
            'file' => string '/var/www/wbtest/modules/opf_prettify/filter.php' (length=47)
            'csspath' => string '/var/www/wbtest/modules/opf_prettify/prettify/prettify.css' (length=58)
            'funcname' => string 'opff_prettify' (length=13)
            'configurl' => string '' (length=0)
            'modules' =>
                array
                    0 => string 'download_gallery' (length=16)
                    1 => string 'manual' (length=6)
                    2 => string 'news' (length=4)
                    3 => string 'wysiwyg' (length=7)
            'desc' => string 'Prettifies all &lt;pre class=&quot;prettyprint&quot;&gt; and ...' (length=455)
            'pages' =>
                array
                    0 => string 'all' (length=3)
                    1 => string '99' (length=2)
                    2 => string '104' (length=3)
                    3 ...
            'pages_parent' =>
                array
                    0 => string 'all' (length=3)
                    1 => string '99' (length=2)
                    2 => string '104' (length=3)
                    3 ...
            'current' => boolean true
            'activated' => boolean false
            'failed' => boolean false
        (end)
*/
function opf_filter_get_data($name='') {
    global $opf_FILTERS;
    if(!(isset($opf_FILTERS) && is_array($opf_FILTERS))) {
        opf_preload_filter_definitions();
    }
    $name = opf_check_name($name);
    if(!$name)
        $name = opf_filter_get_name_current();
    if($name && array_key_exists($name, $opf_FILTERS))
        return($opf_FILTERS[$name]);
    else return(FALSE);
}


/*
    Function: opf_filter_get_rel_pos
        Checks if a given filter was or will be executed.

    Prototype:
        %int% opf_filter_get_rel_pos( %string% $name )

    Parameters:
        $name - %string% Name of Filter to test.

    Returns:
        -1    - Filter !$name! was executed before the current one
        0     - Filter !$name! is the current one
        1     - Filter !$name! will be executed later
        FALSE - Filter !$name! is not active, or not installed. Or an error occurred.

    Examples:
        (start code)
        if(opf_filter_get_rel_pos('Menu Linebreak')) {
            // filter "Menu Linebreak" is installed and active (but not the currend one)
        }
        (end)

        (start code)
        $pos = opf_filter_get_rel_pos('Menu Linebreak');
        if($pos==1) {
            // filter Menu Linebreak will be called after this one
        } elseif($pos==-1) {
            // filter Menu Linebreak was already called before this one
        }
        (end)
*/
function opf_filter_get_rel_pos($name) {
    global $opf_FILTERS; // global storage
    global $opf_MODULE, $opf_PAGEID; // set by opf_apply_filters()
    if(!(isset($opf_FILTERS) && is_array($opf_FILTERS))) {
        opf_preload_filter_definitions();
    }
    if(!isset($opf_MODULE)) $opf_MODULE = FALSE;
    if(!isset($opf_PAGEID)) $opf_PAGEID = FALSE;
    if($opf_MODULE=='') $opf_MODULE = FALSE;
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    // get data from filter $name
    if(!($data = opf_filter_get_data($name)))
        return(FALSE);
    // active?
    if(!opf_is_active($name)) return(FALSE);
    // check type, module, page
    if(opf_type_uses_sections($data['type'])) {
        if(!in_array($opf_MODULE, $data['modules']) && !in_array('all', $data['modules']))
            return(FALSE);
    }

    if (!( ( ($opf_PAGEID == 'backend') && (in_array('backend', $data['pages']) || in_array('backend',$data['pages_parent'])) )
      || ( ($opf_PAGEID != 'backend') && (in_array('all', $data['pages']) || in_array($opf_PAGEID, $data['pages'])) )
      || ( ($opf_PAGEID != 'backend') && (in_array('all', $data['pages_parent']) || in_array($opf_PAGEID, $data['pages_parent'])) )
      || ( ($opf_PAGEID == '0') && (in_array('all', $data['pages']) || in_array('all', $data['pages_parent']) || in_array('0', $data['pages_parent'])) )
    ))  return(FALSE);

    // 1: filter will be activated later, -1: filter was activated, 0: filter identical
    if($data['current'])
        return(0);
    if($data['activated']==FALSE && $data['failed']==FALSE)
        return(1);
    if($data['activated']==TRUE || $data['failed']==TRUE)
        return(-1);
    return(FALSE); // not installed or inactive
}


/*
    Function: opf_filter_exists
        Checks whether a given filter exists

    Prototype:
        %bool% opf_filter_exists( %string% $name, %bool% $verbose )

    Parameters:
        $name - %string% Name of Filter to test.
        $verbose - %bool% Outout a Error message (E_USER_WARNING) if !$filter! doesn't exists.

    Returns:
        !TRUE! if !$filter! exists (i.e. is installed), !FALSE! otherwise.

    Examples:
        (start code)
        if(opf_filter_exists('Menu Linebreak')) {
            // filter "Menu Linebreak" is installed
        }
        (end)
*/
function opf_filter_exists($name, $verbose=FALSE) {
    global $opf_FILTERS;
    if(!(isset($opf_FILTERS) && is_array($opf_FILTERS))) {
        opf_preload_filter_definitions();
    }
    if(!isset($opf_FILTERS[$name])) {
        if($verbose) trigger_error('opf_filter_exists(): '.htmlspecialchars($name,ENT_QUOTES).' not defined', E_USER_WARNING);
        return(FALSE);
    } else
        return(TRUE);
}


/*
    Function: opf_is_childpage
        Checks whether a page is a subpage of a given page (or the same page)

    Prototype:
        %bool% opf_is_childpage( %int% $child,  %int% $parent )

    Parameters:
        $child - %int% Page-ID of the possible childpage
        $parent - %int% Page-ID of page to test against

    Returns:
        !TRUE! if !$child! is a childpage of !$parent! or if !$child! and !$parent! are the same pages.
        !FALSE! otherwise.

    Examples:
        (start code)
        if(opf_is_childpage(101, 9)) {
            // page-id 101 is a childpage of page-id 9
        }
        (end)
*/
function opf_is_childpage($child, $parent) {
    global $opf_PAGECHILDS;
    if(!(isset($opf_FILTERS) && is_array($opf_FILTERS))) {
        opf_preload_filter_definitions();
    }
    if($child==$parent) return(FALSE);
    if(isset($opf_PAGECHILDS[$parent])) {
        if(in_array($child, $opf_PAGECHILDS[$parent]))
            return(TRUE);
    }
    return(FALSE);
}


/*
    Function: opf_filter_is_active
        Checkes whether a given filter is active for the actual module and page_id

    Prototype:
        %bool% opf_filter_is_active( %string% $name )

    Parameters:
        $name - %string% Name of Filter

    Returns:
        !TRUE! if filter !$name! is active for actual module and page_id.
        !FALSE! otherwise.

    Examples:
        (start code)
        if(opf_filter_is_active('Menu Linebreak')) {
            // Filter 'Menu Linebreak' is active for actual module and page_id.
        }
        (end)
*/
function opf_filter_is_active($name) {
    if(opf_filter_get_rel_pos($name)===FALSE)
        return(FALSE);
    else
        return(TRUE);
}


/*
    Function: opf_filter_get_additional_values
        Receive additional filter arguments

    Prototype:
        %array% opf_filter_get_additional_values( %void% )

    This function fetches additional filter arguments from filter-settings.
    See <opf_register_filter()>.

    Returns:
        Array containing the additional arguments, or !FALSE! in case of error.

    Examples:
        (start code)
        $values = opf_filter_get_additional_values();
        $locale = $values['locale'];
        $date_formats = $values['date_formats'];
        (end)
*/
function opf_filter_get_additional_values() {
    global $opf_FILTERS;
    if(!(isset($opf_FILTERS) && is_array($opf_FILTERS))) {
        opf_preload_filter_definitions();
    }
    $name = opf_filter_get_name_current();
    if($name)
        return($opf_FILTERS[$name]['additional_values']);
    else return(FALSE);
}



/*
    Function: opf_filter_name_to_setting
        For WBCE 1.2: This function converts the name of a filter to the settings string
        which is associated with the active/inactive state of the given filter name.

    Prototype:
        %string% opf_filter_name_to_setting( %string% )

    Parameters:
        $name - %string% the name of the filter

    Returns:
        the name of the corresponding setting (in lowercase letters)

    Examples:
        (start code)
        if(Settings::Get(opf_filter_name_to_setting($name))){
          // do something;
        }
        (end)
*/
function opf_filter_name_to_setting($name) {
    return "opf_" . preg_replace('/[^a-z_]/','', str_replace(' ','_', strtolower($name)));
}
