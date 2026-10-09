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

// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
    require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
    throw new IllegalFileException();
}

$table_name = TABLE_PREFIX .'mod_droplets';
$description = 'INT NOT NULL default 0 ';

$database->addField($table_name, 'show_wysiwyg', $description.'AFTER `active`', false);
$database->addField($table_name, 'admin_view',   $description.'AFTER `active`', false);
$database->addField($table_name, 'admin_edit',   $description.'AFTER `active`', false);

// since WBCE 1.6.0 (OpF Filter was moved to modules/droplets/opff_droplets.php
// install OpF Filter 
// check whether outputfilter-module is installed
if(file_exists($sOpfFile = WB_PATH.'/modules/outputfilter_dashboard/functions.php')) {
    require_once $sOpfFile;
  
    if(opf_is_registered('Droplets')){
        // unregister old filter if already registered        
        opf_unregister_filter('Droplets');
        rm_full_dir(WB_PATH.'/modules/mod_opf_droplets');
    }

    // install filter
    // Settings::set() must run BEFORE the return below -- `return $a && $b;`
    // makes any statement after it unreachable, so these two calls used to
    // never execute. That's exactly why this only breaks upgraded installs:
    // an install carrying an opf_droplets/_be row from an older WBCE version
    // with a falsy stored value never gets it reset to 1 here, so
    // Settings::Get('opf_droplets', true) returns that stale falsy value
    // instead of falling back to its `true` default, and opff_droplets()
    // gates droplet processing off for good -- every [[droplet]] call is
    // left as literal text on the page. A fresh install has no such row, so
    // the `true` default silently covered for the dead code and masked the bug.
    Settings::set('opf_droplets', 1, false);
    Settings::set('opf_droplets_be', 1, false);

    return opf_register_filter(array(
        'name'     => 'Droplets',
        'type'     => OPF_TYPE_PAGE,
        'file'     => '{SYSVAR:WB_PATH}/modules/droplets/opf_filter_droplets.php',
        'funcname' => 'opff_droplets',
        'desc'     => "Filter that replaces Droplet calls in contents",
        'active'   => 1,
        'allowedit' => 0,
        'pages_parent' => 'all, backend, search'
    ))
    && opf_move_up_before('Droplets');  // move up to the top
 }
 
 // remove files and directories that are not needed any longer
$obsoleteFilesAndDirs = [
    '/js',           // obsolete jquery plugin (tablesorter) and mdcr.js (moved to OpF long ago)
    '/img',          // obsolete, since we use font-awesome for a long time now
    '/backend.css',  
    '/backend.js',  
    '/backend_body.js',  
];
foreach ($obsoleteFilesAndDirs as $rec) {
    $path   = __DIR__ . $rec;
    $signal = removePath($path);
    // Read the signal through L_(), not the raw $SIGNAL array: upgrade.php is
    // require'd from inside upgrade_module(), so it runs in function scope,
    // where that global is not visible -- sprintf() was being handed null and
    // printed nothing at all. L_() reads the Lang registry and works in any
    // scope, and falls back to a readable string if a signal is ever untranslated.
    echo L_("SIGNAL['$signal']", $rec) . '<br>';
}