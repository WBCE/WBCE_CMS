<?php

/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

$module_directory   = 'droplets';
$module_name        = 'Droplets';
$module_function    = 'tool';
$module_version     = '3.0.1';
$module_platform    = '2.8.x';
$lepton_platform    = '1.x';
$module_author      = 'Ruud, pcwacht, WebBird (for WBCE)';
$module_license     = 'GNU General Public License v2';
$module_description = 'This tool allows you to manage your local Droplets.';
$module_icon        = 'fa fa-eyedropper';

$module_home = 'http://www.wbce.org';
$module_guid = '9F2AC2DF-C3E1-4E15-BA4C-2A86E37FE6E5';

/**
 * Version history
 *
 * v3.0.1 - cwsoft (fixed fatal error in case Droplet code syntax was invalid)
 *        - see https://github.com/WBCE/WebsiteBaker_CommunityEdition/issues/216
 *
 * v3.0.0 - norhei (Added Icon)
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
 * v1.64 - Bianka Martinovic ("WebBird")
 *       - fixed delete_droplet.php
 *
 * v1.63 - Bianka Martinovic ("WebBird")
 *       - use motTableSorter (loaded by LibraryAdmin) if available
 *
 * v1.6  - Bianka Martinovic ("WebBird")
 *       - changes needed to run with WB 2.8.2
 *
 * v1.51 - Bianka Martinovic ("WebBird")
 *       - updated NO language file
 *       - added RU language file (thanks to forum user "Eugene"!)
 *       - new: Error message when no droplet is marked while clicking on
 *              'Export' or 'Delete' button
 *
 * v1.5  - Bianka Martinovic ("WebBird")
 *       - fix: hardcoded admin path
 *
 * v1.4  - Bianka Martinovic ("WebBird")
 *       - new: Duplicate a droplet with one click
 *       - new: Show module version
 *       - new: added links to AMASP module and droplets download pages
 *       - new: Show droplets count
 *
 **/
?>
