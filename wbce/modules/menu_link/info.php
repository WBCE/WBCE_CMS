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

$core = true;
$module_directory   = 'menu_link';
$module_name        = 'Menu Link';
$module_function    = 'page';
$module_version     = '3.0.3';
$module_platform    = '1.7.0';
$module_author      = 'Ryan Djurovich, thorn, Christian M. Stefan';
$module_license     = 'GNU General Public License';
$module_description = 'This module allows you to insert a link into the menu.';
$module_icon        = 'fa fa-sitemap';

/**
 * Version history
 * 
 * 3.0.3
 *        - multi-driver SQL corrections (MySQL/SQLite) as preparation for
 *          experimental SQLite readiness
 *
 * 3.0.2
 *        - new third link type "Structure Only (no link)" (target_page_id=-2):
 *          renders href="#", not clickable, children still shown - for parent
 *          items that exist only to group their children in the menu
 *        - add.php: newly created menu_link pages now default to this
 *          structure-only state instead of an unconfigured, dead-clicking
 *          accessfile stub
 *        - fix modify.twig link-type toggle: .trigger('change') was firing the
 *          handler for every radio in the group (not just the checked one),
 *          so the UI always ended up reflecting whichever option was last in
 *          the DOM instead of the actual selection
 *        - new NO_LINK / NO_LINK_HINT language strings (DE/EN carefully
 *          translated, DA/FR/NL/NO/PL/RU best-effort)
 * 
 * 3.0.1  
 *        - set $core var, 
 *        - remove deprecated $module_level var
 * 
 * 3.0.0 
 *        - Adjustments to db queries (PDO)
 *        - added PL language file
 *        - changed RU language file to UTF-8 cyrillic and added a missing key
 *          Christian M. Stefan
 *
 * 2.9.8 - cs fixed files
 *
 * 2.9.7 - add redirection type "200"
 * 
 * 2.9.6 - MYSQL_ASSOC -> MYSQLI_ASSOC
 *
 * 2.9.5 - Add module_level core status
 *       - Update module_platform
 *
 * 2.9.4 - Add module_name translation
 *
 **/
