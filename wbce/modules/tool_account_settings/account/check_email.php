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

// Prevent this file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 

// Check FTAN
if (!$wb->checkFTAN()) {
    $wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
}

// Get entered values
$password     = $wb->get_post('current_password');
$sEncPassword = $wb->checkPasswordPattern($password);
$sNewEmail    = $wb->get_post('email');

// validate password
$sSql  = "SELECT `user_id` FROM `{TP}users` WHERE `user_id` = %d AND `password` = '%s'";
// Validate values
if($database->get_one(sprintf($sSql, $wb->get_user_id(), $sEncPassword)) == false) {
    $error[] = $MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'];
} else {
    if(!$wb->validate_email($sNewEmail)) {
        $error[] = $MESSAGE['USERS_INVALID_EMAIL'];
    } else {
        // Update the database
        $aUpdate = array(
            'user_id' => $wb->get_user_id(),
            'email'   => $database->escapeString($sNewEmail)
        );
        // Update the database
        if ($database->updateRow('{TP}users', 'user_id', $aUpdate)) {
            $success[] = $MESSAGE['PREFERENCES_EMAIL_UPDATED'];
            $_SESSION['EMAIL'] = $sNewEmail;
        } else {
            $error[] = $database->get_error();
        }
    }
}