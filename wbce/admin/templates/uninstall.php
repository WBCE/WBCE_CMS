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

// Setup admin object
require('../../config.php');

// suppress to print the header, so no new FTAN will be set
$admin = new admin('Addons', 'templates_uninstall', false);
if( !$admin->checkFTAN() )
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Check if user selected template
if(!isset($_POST['file']) OR $_POST['file'] == "") {
    header("Location: index.php");
    exit(0);
} else {
    $file = $_POST['file'];
}

// Extra protection
if(trim($file) == '') {
    header("Location: index.php");
    exit(0);
}

// Include the WB functions file
 

// Check if the template exists
if(!is_dir(WB_PATH.'/templates/'.$file)) {
    $admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

if (!function_exists("replace_all")) {
    function replace_all ($aStr = "", &$aArray ) {
        foreach($aArray as $k=>$v) $aStr = str_replace("{{".$k."}}", $v, $aStr);
        return $aStr;
    }
}

/**
*    Check if the template is the standard-template or still in use
*/


// check whether the template is used as default wb theme
if($file == DEFAULT_THEME) {
    $temp = array ('name' => $file );
    $msg = replace_all( $MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'], $temp );
    $admin->print_error( $msg );
}

if ($file == DEFAULT_TEMPLATE) {
    $temp = array ('name' => $file );
    $msg = replace_all( $MESSAGE['GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE'], $temp );
    $admin->print_error( $msg );

} else {
    
    /**
    *    Check if the template is still in use by a page ...
    */
    $info = $database->query("SELECT page_id, page_title FROM ".TABLE_PREFIX."pages WHERE template='".$file."' order by page_title");
    
    if ($info->numRows() > 0) {
        /**
        *    Template is still in use, so we're collecting the page-titles
        */
        
        /**
        *    The base-message template-string for the top of the message
        */
      
        $msg_template_str = $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'];
        $temp = explode(";",$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES']);
        $add = $info->numRows() == 1 ? $temp[0] : $temp[1];
        
        /**
        *    The template-string for displaying the Page-Titles ... in this case as a link
        */
        $page_template_str = "- <b><a href='../pages/settings.php?page_id={{id}}'>{{title}}</a></b><br />";
        
        $values = array ('type' => 'Template', 'type_name' => $file, 'pages' => $add);
        $msg = replace_all ( $msg_template_str,  $values );
        
        $page_names = "";
        
        while ($data = $info->fetchRow() ) {
            
            $page_info = array(
                'id'    => $data['page_id'], 
                'title' => $data['page_title']
            );
            
            $page_names .= replace_all ( $page_template_str, $page_info );
        }
        
        /**
        *    Printing out the error-message and die().
        */
        $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE'].$msg.$page_names);
    }
}

// Check if we have permissions on the directory
if(!is_writable(WB_PATH.'/templates/'.$file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL'].WB_PATH.'/templates/'.$file);
}

// Try to delete the template dir
if(!rm_full_dir(WB_PATH.'/templates/'.$file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
} else {
    // Remove entry from DB
    $database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE directory = '".$file."' AND type = 'template'");
}

// Update pages that use this template with default template
// $database = new database();
$database->query("UPDATE ".TABLE_PREFIX."pages SET template = '".DEFAULT_TEMPLATE."' WHERE template = '$file'");

// Print success message
$admin->print_success($MESSAGE['GENERIC_UNINSTALLED']);

// Print admin footer
$admin->print_footer();
