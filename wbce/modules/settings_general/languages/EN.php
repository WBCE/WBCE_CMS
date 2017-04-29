<?php
/**
 * @category        modules
 * @package         Maintainance Mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */
 
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// module description
$module_description = 'System settings like directory limits, trashbin, menu etc.';
$module_title = "General Settings";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'System settings';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Please be careful! It is strongly recommended not to change these settings on a running site unless you really know what you do.';
