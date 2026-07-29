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

$admin_header         = false; // don't print header immediately
$update_when_modified = true;  // Tell script to update when this page was last updated
require WB_PATH.'/modules/admin.php'; // Include WB admin wrapper script
$js_back     = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
$js_back_all = ADMIN_URL.'/pages';

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}


if (isset($_POST['menu_link'])) {
    
    // if target is set, write it directly into the {TP}pages table
    // this will be used by show_menu2 to directly link to the URL
    $aUpdatePageTable = array(
        'page_id'  => $page_id,
        'target'   => $admin->get_post('target'),
    );
    $database->upsertRow('{TP}pages', 'page_id', $aUpdatePageTable);
    
    // determine the target: '-1' external, '-2' structure-only (no link), else an internal page id
    if ($_POST['linktype'] == 'ext') {
        $targetPageId = -1;
    } elseif ($_POST['linktype'] == 'none') {
        $targetPageId = -2;
    } else {
        $targetPageId = intval($admin->get_post('menu_link'));
    }

    // update {TP}mod_menu_link table
    $aUpdateModTable = array(
        'page_id'        => $page_id,
        'section_id'     => $section_id,
        'target_page_id' => $targetPageId,
        'redirect_type'  => $admin->get_post('r_type'),
        'anchor'         => $_POST['linktype'] == 'int' ? $admin->get_post('anchor') : '0',
        'extern'         => isset($_POST['extern']) && $_POST['linktype'] == 'ext' ? $admin->get_post('extern') : '',
    );
    $database->upsertRow('{TP}mod_menu_link', 'section_id', $aUpdateModTable);

    // Check if there is a database error, otherwise say successful
    if ($database->hasError()) {
        
        $admin->print_header();
        $admin->print_error($database->getError(), $js_back, true);
    } else {
        (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
        $redirect = isset($_POST['save_and_back']) ? $js_back_all : $js_back;
        header('Location: ' . $redirect);
        exit;
    }
} else {    
    $admin->print_header();
    $admin->print_error('No Data was set', $js_back, true);
}
