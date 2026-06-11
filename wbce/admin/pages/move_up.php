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

require('../../config.php');

// Determine what is being moved (page or section)
if (isset($_GET['page_id']) and is_numeric($_GET['page_id'])) {
    if (isset($_GET['section_id']) and is_numeric($_GET['section_id'])) {
        $page_id      = $_GET['page_id'];
        $id           = $_GET['section_id'];
        $id_field     = 'section_id';
        $common_field = 'page_id';
        $table        = TABLE_PREFIX . 'sections';
    } else {
        $id           = $_GET['page_id'];
        $id_field     = 'page_id';
        $common_field = 'parent';
        $table        = TABLE_PREFIX . 'pages';
    }
} else {
    header("Location: index.php");
    exit(0);
}

$admin  = new Admin('Pages', 'pages_settings', false);
$alerts = new Alerts();
$order  = new Order($table, 'position', $id_field, $common_field);

if ($id_field == 'page_id') {
    $backUrl = ADMIN_URL . '/pages/index.php';
    if ($order->move_up($id)) {
        $alerts->sessionToast($MESSAGE['PAGES_REORDERED'], 'success');
        header('Location: ' . $backUrl);
        exit;
    } else {
        $admin->print_header();
        $admin->print_error($MESSAGE['PAGES_CANNOT_REORDER'], $backUrl);
    }
} else {
    $backUrl = ADMIN_URL . '/pages/sections.php?page_id=' . $page_id;
    if ($order->move_up($id)) {
        $alerts->sessionToast($TEXT['SUCCESS'], 'success');
        header('Location: ' . $backUrl);
        exit;
    } else {
        $admin->print_header();
        $admin->print_error($TEXT['ERROR'], $backUrl);
    }
}
