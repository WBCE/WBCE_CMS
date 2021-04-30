<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require('../../../config.php');
require_once(WB_PATH . '/framework/class.admin.php');
$admin = new admin('Pages', 'pages');

if (!isset($_GET['dd']) || !is_numeric($_GET['dd'])) {
    exit();
}

$query_order_pages = sprintf(
    "UPDATE `" . TABLE_PREFIX . "mod_jsadmin` 
								SET `value` = '%d' 
								WHERE `name` = 'mod_jsadmin_ajax_order_pages'",
    $_GET['dd']
);

$database->query($query_order_pages);
if ($database->is_error()) {
    $admin->print_error($database->get_error(), ADMIN_URL . '/pages/index.php');
} else {
    $admin->print_success($TEXT['SUCCESS'], ADMIN_URL . '/pages/index.php');
}
$admin->print_footer();
?>

