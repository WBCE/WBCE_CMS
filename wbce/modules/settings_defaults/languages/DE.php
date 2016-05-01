<?php
/**
 * @category        modules
 * @package         Default Settings
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2 or any later 
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));
 
// module description 
$module_description = 'Systemweite und allgemeingültige Einstellungen und Vorgaben';
$module_title= "Grundeinstellungen";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'Grundeinstellungen';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Systemweite und allgemeingültige Einstellungen und Vorgaben für diese WBCE-Installation';
