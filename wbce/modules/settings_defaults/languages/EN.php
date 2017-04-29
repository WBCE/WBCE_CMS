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
$module_description = 'Default values for language, timezone, date format';
$module_title = "Default Values";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'Default Values';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Default timezone, date format and language which can be individually changed in the user profile settings ';
