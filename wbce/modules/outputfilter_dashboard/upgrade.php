<?php
/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.7.0
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, 
 *                   Christian M. Stefan  (https://www.wbEasy.de), 
 *                   Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010-2023 Christian M. Stefan, 2016-2023 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            https://addons.wbce.org/pages/addons.php?do=item&item=53
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU General Public License, Version 3
 * @platform        WBCE 1.7.x
 * @requirements    PHP 8.1
 *
 * This file is part of OutputFilter-Dashboard, a module for WBCE and Website Baker CMS.
 *
 * OutputFilter-Dashboard is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OutputFilter-Dashboard is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OutputFilter-Dashboard. If not, see <http://www.gnu.org/licenses/>.
 *
 **/



// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../index.php'));

// obtain module directory
$mod_dir = basename(__DIR__);
require WB_PATH.'/modules/'.$mod_dir.'/info.php';

// include module.functions.php
include_once WB_PATH . '/framework/module.functions.php';

// include the module language file depending on the backend language of the current user
if (!include(get_module_language_file($mod_dir))) return;

// load outputfilter-functions
require_once __DIR__.'/functions.php';

// obtain module directory
$mod_dir = basename(__DIR__);
require WB_PATH.'/modules/'.$mod_dir.'/info.php';

// include module.functions.php
include_once WB_PATH . '/framework/module.functions.php';

// load outputfilter-functions
require_once __DIR__."/functions.php";

if(is_dir(WB_PATH.'/temp')){
    opf_io_mkdir(WB_PATH.'/temp/opf_plugins');
}

$database->query("DROP TABLE IF EXISTS `{TP}mod_outputfilter_dashboard_settings`");


// ── Remove what earlier versions installed and this one no longer ships ─────
//
// One list, one call each, using the core's removePath() instead of this
// module's opf_io_unlink()/opf_io_rmdir() and the older rm_full_dir(): it
// handles a single file and a whole tree alike, and it returns a signal saying
// what actually happened rather than a bare bool nobody looked at.
//
// This has to run BEFORE the plugin_install.php loop further down, which
// executes every plugin_install.php it finds under plugins/. As long as an
// upgraded site still had plugins/cachecontrol/ on disk, that loop re-created
// the "Cache Control" filter the rename above had just migrated away.
//
// Deliberately NOT in this list: config_init.php and precheck.php. Earlier
// versions of this file tried to delete both, but passed $mod_dir -- the bare
// directory *name*, not a path -- so the target resolved against the admin
// script's working directory and never matched. Both are still shipped by this
// module; that broken path was the only thing stopping the upgrade from
// deleting two live files.
$obsoletePaths = [
    // Renamed when the tool.php family was introduced (1.6.0)
    '/debug_config.php',            // no replacement
    '/debug_conf.php',              // no replacement
    '/add_filter.php',              // -> tool_add_filter.php
    '/edit_filter.php',             // -> tool_edit_filter.php
    '/css.php',                     // -> tool_edit_css.php
    '/ajax/ajax_dragdrop.js',       // -> ajax/ajax.js
    '/templates',                   // -> twig/, reworked to Twig

    // 1.7.0: assets collected under assets/, bundled UI libraries dropped
    '/backend.css',                 // -> assets/backend.css
    '/backend_body.js',             // -> assets/backend_body.js
    '/ajax/jquery.collapser.min.js',// -> opfInitShortDescriptions()
    '/dialog',                      // own popup dialogs -> Alerts/toasts
    '/images',                      // -> assets/images/

    // 1.7.0: "Cache Control" became the "Assets Cache Busting" plugin
    '/plugins/cachecontrol',

    // Superseded documentation
    '/docs',                        // generated phpDocumentor tree -> documentation/
    '/naturaldocs_txt',             // generator input for that tree
    '/readme',                      // screenshots -> documentation/images/
    '/CHANGELOG',                   // -> CHANGELOG.md
    '/README.txt',                  // -> README.md
    '/licenses.txt',                // -> LICENSE.md
    '/FTAN_SUPPORTED',              // marker file; FTAN has been core for years
];

// removePath()'s signals are translated under the SIGNAL namespace. They are
// read through L_() rather than the raw $SIGNAL array on purpose: upgrade.php is
// require'd from inside upgrade_module() and therefore runs in function scope,
// where a global array is only visible if it was imported -- and that function
// imports $database, $admin and $MESSAGE, not $SIGNAL. L_() reads the Lang
// registry, which is static and does not care about scope at all.
foreach ($obsoletePaths as $sRelPath) {
    $sSignal = removePath(__DIR__ . $sRelPath);

    // "Not found" is the normal outcome -- on a fresh install and on every
    // upgrade after the first. Only real removals and real problems are worth
    // a line of output.
    if ($sSignal === 'RM_PATH_NOT_FOUND') {
        continue;
    }

    echo L_("SIGNAL['$sSignal']", $mod_dir . $sRelPath) . '<br />';
}

// ── WBCE 1.7.0: rename "Cache Control" -> "Assets Cache Busting" ────────────
// Rename the existing row in place, BEFORE plugin_install.php re-registers
// below under the new name/plugin/funcname. opf_register_filter() looks up
// an existing row by `name` when no id is given (as is always the case from
// plugin_install.php); renaming here first means it finds this row and takes
// the UPDATE path, which preserves `active` and all other settings
// automatically. A plain name change without this step would instead insert
// a brand new row (active=1 default) and orphan the old one, silently
// discarding whatever on/off state the admin had set.
$database->query(
    "UPDATE `{TP_OPFD}` SET `name`=?, `plugin`=? WHERE `name`=? AND `plugin`=?",
    ['Assets Cache Busting', 'opf_assets_cache_busting', 'Cache Control', 'cachecontrol']
);
if($database->hasError()) {
    error_log('outputfilter_dashboard upgrade: rename Cache Control -> Assets Cache Busting failed: '.$database->getError());
}
// The old Settings keys (opf_cache_control / opf_cache_control_be) were
// derived from the old name and are now orphaned -- opf_register_filter()'s
// own opf_set_active() call below will already have written fresh
// opf_assets_cache_busting / _be keys with the preserved active value.
if (class_exists('Settings')) {
    Settings::delete('opf_cache_control');
    Settings::delete('opf_cache_control_be');
}

// run install scripts of plugin filters  - they should start upgrade if already installed
foreach( preg_grep('/\/plugin_install.php/', opf_io_filelist(__DIR__.'/plugins/')) as $installer){
    require $installer;
}



// Only block this if WBCE CMS installer is running, if this is an Upgrade or
// Module install, we need this. But the installer registers the filter-modules later.
if(!defined('WB_INSTALLER')){
    // run install scripts of module filters - they should start upgrade if already installed
    foreach( preg_grep('/\/install.php/', opf_io_filelist(WB_PATH.'/modules')) as $installer){
        if(strpos($installer,'outputfilter_dashboard')===FALSE){
            $contents = file_get_contents($installer);
            if(preg_match('/opf_register_filter/',$contents)){
                if (strpos($installer,'droplets')===FALSE) {
                    require $installer;
                }
            }
        }
    }
}

// convert database entries to use generic path and url placeholders internally
$filters = opf_select_filters();
if(is_array($filters)) {
    foreach($filters as $filter) {
        $filter['modules'] = unserialize($filter['modules']);
        $filter['desc'] = unserialize($filter['desc']);
        $filter['helppath'] = unserialize($filter['helppath']);
        $filter['pages_parent'] = unserialize($filter['pages_parent']);
        $filter['pages'] = unserialize($filter['pages']);
        $filter['additional_values'] = unserialize($filter['additional_values']);
        $filter['additional_fields'] = unserialize($filter['additional_fields']);
        $filter['additional_fields_languages'] = unserialize($filter['additional_fields_languages']);
        $filter = opf_insert_sysvar($filter);
        $filter['helppath'] = opf_insert_sysvar($filter['helppath'],$filter['plugin']);
        $filter['modules'] = serialize($filter['modules']);
        $filter['desc'] = serialize($filter['desc']);
        $filter['helppath'] = serialize($filter['helppath']);
        $filter['pages_parent'] = serialize($filter['pages_parent']);
        $filter['pages'] = serialize($filter['pages']);
        $filter['additional_values'] = serialize($filter['additional_values']);
        $filter['additional_fields'] = serialize($filter['additional_fields']);
        $filter['additional_fields_languages'] = serialize($filter['additional_fields_languages']);
        $database->upsertRow('{TP}mod_outputfilter_dashboard', 'id', [
            'id'                          => $filter['id'],
            'userfunc'                    => $filter['userfunc'],
            'plugin'                      => $filter['plugin'],
            'file'                        => $filter['file'],
            'func'                        => $filter['func'],
            'desc'                        => $filter['desc'],
            'configurl'                   => $filter['configurl'],
            'csspath'                     => $filter['csspath'],
            'helppath'                    => $filter['helppath'],
            'additional_values'           => $filter['additional_values'],
            'additional_fields'           => $filter['additional_fields'],
            'additional_fields_languages' => $filter['additional_fields_languages'],
        ]);
        if($database->hasError())
         echo "SQL statement failed: " . $database->getError();
    }
}

// Renaming filters, since 1.6.0 (the file and directory removals that used to
// sit here moved up into the single $obsoletePaths pass near the top)

$aFilters = array(
    // OLD name        // NEW name
    'Droplets'      => 'Droplets Injector',
    'jQ ColorBox'   => 'Colorbox',
    'Replace Stuff' => 'Replace Contents',
    'Move Stuff'    => 'Move Contents',
    'E-Mail'        => 'E-Mail Masking',
    'WB-Link'        => 'Internal Link Replacer',
    'Insert'        => 'Class Insert Helper',
);

$aFiltersDB = $database->fetchAll("SELECT `name` FROM `{TP_OPFD}`");
$aFilterNames = array_column($aFiltersDB, 'name');
foreach($aFilters as $old=>$new){
    // old filter name still in the DB
    if(in_array($old,$aFilterNames)){
        // new filter name already in the DB
        if(in_array($new,$aFilterNames)){
            // delete the row with old filter name
            $database->deleteRow('{TP_OPFD}', 'name', $old);
        }
    }
}

// ── WBCE 1.7.0: remove filters made obsolete by AssetQueue ──────────────────
// auto_placeholder, csstohead, and move_stuff are fully absorbed into
// AssetQueue::process() (scan + processMoveBlocks). Their DB rows must be
// deleted before plugin_install.php runs so opf_register_filter() does not
// find stale entries and leave them active.
$obsoleteFuncs = [
    'opff_mod_opf_auto_placeholder',
    'opff_mod_opf_csstohead',
    'opff_mod_opf_move_stuff',
];
$database->deleteRow('{TP}mod_outputfilter_dashboard', 'funcname', $obsoleteFuncs);

// ── Remove old standalone mod_opf_* module entries and directories ───────────
// These filters now live in outputfilter_dashboard/plugins/core_outputfilters/.
// Remove their addons-table entries so they are not re-registered on the next
// addon reload, then delete the module directories from disk.
$removeOpfMods = [
    'mod_opf_auto_placeholder', 'mod_opf_csstohead', 'mod_opf_insert',
    'mod_opf_move_stuff', 'mod_opf_remove_system_ph', 'mod_opf_replace_stuff', 'mod_opf_wblink',
];
foreach ($removeOpfMods as $mod) {
    $database->deleteRow('{TP}addons', 'directory', $mod);

    $sSignal = removePath(WB_PATH . '/modules/' . $mod);
    if ($sSignal === 'RM_PATH_NOT_FOUND') {
        continue;   // never installed on this site -- nothing to report
    }
    echo L_("SIGNAL['$sSignal']", 'modules/' . $mod) . '<br />';
}

// ── WBCE 1.7.0: migrate opf_wblink setting key → opf_pagelink ───────────────
// The filter was renamed from opff_mod_opf_wblink to opff_mod_opf_pagelink and
// its file from opf_wblink.php to opf_pagelink.php. The DB row is updated
// automatically by opf_register_filter() (matched by name). Preserve the
// user's active/inactive choice by copying the old setting key.
if (class_exists('Settings')) {
    $oldVal = Settings::Get('opf_wblink', null);
    if ($oldVal !== null) {
        Settings::set('opf_pagelink', (int)$oldVal);
    }
}