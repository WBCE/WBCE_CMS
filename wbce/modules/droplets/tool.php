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
// CodeMirror CSS
$aCssFiles = [
    // make use of theme_fallbacks CSS files 
    // if the THEME does not deliver them yet
    get_url_from_path($admin->correct_theme_source('../css/ACPI_backend.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_content.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_buttons.css'))
];
I::insertCssFile($aCssFiles, 'HEAD TOP+');

global $module_version;
require_once dirname(__FILE__).'/functions.inc.php';

// removes empty entries from the table; this may happen if someone adds a
// droplet but does not save any changes
if (!$admin->get_get("do")) {
    $database->query("DELETE FROM `{TP}mod_droplets` WHERE name=''");
}

$oMsgBox = new MessageBox();
$aToTwig = array();


// if there are exports, there will be an additional button, so check it out
$backup_files = wbce_find_backups( DROPLETS_BACKUP_DIR );
$backup_mgmt  = NULL;
if ( count( $backup_files ) > 0 ) {
    $aToTwig['backup_mgmt'] = true;
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
        $_GET['do'] = 'copy';
    }
} elseif (isset($_GET['recover'])){
    // ----- recover from backup -----
    if (file_exists( $sRecoverFile = __DIR__.'/export/'.$_GET['recover'])){
        $_GET['do'] = 'recover';
    }
}

