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

// If this is defined we are on a FE page , just nice to know.  
if (!defined('WB_FRONTEND')) define('WB_FRONTEND', true);

if(!defined ('WB_DEBUG')) define('WB_DEBUG', false);
if(!defined ('DEBUG')) define('DEBUG', WB_DEBUG); //damm compatibility

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

// Get template include file if exits
if (file_exists(WB_PATH . '/templates/' . TEMPLATE . '/include.php'))
    require WB_PATH . '/templates/' . TEMPLATE . '/include.php';

//Get pagecontent in buffer for Droplets and/or Filter operations
ob_start();
require WB_PATH . '/templates/' . TEMPLATE . '/index.php';

// fetch the Page content for applying filters 
$output = ob_get_clean();
 

// Load OPF Dashboard OutputFilter functions
// As thish should replace the old filters on the long run , its a bad idea to 
// have this in an output filter. 
// Besides you cannnot deactivate the old filters if its IN the old filters   
$sOpfFile = WB_PATH.'modules/outputfilter_dashboard/functions.php';
if (is_readable($sOpfFile)) {
	require_once($sOpfFile);
   // apply outputfilter
	if (function_exists('opf_apply_filters')) {
		// use 'cache' instead of 'nocache' to enable page-cache.
		// Do not use 'cache' in case you use dynamic contents (e.g. snippets)!
		opf_controller('init', 'nocache');
		$output = opf_controller('page', $output);
	}
}


// execute old frontend output filters or not
// Sooner or later this is going to be removed as we dont need to OPF systems 
// So if the module is removed , this goes inactive. 
if (!defined("WB_SUPPRESS_OLD_OPF") or  WB_SUPPRESS_OLD_OPF===false){
    $sOldOpfPath=WB_PATH . '/modules/output_filter/filter_routines.php';
    // Module is installed?
    if (file_exists($sOldOpfPath)) {
        require_once $sOldOpfPath;
        // module correctly loaded 
        if (function_exists('executeFrontendOutputFilter')) {
            $output = executeFrontendOutputFilter($output);
        }
    }
}


// now send complete page to the browser
echo $output;

// end of wb-script
exit;
