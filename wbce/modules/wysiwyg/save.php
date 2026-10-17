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

// suppress to print the header, so no new FTAN will be set
$admin_header = false;

// Tells script to update when this page was last updated
$update_when_modified = true;

// Include WB admin wrapper script
require(WB_PATH . '/modules/admin.php');

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
}

// After check print the header
$admin->print_header();

// Ensure the posted section actually belongs to the posted page. modules/admin.php
// only checks admin rights on page_id and that the section exists (get_section_details
// looks the section up by section_id alone), so without this an editor of page A could
// POST section_id of a section on page B and overwrite that page's content.
if ((int) $section_id !== 0 && (!isset($section['page_id']) || (int) $section['page_id'] !== (int) $page_id)) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'], ADMIN_URL . '/pages/index.php');
}

// Include the WB functions file
require_once(WB_PATH . '/framework/functions.php');

$sMediaUrl = WB_URL . MEDIA_DIRECTORY;
$bBackLink = isset($_POST['pagetree']);

// Update the mod_wysiwygs table with the contents
if (isset($_POST['content' . $section_id])) {
    $content   = $_POST['content' . $section_id];
    $searchfor = '@(<[^>]*=\s*")(' . preg_quote($sMediaUrl) . ')([^">]*".*>)@siU';
    $content   = preg_replace($searchfor, '$1{SYSVAR:MEDIA_REL}$3', $content);
    $text      = strip_tags($content);
    $sql       = 'UPDATE `' . TABLE_PREFIX . 'mod_wysiwyg` ' . 'SET `content`=\'' . $database->escapeString($content) . '\', ' . '`text`=\'' . $database->escapeString($text) . '\' ' . 'WHERE `section_id`=' . (int) $section_id;
    $database->query($sql);
}

$sec_anchor = (defined('SEC_ANCHOR') && (SEC_ANCHOR != '') ? '#' . SEC_ANCHOR . $section['section_id'] : '');
if (defined('EDIT_ONE_SECTION') && EDIT_ONE_SECTION) {
    $edit_page = ADMIN_URL . '/pages/modify.php?page_id=' . $page_id . '&wysiwyg=' . $section_id;
} elseif ($bBackLink) {
    $edit_page = ADMIN_URL . '/pages/index.php';
} else {
    $edit_page = ADMIN_URL . '/pages/modify.php?page_id=' . $page_id . $sec_anchor;
}

// Check if there is a database error, otherwise say successful
if ($database->is_error()) {
    $admin->print_error($database->get_error(), $js_back);
} else {
    $admin->print_success($MESSAGE['PAGES_SAVED'], $edit_page);
}

// Print admin footer
$admin->print_footer();