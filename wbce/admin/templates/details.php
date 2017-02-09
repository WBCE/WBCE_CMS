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

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'templates_view', false, true);
if(! $admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user selected a valid template file
$file = trim($admin->get_post('file'));
$root_dir = realpath(WB_PATH . DIRECTORY_SEPARATOR . 'templates');
$raw_dir = realpath($root_dir . DIRECTORY_SEPARATOR . $file);
if(! ($file && $raw_dir && is_dir($raw_dir) && (strpos($raw_dir, $root_dir) === 0))) {
	// template file empty or outside WBCE templates folder
	$admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Extract template folder from realpath for further usage inside script
$file = basename($raw_dir);

// Create new template object
$template = new Template(dirname($admin->correct_theme_source('templates_details.htt')));
$template->set_file('page', 'templates_details.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('FTAN', $admin->getFTAN());

// Insert values
$file_escaped = $database->escapeString($file);
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND directory = '$file_escaped'");
if($result->numRows() > 0) {
    $row = $result->fetchRow();
}

// check if a template description exists for the displayed backend language
$tool_description = false;
if(function_exists('file_get_contents') && file_exists(WB_PATH.'/templates/'.$file.'/languages/'.LANGUAGE .'.php')) {
    // read contents of the template language file into string
    $data = @file_get_contents(WB_PATH .'/templates/' .$file .'/languages/' .LANGUAGE .'.php');
    // use regular expressions to fetch the content of the variable from the string
    $tool_description = get_variable_content('template_description', $data, false, false);
    // replace optional placeholder {WB_URL} with value stored in config.php
    if($tool_description !== false && strlen(trim($tool_description)) != 0) {
        $tool_description = str_replace('{WB_URL}', WB_URL, $tool_description);
    } else {
        $tool_description = false;
    }
}
if($tool_description !== false) {
    // Override the template-description with correct desription in users language
    $row['description'] = $tool_description;
}

$template->set_var(
    array(
        // General data
        'NAME' => $row['name'],
        'AUTHOR' => $row['author'],
        'DESCRIPTION' => $row['description'],
        'VERSION' => $row['version'],
        'DESIGNED_FOR' => $row['platform'],

        // Headings
        'HEADING_TEMPLATE_DETAILS' => $HEADING['TEMPLATE_DETAILS'],

        // Text messages
        'TEXT_NAME' => $TEXT['NAME'],
        'TEXT_AUTHOR' => $TEXT['AUTHOR'],
        'TEXT_VERSION' => $TEXT['VERSION'],
        'TEXT_DESIGNED_FOR' => $TEXT['DESIGNED_FOR'],
        'TEXT_DESCRIPTION' => $TEXT['DESCRIPTION'],
        'TEXT_BACK' => $TEXT['BACK']
        )
);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
