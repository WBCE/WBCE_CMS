<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @category   modules
 * @package    JsAdmin
 * @author     WebsiteBaker Project, modified by Swen Uth for Website Baker 2.7
 * @author     WBCE Project, modified by Christian M. Stefan to implement Insert methods
 * @copyright  Ryan Djurovich (2004-2009)
 * @copyright  WebsiteBaker Org. e.V. (2009-2015)
 * @copyright  WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// obtain the admin folder (e.g. /admin)
$admin_folder = str_replace(WB_PATH, '', ADMIN_PATH);
$JSADMIN_PATH = WB_URL.'/modules/jsadmin';
$YUI_PATH     = WB_URL.'/include/yui';
$script       = $_SERVER['SCRIPT_NAME'];

// init page_type
$page_type = ''; 
if(strstr($script, $admin_folder."/pages/index.php"))
	$page_type = 'pages';
elseif(strstr($script, $admin_folder."/pages/sections.php"))
	$page_type = 'sections';
elseif(strstr($script, $admin_folder."/settings/tool.php")
	&& isset($_REQUEST["tool"]) && $_REQUEST["tool"] == 'jsadmin')
	$page_type = 'config';
elseif(strstr($script, $admin_folder."/pages/modify.php"))
	$page_type = 'modules';

if($page_type != '') {
	require_once(WB_PATH.'/modules/jsadmin/jsadmin.php');

	// Default scripts
	$js_buttonCell = 5;
	$js_scripts    = array();
	$js_scripts[]  = 'jsadmin.js';

	if($page_type == 'modules') {
		if(!get_setting('mod_jsadmin_persist_order', '1')) { //Maybe Bug settings to negativ for persist , by Swen Uth
			$js_scripts[] = 'restore_pages.js';
  		}
		if(get_setting('mod_jsadmin_ajax_order_pages', '1')) {
			$js_scripts[] = 'dragdrop.js';
			$js_buttonCell= 7; // This ist the Cell where the Button "Up" is , by Swen Uth
		}
	} elseif($page_type == 'pages') {
		if(!get_setting('mod_jsadmin_persist_order', '1')) { //Maybe Bug settings to negativ for persist , by Swen Uth
			$js_scripts[] = 'restore_pages.js';
  		}
		if(get_setting('mod_jsadmin_ajax_order_pages', '1')) {
			$js_scripts[] = 'dragdrop.js';
			$js_buttonCell= 7; // This ist the Cell where the Button "Up" is , by Swen Uth
		}
	} elseif($page_type == 'sections') {
		if(get_setting('mod_jsadmin_ajax_order_sections', '1')) {
			$js_scripts[] = 'dragdrop.js';
			if(SECTION_BLOCKS) {
				$js_buttonCell= 5; 
			} else { 
				$js_buttonCell= 5; // This ist the Cell where the Button "Up" is , by Swen Uth
			} 
		}
	} elseif($page_type == 'config') {
		$js_scripts[] = 'tool.js';
	} else {
		$admin->print_error('PageTtype '.$TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
	
	$sToJS = "
		var JsAdmin = { WB_URL : WB_URL, ADMIN_URL : ADMIN_URL };
		var JsAdminTheme = { THEME_URL : THEME_URL };
		buttonCell=".$js_buttonCell.";\n"; // For variable cell structure in the tables of admin content by Swen  Uth
	I::insertJsCode ($sToJS, "BODY BTM-", 'JsAdminVars');
	
	// Check and Load the needed YUI functions  //, all by Swen Uth
	$YUI_ERROR             = false; // is there an Error
	$YUI_PUT               = '';    // String with javascipt includes
	$YUI_PUT_MISSING_Files = '';    // String with missing files
	reset($js_yui_scripts);
	foreach($js_yui_scripts as $script) {
		if(file_exists(WB_PATH.$script)){
			I::insertJsFile(WB_URL.$script, 'BODY BTM-');
		} else {
			$YUI_ERROR = true;
			$YUI_PUT_MISSING_Files = $YUI_PUT_MISSING_Files."- ".WB_URL.$script."\\n"; // catch all missing files
		}
	}
	
	if(!$YUI_ERROR)	{
		echo $YUI_PUT;  // no Error so go and include
		// Load the needed functions
		foreach($js_scripts as $script) {
			I::insertJsFile($JSADMIN_PATH."/js/".$script, 'BODY BTM-');
		}
	} else {
	    $sToJS = "alert('YUI ERROR!! File not Found: \\n".$YUI_PUT_MISSING_Files." \\nso look in the include folder or switch Javascript Admin off!');\n";
		I::insertJsCode ($sToJS, "BODY BTM-", 'YUI_Error');
	}
}