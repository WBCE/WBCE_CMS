<?php
/**
 *
 * @category        admin
 * @package         templates
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: install.php 1467 2011-07-02 00:06:53Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/templates/install.php $
 * @lastmodified    $Date: 2011-07-02 02:06:53 +0200 (Sa, 02. Jul 2011) $
 *
 */

// do not display notices and warnings during installation
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Setup admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Addons', 'templates_install', false);
if( !$admin->checkFTAN() )
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Check if user uploaded a file
if(!isset($_FILES['userfile'])) {
    header("Location: index.php");
    exit(0);
}

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Set temp vars
$temp_dir = WB_PATH.'/temp/';
$temp_file = $temp_dir . $_FILES['userfile']['name'];
$temp_unzip = WB_PATH.'/temp/unzip/';

// Try to upload the file to the temp dir
if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_file)) {
    $admin->print_error($MESSAGE['GENERIC']['CANNOT_UPLOAD']);
}

// Include the PclZip class file (thanks to 
require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');

// Remove any vars with name "template_directory" and "theme_directory"
unset($template_directory);
unset($theme_directory);

// Setup the PclZip object
$archive = new PclZip($temp_file);
// Unzip the files to the temp unzip folder
$list = $archive->extract(PCLZIP_OPT_PATH, $temp_unzip);

// Check if uploaded file is a valid Add-On zip file
if (!($list && file_exists($temp_unzip . 'index.php'))) $admin->print_error($MESSAGE['GENERIC']['INVALID_ADDON_FILE']);

// Include the templates info file
require($temp_unzip.'info.php');

// Perform Add-on requirement checks before proceeding
require(WB_PATH . '/framework/addon.precheck.inc.php');
preCheckAddon($temp_file);

// Delete the temp unzip directory
rm_full_dir($temp_unzip);

// Check if the file is valid
if(!isset($template_directory)) {
    if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
    $admin->print_error($MESSAGE['GENERIC']['INVALID']);
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
            $admin->print_error($MESSAGE['GENERIC']['ALREADY_INSTALLED']);
        }
    } 
    $success_message=$MESSAGE['GENERIC']['UPGRADED'];
} else {
    $success_message=$MESSAGE['GENERIC']['INSTALLED'];
}

// Check if template dir is writable
if(!is_writable(WB_PATH.'/templates/')) {
    if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
    $admin->print_error($MESSAGE['TEMPLATES']['BAD_PERMISSIONS']);
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
$list = $archive->extract(PCLZIP_OPT_PATH, $template_dir, PCLZIP_OPT_REPLACE_NEWER);
if(!$list) {
    $admin->print_error($MESSAGE['GENERIC']['CANNOT_UNZIP']);
}

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
