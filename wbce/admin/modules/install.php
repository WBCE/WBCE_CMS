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


// Setup admin object
require('../../config.php');
$admin = new admin('Addons', 'modules_install');

// Perform Add-on requirement checks before proceeding
require_once(WB_PATH . '/framework/addon.precheck.inc.php');

// check if a valid form is send
if(! $admin->checkFTAN()) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Check if module folder is writable
if(! is_writable(WB_PATH.'/modules/')) {
    if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
    $admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS']);
}

// Check if user uploaded a file
if(!isset($_FILES['userfile'])) {
    header("Location: index.php");
    exit(0);
}


// Set temp vars
$temp_dir = WB_PATH.'/temp/';
$temp_file = $temp_dir . $_FILES['userfile']['name'];
$temp_unzip = WB_PATH.'/temp/unzip/';

if(! $_FILES['userfile']['error']) {
    // Try to upload the file to the temp dir
    if( !move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_file)) {
        $admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS']);
    }
} else {
    $error_code=$_FILES['userfile']['error'];
    // index for language files
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                $key = 'UPLOAD_ERR_INI_SIZE';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $key = 'UPLOAD_ERR_FORM_SIZE';
                break;
            case UPLOAD_ERR_PARTIAL:
                $key = 'UPLOAD_ERR_PARTIAL';
                break;
            case UPLOAD_ERR_NO_FILE:
                $key = 'UPLOAD_ERR_NO_FILE';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $key = 'UPLOAD_ERR_NO_TMP_DIR';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $key = 'UPLOAD_ERR_CANT_WRITE';
                break;
            case UPLOAD_ERR_EXTENSION:
                $key = 'UPLOAD_ERR_EXTENSION';
                break;
            default:
                $key = 'UNKNOW_UPLOAD_ERROR';
        }
    $admin->print_error($MESSAGE[$key].'<br />'.$MESSAGE['GENERIC_CANNOT_UPLOAD']);
}

// include PclZip and create object from Add-on zip archive
$archive = new PclZip($temp_file);


// extract Add-on files into WBCE temp folder
$aAddonRootPath = find_addon_root_path($archive);
echo "<pre>"; print_r($aAddonRootPath);echo "</pre>";


// No info.php found
if (count ($aAddonRootPath) <1) {
  $admin->print_error($MESSAGE['GENERIC_INVALID_ADDON_FILE']);
}


$ecount=1;

