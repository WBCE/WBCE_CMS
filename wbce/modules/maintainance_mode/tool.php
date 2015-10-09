<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license			WTFPL
 */

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// modules need to load languages on their own ??? What a piece of shit !!!
// loading it this way we avoid partial translation errors.
$mpath=WB_PATH.'/modules/maintainance_mode/'; // we need this one later on too 
$lpath=$mpath.'languages/';
// basic EN
require_once($lpath.'EN.php');
// get other language if exists
if(is_file($lpath.LANGUAGE.'.php')) 
   require_once($lpath.LANGUAGE.'.php'); 

// hopefully we dont need this ...
/*
// check if backend.css file needs to be included into the <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH .'/modules/jsadmin/backend.css')) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/jsadmin/backend.css');
	echo "\n</style>\n";
}
*/

// Form send , ok lets see what to do
if(isset($_POST['save_settings']))  {

    // check if this is is no attack
	if (!$admin->checkFTAN()) {
		if(!$admin_header) { $admin->print_header(); }
		$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$_SERVER['REQUEST_URI']); //ends page here
	}

	// Include functions file
    // not sure we need this?? We test this later.
	require_once(WB_PATH.'/framework/functions.php');


    // here the actual action is going on, we set the setting
    if ($admin->get_post("mmode")) Settings::Set ("wb_maintainance_mode", true);
    else                           Settings::Set ("wb_maintainance_mode", false);


	// check if there is a database error, otherwise say successful
    // this should be refined , as the functions are capable of reporting errors.
	if(!$admin_header) { $admin->print_header(); }
	if($database->is_error()) {
		$admin->print_error($database->get_error(), $js_back); // ends page here
	} else {
		$admin->print_success($MESSAGE['PAGES_SAVED'], ADMIN_URL.'/admintools/tool.php?tool=maintainance_mode'); // ends page here
	}

} 


// Display form
    // get setting from DB , as constant may not be set yet.
	$mmode=(string)Settings::Get ("wb_maintainance_mode");
    if ($mmode=="true") $mmode=' checked="checked" ';
    else                $mmode='';  

    include($mpath."templates/maintainance.tpl.php");

 
