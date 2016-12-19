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

// index file usually loaded directly or by WB accessfiles 
// (files stored in /pages/ to represent the actual pages)

// get the starttime in microseconds
$starttime = array_sum(explode(" ", microtime()));

// Set debug mode (old and deprecated new WB_DEBUG is set in config or initialize.php)
define('DEBUG', false);

// Hey we are on a frontend Page
if (!defined('WB_FRONTEND')) define('WB_FRONTEND', true);

// Include config file
// Config file includes /framework/initialize.php as there are many 
// scripts that wont go via the index.php we cannot change this right now.
// Initialize loads the autoloader so from now on classes don't need to be included
$file = dirname(__FILE__) . '/config.php';
if (file_exists($file)) {
    require_once $file;
} 


// Check if the config file has been set-up
// If not redirect to the installer.
if (!defined('TABLE_PREFIX')) {
    require dirname(__FILE__) . '/framework/includes/redirect_installer.php';
}


// Create new frontend object
$wb = new frontend();
$admin=$wb; // a wild hack for some modules calling on $admin in the FE...

// Figure out which page to display
// Stop processing if intro page was shown
$wb->page_select() or die();


// Collect info about the currently viewed page
// and check permissions
$wb->get_page_details();


// Collect general website settings
$wb->get_website_settings();


// OPF hook ,Load OutputFilter functions
$file= WB_PATH .'/modules/outputfilter_dashboard/functions.php';
if(file_exists($file)) {
    include($file);
    opf_controller('init', 'nocache');
}


// Load functions available to templates, modules and code sections
// also, set some aliases for backward compatibility
require WB_PATH . '/framework/frontend.functions.php';


//Get pagecontent in buffer for Droplets and/or Filter operations
ob_start();


// require template include.php 
$file=WB_PATH . '/templates/' . TEMPLATE . '/include.php';
if (file_exists($file)){
    require ($file); 
}


//require the actual template file 
require WB_PATH . '/templates/' . TEMPLATE . '/index.php';


// fetch the Page content for applying filters 
$output = ob_get_clean();


// OPF hook, apply outputfilter
if(function_exists('opf_apply_filters')) {
    $output = opf_controller('page', $output);
}


// execute old frontend output filters or not
if (!defined("WB_SUPPRESS_OLD_OPF") or !WB_SUPPRESS_OLD_OPF){
    // Module is installed, filter file in place?
    $file=WB_PATH . '/modules/output_filter/filter_routines.php';
    if (file_exists($file)) {
        include_once ($file);
        if (function_exists('executeFrontendOutputFilter')) {
            $output = executeFrontendOutputFilter($output);
        }
    }
}

// Process direct Output if set. This ends the script here and regular output is not put out. 
$wb->DirectOutput();

// now send complete page to the browser
echo $output;


// end of wb-script
exit;
