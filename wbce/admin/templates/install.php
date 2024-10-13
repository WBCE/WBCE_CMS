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
$admin = new admin('Addons', 'templates_install', false, true);
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

// Check write permissions for templates folder
if (!is_writable(WB_PATH . '/templates/')) {
    $admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS']);
}

// Create unique file within WBCE /temp folder
$temp_dir = WB_PATH . '/temp/';
$temp_file = tempnam($temp_dir, 'wb_');
$temp_unzip = WB_PATH . '/temp/unzip/';

// Move uploaded file to WBCE /temp folder and deal with possible upload errors
if (!$_FILES['userfile']['error']) {
    // Try uploading file to WBCE /temp folder
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

// Include the templates info file
unset($template_directory);
unset($theme_directory);
require($temp_unzip . 'info.php');

// Perform Add-on requirement checks before proceeding
preCheckAddon($temp_file);

// Check if the file is valid
if (!isset($template_directory)) {
    if (file_exists($temp_file)) {
        unlink($temp_file);
    } // Remove temp file
    $admin->print_error($MESSAGE['GENERIC_INVALID']);
}

// Check if this module is already installed
// and compare versions if so
$new_template_version = $template_version;
if (is_dir(WB_PATH . '/templates/' . $template_directory)) {
    if (file_exists(WB_PATH . '/templates/' . $template_directory . '/info.php')) {
        require(WB_PATH . '/templates/' . $template_directory . '/info.php');
        // Version to be installed is older than currently installed version
        if (versionCompare($template_version, $new_template_version, '>=')) {
            if (file_exists($temp_file)) {
                unlink($temp_file);
            } // Remove temp file
            $admin->print_error($MESSAGE['GENERIC_ALREADY_INSTALLED']);
        }
    }
    $success_message = $MESSAGE['GENERIC_UPGRADED'];
} else {
    $success_message = $MESSAGE['GENERIC_INSTALLED'];
}

// Set template dir
$template_dir = WB_PATH . '/templates/' . $template_directory;

// Make sure the template dir exists, and chmod if needed
if (!file_exists($template_dir)) {
    make_dir($template_dir);
} else {
    change_mode($template_dir, 'dir');
}

// Unzip template to the template dir
$list = $archive->extract(
    PCLZIP_OPT_PATH,
    $template_dir,
    PCLZIP_CB_PRE_EXTRACT,
    'pclzip_extraction_filter',
    PCLZIP_OPT_REPLACE_NEWER
);

// Chmod all the uploaded files
$dir = dir($template_dir);
while (false !== $entry = $dir->read()) {
    // Skip pointers
    if (substr($entry, 0, 1) != '.' and $entry != '.svn' and !is_dir($template_dir . '/' . $entry)) {
        // Chmod file
        change_mode($template_dir . '/' . $entry);
    }
}

// Load template info into DB, but no longer from $template_dir.
// Instead from original unzipped dir, due to possible opcache issues otherwise!
load_template($temp_unzip);

// Remove the temp unzip directory and the temp zip file
rm_full_dir($temp_unzip);
if (file_exists($temp_file)) {
    unlink($temp_file);
}

// Print success message
$admin->print_success($success_message);

// Print admin footer
$admin->print_footer();

