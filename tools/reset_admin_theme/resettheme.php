<?php
/*
1. Upload the script to the root of your server.
2. Run it once by calling the url http://yourserver/resettheme.php
3. Delete the file from the server.

Your admin now should be reset to the default WB_Theme.

*/




include ('config.php');

//$theme="wb_theme";  //WBclassic
$theme="advancedThemeWbFlat"; //WBCE

$database->query( "UPDATE ".TABLE_PREFIX."settings SET `value` = '".$theme."' where `name` = 'default_theme'");
if($database->is_error()) {
	echo "Error: ".$database->get_error();
} else {
	echo "Succesfully reset default Admin-Theme to 'wb_theme'";
}


