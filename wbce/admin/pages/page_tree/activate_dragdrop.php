<?php
require('../../../config.php');
 
$admin = new admin('Pages', 'pages');

if(!isset($_GET['dd']) || !is_numeric($_GET['dd']))	exit();

$query_order_pages = sprintf("UPDATE `".TABLE_PREFIX."mod_jsadmin` 
								SET `value` = '%d' 
								WHERE `name` = 'mod_jsadmin_ajax_order_pages'",
							$_GET['dd']);
							
$database->query($query_order_pages);
if($database->is_error()) 
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/index.php');
else
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/index.php');
$admin->print_footer();
?>

