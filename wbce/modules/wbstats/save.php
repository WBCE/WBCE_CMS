<?php


// manually include the config.php file (defines the required constants)
require('../../config.php');
$update_when_modified = true;
require(WB_PATH.'/modules/admin.php');


// print admin footer
$admin->print_footer()

?>