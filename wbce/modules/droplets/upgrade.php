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
        
    Settings::Set('opf_droplets', 1, false);
    Settings::Set('opf_droplets_be', 1, false);
 }