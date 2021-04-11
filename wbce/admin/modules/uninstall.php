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
$admin = new admin('Addons', 'modules_uninstall', false, true);
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
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
$file = basename($raw_dir);

// Helper function
if (!function_exists("replace_all")) {
    function replace_all(&$aArray, $aStr = "")
    {
        foreach ($aArray as $k => $v) {
            $aStr = str_replace("{{" . $k . "}}", $v, $aStr);
        }
        return $aStr;
    }
}

/**
 * Check if the module is used on pages/sections
 */
$mod_dir_escaped = $database->escapeString($file);
$info = $database->query("SELECT section_id, page_id FROM " . TABLE_PREFIX . "sections WHERE module='" . $mod_dir_escaped . "'");
if ($info->numRows() > 0) {
    // Module is in use, so we have to warn the user
    $msg_template_str = $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'];
    $temp = explode(";", $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES']);
    $add = $info->numRows() == 1 ? $temp[0] : $temp[1];

    /**
     * The template-string for displaying the Page-Titles ... in this case as a link
     */
    $page_template_str = "- <b><a href='../pages/sections.php?page_id={{id}}'>{{title}}</a></b><br />";
    $values = array('type' => 'Modul', 'type_name' => $file, 'pages' => $add);
    $msg = replace_all($values, $msg_template_str);

    $page_names = "";
    while ($data = $info->fetchRow()) {
        $temp = $database->query("SELECT page_title from " . TABLE_PREFIX . "pages where page_id=" . $data['page_id']);
        $temp_title = $temp->fetchRow();
        $page_info = array(
            'id' => $data['page_id'],
            'title' => $temp_title['page_title']
        );
        $page_names .= replace_all($page_info, $page_template_str);
    }

    // Print error-message and exit
    $admin->print_error(str_replace($TEXT['FILE'], "Modul", $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE']) . $msg . $page_names);
}

// check if we got a core module as you may not uninstall this
include_once(WB_PATH . '/modules/' . $file . '/info.php');
if (isset($module_level) and $module_level == "core") {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL_CORE_MODULES']);
}

// Check if we have permissions on the directory
if (!is_writable(WB_PATH . '/modules/' . $file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
}

// Run the modules uninstall script if there is one
if (file_exists(WB_PATH . '/modules/' . $file . '/uninstall.php')) {
    require(WB_PATH . '/modules/' . $file . '/uninstall.php');
}

// Try to delete the module dir
if (!rm_full_dir(WB_PATH . '/modules/' . $file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
} else {
    // Remove entry from DB
    $database->query("DELETE FROM " . TABLE_PREFIX . "addons WHERE directory = '" . $mod_dir_escaped . "' AND type = 'module'");
}

// Print success message
$admin->print_success($MESSAGE['GENERIC_UNINSTALLED']);

// Print admin footer
$admin->print_footer();
