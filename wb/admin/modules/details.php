<?php
/**
 *
 * @category        admin
 * @package         modules
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: details.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/modules/details.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
 */

// Include the config file
require('../../config.php');
require_once(WB_PATH .'/framework/functions.php');
require_once(WB_PATH.'/framework/class.admin.php');
// No print admin header
$admin = new admin('Addons', 'modules_view', false);
if( !$admin->checkFTAN() )
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Get module name
if(!isset($_POST['file']) OR $_POST['file'] == "")
{
	header("Location: index.php");
	exit(0);
}
else
{
	$file = preg_replace('/[^a-z0-9_-]/i', "", $_POST['file']);  // fix secunia 2010-92-1
}

// Check if the module exists
if(!file_exists(WB_PATH.'/modules/'.$file)) {
	header("Location: index.php");
	exit(0);
}

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('modules_details.htt')));
// $template->debug = true;
$template->set_file('page', 'modules_details.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND directory = '$file'");
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

$template->set_var(array(
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
						
switch ($module['function']) {
	case NULL:
		$type_name = $TEXT['UNKNOWN'];
		break;
	case 'page':
		$type_name = $TEXT['PAGE'];
		break;
	case 'wysiwyg':
		$type_name = $TEXT['WYSIWYG_EDITOR'];
		break;
	case 'tool':
		$type_name = $TEXT['ADMINISTRATION_TOOL'];
		break;
	case 'admin':
		$type_name = $TEXT['ADMIN'];
		break;
	case 'administration':
		$type_name = $TEXT['ADMINISTRATION'];
		break;
	case 'snippet':
		$type_name = $TEXT['CODE_SNIPPET'];
		break;
	default:
		$type_name = $TEXT['UNKNOWN'];
}
$template->set_var('TYPE', $type_name);

// Insert language headings
$template->set_var(array(
								'HEADING_MODULE_DETAILS' => $HEADING['MODULE_DETAILS']
								)
						);
// Insert language text and messages
$template->set_var(array(
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
