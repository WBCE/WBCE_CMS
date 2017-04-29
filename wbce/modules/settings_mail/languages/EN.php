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
$module_description = 'Settings for WBCE Mailer.';
$module_title = "Mailer Settings";

// Headings and text outputs
$MOD_SET_MAIL['HEADER'] =           $HEADING['WBMAILER_SETTINGS'];
$MOD_SET_MAIL['DESCRIPTION'] =      $TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'];
