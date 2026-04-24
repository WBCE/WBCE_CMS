<?php
/**
 * @category        modules
 * @package         Maintenance Mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */


$core = true;
$module_directory   = 'maintainance_mode';
$module_name        = 'Maintenance Mode Switcher';
$module_function    = 'tool';
$module_version     = '1.1.6';
$module_platform    = '1.4.0';
$module_author      = 'Norbert Heimsath(heimsath.org)';
$module_license	    = 'WTFPL';
$module_description = 'This tool offers a plain and simple switch to turn maintenance mode on and off.';
$module_icon        = 'fa fa-wrench';

/**
 * Version history
 *
 * 1.1.6  
 *        - set $core var, 
 *        - remove deprecated $module_level var
 * 
 * 1.1.5  - (stefanek) minor cleanup
 * 
 * 1.1.4  - (florian) correct spelling (kept wrong directory name for compatibility reasons)
 *
 * 1.1.3
 *        - Add module_level core status
 *        - Update module_platform 
 *
 * 1.1.2
 *        - Add module_name translation
 *
 **/
