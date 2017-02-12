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
require_once WB_PATH . '/framework/functions.php';	// for WBCE 1.1.x compatibility

// limit advanced Module settings to users with access to admintools
$admin = new admin('Admintools', 'admintools', false, false);
if ($admin->get_permission('admintools') == false) {
	die(header('Location: index.php'));
}

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'modules_install', false, true);
$js_back = ADMIN_URL . '/modules/index.php?advanced';
if(! $admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if a valid action was defined
$action = $admin->get_post('action');
if (! in_array($action, array('install', 'upgrade', 'uninstall'))) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}

// Check if a valid module file was defined
$file = trim($admin->get_post('file'));
$root_dir = realpath(WB_PATH . DIRECTORY_SEPARATOR . 'modules');
$raw_dir = realpath($root_dir . DIRECTORY_SEPARATOR . $file);
if(! ($file && $raw_dir && is_dir($raw_dir) && (strpos($raw_dir, $root_dir) === 0))) {
    // module file empty or outside WBCE module folder
    $admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED'], $js_back);
}

// Extract module folder from realpath for further usage inside script
$file = basename($raw_dir);

// Execute specified module action handler (install.php, upgrade.php or uninstall.php)
$mod_path = WB_PATH . '/modules/' . $file;
if(file_exists($mod_path . '/' . $action . '.php')) {
    require $mod_path . '/' . $action . '.php';
} else {
    $admin->print_error($TEXT['NOT_FOUND'].': <tt>"'.htmlentities($file).'/'.$action.'.php"</tt> ', $js_back);
}

// load module info into database and output status message
load_module($mod_path, false);
$msg = $TEXT['EXECUTE'] . ': <tt>"' . htmlentities($file) . '/' . $action . '.php"</tt>';

switch ($action) {
    case 'install':
        $admin->print_success($msg, $js_back);
        break;

    case 'upgrade':
        upgrade_module(basename($mod_path), false);
        $admin->print_success($msg, $js_back);
        break;

    case 'uninstall':
        $admin->print_success($msg, $js_back);
        break;
}
