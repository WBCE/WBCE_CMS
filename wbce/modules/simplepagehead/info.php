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
 * 
 * @brief
 *   The SimplePageHead is responsible for managing and optimizing the `<head>` 
 *   section of WBCE CMS. It provides a centralized way to control meta tags, 
 *   scripts, styles, and other essential elements required for proper page 
 *   rendering and SEO.
 * 
 *   Originally developed by Chio, with additional contributions from thorn, 
 *   the module was later extended for broader compatibility with other modules 
 *   by Christoph Marti. 
 * 
 *   It has been further updated and maintained for WBCE CMS by Florian Meerwinck.
 *   Through these continuous improvements, the module has evolved into a 
 *   flexible and reliable solution for handling most <head>-related functionality 
 *   within the WBCE CMS.
 */

$core = true;
$module_directory   = 'simplepagehead';
$module_name        = 'SimplePageHead';
$module_function    = 'snippet';
$module_version     = '0.8.4';
$module_platform    = '1.4.0';
$module_author      = '';
$module_author      = 'Chio, thorn, Christoph Marti, Florian Meerwinck';
$module_license     = 'GNU General Public License';
$module_description = 'Snippet to generate better and simpler head tags (title, keywords.. ) for core and modules. Function can be invoked from the template. Simplest call: simplepagehead();';

/**
 * Version history
 * 
 * 
 * 0.8.4 
 *        - set $core var, 
 *        - remove deprecated $module_level var
 * 
 * 0.8.3 - better parameter handling 
 *
 * 0.8.2 - more SEO friendly title (hopefully), remove default generator tag
 *
 * 0.8.1 - PHP 8.1 fix (if $section not set)
 *
 * 0.8.0 
 *       - cs fixed files
 *       - fixed Versioning
 *       - updated Readme
 *
 * 0.7.4 - disable outdated notoolbar metatag
 *
 * 0.7.3 - add missing endtags
 *
 * 0.7.2 
 *       - Add module_level core status
 *       - Update module_platform
 *
 * 0.7.1 - Updated Touchicon integration
 *
 * 0.7.0 - Making use of Insert class
 *
 **/
