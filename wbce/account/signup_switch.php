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

// stop this file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly");

require_once __DIR__ .'/functions/functions.php';

// load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

$aSwitches = array(
	'login_details_just_sent',    // Tell the user that he's got his login data via mail and can now login
	'activation_link_sent',       // Tell the user he's got a mail with a confirmation link in it
	'manager_confirm_new_signup', // Tell the user he's to wait for admin confirmation and that he will be notified by mail
	'manager_approval_feedback',  // Tell the admin that the confirmation worked and user was notified by mail
	'forgot_login_details_sent',  // Tell the user that his reset login details were sent by mail
	'wrong_inputs',               // Tell the user that there occured an issue and he should contact the Administrators
);

$sCurrentSwitch = (string) $_GET['switch'];

if(in_array($_GET['switch'], $aSwitches)){
	
	// Get the template based on switch
	include account_getTemplate('msg_'.$sCurrentSwitch);
	
} else {
	header("Location: ". WB_URL ."?switch=".$sCurrentSwitch."&lc=".$_GET['lc']);
}