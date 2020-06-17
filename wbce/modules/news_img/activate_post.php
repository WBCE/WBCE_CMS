<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */


require_once __DIR__.'/functions.inc.php';

// Get id
if ((!isset($_GET['post_id']))and(!isset($_POST['manage_posts']))) {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = false;
// Include WB admin wrapper script
require WB_PATH.'/modules/admin.php';
if (isset($_POST['manage_posts']) && is_array($_POST['manage_posts']) && !$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error(
        $MESSAGE['GENERIC_SECURITY_ACCESS']
     .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php'
    );
    $admin->print_footer();
    exit();
} else {
    if(!defined('CAT_PATH')) {
        $admin->print_header();
    }
}

$post_id = $admin->checkIDKEY('post_id', 0, 'GET');
if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
    $post_id = intval($_GET['post_id']);
}
if (!$post_id && isset($_GET['post_id'])) {
    $admin->print_error(
        $MESSAGE['GENERIC_SECURITY_ACCESS']
     .' (IDKEY) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php'
    );
    $admin->print_footer();
    exit();
}

$value=1;
if (isset($_GET['value']) && ($_GET['value']==0)) {
    $value=0;
}

$_POST['manage_posts'] = array($post_id);
$result = mod_nwi_post_activate($value);

// Check if there is a db error, otherwise say successful
if ($result==false) {
    $admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
    $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();
