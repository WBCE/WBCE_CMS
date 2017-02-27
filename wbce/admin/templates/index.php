<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Include required files
require '../../config.php';

// Setup admin object, print header and check section permissions
$admin = new admin('Addons', 'templates', true, true);

// Create new template object
$template = new Template(dirname($admin->correct_theme_source('templates.htt')));
$template->set_file('page', 'templates.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('FTAN', $admin->getFTAN());

// Insert values into template list
$template->set_block('main_block', 'template_list_block', 'template_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' order by name");
if($result->numRows() > 0) {
    while($addon = $result->fetchRow()) {
        $template->set_var('VALUE', $addon['directory']);
        $template->set_var('NAME', $addon['name']);
        $template->parse('template_list', 'template_list_block', true);
    }
}

// Insert permissions values
if($admin->get_permission('templates_install') != true) {
    $template->set_var('DISPLAY_INSTALL', 'hide');
}
if($admin->get_permission('templates_uninstall') != true) {
    $template->set_var('DISPLAY_UNINSTALL', 'hide');
}
if($admin->get_permission('templates_view') != true) {
    $template->set_var('DISPLAY_LIST', 'hide');
}

// Insert language headings, urls and text messages
$template->set_var(
    array(
        // Headings
        'HEADING_INSTALL_TEMPLATE' => $HEADING['INSTALL_TEMPLATE'],
        'HEADING_UNINSTALL_TEMPLATE' => $HEADING['UNINSTALL_TEMPLATE'],
        'HEADING_TEMPLATE_DETAILS' => $HEADING['TEMPLATE_DETAILS'],

        // URLs
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL,
        'FTAN' => $admin->getFTAN(),

        // Text messages
        'URL_MODULES' => $admin->get_permission('modules') ?
            '<a href="' . ADMIN_URL . '/modules/index.php">' . $MENU['MODULES'] . '</a>' : '',
        'URL_LANGUAGES' => $admin->get_permission('languages') ?
            '<a href="' . ADMIN_URL . '/languages/index.php">' . $MENU['LANGUAGES'] . '</a>' : '',
        'URL_ADVANCED' => '&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;',
        'TEXT_INSTALL' => $TEXT['INSTALL'],
        'TEXT_UNINSTALL' => $TEXT['UNINSTALL'],
        'TEXT_VIEW_DETAILS' => $TEXT['VIEW_DETAILS'],
        'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
        'CHANGE_TEMPLATE_NOTICE' => $MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE'],
        'TEXT_ADDONS' => $MENU['ADDONS'],
        'TEXT_TEMPLATES' => $MENU['TEMPLATES'],
    )
);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
