<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

global $module_version;

require_once dirname(__FILE__).'/functions.inc.php';

// removes empty entries from the table; this may happen if someone adds a
// droplet but does not save any changes
if (!$admin->get_get("do")) {
    $database->query(sprintf("DELETE FROM `%smod_droplets` WHERE name=''",TABLE_PREFIX));
}


$twig_data = array(
    'FTAN' => (method_exists($admin,'getFTAN') ? $admin->getFTAN() : '')
);

// if there are exports, there will be an additional button, so check it out
$backup_files = wbce_find_backups( WB_PATH.'/modules/droplets/export/' );
$backup_mgmt  = NULL;
if ( count( $backup_files ) > 0 ) {
    $twig_data['backup_mgmt'] = true;
}

// convert some keys into actions
foreach(array_values(array('export','upload','delete')) as $key)
{
    if(isset($_REQUEST[$key]))
    {
        $_GET['do'] = $key;
        break;
    }
}

// ----- duplicate -----
if ( isset($_GET['copy']) )
{
    $id = $_GET['copy'];
    if ( is_numeric($id) ) {
        wbce_copy_droplet($id);
    }
}
// ----- recover from backup -----
elseif ( isset($_GET['recover']) && file_exists( WB_PATH.'/modules/droplets/export/'.$_GET['recover'] ) )
{
    $result = wbce_unpack_and_import( WB_PATH.'/modules/droplets/export/'.$_GET['recover'], WB_PATH.'/temp/unzip/' );
    // show errors
    if ( isset( $result['errors'] ) && is_array( $result['errors'] ) && count( $result['errors'] ) > 0 ) {
        $twig_data['content']
            = '<div style="border: 1px solid #f00; padding: 5px; color: #f00; font-weight: bold;">'
            . $DR_TEXT['IMPORT_ERRORS']
            . "<br />\n";
        foreach ( $result['errors'] as $droplet => $error )
        {
            $twig_data['content']
                .= 'Droplet: ' . $droplet . '<br />'
                .  '<span style="padding-left: 15px">'
                .  $error
                .  '</span>';
        }
        $twig_data['content'] .= "</div>\n";
    } else {
        $new = isset( $result['imported'] ) ? $result['imported'] : array();
        $twig_data['content']
            = '<div class="drok">'
            . $result['count']
            . " "
            . $DR_TEXT['IMPORTED']
            . '</div>';
    }
    $twig_data['more_header_links'] = $DR_TEXT['IMPORTED'];
}

// action
if(isset($_GET['do']))
{
    switch($_GET['do'])
    {
        // ----- export -----
        case 'export':
            if (!empty($_REQUEST['markeddroplet'])){
                $list = $_REQUEST['markeddroplet'];
                if(is_array($list) AND count($list)) {
                    $twig_data['info'] = wbce_export_droplets($list);
                }
            }
            break;
        // ----- import -----
        case 'upload':
            $twig_data['content'] = wbce_handle_upload();
            $twig_data['more_header_links'] = $DR_TEXT['UPLOAD'];
            break;

        // ----- delete -----
        case 'delete':
           if(isset($_GET['droplet_id']) && ! isset($_POST['markeddroplet']))
           {
               $_POST['markeddroplet'] = array($_GET['droplet_id']);
           }
           wbce_delete_droplets();
           break;

        // ----- modify droplet -----
        case 'modify':
            include_once WB_PATH . '/include/editarea/wb_wrapper_edit_area.php';
            echo registerEditArea ('contentedit','php',true,'both',true,true,600,450,$toolbar = 'default');

            $modified_when     = time();
            $modified_by       = $admin->get_user_id();
            $sOverviewDroplets = $TEXT['LIST_OPTIONS'].' '.$DR_TEXT['DROPLETS'];
            $droplet_id        = intval($_GET['droplet_id']);

            // Get header and footer
            $query_content = $database->query(sprintf(
                "SELECT * FROM `%smod_droplets` WHERE `id` = '%d'",
                TABLE_PREFIX, $droplet_id
            ));
            $fetch_content = $query_content->fetchRow(MYSQLI_ASSOC);

            $twig_data['content'] = wbce_twig_display(
                array('data'=>$fetch_content),
                'modify',
                true
            );
            break;

        // ----- create full backup -----
        case 'backup_droplets':
            $query_droplets = $database->query(sprintf(
                "SELECT * FROM `%smod_droplets`",
                 TABLE_PREFIX
            ));
            $list = array();
            while ( $droplet = $query_droplets->fetchRow() )
            {
                $list[] = $droplet;
            }
            // backup
            $twig_data['content'] = wbce_backup_droplets($list);
            $twig_data['more_header_links'] = $DR_TEXT['BACKUP'];
            break;

        // ----- manage backups -----
        case 'manage_backups':
	require_once( WB_PATH . '/include/pclzip/pclzip.lib.php' );
            $backup_files = wbce_find_backups( WB_PATH.'/modules/droplets/export/' );
            // file to delete?
            if ( isset( $_GET['del'] ) ) {
                if ( $_GET['del'] !== 'all' ) {
                    if ( file_exists( WB_PATH.'/modules/droplets/export/'.$_GET['del'] ) ) {
                        unlink( WB_PATH.'/modules/droplets/export/'.$_GET['del'] );
                    }
                }
                else {
                    foreach ( $backup_files as $file ) {
                        unlink( WB_PATH.'/modules/droplets/export/'.$file );
                    }
                }
            }
            $backup_files = wbce_find_backups( WB_PATH.'/modules/droplets/export/' );
            $data = array();
            if ( count( $backup_files ) > 0 )
            {
                // sort by name
                sort($backup_files);
                foreach( $backup_files as $i => $file )
                {
                    // stat
                    $stat  = stat(WB_PATH.'/modules/droplets/export/'.$file);
                    // get zip contents
                    $zip   = new PclZip(WB_PATH.'/modules/droplets/export/'.$file);
                    // get file count in zip
                    $count = $zip->listContent();

                    $data[] = array(
                        'stat'  => $stat,
                        'count' => count($count),
                        'name'  => $file,
                        'list'  => implode( ", ", array_map( function($cnt) { return $cnt["filename"]; }, $count )),
                    );
                }
            }
            $twig_data['content'] = wbce_twig_display(array('backup_files'=>$data),'backups',true);
            $twig_data['more_header_links'] = $DR_TEXT['MANAGE_BACKUPS'];
            break;
    }
}

if(! isset($twig_data['content']))
{
    $twig_data['droplets'] = wbce_list_droplets();
}

// print result
wbce_twig_display($twig_data);
