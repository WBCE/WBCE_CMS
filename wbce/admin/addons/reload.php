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

// limit advanced Addon settings to users with access to admintools
$admin = new admin('Admintools', 'admintools', false, false);
if ($admin->get_permission('admintools') == false) {
	die(header('Location: index.php'));
}

// reload Addon overview page if not at least on advanced Addon setting was selected
$post_check = array('reload_modules', 'reload_templates', 'reload_languages');
foreach ($post_check as $index => $key) {
	if (!isset($_POST[$key])) unset($post_check[$index]);
}
if (count($post_check) == 0) {
	die(header('Location: index.php?advanced'));
}

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'addons', false, true);
$js_back = ADMIN_URL . '/addons/index.php?advanced';
if(! $admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

/**
 * Reload all specified Addons
 */
require_once WB_PATH . '/languages/' . LANGUAGE .'.php';
$msg = array();
$table = TABLE_PREFIX . 'addons';

foreach ($post_check as $key) {
	switch ($key) {
		case 'reload_modules':
			if ($handle = opendir(WB_PATH . '/modules')) {
				// delete modules from database
				$sql = "DELETE FROM `$table` WHERE `type` = 'module'";
				$database->query($sql);

				// loop over all modules
				while(false !== ($file = readdir($handle))) {
					if ($file != '' && substr($file, 0, 1) != '.' && $file != 'admin.php' && $file != 'index.php') {
						load_module(WB_PATH . '/modules/' . $file);
					}
				}
				closedir($handle);
				// add success message
				$msg[] = $MESSAGE['ADDON_MODULES_RELOADED'];

			} else {
				// provide error message and stop
				$admin->print_error($MESSAGE['ADDON_ERROR_RELOAD'], $js_back);
			}
			break;

		case 'reload_templates':
			if ($handle = opendir(WB_PATH . '/templates')) {
				// delete templates from database
				$sql = "DELETE FROM `$table` WHERE `type` = 'template'";
				$database->query($sql);

				// loop over all templates
				while(false !== ($file = readdir($handle))) {
					if($file != '' AND substr($file, 0, 1) != '.' AND $file != 'index.php') {
						load_template(WB_PATH . '/templates/' . $file);
					}
				}
				closedir($handle);
				// add success message
				$msg[] = $MESSAGE['ADDON_TEMPLATES_RELOADED'];

			} else {
				// provide error message and stop
				$admin->print_error($MESSAGE['ADDON_ERROR_RELOAD'], $js_back);
			}
			break;

		case 'reload_languages':
			if ($handle = opendir(WB_PATH . '/languages/')) {
				// delete languages from database
				$sql = "DELETE FROM `$table` WHERE `type` = 'language'";
				$database->query($sql);

				// loop over all languages
				while(false !== ($file = readdir($handle))) {
					if ($file != '' && substr($file, 0, 1) != '.' && $file != 'index.php') {
						load_language(WB_PATH . '/languages/' . $file);
					}
				}
				closedir($handle);
				// add success message
				$msg[] = $MESSAGE['ADDON_LANGUAGES_RELOADED'];

			} else {
				// provide error message and stop
				$admin->print_error($MESSAGE['ADDON_ERROR_RELOAD'], $js_back);
			}
			break;
	}
}

// output success message
$admin->print_success(implode($msg, '<br />'), $js_back);
$admin->print_footer();
