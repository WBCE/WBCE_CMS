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

/**
 * pclzip_extraction_filter
 * PclZip filter to extract only files inside Add-on root path
 */
function pclzip_extraction_filter($p_event, &$p_header)
{
    global $addon_root_path;
    // don't apply filter for regular zipped WBCE Add-on archives w/o subfolders
    if ($addon_root_path == '/.') {
        return 1;
    }

    // exclude all files not stored inside the $addon_root_path (subfolders)
    if (strpos($p_header['filename'], $addon_root_path) == false) {
        return 0;
    }

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
function find_addon_root_path($zip)
{
    // get list of files contained in the zip object
    if (($zip_files = $zip->listContent()) == 0) {
        return '';
    }

    // find first folder containing an info.php file
    foreach ($zip_files as $zip_file => $info) {
        if (basename($info['filename']) == 'info.php') {
            return '/' . dirname($info['filename']);
        }
    }
    return '';
}

// do not display notices and warnings during installation
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Include required files
require '../../config.php';
require_once WB_PATH . '/framework/addon.precheck.inc.php';
require_once WB_PATH . '/framework/functions.php';
require_once WB_PATH . '/include/pclzip/pclzip.lib.php';

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'modules_install', false, true);
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user uploaded a file
if (!(isset($_FILES['userfile']) && isset($_FILES['userfile']['name']))) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Check write permissions for modules folder
if (!is_writable(WB_PATH . '/modules/')) {
    $admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS']);
}

// Create unique file within WBCE /temp folder
$temp_dir = WB_PATH . '/temp/';
$temp_file = tempnam($temp_dir, 'wb_');
$temp_unzip = WB_PATH . '/temp/unzip/';

// Move uploaded file to WBCE /temp folder and deal with possible upload errors
if (!$_FILES['userfile']['error']) {
    // Try moving uploaded file to WBCE /temp folder
    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_file)) {
        $admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS']);
    }
} else {
    // work out error message
    $error_code = $_FILES['userfile']['error'];
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
    $admin->print_error($MESSAGE[$key] . '<br />' . $MESSAGE['GENERIC_CANNOT_UPLOAD']);
}

// remove temporary unzip folder if exists to avoid unzip process fails
rm_full_dir($temp_unzip);

// create PclZip object to extract Addon zip archives
$archive = new PclZip($temp_file);

// extract Add-on files into WBCE temp folder
$addon_root_path = find_addon_root_path($archive);
$list = $archive->extract(
    PCLZIP_OPT_PATH,
    $temp_unzip,
    PCLZIP_CB_PRE_EXTRACT,
    'pclzip_extraction_filter',
    PCLZIP_OPT_REPLACE_NEWER
);

// Check if uploaded file is a valid Add-On zip file
if (!($list && file_exists($temp_unzip . 'info.php'))) {
    // Remove the temp unzip directory and the temp zip file
    rm_full_dir($temp_unzip);
    if (file_exists($temp_file)) {
        unlink($temp_file);
    }
    $admin->print_error($MESSAGE['GENERIC_INVALID_ADDON_FILE']);
}

// Include module info file
unset($module_directory);
require($temp_unzip . 'info.php');

// Perform Add-on requirement checks before proceeding
preCheckAddon($temp_file);

// Check if the file is valid
if (!isset($module_directory)) {
    rm_full_dir($temp_unzip);
    if (file_exists($temp_file)) {
        unlink($temp_file);
    } // Remove temp file
    $admin->print_error($MESSAGE['GENERIC_INVALID']);
}

// Check if this module is already installed
// and compare versions if so
$new_module_version = $module_version;
$action = "install";
if (is_dir(WB_PATH . '/modules/' . $module_directory)) {
    if (file_exists(WB_PATH . '/modules/' . $module_directory . '/info.php')) {
        require(WB_PATH . '/modules/' . $module_directory . '/info.php');
        // Version to be installed is older than currently installed version
        if (versionCompare($module_version, $new_module_version, '>=')) {
	    rm_full_dir($temp_unzip);
            if (file_exists($temp_file)) {
                unlink($temp_file);
            } // Remove temp file
            $admin->print_error($MESSAGE['GENERIC_ALREADY_INSTALLED']);
        }
        $action = "upgrade";
    }
}

// Set module directory
$module_dir = WB_PATH . '/modules/' . $module_directory;

// Make sure the module dir exists, and chmod if needed
make_dir($module_dir);

// Unzip module to the module dir
if (isset($_POST['overwrite'])) {
    $list = $archive->extract(
        PCLZIP_OPT_PATH,
        $module_dir,
        PCLZIP_CB_PRE_EXTRACT,
        'pclzip_extraction_filter',
        PCLZIP_OPT_REPLACE_NEWER
    );
} else {
    $list = $archive->extract(
        PCLZIP_OPT_PATH,
        $module_dir,
        PCLZIP_CB_PRE_EXTRACT,
        'pclzip_extraction_filter'
    );
}

if (!$list) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNZIP']);
}

// Chmod all the uploaded files
$dir = dir($module_dir);
while (false !== $entry = $dir->read()) {
    // Skip pointers
    if (substr($entry, 0, 1) != '.' and $entry != '.svn' and !is_dir($module_dir . '/' . $entry)) {
        // Chmod file
        change_mode($module_dir . '/' . $entry, 'file');
    }
}

// Load upgraded module info into DB, but no longer from $module_dir.
// Instead from original unzipped dir, due to possible opcache issues otherwise! Especially when upgrading the module!
// Print success message
if ($action == "install") {
    load_module($temp_unzip, false);
    $admin->print_success($MESSAGE['GENERIC_INSTALLED']);
} elseif ($action == "upgrade") {
    upgrade_module($temp_unzip, false);
    $admin->print_success($MESSAGE['GENERIC_UPGRADED']);
}

// Remove the temp unzip directory and the temp zip file
rm_full_dir($temp_unzip);
if (file_exists($temp_file)) {
    unlink($temp_file);
}

// Run the modules install // upgrade script if there is one.
// This needs to happen *after* $temp_unzip is removed just above.
// Otherwise there might be unwanted interferences if the modules install/upgrade doesn't clean up $temp_unzip itself.
if (file_exists($module_dir . '/' . $action . '.php')) {
    require($module_dir . '/' . $action . '.php');
}

// Print admin footer
$admin->print_footer();
