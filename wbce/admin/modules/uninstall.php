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
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'modules_uninstall', false);
if( !$admin->checkFTAN() )
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
// After check print the header
$admin->print_header();

// Check if user selected module
if(!isset($_POST['file']) OR $_POST['file'] == "") {
    header("Location: index.php");
    exit(0);
} else {
    $file = $admin->add_slashes($_POST['file']);
}

// Extra protection
if(trim($file) == '') {
    header("Location: index.php");
    exit(0);
}

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Check if the module exists
if(!is_dir(WB_PATH.'/modules/'.$file)) {
    $admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

if (!function_exists("replace_all")) {
    function replace_all ($aStr = "", &$aArray ) {
        foreach($aArray as $k=>$v) $aStr = str_replace("{{".$k."}}", $v, $aStr);
        return $aStr;
    }
}

$info = $database->query("SELECT section_id, page_id FROM ".TABLE_PREFIX."sections WHERE module='".$_POST['file']."'" );

if ( $info->numRows() > 0) {
    
    /**
    *    Modul is in use, so we have to warn the user
    */
    
        $msg_template_str = $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL'];
        $temp = explode(";",$MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES']);
        $add = $info->numRows() == 1 ? $temp[0] : $temp[1];
    
    /**
    *    The template-string for displaying the Page-Titles ... in this case as a link
    */
    $page_template_str = "- <b><a href='../pages/sections.php?page_id={{id}}'>{{title}}</a></b><br />";
    
    $values = array ('type' => 'Modul', 'type_name' => $file, 'pages' => $add );
    $msg = replace_all ( $msg_template_str,  $values );
        
    $page_names = "";
    
    while ($data = $info->fetchRow() ) {
    
        $temp = $database->query("SELECT page_title from ".TABLE_PREFIX."pages where page_id=".$data['page_id']);
        $temp_title = $temp->fetchRow();
        
        $page_info = array(
            'id'    => $data['page_id'], 
            'title' => $temp_title['page_title']
        );
            
        $page_names .= replace_all ( $page_template_str, $page_info );
    }
    
    /**
    *    Printing out the error-message and die().
    */
    $admin->print_error(str_replace ($TEXT['FILE'], "Modul", $MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE']).$msg.$page_names);
}

// Check if we have permissions on the directory
if(!is_writable(WB_PATH.'/modules/'.$file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
}

// Run the modules uninstall script if there is one
if(file_exists(WB_PATH.'/modules/'.$file.'/uninstall.php')) {
    require(WB_PATH.'/modules/'.$file.'/uninstall.php');
}

// Try to delete the module dir
if(!rm_full_dir(WB_PATH.'/modules/'.$file)) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
} else {
    // Remove entry from DB
    $database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE directory = '".$file."' AND type = 'module'");
}

// Print success message
$admin->print_success($MESSAGE['GENERIC_UNINSTALLED']);

// Print admin footer
$admin->print_footer();
