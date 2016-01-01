<?php
/**
 *
 * @category        modules
 * @package         backup
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
*/

// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// backup send
if( !isset($_POST['backup']) ) {
    //show form
    include($modulePath."templates/backup.tpl.php");
} else {
    // ftan went wrong , but we are in no_page mode so we need to print header and then go for error 
    if (!$admin->checkFTAN()) {
        $admin->print_header();
        //3rd param = false =>no auto footer, no exit.
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$returnUrl, true); 
    }
    
    //all ok do backup
    include($modulePath."includes/backup-sql.php");
}
