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
$module_description = 'Webseitentitel, Seitenbeschreibung, Kopf-/Fußzeile';
$module_title= "SEO/Metadaten-Einstellungen";

// Headings and text outputs
$MOD_SET_GENERAL['HEADER'] =           'SEO/Metadaten';
$MOD_SET_GENERAL['DESCRIPTION'] =      'Die hier hinterlegte Websitebeschreibung und die Schlüsselbegriffe werden verwendet, sofern bei Seiten keine eigenen Daten dafür hinterlegt sind. Die Verwendung der in Kopf- und Fußzeile hinterlegten Daten ist abhängig vom Template.';
