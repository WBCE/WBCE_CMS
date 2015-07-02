<?php
/**
 *
 * @category        admin
 * @package         templates
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: index.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/templates/index.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
 */

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'templates');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('templates.htt')));
// $template->debug = true;
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

// Insert language headings
$template->set_var(array(
                    'HEADING_INSTALL_TEMPLATE' => $HEADING['INSTALL_TEMPLATE'],
                    'HEADING_UNINSTALL_TEMPLATE' => $HEADING['UNINSTALL_TEMPLATE'],
                    'HEADING_TEMPLATE_DETAILS' => $HEADING['TEMPLATE_DETAILS']
                )
            );
// insert urls
$template->set_var(array(
                    'ADMIN_URL' => ADMIN_URL,
                    'WB_URL' => WB_URL,
                    'THEME_URL' => THEME_URL,
                    'FTAN' => $admin->getFTAN()
                )
            );
// Insert language text and messages
$template->set_var(array(
    'URL_MODULES' => $admin->get_permission('modules') ? 
        '<a href="' . ADMIN_URL . '/modules/index.php">' . $MENU['MODULES'] . '</a>' : '',
    'URL_LANGUAGES' => $admin->get_permission('languages') ?
        '<a href="' . ADMIN_URL . '/languages/index.php">' . $MENU['LANGUAGES'] . '</a>' : '',
    'URL_ADVANCED' => '&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;',
    'TEXT_INSTALL' => $TEXT['INSTALL'],
    'TEXT_UNINSTALL' => $TEXT['UNINSTALL'],
    'TEXT_VIEW_DETAILS' => $TEXT['VIEW_DETAILS'],
    'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
    'CHANGE_TEMPLATE_NOTICE' => $MESSAGE['TEMPLATES']['CHANGE_TEMPLATE_NOTICE']
    )
);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
