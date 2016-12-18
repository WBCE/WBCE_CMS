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
$module_description = 'Systemweite und allgemeingültige Standard Einstellungen';
$module_title= "Standard Einstellungen";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'Standard Einstellungen';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Systemweite und allgemeingültige Standard Einstellungen, standard Template, standard Sprache, standard Zeitzone...';
