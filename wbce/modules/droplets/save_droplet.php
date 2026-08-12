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

$sBackToList = ADMIN_URL.'/admintools/tool.php?tool=droplets';
// check permission
if ( $admin->get_permission('admintools') == true ){
    // Get id
    if ( isset($_POST['droplet_id']) && is_numeric($_POST['droplet_id']) && !empty($_POST['droplet_id'])) {
        $droplet_id = intval($_POST['droplet_id']);
    } else {
        header( "Location: " . ADMIN_URL . "/pages/index.php" );
    }
} else {   
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $sBackToList );   
    $admin->print_footer();
    exit();
}

$sBackURL = ADMIN_URL.'/admintools/tool.php?tool=droplets&do=modify&droplet_id='.$droplet_id;
// Validate all fields
$sName = $admin->get_post('title');
if($sName == '') {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_FILL_IN_ALL'], $sBackURL);
    $admin->print_footer();
} else {
    $tags = array('<?php', '?'.'>' , '<?');
    $sCode = str_replace($tags, '', $_POST['savecontent']);

    // ── PHP syntax + security check — block save if broken or unsafe ──────────
    $syntaxError = CodeVet::checkSyntax($sCode, $syntaxLine);
    $findings    = $syntaxError === null ? CodeVet::scan($sCode, CodeVetProfile::Droplet) : [];

    if ($syntaxError !== null || $findings !== []) {
        if ($findings !== []) {
            CodeVet::logEvent('droplet_save_blocked', CodeVetProfile::Droplet, $findings, ['droplet_id' => $droplet_id]);
        }
        $sMessage = (function_exists('L_') ? L_('DR_TEXT:INVALIDCODE') : 'Invalid PHP code')
                  . ': ' . ($syntaxError ?? $findings[0]->message);

        // Never discard the admin's unsaved edit — stash it for one read by
        // tool.php?do=modify, which overlays it on top of the DB row and
        // clears it immediately after. Redirect straight back (same pattern
        // Code2's save.php uses) instead of rendering a standalone error page
        // the admin then has to click "back" from.
        $_SESSION['codevet_draft']['droplet_' . $droplet_id] = [
            'name'        => $sName,
            'description' => $admin->get_post('description'),
            'active'      => (int) $admin->get_post('active'),
            'admin_edit'  => (int) $admin->get_post('admin_edit'),
            'admin_view'  => (int) $admin->get_post('admin_view'),
            'code'        => $sCode,
            'comments'    => $admin->get_post('comments'),
            'line'        => $syntaxError !== null ? $syntaxLine : ($findings[0]->line ?? -1),
        ];
        (new Alerts())->sessionToast($sMessage, 'error');
        header('Location: ' . $sBackURL);
        exit();
    }

    $aUpdate = array(
        'id'            => $droplet_id,
        'name'          => $sName,
        'active'        => (int) $admin->get_post('active'),
        'admin_view'    => (int) $admin->get_post('admin_view'),
        'admin_edit'    => (int) $admin->get_post('admin_edit'),
        'show_wysiwyg'  => (int) $admin->get_post('show_wysiwyg'),
        'description'   => $admin->get_post('description'),
        'code'          => $sCode,
        'comments'      => $admin->get_post('comments'),
        'modified_when' => time(),
        'modified_by'   => (int) $admin->get_user_id(),
    );

    $database->upsertRow('{TP}mod_droplets', 'id', $aUpdate);

    if ($database->hasError()) {
        (new Alerts())->sessionToast($database->getError(), 'error');
    } else {
        (new Alerts())->sessionToast($MESSAGE['RECORD_MODIFIED_SAVED'] ?? 'Saved', 'success');
    }

    $sGoto = isset($_POST['save_back']) ? $sBackToList.'&hilite='.$sName : $sBackURL;
    header('Location: ' . $sGoto);
    exit;
}
