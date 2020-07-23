<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga - www.dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.4+
 * @version         1.0
 * @lastmodified    November 12, 2019
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

$module_directory   = 'errorlogger';
$module_name        = 'Errorlog viewer';
$module_function    = 'tool,preinit,initialize';
$module_version     = '1.1.0';
$module_platform    = '1.4.x';
$module_author      = 'Ruud Eisinga - Dev4me';
$module_license	    = 'GNU General Public License';
$module_description = 'Catch PHP warnings and errors into a logfile and view them using this tool.';
$module_icon        = 'fa fa-tasks';
//$module_level       = 'core';


/*
 * preinit.php is used to activate the errorhandler
 *
 * initialize.php is used to set the errorlevel to E_ALL regardless the WB setting
 *
 */

/*******************************************************************************
	DEVELOPMENT HISTORY (Change Log):
    
	v.1.1.0 2020-07-23 Christian M. Stefan (Stefanek)
            [+] Implementation of Table View
            [c] changes to CSS file adding styles for the Table View
            
 */



