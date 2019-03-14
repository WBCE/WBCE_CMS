<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (since 2015)
 * @license GNU GPL2 (or any later version)
 */

// stop this file from being accessed directly
defined('WB_PATH') or die("Insufficient privileges");
$oAccounts = new Accounts();

// load Language Files 
foreach ($oAccounts->getLanguageFiles() as $sLangFile) require_once $sLangFile;

$aSwitches = array(
    'login_details_just_sent',    // Tell the user that he's got his login data sent via email and can now login
    'activation_link_sent',       // Tell the user he's got a mail with a confirmation link in it sent
    'manager_confirm_new_signup', // Tell the user he's to wait for admin confirmation and that he'll be notified by email
    'forgot_login_details_sent',  // Tell the user that his reset login details were sent by mail
    'wrong_inputs',               // Tell the user that there occured an issue and he should contact the Administrators
    'manager_approval_feedback',  // Tell the admin that the confirmation worked and user was notified by email
);

$sCurrentSwitch = (string) $_GET['switch'];

if(in_array($_GET['switch'], $aSwitches)){	
    // Get the Twig template based on switch
    $aToTwig = array(
        'SUPPORT_EMAIL' => $oAccounts->cfg['support_email'],  
    );
    $oAccounts->useTwigTemplate('msg_'.$sCurrentSwitch.'.twig', $aToTwig);
} else {
    header("Location: ". WB_URL ."?switch=".$sCurrentSwitch."&lc=".$_GET['lc']);
}