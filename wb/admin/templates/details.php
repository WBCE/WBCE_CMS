<?php
/**
 *
 * @category        admin
 * @package         templates
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: details.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/templates/details.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
 */

// Include the config file
require('../../config.php');
require_once(WB_PATH .'/framework/functions.php');
require_once(WB_PATH.'/framework/class.admin.php');
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Addons', 'templates_view', false);
if( !$admin->checkFTAN() )
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Get template name
if(!isset($_POST['file']) OR $_POST['file'] == "") {
    header("Location: index.php");
    exit(0);
} else {
    $file = preg_replace('/[^a-z0-9_-]/i', "", $_POST['file']);  // fix secunia 2010-92-2
}

// Check if the template exists
if(!file_exists(WB_PATH.'/templates/'.$file)) {
    header("Location: index.php");
    exit(0);
}

// Print admin header
$admin = new admin('Addons', 'templates_view');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('templates_details.htt')));
// $template->debug = true;
$template->set_file('page', 'templates_details.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('FTAN', $admin->getFTAN());

// Insert values
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND directory = '$file'");
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

$template->set_var(array(
                                'NAME' => $row['name'],
                                'AUTHOR' => $row['author'],
                                'DESCRIPTION' => $row['description'],
                                'VERSION' => $row['version'],
                                'DESIGNED_FOR' => $row['platform']
                                )
                        );

// Insert language headings
$template->set_var(array(
                                'HEADING_TEMPLATE_DETAILS' => $HEADING['TEMPLATE_DETAILS']
                                )
                        );
// Insert language text and messages
$template->set_var(array(
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
