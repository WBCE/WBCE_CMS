<?php
/**
 * WBCE CMS AdminTool: tool_debug_dump
 *
 * @platform    WBCE CMS 1.7.0
 * @package     modules/tool_debug_dump
 * @author      Christian M. Stefan (https://www.wbEasy.de)
 * @copyright   Christian M. Stefan (2026)
 * @license     GNU/GPL2
 */
 
// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));  

// default settings
$aCfg = array(
    'theme'     => "dark", // classic|dark
    'font'      => 'Proggy',
    'font_size' => '15',  // px
    'height'    => '300', // px
    'run'       => true
);

// write default settings into {TP}settings table
Settings::set("debug_dump_cfg", serialize( $aCfg ));