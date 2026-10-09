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
 * - Brodie Thiesfield (“brofield”) — original author, 2006–2013
 * - Manuela v.d.Decken — co-author (WebsiteBaker era)
 * - Norbert Heimsath (NorHei) 
 * - Martin Hecht (mrbaseman) — maintainer at the WB→WBCE transition, 2016–2019
 * - cwsoft — WBCE branding, 2015
 * - Christoph Bleiweis — WBCE maintainer, 2017–2018
 * - instantflorian — WBCE maintainer, 2017–2020
 * - Colinax — WBCE maintainer, 2019–2021
 * - Bianka Martinovic — WBCE, 2023
 * - Bernd Michna — listed as involved according to the former `module_author` field, WBCE wrapper developer according to web search[^bernd]; exact SM2 contributions not verifiable
 * - Christian M. Stefan — WBCE maintainer since 2018, among other things menu-link resolution, ARIA support, `show_menu()`/`SM_*` aliases (2026)
 */

$module_directory   = 'show_menu2';
$module_name        = 'show_menu2';
$module_function    = 'snippet';
$module_version     = '4.16.1';
$module_platform    = '1.7.0';
$module_author      = 'Brodie Thiesfield, WBCE Dev Team';
$module_license     = 'GNU General Public License v2';
$module_description = 'A code snippet for the WBCE CMS providing menu functions. See the <a href="' .WB_URL .'/modules/show_menu2/README.en.txt" target="_blank">readme</a> file or view <a href="https://sm2.wbce-cms.org/" target="_blank">sm2.wbce-cms.org</a>.';
$module_level       = 'core';

/* LATEST CHANGES (see HISTORY.md for a reconstruction attempt of the CHANGELOG)
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 * v. 4.16.1   2026-09-06  Christian M. Stefan
 *             Fixed a PHP 8.1+ deprecation notice ("Using null as the key
 *             parameter for array_key_exists() is deprecated") that was
 *             logged on every request for menus declared in the template
 *             but not assigned any pages. 
 *
 * v. 4.16.0   2026-08-05  Christian M. Stefan
 *             show_menu() is now available as a plain alias for show_menu2()
 *             (identical signature, no behavior change). Likewise, every
 *             SM2_* flag/option constant now has an equivalent SM_* alias
 *             (e.g. SM_TRIM == SM2_TRIM) - both prefixes are fully
 *             interchangeable and can be mixed freely.
 *
 * v. 4.15.0   2026-07-29  Christian M. Stefan
 *             menu_link targets are now resolved natively at menu-generation
 *             time instead of via the old SM2_CORRECT_MENU_LINKS constant +
 *             post-hoc string replacement (which ran N+1 DB queries per
 *             output() call). Internal targets always resolve automatically;
 *             external targets resolve when the new SM2_EXTERNAL_MENULINKS
 *             flag is passed. New SM2_USE_ARIA flag + [aria] format string
 *             add aria-current/aria-haspopup/aria-expanded for screen
 *             readers, inserted automatically into the built-in [a]/[ac]
 *             tags. The menu_link module also gained a "Structure Only (no
 *             link)" mode (href="#", children still shown) for parent items
 *             that only exist to group their children in the menu.
 *
 */