$aToTwig['TABS'] = renderTabs('overview');
$sToolURI = ADMIN_URL.'/admintools/tool.php?tool='.ADMIN_TOOL_DIR;
// action
if(isset($_GET['do']))
{
    switch($_GET['do'])
    {
        // ----- change show_date -----
        case 'show_date':
            debug_dump($_GET);
            $value = ($_GET['val'] == '0') ? true : false;
            debug_dump($value);
            Settings::Set('droplets_show_by_date', (bool) $value);
            $oMsgBox->redirect($sToolURI.'#show_date');
            break;
            
        // ----- export -----
        case 'export':
            if (!empty($_REQUEST['markeddroplet'])){
                $list = $_REQUEST['markeddroplet'];
                if(is_array($list) AND count($list)) {
                    $aToTwig['info'] = wbce_export_droplets($list);
                }
            } 
            break;
        // ----- import -----
        case 'upload':
            $aToTwig['content'] = wbce_handle_upload();
            $aToTwig['more_header_links'] = $DR_TEXT['UPLOAD'];
            $aToTwig['TABS'] = renderTabs('upload');
           # $oMsgBox->redirect($sToolURI.'&hilite='.implode(',',$result['imported']));
            
            break;

        // ----- delete -----
        case 'delete':
           if(isset($_GET['droplet_id']) && ! isset($_POST['markeddroplet']))
           {
               $_POST['markeddroplet'] = array($_GET['droplet_id']);
           }
           wbce_delete_droplets();
           break;

        

        // ----- recover -----
         case 'recover':        
            $result = importDropletFromZip( 
                    $sRecoverFile, 
                    WB_PATH.'/temp/unzip/' 
                );
                // show errors
                if ( isset( $result['errors'] ) && is_array( $result['errors'] ) 
                        && count( $result['errors'] ) > 0 ) {
                    $sErrors = '';
                    foreach ( $result['errors'] as $droplet => $error ) {
                        $sErrors .= 'Droplet: '.$droplet.'<br />'
                                .  '<b>- '.$error.'</b>';
                    }
                    $oMsgBox->error($sErrors);
                } else {
                    $oMsgBox->success($result['count']. " ".$DR_TEXT['IMPORTED']);
                    $oMsgBox->redirect($sToolURI.'&hilite='.implode(',',$result['imported']));
                }
                $aToTwig['content'] = 'nottin at all';
                $aToTwig['more_header_links'] = $DR_TEXT['IMPORTED'];
            break;
        
        // ----- show_help -----
         case 'copy':        
            $iID = wbce_copy_droplet($id);
            if(is_numeric($iID)){
                $oMsgBox->success('<b>'.$DR_TEXT['DUPLICATE'].'</b><br>'.strtolower($TEXT['SUCCESS']).'!');
                $oMsgBox->redirect($sToolURI.'&do=modify&droplet_id='.$iID);
            }
            break;
            
        // ----- modify droplet -----
        case 'modify':

            $modified_when     = time();
            $modified_by       = $admin->get_user_id();
            $sOverviewDroplets = $TEXT['LIST_OPTIONS'].' '.$DR_TEXT['DROPLETS'];
            $droplet_id        = intval($_GET['droplet_id']);

            // Get header and footer
            $data = $database->get_array(sprintf(
                "SELECT * FROM `{TP}mod_droplets` WHERE `id` = '%d'", $droplet_id
            ))[0];

            $aToTwig['content'] = wbce_twig_display(
                array('data'=>$data),
                'modify',
                true
            );
            break;

        // ----- create full backup -----
        case 'backup_droplets':
            $list = $database->get_array("SELECT * FROM `{TP}mod_droplets`");
            // backup
            $aToTwig['content'] = wbce_backup_droplets($list);
            $aToTwig['more_header_links'] = $DR_TEXT['BACKUP'];
            $aToTwig['TABS'] = renderTabs('backup_droplets');
            $oMsgBox->success($TEXT['SUCCESS']);
            $oMsgBox->redirect($sToolURI.'&do=manage_backups');
            break;

        // ----- manage backups -----
        case 'manage_backups':
            require_once( WB_PATH . '/include/pclzip/pclzip.lib.php' );
            $backup_files = wbce_find_backups( DROPLETS_BACKUP_DIR );
            // file to delete?
            if ( isset( $_GET['del'] ) ) {
                if ( $_GET['del'] !== 'all' ) {
                    if ( file_exists( $sFile = DROPLETS_BACKUP_DIR.$_GET['del'])) {
                        if(unlink($sFile)){
                            $oMsgBox->success('<b>'.$_GET['del'].'</b><br>'.$MESSAGE['MEDIA_DELETED_FILE']);
                        }
                    }
                } else {
                    $sFiles = '';
                    foreach ( $backup_files as $file ) {                        
                        if ( file_exists( $sFile = DROPLETS_BACKUP_DIR.$file)) {
                            if(unlink($sFile)){
                                $sFiles .= '<br>&nbsp;'.$file;
                            }
                        }
                    }
                    $oMsgBox->success('<b>'.$MESSAGE['MEDIA_DELETED_FILE'].'</b>:'.$sFiles);
                }
                
            }
            $backup_files = wbce_find_backups( DROPLETS_BACKUP_DIR );
            $data = array();
            if ( count( $backup_files ) > 0 )
            {
                // sort by name
                sort($backup_files);
                foreach( $backup_files as $i => $file )
                {
                    // stat
                    $stat  = stat(DROPLETS_BACKUP_DIR.$file);
                    // get zip contents
                    $zip   = new PclZip(DROPLETS_BACKUP_DIR.$file);
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
            $aToTwig['content'] = wbce_twig_display(array('backup_files'=>$data),'backups',true);
            $aToTwig['more_header_links'] = $DR_TEXT['MANAGE_BACKUPS'];            
            $aToTwig['TABS'] = renderTabs('manage_backups');
            break;
             
            // ----- show_help -----
            case 'show_help':        
                $aToTwig['TABS'] = renderTabs('show_help');
                ob_start();
                $sReadmeFile = 'readme_EN.php';
                if(file_exists(__DIR__.'/readme/readme_'.LANGUAGE.'.php')){
                     $sReadmeFile = 'readme_'.LANGUAGE.'.php';
                }
                include __DIR__.'/readme/'.$sReadmeFile;
                $sModUrl = get_url_from_path(__DIR__);
                $sRetVal = ob_get_clean();
                $sRetVal = str_replace('../img', $sModUrl.'/img', $sRetVal);
                $aToTwig['content'] = wbce_twig_display(array('content'=>$sRetVal),'help',true);
            break;
    }
}

$bShowDate = Settings::Get('droplets_show_by_date', TRUE) == 1 ? true: false;
$aToTwig['show_date'] = $bShowDate;
$aToTwig['hilite'] = isset($_GET['hilite']) ? explode(',', $_GET['hilite']) : [];
if(! isset($aToTwig['content'])) {
    $aToTwig['droplets'] = wbce_list_droplets($bShowDate);
}
$aToTwig['MESSAGE_BOX'] = $oMsgBox->fetchDisplay();

// print result
wbce_twig_display($aToTwig);
