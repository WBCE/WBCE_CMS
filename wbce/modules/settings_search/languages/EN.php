<?php
/**
 * @category        modules
 * @package         SEO Settings
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2 or any later 
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));
 
// module description 
$module_description = 'Settings for website search.';
$module_title = "Search Settings";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'Search Settings';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Settings for website search.';
