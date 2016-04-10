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
$module_description = 'SEO Settings like website title , keywords, description , header and footer .';
$module_title= "SEO Settings";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'SEO Settings';
$MOD_SET_GENERAL['DESCRIPTION'] =      'SEO Settings like website title , keywords, description , header and footer .';
