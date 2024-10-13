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

// Include required files
require '../../config.php';

// Setup admin object, print header and check section permissions
$admin = new admin('Addons', 'languages', true, true);

// Create new template object
$template = new Template(dirname($admin->correct_theme_source('languages.htt')));
$template->set_file('page', 'languages.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values into language list
$template->set_block('main_block', 'language_list_block', 'language_list');
$result = $database->query("SELECT * FROM " . TABLE_PREFIX . "addons WHERE type = 'language' order by directory");
if ($result->numRows() > 0) {
    while ($addon = $result->fetchRow()) {
        $template->set_var('VALUE', $addon['directory']);
        $template->set_var('NAME', $addon['name'] . ' (' . $addon['directory'] . ')');
        $template->parse('language_list', 'language_list_block', true);
    }
}

// Insert permissions values
if ($admin->get_permission('languages_install') != true) {
    $template->set_var('DISPLAY_INSTALL', 'hide');
}
if ($admin->get_permission('languages_uninstall') != true) {
    $template->set_var('DISPLAY_UNINSTALL', 'hide');
}
if ($admin->get_permission('languages_view') != true) {
    $template->set_var('DISPLAY_LIST', 'hide');
}

// Insert language headings
$template->set_var(
    array(
        // Headings
        'HEADING_INSTALL_LANGUAGE' => $HEADING['INSTALL_LANGUAGE'],
        'HEADING_UNINSTALL_LANGUAGE' => $HEADING['UNINSTALL_LANGUAGE'],
        'HEADING_LANGUAGE_DETAILS' => $HEADING['LANGUAGE_DETAILS'],
		'INFO_INSTALL_LANGUAGE' =>$TEXT['INFO_INSTALL_LANGUAGE'],
		'TEXT_RELOAD' => $TEXT['RELOAD'],

        // URLs
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL,
        'FTAN' => $admin->getFTAN(),

        // Text messages
        'URL_MODULES' => $admin->get_permission('modules') ?
            '<a href="' . ADMIN_URL . '/modules/index.php">' . $MENU['MODULES'] . '</a>' : '',
        'URL_TEMPLATES' => $admin->get_permission('templates') ?
            '<a href="' . ADMIN_URL . '/templates/index.php">' . $MENU['TEMPLATES'] . '</a>' : '',
        'URL_ADVANCED' => '&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;',
        'TEXT_INSTALL' => $TEXT['INSTALL'],
        'TEXT_UNINSTALL' => $TEXT['UNINSTALL'],
        'TEXT_VIEW_DETAILS' => $TEXT['VIEW_DETAILS'],
        'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT']
    )
);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
