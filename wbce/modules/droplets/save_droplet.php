<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require_once '../../config.php';

$sBackURL = ADMIN_URL.'/admintools/tool.php?tool=droplets';

// Include WB admin wrapper script
$admin = new admin('admintools', 'admintools');


// check permission
if ( $admin->get_permission('admintools') == true ) {
    // Get id
    if ( isset($_POST['droplet_id']) && is_numeric($_POST['droplet_id']) && !empty($_POST['droplet_id']))
    {
        $droplet_id = intval($_POST['droplet_id']);
    }
    else
    {
        header( "Location: " . ADMIN_URL . "/pages/index.php" );
    }
} else {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $sBackURL );
    $admin->print_footer();
    exit();
}

$sStayURL = $sBackURL.'&do=modify&droplet_id='.$droplet_id;
// Validate all fields
if ($admin->get_post('title') == '') {
    $admin->print_error($MESSAGE['GENERIC_FILL_IN_ALL'], $sStayURL);
    $admin->print_footer();
    exit();
    
}

$tags = array('<?php', '?'.'>' , '<?');
$aUpdate = array(
    'id'            => $droplet_id,
    'name'          => $admin->get_post('title'),
    'active'        => (int) $admin->get_post('active'),
    'admin_view'    => (int) $admin->get_post('admin_view'),
    'admin_edit'    => (int) $admin->get_post('admin_edit'),
    'show_wysiwyg'  => (int) $admin->get_post('show_wysiwyg'),
    'description'   => $admin->get_post('description'),
    'code'          => str_replace($tags, '', $admin->get_post('savecontent')),
    'comments'      => $admin->get_post('comments'),
    'modified_when' => time(),
    'modified_by'   => (int) $admin->get_user_id()
);
$database->updateRow('{TP}mod_droplets', 'id', $aUpdate);

if ($database->hasError()) {
    $admin->print_error($database->getError(), $sBackURL);
} else {
    $admin->print_success($TEXT['SUCCESS'], $sStayURL);
}
// Print admin footer
$admin->print_footer();