foreach($aAddonRootPath as $addon_root_path){ 

    echo "Addon root path: $addon_root_path<br>";
    
    
    
    $list = $archive->extract(
        PCLZIP_OPT_PATH, $temp_unzip,
        PCLZIP_CB_PRE_EXTRACT, 'pclzip_extraction_filter',
        PCLZIP_OPT_REPLACE_NEWER
    );

    // Check if uploaded file is a valid Add-On zip file
    if (! ($list && file_exists($temp_unzip . 'info.php'))) {
        $admin->print_error($MESSAGE['GENERIC_INVALID_ADDON_FILE'].  $addon_root_path );
    }
    
    // Include module info file
    unset($module_directory);
    // require always fetched the first cached file 
    //require($temp_unzip.'info.php');
    $exec=file_get_contents ($temp_unzip.'info.php');
    $exec=preg_replace("/^.*\<\?(php)?/uis","", $exec);
    eval ($exec);
   
    // Perform Add-on requirement checks before proceeding
    preCheckAddon($temp_file);

    // Delete temporary unzip directory
    rm_full_dir($temp_unzip);
 
    // Check if the file is valid
    if(! isset($module_directory)) {
        if (file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
        $admin->print_error($MESSAGE['GENERIC_INVALID'].": $temp_file" );
    }
    
    echo "Module dir : $module_directory<br>";
    
    //if ($ecount==0) exit;
    
    // Check if this module is already installed
    // and compare versions if so
    $new_module_version = $module_version;
    $action="install";
    if(is_dir(WB_PATH.'/modules/'.$module_directory)) {
        if(file_exists(WB_PATH.'/modules/'.$module_directory.'/info.php')) {
            require(WB_PATH.'/modules/'.$module_directory.'/info.php');
            // Version to be installed is older than currently installed version
            if (versionCompare($module_version, $new_module_version, '>=')) {
                echo "$module_name - already installled !<br>"; $ecount--; continue;
                //if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
                //$admin->print_error($MESSAGE['GENERIC_ALREADY_INSTALLED'].": $module_name");
            }
            $action="upgrade";
        }
    }
    
    // Set module directory
    $module_dir = WB_PATH.'/modules/'.$module_directory;
    echo "moduledir: $module_dir<br>";
    
    // Make sure the module dir exists, and chmod if needed
    make_dir($module_dir);
    
    // Unzip module to the module dir
    if(isset($_POST['overwrite'])) {
        $list = $archive->extract(
            PCLZIP_OPT_PATH, $module_dir,
            PCLZIP_CB_PRE_EXTRACT, 'pclzip_extraction_filter',
            PCLZIP_OPT_REPLACE_NEWER
        );
    } else {
        $list = $archive->extract(
            PCLZIP_OPT_PATH, $module_dir,
            PCLZIP_CB_PRE_EXTRACT, 'pclzip_extraction_filter'
        );
    }

    if(! $list) {
        $admin->print_error($MESSAGE['GENERIC_CANNOT_UNZIP']);
    }

    

    // Chmod all the uploaded files
    $dir = dir($module_dir);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if(substr($entry, 0, 1) != '.' AND $entry != '.svn' AND !is_dir($module_dir.'/'.$entry)) {
            // Chmod file
            change_mode($module_dir.'/'.$entry, 'file');
        }
    }

    // Run the modules install // upgrade script if there is one
    if(file_exists($module_dir.'/'.$action.'.php')) {
        require($module_dir.'/'.$action.'.php');
    }

    // Load/update module info in DB and print success message
    if ($action=="install") {
        load_module(WB_PATH.'/modules/'.$module_directory, false);
            echo "$module_name - installed! <br>";
       // $admin->print_success($MESSAGE['GENERIC_INSTALLED']. ": $module_name");
    } elseif ($action=="upgrade") {
        upgrade_module($module_directory, false);
         echo "$module_name - upgraded! <br>";
        //$admin->print_success($MESSAGE['GENERIC_UPGRADED']. ": $module_name");
    }
    
    $ecount--;
    
}    

// Delete the temp zip file
if(file_exists($temp_file)) { unlink($temp_file); }

// Print admin footer
$admin->print_footer();



// Helper  Functions
/**
 * pclzip_extraction_filter
 * PclZip filter to extract only files inside Add-on root path
 */
function pclzip_extraction_filter($p_event, &$p_header) {
    global $addon_root_path;
    // don't apply filter for regular zipped WBCE Add-on archives w/o subfolders
    if ($addon_root_path == '/.') return 1;

    // exclude all files not stored inside the $addon_root_path (subfolders)
    if (strpos($p_header['filename'], $addon_root_path) == false) return 0;

    // remove $addon_root_path from absolute path of the file to extract
    $p_header['filename'] = str_replace($addon_root_path, '', $p_header['filename']);
    return 1;
}

/**
 * find_addon_root_path
 * Returns WBCE Add-on root path inside a given zip archive
 * Supports nested archives (e.g. incl. Add-on folder or GitHub archives)
 * @return string
 */
function find_addon_root_path($zip) {
    $aAddonList=array();
    // get list of files contained in the zip object
    if (($zip_files = $zip->listContent()) == 0) return '';
 
    // find first folder containing an info.php file
    foreach($zip_files as $zip_file => $info) {
        if (basename($info['filename']) == 'info.php') {
            $aAddonList[] ='/' . dirname($info['filename']);
        }
    }
    return $aAddonList;
}
