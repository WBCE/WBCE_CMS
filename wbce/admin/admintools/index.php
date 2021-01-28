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
$admin = new admin('admintools', 'admintools');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('admintools.htt')));
// $template->debug = true;
$template->set_file('page', 'admintools.htt');
$template->set_block('page', 'main_block', 'main');

// Insert required template variables
$template->set_var('ADMIN_URL', ADMIN_URL);
$template->set_var('THEME_URL', THEME_URL);
$template->set_var('HEADING_ADMINISTRATION_TOOLS', $HEADING['ADMINISTRATION_TOOLS']);

// Insert tools into tool list
$template->set_block('main_block', 'tool_list_block', 'tool_list');

$tool_default_icon = "fa fa-graduation-cap";
$rTools = $database->query(
    "SELECT `directory` FROM {TP}addons 
     WHERE type = 'module' AND `function` LIKE '%tool%' AND `function` NOT LIKE '%hidden%' 
     order by name"
);
if ($rTools->numRows() > 0) {
    while ($tool = $rTools->fetchRow(MYSQLI_ASSOC)) {
        $tool_icon = false;
        $data = @file_get_contents(WB_PATH . '/modules/' . $tool['directory'] . '/info.php');
        $tool_icon = get_variable_content('module_icon', $data, true, false);

        $template->set_var(
            array(
                'TOOL_DIR' => $tool['directory'],
                'TOOL_NAME' => $admin->get_module_name($tool['directory']),
                'TOOL_DESCRIPTION' => $admin->get_module_description($tool['directory']),
                'TOOL_ICON' => ($tool_icon === false) ? $tool_default_icon : $tool_icon
            )
        );
        $template->parse('tool_list', 'tool_list_block', true);
    }
} else {
    $template->set_var('TOOL_LIST', $TEXT['NONE_FOUND']);
}

// Parse template objects output
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

$admin->print_footer();
