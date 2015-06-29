<?php
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

// use Debug Mode?
$debugmode = false;
// make directory changes more flexible
$module_name = 'quickSkin';
$module_directory = basename(dirname(__FILE__));

$aMsg = array();
require_once(WB_PATH.'/framework/functions.php');
// COMPILED TEMPLATES
$_CONFIG['quickskin_compiled'] = WB_PATH.'/temp/'.$module_name.'/_skins_tmp/';
if(!is_dir($_CONFIG['quickskin_compiled'])) {
	$msg = createFolderProtectFile($_CONFIG['quickskin_compiled']);
	if(sizeof($msg)) {
		// $admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS'],$module_overview_link );
		$aMsg[] = $msg;
	}
}

// CACHED FILES
$_CONFIG['quickskin_cache'] = WB_PATH.'/temp/'.$module_name.'/_skins_cache/';
if(!is_dir($_CONFIG['quickskin_cache'])) {
	$msg = createFolderProtectFile($_CONFIG['quickskin_cache']);
	if(sizeof($msg)) {
		//$admin->print_error($MESSAGE['GENERIC_BAD_PERMISSIONS'],$module_overview_link );
		$aMsg[] = $msg;
	}
}
$_CONFIG['cache_lifetime'] = 600;

// EXTENTSIONS DIR
$_CONFIG['extensions_dir'] = str_replace('\\','/', dirname(__FILE__).'/_lib/qx'); 

require_once (WB_PATH.'/include/'.$module_directory.'/_lib/quickSkin_28/class.quickskin.php');


/**
	Comment out : will not work on all clients
	Must be also made dependent from the client browser

	SET UP COMPRESSION

if ( ini_get( 'zlib.output_compression' )  && ini_get( 'zlib.output_compression_level' ) != 5 ) {
  ini_set( 'zlib.output_compression_level', '5' );
  ob_start();
}
*/

/**
 * use_common_placeholders DEPRICATED
 * 
 * This function is for QuickSkins internal use.
 * It will replace common placeholders to ease the work 
 * and the creation of modules and its templates
 * This function is called in the class.quickskin.php
 *
 */

function use_common_placeholders($text) {  //  DEPRICATED

	/**
		This function makes possible to use the following PLACEHOLDERS within your modules.
		Works good in PAGE Type and ADMIN TOOL Type modules.
		As of date 12-18-2011, SNIPPET Type Modules weren't tested
		[MODULE_NAME]
		[MODULE_URL]

		[WB_URL]
		[ADMIN_URL]
		[THEME_URL]
		[MEDIA_DIRECTORY]
			
		[TEMPLATE_DIR]
		[TEMPLATE_NAME]
		[TEMPLATE]

	*/
	switch (TRUE){
		case isset($GLOBALS['tool']): $MOD_NAME = $GLOBALS['tool']; break;  // AdminTool
		case isset($GLOBALS['section']['module']): $MOD_NAME = $GLOBALS['section']['module']; break;  // PageType Module
		case isset($GLOBALS['module_dir']): $MOD_NAME = $GLOBALS['module_dir']; break;  // SnippetType Module
		default: $MOD_NAME = FALSE;
	
	}
	
	if(isset($MOD_NAME)) {
		$text = str_replace('[MODULE_NAME]', $MOD_NAME, $text);
		$text = str_replace('[MODULE_URL]', WB_URL.'/modules/'.$MOD_NAME, $text);
	}	
	
	// WB CONSTANTS (frontend only)
	if(defined('TEMPLATE_DIR'))	 $text = str_replace('[TEMPLATE_DIR]', TEMPLATE_DIR, $text);
	if(defined('TEMPLATE_NAME')) $text = str_replace('[TEMPLATE_NAME]', TEMPLATE_NAME, $text);
	if(defined('TEMPLATE'))	     $text = str_replace('[TEMPLATE]', TEMPLATE, $text);	
	
	// WB CONSTANTS (always accessible) 
	$text = str_replace('[WB_URL]', WB_URL, $text);	
	$text = str_replace('[ADMIN_URL]', ADMIN_URL, $text);
	$text = str_replace('[MEDIA_DIRECTORY]', MEDIA_DIRECTORY, $text);
	$text = str_replace('[THEME_URL]', THEME_URL, $text);
		
	return $text;
}