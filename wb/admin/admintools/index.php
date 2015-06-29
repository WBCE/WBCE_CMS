<?php
/**
 *
 * @category        admin
 * @package         admintools
 * @author          WB-Project, Werner v.d. Decken
 * @copyright       2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: index.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/admintools/index.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
 */

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('admintools', 'admintools');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

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
$results = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'tool' order by name");
if($results->numRows() > 0) {
	while($tool = $results->fetchRow()) {
		$template->set_var('TOOL_NAME', $tool['name']);
		$template->set_var('TOOL_DIR', $tool['directory']);
		// check if a module description exists for the displayed backend language
		$tool_description = false;
		if(function_exists('file_get_contents') && file_exists(WB_PATH.'/modules/'.$tool['directory'].'/languages/'.LANGUAGE .'.php')) {
			// read contents of the module language file into string
			$data = @file_get_contents(WB_PATH .'/modules/' .$tool['directory'] .'/languages/' .LANGUAGE .'.php');
			$tool_description = get_variable_content('module_description', $data, true, false);
		}		
		$template->set_var('TOOL_DESCRIPTION', ($tool_description === False)? $tool['description'] :$tool_description);
		$template->parse('tool_list', 'tool_list_block', true);
	}
} else {
	$template->set_var('TOOL_LIST', $TEXT['NONE_FOUND']);	
}

// Parse template objects output
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

$admin->print_footer();
