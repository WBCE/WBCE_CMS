<?php

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../../index.php'));


opf_register_filter(
    array(
        'name' => 'Colorbox',
        'type' => OPF_TYPE_PAGE_FIRST,
        'funcname' => 'opff_jq_colorbox',
        'desc' => array(
            'EN' => "Use class=colorbox, iframe, youtube, cslide, cfade or csingle as additional CSS class to open a link in a modal window. 5 different styles available via filter config. See http://www.jacklmoore.com/colorbox/ for details.",
            'DE' => "Verwende class=colorbox, iframe, youtube, cslide, cfade or csingle als zusätzliche CSS-Klasse, um einen Link in einem Overlay zu öffnen. 5 verschiedene Styles per Filterkonfiguration auswählbar. Besuche http://www.jacklmoore.com/colorbox/ für Details."
        ),
        'file' => '{OPF:PLUGIN_PATH}/filter.php',
        'plugin' => 'opf_jq_colorbox',
        'helppath' => array(
                'EN'=> '{OPF:PLUGIN_URL}/help/EN.html',
                'DE'=> '{OPF:PLUGIN_URL}/help/DE.html'
        ),
        'active' => 1,
        'allowedit' => 1,
        'allowedittarget' => 1,
        'additional_fields' => array(

            array( // add select-field "head || body  ?"
                'text'     => 'Write js files to',
                'variable' => 'places',
                'type'     => 'select',
                'name'     => 'where',
                'checked'  => 'body',    // selected option
                'value' => array(        // options
                    'head'  => 'Head',
                    'body'  => 'Body'
                ),
            ),
            array( // add checkbox "$cb_design"
                'text'     => 'ColorBox Design Theme',
                'variable' => 'cb_design',
                 'type'    => 'select',
                'name'     => 'design',
                'checked'  => '1',       // selected option
                'value' => array(        // options
                    '1'  => '1',
                    '2'  => '2',
                    '3'  => '3',
                    '4'  => '4',
                    '5'  => '5',
                    'custom'  => 'custom',
                ),
            )
        )
    )
);

// set inactive by default if the module is already installed
if(is_dir(WB_PATH.'/modules/colorbox')) opf_set_active('jQ ColorBox',0);
