<?php
/**
 * @category        modules
 * @package         Maintainance Mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// module description
$module_description = 'Deactivate access to the whole frontend.';
$module_title = "Maintainance Mode";

// Headings and text outputs
$MOD_MAINTAINANCE['HEADER'] =      'Maintainance Mode';
$MOD_MAINTAINANCE['DESCRIPTION'] = 'In the maintainance mode visitors will only see an "under construction" message page. As logged-in administrator the pages are still accessable.';
$MOD_MAINTAINANCE['CHECKBOX'] =    'Activate Maintainance Mode';
