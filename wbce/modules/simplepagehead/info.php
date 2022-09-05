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

$module_directory = 'simplepagehead';
$module_name = 'SimplePageHead';
$module_function = 'snippet';
$module_version = '0.8.1';
$module_platform = '1.4.0';
$module_author = 'Chio, with a litte help from thorn. Extended for use with other modules by Christoph Marti. Updated by Florian Meerwinck for WBCE';
$module_license = 'GNU General Public License';
$module_description = 'Snippet to generate better and simpler head tags (title, keywords.. ) for core and modules. Function can be invoked from the template. Simplest call: simplepagehead();';
$module_level = 'core';

/**
 * Version history
 *
 * 0.8.1 - PHP 8.1 fix (if $section not set)
 *
 * 0.8.0 - cs fixed files
 *       - fixed Versioning
 *       - updated Readme
 *
 * 0.7.4 - disable outdated notoolbar metatag
 *
 * 0.7.3 - add missing endtags
 *
 * 0.7.2 - Add module_level core status
 *       - Update module_platform
 *
 * 0.7.1 - Updated Touchicon integration
 *
 * 0.7.0 - Making use of Insert class
 *
 **/
