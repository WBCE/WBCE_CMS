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
$admin = new admin('Addons', 'modules_view', false, true);
if(! $admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user selected a valid module file
$file = trim($admin->get_post('file'));
$root_dir = realpath(WB_PATH . DIRECTORY_SEPARATOR . 'modules');
$raw_dir = realpath($root_dir . DIRECTORY_SEPARATOR . $file);
if(! ($file && $raw_dir && is_dir($raw_dir) && (strpos($raw_dir, $root_dir) === 0))) {
	// module file empty or outside WBCE module folder
	$admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Extract module folder from realpath for further usage inside script
$file = basename($raw_dir);

// Create new template object
$template = new Template(dirname($admin->correct_theme_source('modules_details.htt')));
$template->set_file('page', 'modules_details.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values
$file_escaped = $database->escapeString($file);
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND directory = '$file_escaped'");
if($result->numRows() > 0) {
	$module = $result->fetchRow();
}

// check if a module description exists for the displayed backend language
$tool_description = false;
if(function_exists('file_get_contents') && file_exists(WB_PATH.'/modules/'.$file.'/languages/'.LANGUAGE .'.php')) {
	// read contents of the module language file into string
	$data = @file_get_contents(WB_PATH .'/modules/' .$file .'/languages/' .LANGUAGE .'.php');
	// use regular expressions to fetch the content of the variable from the string
	$tool_description = get_variable_content('module_description', $data, false, false);
	// replace optional placeholder {WB_URL} with value stored in config.php
	if($tool_description !== false && strlen(trim($tool_description)) != 0) {
		$tool_description = str_replace('{WB_URL}', WB_URL, $tool_description);
	} else {
		$tool_description = false;
	}
}
if($tool_description !== false) {
	// Override the module-description with correct desription in users language
	$module['description'] = $tool_description;
}

$template->set_var(
	array(
		'NAME' => $module['name'],
		'AUTHOR' => $module['author'],
		'DESCRIPTION' => $module['description'],
		'VERSION' => $module['version'],
		'DESIGNED_FOR' => $module['platform'],
		'ADMIN_URL' => ADMIN_URL,
		'WB_URL' => WB_URL,
		'THEME_URL' => THEME_URL
	)
);

$type_name = '';
if (empty($module['function'])){
    $type_name = $TEXT['UNKNOWN'];
}
if (preg_match("/page/", $module['function'])){
    $type_name .= $TEXT['PAGE'].", ";
}
if (preg_match("/wysiwyg/", $module['function'])){
    $type_name .= $TEXT['WYSIWYG_EDITOR'].", ";
}
if (preg_match("/tool/", $module['function'])){
    $type_name .= $TEXT['ADMINISTRATION_TOOL'].", ";
}
if (preg_match("/admin/", $module['function'])){
    $type_name .= $TEXT['ADMIN'].", ";
}
if (preg_match("/snippet/", $module['function'])){
    $type_name .= $TEXT['CODE_SNIPPET'].", ";
}
if (preg_match("/initialize/", $module['function'])){
    $type_name .= $TEXT['INITIALIZE'].", ";
}
if (preg_match("/preinit/", $module['function'])){
    $type_name .= $TEXT['PREINIT'].", ";
}

$type_name= trim($type_name,", ");
$template->set_var('TYPE', $type_name);

// Insert language headings and text messages
$template->set_var(
	array(
		'HEADING_MODULE_DETAILS' => $HEADING['MODULE_DETAILS'],
		'TEXT_NAME' => $TEXT['NAME'],
		'TEXT_TYPE' => $TEXT['TYPE'],
		'TEXT_AUTHOR' => $TEXT['AUTHOR'],
		'TEXT_VERSION' => $TEXT['VERSION'],
		'TEXT_DESIGNED_FOR' => $TEXT['DESIGNED_FOR'],
		'TEXT_DESCRIPTION' => $TEXT['DESCRIPTION'],
		'TEXT_BACK' => $TEXT['BACK']
	)
);

// Parse module object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
