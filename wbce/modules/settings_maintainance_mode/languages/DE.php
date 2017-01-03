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
 
// Module description 
$module_description = 'Zugriff auf das gesamte Frontend deaktivieren';
$module_title = "Wartungsmodus";

// Language vars
$MOD_MAINTAINANCE['HEADER'] =      'Wartungsmodus';
$MOD_MAINTAINANCE['DESCRIPTION'] = 'Im Wartungsmodus wird Besuchern im Frontend nur eine Baustellenseite angezeigt. <br />Hinweis: Als im Backend angemeldeter Administrator sieht man die Frontend-Inhalte auch bei aktiviertem Wartungsmodus.';
$MOD_MAINTAINANCE['CHECKBOX'] =    'Wartungsmodus aktivieren';
