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
$admin = new admin('Addons', 'templates_uninstall', false, true);
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user selected a valid template file
$file = trim($admin->get_post('file'));
$root_dir = realpath(WB_PATH . DIRECTORY_SEPARATOR . 'templates');
$raw_dir = realpath($root_dir . DIRECTORY_SEPARATOR . $file);
if (!($file && $raw_dir && is_dir($raw_dir) && (strpos($raw_dir, $root_dir) === 0))) {
    // template file empty or outside WBCE module folder
    $admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Extract template folder from realpath for further usage inside script
$file = basename($raw_dir);

// Helper function
if (!function_exists("replace_all")) {
    function replace_all($aStr = "", &$aArray)
    {
        foreach ($aArray as $k => $v) {
            $aStr = str_replace("{{" . $k . "}}", $v, $aStr);
        }
        return $aStr;
    }
}

// Check if the theme is the default
if ($file == DEFAULT_THEME) {
    $temp = array('name' => $file);
    $msg = replace_all($MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_THEME'], $temp);
    $admin->print_error($msg);
}

// Check if the template is the default or otherwise still in use
if ($file == DEFAULT_TEMPLATE) {
    $temp = array('name' => $file);
    $msg = replace_all($MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'], $temp);
    $admin->print_error($msg);
} else {
    // Check if the template is still in use by a page ...
    $tpl_dir_escaped = $database->escapeString($file);
    $info = $database->query("SELECT page_id, page_title FROM " . TABLE_PREFIX . "pages WHERE template='" . $tpl_dir_escaped . "' order by page_title");

    if ($info->numRows() > 0) {
        // Template is still in use, so we're collecting the page-titles
        $msg_template_str = $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'];
        $temp = explode(";", $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES']);
        $add = $info->numRows() == 1 ? $temp[0] : $temp[1];

        // The template-string for displaying the Page-Titles ... in this case as a link
        $page_template_str = "- <b><a href='../pages/settings.php?page_id={{id}}'>{{title}}</a></b><br />";
        $values = array('type' => 'Template', 'type_name' => $file, 'pages' => $add);
        $msg = replace_all($msg_template_str, $values);

        $page_names = "";
        while ($data = $info->fetchRow()) {
            $page_info = array(
                'id' => $data['page_id'],
                'title' => $data['page_title']
            );
            $page_names .= replace_all($page_template_str, $page_info);
        }

        // Print error-message and exit
        $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'] . $msg . $page_names);
    }
}

// Check if we have permissions on the directory
if (!is_writable(WB_PATH . '/templates/' . $file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL'] . WB_PATH . '/templates/' . $file);
}

// Try to delete the template dir
if (!rm_full_dir(WB_PATH . '/templates/' . $file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
} else {
    // Remove entry from DB
    $database->query("DELETE FROM " . TABLE_PREFIX . "addons WHERE directory = '" . $tpl_dir_escaped . "' AND type = 'template'");
}

// Update pages that use this template with default template
$database->query("UPDATE " . TABLE_PREFIX . "pages SET template = '" . DEFAULT_TEMPLATE . "' WHERE template = '$tpl_dir_escaped'");

// Print success message
$admin->print_success($MESSAGE['GENERIC_UNINSTALLED']);

// Print admin footer
$admin->print_footer();
