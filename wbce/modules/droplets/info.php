<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */


$module_directory = 'droplets';
$module_name      = 'Droplets';
$module_function  = 'tool';
$module_version   = '2.4.0';
$module_platform  = '1.6.0';
$module_author    = 'Ruud, pcwacht, WebBird, cwsoft, Norhei, Colinax, Christian M. Stefan (Stefek)';
$module_license   = 'GNU General Public License';
$module_description = 'This tool allows you to manage your local Droplets.';
$module_icon  = 'fa fa-tint';
$module_home  = 'https://addons.wbce.org/pages/addons.php?do=item&item=54';
$module_guid  = '9F2AC2DF-C3E1-4E15-BA4C-2A86E37FE6E5';
$module_level = 'core';

/**
 * Version history
 * ===============
 *
 * 
 * v2.4.0 Stefek 06.02 2023
 *        - move OpF Filter file to `/modules/droplets/` directory 
             and remove `/modules/mod_opf_droplets/` directory
 *
 * v2.3.4 Stefek 05.02 2023
 *        - improve some javascript behavior
 *            [] inline delete confirm
 *            [] deactivat droplet from within droplets list
 *
 * v2.3.3 Stefek 04.02 2023
 *        - expand the readme/help file
 *        - include German translation for readme/help file
 *        - insert credits of original and subsequent module devs 
 *
 * v2.3.2 Stefek 02.02 2023
 *        - template rework making the module ACPI Ready
 *        - add setting to show date (latest edit/creation)
 *
 *
 * v2.3.1 - Colinax
 *        - add mp4video Droplet 
 *        - removed some old Droplet
 *
 * v2.3.0 - Bianka Martinovic ("WebBird")
 *        - fix issues with changed twig version and PHP 8
 *
 * v2.2.9 - Bernd
 *        - fixed upgrade.php regarding MySQL-Strict / Doctrine (Bernd)
 *
 * v2.2.8 - Bernd
 *        - MYSQL_ASSOC -> MYSQLI_ASSOC
 *
 * 
 * v2.2.7
 *        - Add module_level core status
 *        - Update module_platform 
 *
 * v2.2.6
 *        - SectionPicker Droplet uses Insert class
 *
 * v2.2.5
 *        - small fixes
 *
 * v2.2.4 - norhei
 *        - added support for backend droplets
 *
 * v2.1.4 - colinax
 *        - Revert "Fix for no droplet bug" - The fix does not work correctly
 *        - add some missing css styles
 *
 * v2.1.3 - colinax
 *        - Fix for no droplet bug
 *
 * v2.1.2 - colinax
 *        - Bugfix for backup function
 *
 * v2.1.1 - colinax
 *        - some template bugfixes
 *
 * v2.1.0 - colinax
 *        - DEBUG mode disabled in add_droplet.php
 *        - Updated language files
 *        - Updated Help
 *        - Remove deprecated HTML Tags in functions.inc.php
 *        - Updated jquery.tablesorter.js - https://github.com/Mottie/tablesorter
 *
 * v2.0.2 - cwsoft & colinax
 *        - fixed fatal error in case Droplet code syntax was invalid)
 *        - see https://github.com/WBCE/WebsiteBaker_CommunityEdition/issues/216
 *
 * v2.0.1 - norhei & colinax
 *        - see https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f6e69206e22a6aa277b02b6c6887554a0da6371b
 *
 * v2.0.0 - Bianka Martinovic ("WebBird")
 *        - see https://github.com/WBCE/WebsiteBaker_CommunityEdition/issues/92
 *
 * v1.75 - Bianka Martinovic ("WebBird")
 *       - fixed "Undefined variable: imports" issue
 *       - fixed "Undefined offset: 0 in ./modules/droplets/install.php" issue
 *
 * v1.74 - Bianka Martinovic ("WebBird")
 *       - added shorturl droplet to installation
 *       - fixed little layout issue with Flat Theme
 *
 * v1.73 - Bianka Martinovic ("WebBird")
 *       - added 'false' to all occurances of "new admin()"; thnx to "Martin Hecht"
 *
 * v1.72 - Bianka Martinovic ("WebBird")
 *       - fix for WB 2.8.3 (some intval() too much...)
 *
 * v1.71 - Bianka Martinovic ("WebBird")
 * 		 - added some minor changes from Droplets 1.2.0 which is part of WB 2.8.3 bundle
 *
 * v1.70 - Bianka Martinovic ("WebBird")
 *		 - no longer uses jQueryAdmin or LibraryAdmin;
 *         tablesorter jQuery plugin included directly;
 *         please note that you will have to ensure that jquery is loaded!
 *
 **/
