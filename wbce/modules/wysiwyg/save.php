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

require '../../config.php';

$admin_header = false;
$update_when_modified = true;

require WB_PATH . '/modules/admin.php';

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error(
        $MESSAGE['GENERIC_SECURITY_ACCESS'],
        ADMIN_URL . '/pages/modify.php?page_id=' . $page_id
    );
}

$admin->print_header();

$sMediaUrl = WB_URL . MEDIA_DIRECTORY;
$bBackLink = isset($_POST['pagetree']);

if (isset($_POST['content' . $section_id])) {
    $content = $_POST['content' . $section_id];

    // Replace absolute media URLs with portable placeholder
    $content = preg_replace(
        '@(<[^>]*=\s*")(' . preg_quote($sMediaUrl, '@') . ')([^">]*".*>)@siU',
        '$1{SYSVAR:MEDIA_REL}$3',
        $content
    );

    $database->upsertRow('{TP}mod_wysiwyg', 'section_id', [
        'section_id' => (int) $section_id,
        'page_id'    => (int) $page_id,
        'content'    => $content,
        'text'       => strip_tags($content),
    ]);
}

$sec_anchor = defined('SEC_ANCHOR') && SEC_ANCHOR !== ''
    ? '#' . SEC_ANCHOR . $section_id
    : '';

if (defined('EDIT_ONE_SECTION') && EDIT_ONE_SECTION) {
    $redirect = ADMIN_URL . '/pages/modify.php?page_id=' . $page_id . '&wysiwyg=' . $section_id;
} elseif ($bBackLink) {
    $redirect = ADMIN_URL . '/pages/index.php';
} else {
    $redirect = ADMIN_URL . '/pages/modify.php?page_id=' . $page_id . $sec_anchor;
}

if ($database->hasError()) {
    $admin->print_error($database->getError(), $redirect);
}

$admin->print_success($MESSAGE['PAGES_SAVED'], $redirect);
$admin->print_footer();