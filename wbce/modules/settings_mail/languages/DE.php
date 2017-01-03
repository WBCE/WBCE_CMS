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
$module_description = 'Einstellungen für den Versand system- und modulgenerierter E-Mails';
$module_title= "Mail-Einstellungen";

// Headings and text outputs
$MOD_SET_MAIL['HEADER'] =           "Mail-Einstellungen";
$MOD_SET_MAIL['DESCRIPTION'] =      $TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'];
