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

$module_directory = 'sitemap';
$module_name      = 'Sitemap';
$module_function  = 'page';
$module_version   = '4.0.10';
$module_platform  = '1.4.0';
$module_author    = 'Ryan Djurovich, Frank Schoep, Woudloper, Ruebenwurzel, Rob Smith, Mouring Kolhoff, Michael Milette, Dietrich Roland Pehlke, Christian M. Stefan (Stefanek) (last)';
$module_license   = 'GNU General Public License';
$module_description = 'This module allows you to easily create a sitemap. You can even work with additional layout settings for the sitemap. It also gives you the option to show hidden menu items if required';




/*******************************************************************************\
[changelog]
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    DEVELOPMENT HISTORY (Change Log):

    v.4.0.10 Colinax
            remove MYSQLI_ASSOC

    v.4.0.9 Bernd
            added SM2_CORRECT_MENU_LINKS functionality

    v.4.0.8 Florian
            fix upgrade.php (missing variable definitions), branding

    v.4.0.7 Colinax
            upgrade.php fixes a bug that prevents the module from being updated manually

    v.4.0.6 Colinax
            Update module_platform

    v.4.0.5 Christian M. Stefan (Stefanek)
            clean up and use new DB functions

    v.4.0.4 2017-09-16 Christian M. Stefan (Stefanek)
        [c] mostly CSS file addition for the backend
        [+] added support for new CSS move to head functions
            see: https://forum.wbce.org/viewtopic.php?id=1598

    v.4.0.3 2017-01-30 Christian M. Stefan (Stefanek)
        [c] upgrade.php replace $database->error() with $database->get_error();
            thanks to Florian for appropriate handling suggestions

    v.4.0.2 2017-01-29 Christian M. Stefan (Stefanek)
        [c] remmove testing output in (view.php)

    v.4.0.1 2017-01-29 Christian M. Stefan (Stefanek)
        [c] correct typos in installation file (install.php)
        [c] rewrite upgrade.php

    v.4.0.0 2017-01-23 Christian M. Stefan (Stefanek)
        New DB fields allowing for new features
        [+] select the menu or several menus the SiteMap should
            include in the frontend output (only selectable if the DEFAULT_TEMPLATE
            has multiple menus enabled)
        [+] new placeholder [LEVEL] can be now used in level-header and sitemap-loop
        [+] add button to hide/show the settings
        [+] allow for tab indentation in textareas for more comfort
            in layout fields
        [+] add FTAN Support
        [+] added if(!function_exists("sitemap")) before the function;
            its non existence caused the page to break if sitemap module
            was used more than once on the same page
        [+] added preg_replace mechanism for advanced output filtering;
            this feature is disabled by default -- in order to use it: rename
            folder "_regex" to "regex" and include your own replace rules in
            the file regex_section_all.php if rules shall run on all sections
            or in the file regex_section_[section_in].php if only for specific
            sections
        [+] added initial README.md file
        [c] get rid of table layout in the settings
        [c] code cleanups
        [c] upgrade script changes to add new columms on upgrade

[/changelog]
\*******************************************************************************/
