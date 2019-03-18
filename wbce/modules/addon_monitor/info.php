<?php
/**
 * WebsiteBaker CMS AdminTool: addonMonitor
 *
 * This file defines the obligatory variables required for WebsiteBaker CMS
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     addonMonitor
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */
 
$module_directory 		= 'addon_monitor';
$module_name 			= 'Addon Monitor';
$module_function 		= 'tool';
$module_version 		= '0.6.5';
$module_status          = 'stable';
$module_platform 		= '1.0.0';
$module_author 			= 'Christian M. Stefan (Stefek)';
$module_license 		= 'GNU/GPL v.2';
$module_description 	= 'This AdminTool\'s entire purpose is to give you a handy way to overview all the installed add-ons. You will be able to see whether or not your installed modules are in use and where.';

/**
 * Version history
 *
 * 0.6.5
 *        - Use own jquery instead of Google CDN in colorbox fallback
 *
 * 0.6.4
 *        - corrects the path to the flag icons
 *
 * 0.6.3
 *        - Some wording updates
 *
 * 0.6.2
 *        - Removed Twig from modules addon_monitor and wbSeoTool
 *
 **/
