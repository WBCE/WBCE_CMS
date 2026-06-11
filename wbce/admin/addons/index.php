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
$admin = new admin('Addons', 'addons', true, true);

// Create new template object
$template = new Template(dirname($admin->correct_theme_source('addons.htt')));
$template->set_file('page', 'addons.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values into the template object
$template->set_var(
    array(
        'ADMIN_URL' => ADMIN_URL,
        'THEME_URL' => THEME_URL,
        'WB_URL' => WB_URL
    )
);

/**
 *    Setting up the blocks
 */
$template->set_block('main_block', "modules_block", "modules");
$template->set_block('main_block', "templates_block", "templates");
$template->set_block('main_block', "languages_block", "languages");
$template->set_block('main_block', "reload_block", "reload");

/**
 *    Insert permission values into the template object
 *    Obsolete as we are using blocks ... see "parsing the blocks" section
 */
$display_none = 'style="display: none;"';

// Main addon-card visibility: requires any sub-permission for that type
if (!$admin->get_permission('modules')) {
    $template->set_var('DISPLAY_MODULES', $display_none);
}
if (!$admin->get_permission('templates')) {
    $template->set_var('DISPLAY_TEMPLATES', $display_none);
}
if (!$admin->get_permission('languages')) {
    $template->set_var('DISPLAY_LANGUAGES', $display_none);
}

// Advanced / Reload section: requires install permission for at least one addon type
$can_reload_modules   = $admin->get_permission('modules_install');
$can_reload_templates = $admin->get_permission('templates_install');
$can_reload_languages = $admin->get_permission('languages_install');
$has_install = $can_reload_modules || $can_reload_templates || $can_reload_languages;

// Per-type visibility inside the reload block (install permission required)
$template->set_var('DISPLAY_RELOAD_MODULES',   $can_reload_modules   ? '' : $display_none);
$template->set_var('DISPLAY_RELOAD_TEMPLATES', $can_reload_templates ? '' : $display_none);
$template->set_var('DISPLAY_RELOAD_LANGUAGES', $can_reload_languages ? '' : $display_none);

// Hide the Advanced icon/link wrapper and reload block when no install permissions exist
if (!$has_install) {
    $template->set_var('DISPLAY_ADVANCED_ICON', $display_none);
}
if (!isset($_GET['advanced']) || !$has_install) {
    $template->set_var('DISPLAY_RELOAD', $display_none);
}

/**
 *    Insert section names and descriptions
 */
$template->set_var(
    array(
        'ADDONS_OVERVIEW' => $MENU['ADDONS'],
        'MODULES' => $MENU['MODULES'],
        'TEMPLATES' => $MENU['TEMPLATES'],
        'LANGUAGES' => $MENU['LANGUAGES'],
        'MODULES_OVERVIEW' => $OVERVIEW['MODULES'],
        'TEMPLATES_OVERVIEW' => $OVERVIEW['TEMPLATES'],
        'LANGUAGES_OVERVIEW' => $OVERVIEW['LANGUAGES'],
        'TXT_ADMIN_SETTINGS' => $TEXT['ADMIN'] . ' ' . $TEXT['SETTINGS'],
        'MESSAGE_RELOAD_ADDONS' => $MESSAGE['ADDON_RELOAD'],
        'TEXT_RELOAD' => $TEXT['RELOAD'],
        'RELOAD_URL' => ADMIN_URL . '/addons/reload.php',
        'URL_ADVANCED' => $has_install
            ? '<a href="' . ADMIN_URL . '/addons/index.php?advanced">' . $TEXT['ADVANCED'] . '</a>'
            : '',
        'ADVANCED_URL' => $has_install ? ADMIN_URL . '/addons/index.php' : '',
        'TEXT_ADVANCED' => $TEXT['ADVANCED'],
        'FTAN' => $admin->getFTAN()
    )
);

/**
 *    Parsing the blocks ...
 */
if ($admin->get_permission('modules') == true) {
    $template->parse('main_block', "modules_block", true);
}
if ($admin->get_permission('templates') == true) {
    $template->parse('main_block', "templates_block", true);
}
if ($admin->get_permission('languages') == true) {
    $template->parse('main_block', "languages_block", true);
}
if (isset($_GET['advanced']) and $admin->get_permission('admintools') == true) {
    $template->parse('main_block', "reload_block", true);
}

/**
 *    Parse template object
 */
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

/**
 *    Print admin footer
 */
$admin->print_footer();
