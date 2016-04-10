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
$module_description = 'Default Settings description.';
$module_title= "Default Settings";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'Default Settings';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Default Settings te';
