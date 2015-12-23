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
    // get list of files contained in the zip object
    if (($zip_files = $zip->listContent()) == 0) return '';

    // find first folder containing an info.php file
    foreach($zip_files as $zip_file => $info) {
        if (basename($info['filename']) == 'info.php') {
            return '/' . dirname($info['filename']);
        }
    }
    return '';
}

// do not display notices and warnings during installation
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Setup admin object
require('../../config.php');


// suppress to print the header, so no new FTAN will be set
$admin = new admin('Addons', 'templates_install', false);
if(! $admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
$admin->print_header();

// Check if template dir is writable
if(! is_writable(WB_PATH.'/templates/')) {
    if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
    $admin->print_error($MESSAGE['TEMPLATES']['BAD_PERMISSIONS']);
}

// Check if user uploaded a file
if(! isset($_FILES['userfile'])) {
    header("Location: index.php");
    exit(0);
}

// Include the WB functions file
 

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
    // index for language files
    $key = 'UNKNOW_UPLOAD_ERROR';
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            $key = 'UPLOAD_ERR_INI_SIZE';
        case UPLOAD_ERR_FORM_SIZE:
            $key = 'UPLOAD_ERR_FORM_SIZE';
        case UPLOAD_ERR_PARTIAL:
            $key = 'UPLOAD_ERR_PARTIAL';
        case UPLOAD_ERR_NO_FILE:
            $key = 'UPLOAD_ERR_NO_FILE';
        case UPLOAD_ERR_NO_TMP_DIR:
            $key = 'UPLOAD_ERR_NO_TMP_DIR';
        case UPLOAD_ERR_CANT_WRITE:
            $key = 'UPLOAD_ERR_CANT_WRITE';
        case UPLOAD_ERR_EXTENSION:
            $key = 'UPLOAD_ERR_EXTENSION';
        default:
            $key = 'UNKNOW_UPLOAD_ERROR';
    }
    $admin->print_error($MESSAGE[$key].'<br />'.$MESSAGE['GENERIC_CANNOT_UPLOAD']);
}

// include PclZip and create object from Add-on zip archive
$archive = new PclZip($temp_file);

// extract Add-on files into WBCE temp folder
$addon_root_path = find_addon_root_path($archive);
$list = $archive->extract(
    PCLZIP_OPT_PATH, $temp_unzip,
    PCLZIP_CB_PRE_EXTRACT, 'pclzip_extraction_filter',
    PCLZIP_OPT_REPLACE_NEWER
);

// Check if uploaded file is a valid Add-On zip file
if (! ($list && file_exists($temp_unzip . 'info.php'))) {
  $admin->print_error($MESSAGE['GENERIC_INVALID_ADDON_FILE']);
}

// Include the templates info file
unset($template_directory);
unset($theme_directory);
require($temp_unzip.'info.php');

// Perform Add-on requirement checks before proceeding
require(WB_PATH . '/framework/addon.precheck.inc.php');
preCheckAddon($temp_file);

// Delete the temp unzip directory
rm_full_dir($temp_unzip);

// Check if the file is valid
if(! isset($template_directory)) {
    if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
    $admin->print_error($MESSAGE['GENERIC_INVALID']);
}

// Check if this module is already installed
// and compare versions if so
$new_template_version=$template_version;
if(is_dir(WB_PATH.'/templates/'.$template_directory)) {
    if(file_exists(WB_PATH.'/templates/'.$template_directory.'/info.php')) {
        require(WB_PATH.'/templates/'.$template_directory.'/info.php');
        // Version to be installed is older than currently installed version
        if (versionCompare($template_version, $new_template_version, '>=')) {
            if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
            $admin->print_error($MESSAGE['GENERIC_ALREADY_INSTALLED']);
        }
    } 
    $success_message=$MESSAGE['GENERIC_UPGRADED'];
} else {
    $success_message=$MESSAGE['GENERIC_INSTALLED'];
}

// Set template dir
$template_dir = WB_PATH.'/templates/'.$template_directory;

// Make sure the template dir exists, and chmod if needed
if(!file_exists($template_dir)) {
    make_dir($template_dir);
} else {
    change_mode($template_dir, 'dir');
}

// Unzip template to the template dir
$list = $archive->extract(
    PCLZIP_OPT_PATH, $template_dir,
    PCLZIP_CB_PRE_EXTRACT, 'pclzip_extraction_filter',
    PCLZIP_OPT_REPLACE_NEWER
);

// Delete the temp zip file
if(file_exists($temp_file)) { unlink($temp_file); }

// Chmod all the uploaded files
$dir = dir($template_dir);
while(false !== $entry = $dir->read()) {
    // Skip pointers
    if(substr($entry, 0, 1) != '.' AND $entry != '.svn' AND !is_dir($template_dir.'/'.$entry)) {
        // Chmod file
        change_mode($template_dir.'/'.$entry);
    }
}

// Load template info into DB
load_template($template_dir);

// Print success message
$admin->print_success($success_message);

// Print admin footer
$admin->print_footer();
