<?php
/**
 * @file       functions.php
 * @category   admintool
 * @package    addon_monitor
 * @author     Christian M. Stefan (https://www.wbeasy.de)
 * @license    http://www.gnu.org/licenses/gpl.html
 * @platform   WBCE CMS 1.7.0
 */
$module_directory    = 'addon_monitor';
$module_name         = 'AddonMonitor';
$module_function     = 'tool';
$module_version      = '1.0.0';
$module_platform     = '1.7.0';  // !addon won't work with earlier versions of WBCE
$module_author       = 'Christian M. Stefan';
$module_license      = 'GNU/GPL v.2';
$module_description  = 'Monitoring of installed Add-Ons. Find out what Addons '
                    . '(Modules, Templates, Languages) are installed and in active use.';

$module_icon         = 'fa fa-plug';
/**
 * VERSION HISTORY
 *
 * 1.0.0 - Total cleanup and redesign of the `AddonMonitor` Admin-Tool.
 *         - Getting rid of all jQuery Plugins and changing vor simple Javascript
 *         - Modules now show all their functions (scopes of operation) if they are
 *           hybrid modules (e.g. Tool and Page or Snippet and Preinig)
 *         - Restrict access to tabs (modules, templates, languages) based on user permissions
 *         - Add multilingaulity, making use of new WBCE 1.7.0 Lang class.
 *         - All queries are rewritten to work with the new WBCE 1.7.0 PDO Database class        
 * 
 * 0.7.2 - fix for hybrid modules
 *
 * 0.7.1 - cs fixed files
 *
 * 0.7.0 - fix issues changed twig version and PHP 8
 *
 * 0.6.8 - add missing inactive icon (thanks to kleo)
 *
 * 0.6.6 - fix issue with warnings when OfA is installed (thanks to freesbee)
 *
 * 0.6.5 - fix issue with empty lists on PHP 7.4 / MySQL 8
 *
 * 0.6.4 - corrects the path to the flag icons
 *
 */
