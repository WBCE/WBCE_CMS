<?php
/**
 * Core OutputFilters — OPF Dashboard plugin
 *
 * Consolidates the filters that were previously shipped as seven standalone
 * modules (mod_opf_auto_placeholder, mod_opf_csstohead, mod_opf_insert,
 * mod_opf_move_stuff, mod_opf_remove_system_ph, mod_opf_replace_stuff,
 * mod_opf_wblink).  Each had its own entry in the addons table and had to be
 * installed, upgraded and tracked independently — pure maintenance overhead for
 * functionality that is inseparable from the OPF Dashboard and the Code itself.
 *
 * With WBCE 1.7.0 and the introduction of framework/Assets/AssetQueue.php,
 * three of the seven filters became fully obsolete:
 *
 *   • Auto Placeholder  — AssetQueue::process() no longer needs <!--(PH)-->
 *                         markers; it finds </head> and <body> anchors directly.
 *   • CSS to head       — AssetQueue::scan() automatically moves every
 *                         <link rel="stylesheet"> and <style> block out of the
 *                         body into head_late.
 *   • Move Contents     — AssetQueue::processMoveBlocks() handles the legacy
 *                         <!--(MOVE) POSITION -->…<!--(END)--> syntax natively.
 *
 * The remaining four filters are bundled here as a single internal plugin:
 *
 *   • Internal Link Replacer  — resolves [pagelink:NN], [wblinkNN] and module item tokens via LinkResolver.
 *   • Replace Contents        — handles <!--(REPLACE)…--> in old templates.
 *   • Class Insert Helper     — triggers AssetQueue injection (I::doFilter).
 *   • Remove System PH        — strips any <!--(PH)…--> markers that may still
 *                               appear in third-party templates or modules.
 *
 * upgrade.php deletes the obsolete filter DB rows and removes all seven old
 * module directories and their addons-table entries automatically on upgrade.
 */
defined('WB_PATH') or die();

$plugin_directory   = 'core_outputfilters';
$plugin_name        = 'Core OutputFilters';
$plugin_version     = '1.7.0';
$module_status      = 'stable';
$module_platform    = '1.7.0';
$module_author      = 'WBCE CMS Project';
$module_license     = 'GNU General Public License, Version 3 or later';
$module_description = 'Core output filters required for WBCE CMS to function correctly.';
