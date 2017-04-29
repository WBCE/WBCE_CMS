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
$module_description = 'Sprache, Zeitzone, Datums- und Uhrzeitformat';
$module_title = "Standardwerte";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'Standardwerte';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Vorgaben für Sprache, Zeitzone/-format und Datum, die in den Benutzerprofilen individuell abweichend gesetzt werden können';
