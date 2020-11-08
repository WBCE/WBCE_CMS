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
require_once WB_PATH . '/framework/functions.php';

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'modules_view', false, true);
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    $admin->print_footer();
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user selected a valid module file
$file = trim($admin->get_post('file'));
$root_dir = realpath(WB_PATH . DIRECTORY_SEPARATOR . 'modules');
$raw_dir = realpath($root_dir . DIRECTORY_SEPARATOR . $file);
if (!($file && $raw_dir && is_dir($raw_dir) && (strpos($raw_dir, $root_dir) === 0))) {
    // module file empty or outside WBCE module folder
    $admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Extract module folder from realpath for further usage inside script
$sModDir = basename($raw_dir);

// Get Module Data
$rModule = $database->query(
    "SELECT * FROM `{TP}addons` WHERE type = 'module' AND `directory` = '" . $database->escapeString($sModDir) . "'"
);
if ($rModule->numRows() > 0) {
    $aModule = $rModule->fetchRow(MYSQLI_ASSOC);
}

// Collect the modules Type description.
// Since we can now have hybride modules, a single module can have different functions/types
// associated with it.
$TEXT['INITIALIZE'] = isset($TEXT['INITIALIZE']) ? $TEXT['INITIALIZE'] : 'Initialize';
$TEXT['PREINIT'] = isset($TEXT['PREINIT']) ? $TEXT['PREINIT'] : 'Preinit';
$aModType = array();
if (empty($aModule['function'])) {
    $aModType[] = $TEXT['UNKNOWN'];
}
if (preg_match("/page/", $aModule['function'])) {
    $aModType[] = $TEXT['PAGE'];
}
if (preg_match("/wysiwyg/", $aModule['function'])) {
    $aModType[] = $TEXT['WYSIWYG_EDITOR'];
}
if (preg_match("/tool/", $aModule['function'])) {
    $aModType[] = $TEXT['ADMINISTRATION_TOOL'];
}
if (preg_match("/admin/", $aModule['function'])) {
    $aModType[] = $TEXT['ADMIN'];
}
if (preg_match("/snippet/", $aModule['function'])) {
    $aModType[] = $TEXT['CODE_SNIPPET'];
}
if (preg_match("/initialize/", $aModule['function'])) {
    $aModType[] = $TEXT['INITIALIZE'];
}
if (preg_match("/preinit/", $aModule['function'])) {
    $aModType[] = $TEXT['PREINIT'];
}
$sModuleType = implode(", ", $aModType);

// Get the module description or its translation if set in the language file of the module
$aModule['description'] = $admin->get_module_description($sModDir);
// Get the module name or its translation if set in the language file of the module
$aModule['name'] = $admin->get_module_name($sModDir, true, ' <i>[%s]</i>');

// Create new template object
$oTemplate = new Template(dirname($admin->correct_theme_source('modules_details.htt')));
$oTemplate->set_file('page', 'modules_details.htt');
$oTemplate->set_block('page', 'main_block', 'main');

// Hand over language strings and variables to template
$oTemplate->set_var(
    array(
        'TYPE' => $sModuleType,
        'NAME' => $aModule['name'],
        'AUTHOR' => $aModule['author'],
        'DESCRIPTION' => $aModule['description'],
        'VERSION' => $aModule['version'],
        'DESIGNED_FOR' => $aModule['platform'],
        'LICENSE' => $aModule['license'],
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL,
        'TEXT_NAME' => $TEXT['NAME'],
        'TEXT_TYPE' => $TEXT['TYPE'],
        'TEXT_AUTHOR' => $TEXT['AUTHOR'],
        'TEXT_VERSION' => $TEXT['VERSION'],
        'TEXT_DESIGNED_FOR' => $TEXT['DESIGNED_FOR'],
        'TEXT_DESCRIPTION' => $TEXT['DESCRIPTION'],
        'TEXT_LICENSE' => $TEXT['LICENSE'],
        'TEXT_BACK' => $TEXT['BACK'],
        'HEADING_MODULE_DETAILS' => $HEADING['MODULE_DETAILS']
    )
);

// Parse module object
$oTemplate->parse('main', 'main_block', false);
$oTemplate->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
