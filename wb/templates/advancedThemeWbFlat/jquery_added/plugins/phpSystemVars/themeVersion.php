<?php
/**
 * Website Baker Theme
 * Just file for output version number of admin theme
 * 
 * NOTE:
 * As it is not possible to include php to the backend theme
 * the version number can not directly be included to the theme 
 * --> include the output of this file to the theme by jq
 * 
 * 
 * Part off: Website Baker theme advanced Theme WB Flat
 * More information see: info.php in main theme folder
*/




if (!defined('WB_PATH')) {
    // include wb system data/functions
    include('../../../../../config.php');
}
// --- check if logged in 
$bLoggedIn = (isset($_SESSION['USER_ID']) && is_numeric($_SESSION['USER_ID']));

// go on only forward if logged in
if ($bLoggedIn) {





	// ### get template information file with info vars
	include('../../../info.php');
	
	// ### output version number of admin theme
	echo 'Admin version: ' . $template_version;
	
	// ### 
	// ### --> to include the output to the theme use jQuery
	// ###########################################################
	
}

?>