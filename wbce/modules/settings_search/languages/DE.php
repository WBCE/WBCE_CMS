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
$module_description = 'Darstellung und Ergebnisanzeige der Suchfunktion anpassen.';
$module_title = "Such-Einstellungen";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'Einstellungen für die Website-Suche';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Darstellung und Ergebnisanzeige der Suchfunktion anpassen.';
