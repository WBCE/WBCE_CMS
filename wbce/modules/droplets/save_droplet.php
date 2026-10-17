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

require_once '../../config.php';

// Include WB admin wrapper script
$admin = new Admin('admintools', 'admintools', false);
require_once dirname(__FILE__) . '/functions.inc.php';
$oMsgBox = new MessageBox();

$sBackToList = ADMIN_URL.'/admintools/tool.php?tool=droplets';
// check permission (droplets tool, not just any admin-tool) + CSRF token
if ( droplets_may_use_tool($admin) && $admin->checkFTAN() ){
    // Get id
    if ( isset($_POST['droplet_id']) && is_numeric($_POST['droplet_id']) && !empty($_POST['droplet_id'])) {
        $droplet_id = intval($_POST['droplet_id']);
    } else {
        header( "Location: " . ADMIN_URL . "/pages/index.php" );
        exit();
    }
} else {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $sBackToList );   
    $admin->print_footer();
    exit();
}

$sBackURL = ADMIN_URL.'/admintools/tool.php?tool=droplets&do=modify&droplet_id='.$droplet_id;
// Validate all fields
$sName = $admin->add_slashes($admin->get_post('title'));
if($sName == '') {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_FILL_IN_ALL'], $sBackURL);
    $admin->print_footer();
} else {
    // Load the existing droplet and enforce admin_view/admin_edit server-side
    // (the template only hides the controls).
    $oRes      = $database->query(sprintf("SELECT * FROM `{TP}mod_droplets` WHERE id='%d'", $droplet_id));
    $aExisting = ($oRes && $oRes->numRows() > 0) ? $oRes->fetchRow(MYSQLI_ASSOC) : null;
    $bIsAdmin  = droplets_is_admin($admin);
    if (!$aExisting || (!$bIsAdmin && ((int) $aExisting['admin_edit'] === 1 || (int) $aExisting['admin_view'] === 1))) {
        $admin->print_header();
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $sBackToList);
        $admin->print_footer();
        exit();
    }
    $tags = array('<?php', '?'.'>' , '<?');
    $aUpdate = array(
        'id'            => $droplet_id,
        'name'          => $sName,
        'active'        => (int) $admin->get_post('active'),
        // non-admins cannot set or clear the admin-only flags
        'admin_view'    => $bIsAdmin ? (int) $admin->get_post('admin_view') : (int) $aExisting['admin_view'],
        'admin_edit'    => $bIsAdmin ? (int) $admin->get_post('admin_edit') : (int) $aExisting['admin_edit'],
        'show_wysiwyg'  => (int) $admin->get_post('show_wysiwyg'),
        'description'   => $admin->add_slashes($admin->get_post('description')),
        'code'          => $admin->add_slashes(str_replace($tags, '', $_POST['savecontent'])),
        'comments'      => $admin->add_slashes($admin->get_post('comments')),
        'modified_when' => time(),
        'modified_by'   => (int) $admin->get_user_id(),
    );
    
    $database->updateRow('{TP}mod_droplets', 'id', $aUpdate);
    
    
    if($database->is_error()) {
        $oMsgBox->error($database->get_error());
    } else {
        #$oMsgBox->success($MESSAGE['RECORD_MODIFIED_SAVED']);
    }

    $sGoto = isset($_POST['save_back']) ? $sBackToList.'&hilite='.$sName : $sBackURL;
    $oMsgBox->redirect($sGoto);
}
