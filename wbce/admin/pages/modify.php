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

// Create new admin object
require '../../config.php';
require_once WB_PATH . '/framework/class.admin.php';

$admin = new admin('Pages', 'pages_modify');

// Get page id
if (!isset($_REQUEST['page_id']) || !is_numeric($_REQUEST['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = (int)$_REQUEST['page_id'];
}

if (!$page_id || !is_numeric($page_id)) { // double check doesn't hurt ;D
    $admin->print_error('Invalid arguments passed - script stopped.');
}

// Get perms
if (!$admin->get_page_permission($page_id, 'admin')) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

$sectionId = isset($_GET['wysiwyg']) ? htmlspecialchars($admin->get_get('wysiwyg')) : null;

// Get page details
$results_array = $admin->get_page_details($page_id);

// Get display name of person who last modified the page
$user = $admin->get_user_details($results_array['modified_by']);

// Convert the unix ts for modified_when to human a readable form

$modified_ts = ($results_array['modified_when'] != 0) ? date(TIME_FORMAT . ', ' . DATE_FORMAT, $results_array['modified_when'] + (int)TIMEZONE) : 'Unknown';
// $ftan_module = $GLOBALS['ftan_module'];
// Setup template object, parse vars to it, then parse it
// Create new template object
$oTemplate = new Template(dirname($admin->correct_theme_source('pages_modify.htt')));

// Disable removing of unknown vars to prevent the deleting of JavaScript arrays [] or {}
$oTemplate->set_unknowns('keep');

// $oTemplate->debug = true;
$oTemplate->set_file('page', 'pages_modify.htt');
$oTemplate->set_block('page', 'main_block', 'main');
$oTemplate->set_var('FTAN', $admin->getFTAN());

$oTemplate->set_var(array(
    'ADMIN_URL' => ADMIN_URL,
    'WB_URL' => WB_URL,
    'THEME_URL' => THEME_URL,
    'PAGE_ID' => $results_array['page_id'],
    'PAGE_IDKEY' => $results_array['page_id'],
    'PAGE_TITLE' => $results_array['page_title'],
    'MENU_TITLE' => $results_array['menu_title'],
    'MODIFIED_BY' => $user['display_name'],
    'MODIFIED_WHEN' => $modified_ts,
    'MODIFIED_BY_USERNAME' => $user['username'],
    // Language Strings
    'LAST_MODIFIED' => $MESSAGE['PAGES_LAST_MODIFIED'],
    'TEXT_CURRENT_PAGE' => $TEXT['CURRENT_PAGE'],
    'TEXT_CHANGE_SETTINGS' => $TEXT['CHANGE_SETTINGS'],
    'HEADING_MODIFY_PAGE' => $HEADING['MODIFY_PAGE'],
    'TEXT_MANAGE_SECTIONS' => $HEADING['MANAGE_SECTIONS'],
));

$oTemplate->set_block('main_block', 'show_modify_block', 'show_modify');
if ($modified_ts == 'Unknown') {
    $oTemplate->set_block('show_modify', '');
    $oTemplate->set_var('CLASS_DISPLAY_MODIFIED', 'hide');
} else {
    $oTemplate->set_var('CLASS_DISPLAY_MODIFIED', '');
    $oTemplate->parse('show_modify', 'show_modify_block', true);
}

// Work-out if we should show the "manage sections" link
$sSql = "SELECT COUNT(*) FROM `{TP}sections` WHERE `page_id` = " . $page_id . " AND `module` = 'menu_link'";
$bShowMenuLink = $database->get_one($sSql);
if (defined("MENU_LINK_TRANSFORMER") && MENU_LINK_TRANSFORMER == true) {
    $bShowMenuLink = false;
}
$oTemplate->set_block('main_block', 'show_section_block', 'show_section');
if ($bShowMenuLink) {
    $oTemplate->set_block('show_section', '');
    $oTemplate->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
} elseif (MANAGE_SECTIONS == 'enabled') {
    $oTemplate->parse('show_section', 'show_section_block', true);
} else {
    $oTemplate->set_block('show_section', '');
    $oTemplate->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
}

$oTemplate->set_block('main_block', 'section_module_block', 'section_module');
// get template used for the displayed page (for displaying block details)
if (SECTION_BLOCKS) {
    $sSql = 'SELECT `template` FROM `{TP}pages` WHERE `page_id`= ' . $page_id;
    if (($sTemplate = $database->get_one($sSql)) !== null) {
        $sPageTemplate = ($sTemplate == '') ? DEFAULT_TEMPLATE : $sTemplate;
        // include template info.php file if exists
        $sFile = WB_PATH . '/templates/' . $sPageTemplate . '/info.php';
        if (is_readable($sFile)) {
            include_once $sFile;
        }
    }
}

// Get sections for this page
// workout for EDIT_ONE_SECTION for faster pageloading
$sWhereClause = (defined('EDIT_ONE_SECTION') && EDIT_ONE_SECTION && is_numeric($sectionId))
    ? 'WHERE `section_id` = ' . (int)$sectionId
    : 'WHERE `page_id` = ' . (int)$page_id;
$sSql = 'SELECT * FROM `{TP}sections` ' . $sWhereClause . ' ORDER BY position ASC';
if ($rSections = $database->query($sSql)) {
    while ($section = $rSections->fetchRow(MYSQLI_ASSOC)) {
        $section_id = $section['section_id'];
        $module = $section['module'];

        //Have permission?
        if (!is_numeric(array_search($module, $_SESSION['MODULE_PERMISSIONS']))) {
            // Include the modules editing script if it exists
            $sModifyFile = WB_PATH . '/modules/' . $module . '/modify.php';
            if (file_exists($sModifyFile)) {

                // Define section block name
                if (isset($block[$section['block']]) && trim(strip_tags(($block[$section['block']]))) != '') {
                    $sBlockName = htmlentities(strip_tags($block[$section['block']]));
                } else {
                    $sBlockName = '#' . (int)$section['block'];
                    if ($section['block'] == 1) {
                        $sBlockName = $TEXT['MAIN'];
                    }
                }

                // Get correct section anchor
                // We definitely need a section anchor in the Backend (no matter if it's set to empty for the Frontend)
                // Therefore, if SEC_ANCHOR is empty, in the backend we will use 'sec_anchor_' instead.
                $sSectionAnchor = (defined('SEC_ANCHOR') && SEC_ANCHOR ? SEC_ANCHOR : 'sec_anchor_') . $section['section_id'];

                // Set template vars
                $oTemplate->set_var(
                    array(
                        'SECTION_ANCHOR' => $sSectionAnchor,
                        'TEXT_BLOCK' => $TEXT['BLOCK'],
                        'BLOCK_NAME' => $sBlockName,
                        'SECTION_ID' => $section['section_id'],
                        'SECTION_MODULE' => $admin->get_module_name($section['module']),
						'SECTION_MODULE_CLASS' => $section['module'],
                        'SECTION_BLOCK' => $section['block'],
                        'SECTION_NAME' => htmlentities(strip_tags($section['namesection'])),
                    )
                );

                // Set section modify output as template var
                ob_start();
                require $sModifyFile;
                $oTemplate->set_var('SECTION_MODIFY_OUTPUT', ob_get_clean());
                // Parse section module
                $oTemplate->parse('section_module', 'section_module_block', true);
            }
        }
    }
}

// Parse and output template
$oTemplate->parse('main', 'main_block', false);
$oTemplate->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
