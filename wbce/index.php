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

$starttime = array_sum(explode(" ", microtime()));

define('DEBUG', false);
// Include config file
$config_file = dirname(__FILE__) . '/config.php';
if (file_exists($config_file)) {
    require_once $config_file;
} 

// Check if the config file has been set-up
if (!defined('TABLE_PREFIX')) {
/*
 * Remark:  HTTP/1.1 requires a qualified URI incl. the scheme, name
 * of the host and absolute path as the argument of location. Some, but
 * not all clients will accept relative URIs also.
 */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $file = 'install/index.php';
    $target_url = 'http://' . $host . $uri . '/' . $file;
    $sResponse = $_SERVER['SERVER_PROTOCOL'] . ' 307 Temporary Redirect';
    header($sResponse);
    header('Location: ' . $target_url); exit; 
}


// Create new frontend object
$wb = new frontend();

// Figure out which page to display
// Stop processing if intro page was shown
$wb->page_select() or die();

// Collect info about the currently viewed page
// and check permissions
$wb->get_page_details();

// Collect general website settings
$wb->get_website_settings();

// OPF hook ,Load OutputFilter functions
if(file_exists(WB_PATH .'/modules/outputfilter_dashboard/functions.php')) {
    include(WB_PATH .'/modules/outputfilter_dashboard/functions.php');
    // use 'cache' instead of 'nocache' to enable page-cache.
    // Do not use 'cache' in case you use dynamic contents (e.g. snippets)!
    opf_controller('init', 'nocache');
}

// Load functions available to templates, modules and code sections
// also, set some aliases for backward compatibility
require WB_PATH . '/framework/frontend.functions.php';

//Get pagecontent in buffer for Droplets and/or Filter operations
ob_start();

// require template include.php 
if (file_exists(WB_PATH . '/templates/' . TEMPLATE . '/include.php')){
    require WB_PATH . '/templates/' . TEMPLATE . '/include.php'; 
}

//require the actual template file 
require WB_PATH . '/templates/' . TEMPLATE . '/index.php';
$output = ob_get_clean();
 
// OPF hook, apply outputfilter
if(function_exists('opf_apply_filters')) {
    $output = opf_controller('page', $output);
}

// execute old frontend output filters or not
if (!defined("WB_SUPPRESS_OLD_OPF") or !WB_SUPPRESS_OLD_OPF){
    // Module is installed?
    if (file_exists(WB_PATH . '/modules/output_filter/filter_routines.php')) {
        include_once WB_PATH . '/modules/output_filter/filter_routines.php';
        if (function_exists('executeFrontendOutputFilter')) {
            $output = executeFrontendOutputFilter($output);
        }
    }
}

// now send complete page to the browser
echo $output;

// end of wb-script
exit;
