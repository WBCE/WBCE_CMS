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

// prevent this file from being accessed directly in the browser (would set all entries in DB settings table to '')
#if (!isset($_POST['default_language']) || $_POST['default_language'] == '') {
#    die(header('Location: index.php'));
#}

// Find out if the user was view advanced options or not
$advanced = ($_POST['advanced'] == 'yes') ? '?advanced=yes' : '';

// Print admin header
require '../../config.php';
require_once WB_PATH . '/framework/class.admin.php';

// suppress to print the header, so no new FTAN will be set
if ($advanced == '') {
    $admin = new admin('Settings', 'settings_basic', false);
} else {
    $admin = new admin('Settings', 'settings_advanced', false);
}

// Create a javascript back link
$js_back = ADMIN_URL . '/settings/index.php' . $advanced. '#sendtestmail';
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}

// Let's prepare and send the test mail 
require_once WB_PATH . '/account/init.php';
require_once WB_PATH . '/modules/tool_account_settings/functions.php';
$sEmailTemplateName = 'testmail';
$sEmailSubject      = '';
$aTokenReplace      = array( 
    'LOGIN_WEBSITE_TITLE'          => WEBSITE_TITLE, 
    'WBMAILER_DEFAULT_SENDERNAME'  => WBMAILER_DEFAULT_SENDERNAME, 
    'SERVER_EMAIL'                 => SERVER_EMAIL, 
    'WB_URL'                       => WB_URL, 
    'DATE'                         => date("d-m-Y"), 
    'TIME'                         => date("H:i:s"), 
);	

// After check print the header
$admin->print_header();

if (account_sendEmail(SERVER_EMAIL, $aTokenReplace, $sEmailTemplateName) == true) {	                                                                   	
    $admin->print_success(sprintf($MESSAGE['TESTMAIL_SUCCESS'], SERVER_EMAIL), $js_back);
} else {
    $admin->print_error(sprintf($MESSAGE['TESTMAIL_FAILURE'], SERVER_EMAIL), $js_back);		
} 
$admin->print_footer();