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

$database->field_add($table_name, 'show_wysiwyg', $description.'AFTER `active`', false);
$database->field_add($table_name, 'admin_view',   $description.'AFTER `active`', false);
$database->field_add($table_name, 'admin_edit',   $description.'AFTER `active`', false);

// 2.4.3: RandomImage read folders outside /media (?dir=../..), and
// ShowRandomWysiwyg showed sections of pages the visitor may not see.
// Replace the stored code with the fixed example, but only where it is still
// one of the shipped versions -- a droplet the site owner changed stays as is.
$aShippedDroplets = array(
    'RandomImage'       => array('13e123ee3e711149d405c61d891b65b6', 'c1696eabde00fe585ddbedde5c80998d'),
    'ShowRandomWysiwyg' => array('58191cbf5c8a641952d01f074b5a94c2', '4b8c2d252669d54cd1e85f9cf86cd6a2'),
);
foreach ($aShippedDroplets as $sDropletName => $aShippedHashes) {
    $sStored = (string) $database->get_one(
        "SELECT `code` FROM `{TP}mod_droplets` WHERE `name` = '".$database->escapeString($sDropletName)."'"
    );
    $sFile = __DIR__.'/example/'.$sDropletName.'.php';
    if ($sStored === '' || !is_readable($sFile)
        || !in_array(md5(trim(str_replace("\r", '', $sStored))), $aShippedHashes, true)) {
        continue;
    }
    // same format as install.php: without the two //: description lines
    $aLines = explode("\n", str_replace("\r", '', file_get_contents($sFile)));
    while (isset($aLines[0]) && substr($aLines[0], 0, 3) == '//:') {
        array_shift($aLines);
    }
    $database->query(
        "UPDATE `{TP}mod_droplets` SET `code` = '".$database->escapeString(implode("\n", $aLines))."', "
        ."`modified_when` = ".time()." WHERE `name` = '".$database->escapeString($sDropletName)."'"
    );
    if ($database->is_error()) {
        echo $database->get_error().'<br>';
    }
}

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
    // Settings::Set() must run BEFORE the return below -- statements after
    // `return $a && $b;` are unreachable, so these two calls never ran and an
    // upgraded install with a stale falsy opf_droplets/_be row kept droplet
    // processing switched off ([[droplet]] calls stayed literal text).
    Settings::Set('opf_droplets', 1, false);
    Settings::Set('opf_droplets_be', 1, false);

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