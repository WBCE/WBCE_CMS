<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Include the configuration file
require realpath('../../../config.php');
// Include the language file
require WB_PATH . '/languages/' . DEFAULT_LANGUAGE . '.php';
// Include the database class file and initiate an object
require WB_PATH . '/framework/class.admin.php';
$admin = new admin('Start', 'start', false, false);

$oMsgBox = new MessageBox();
$oMsgBox->closeBtn = '';

// Check if the user has already submitted the form, otherwise show it
if (isset($_POST['email']) and $_POST['email'] != "") {
    $email = strip_tags($wb->get_post('email'));
    if ($admin->validate_email($email) == false) {
        $oMsgBox->error($MESSAGE['USERS_INVALID_EMAIL']);
        $email = '';
    }

    // Check if the email exists in the database
    $sSql = "SELECT * FROM `{TP}users` WHERE `email` = '" . $email . "'";
    $rRow = $database->query($sSql);
    if ($rRow->numRows() > 0) {

        // Get the id, username, email, and last_reset from the above db query
        $aUser = $rRow->fetchRow();
        if (strlen($aUser['signup_confirmcode']) > 25) {
            header("Location: " . WB_URL . "/account/signup_continue_page.php?switch=wrong_inputs");
            exit(0); // break up the script here
        }


        // Check if the password has been reset in the last 2 hours
        if ((time() - intval($aUser['last_reset'])) < (2 * 3600)) {
            // Tell the user that their password cannot be reset more than once per hour
            $oMsgBox->error($MESSAGE['FORGOT_PASS_ALREADY_RESET']);
        } else {
            $sCurrentPw = $aUser['password'];

            // Generate a random password then update the database with it
            $sNewPw = '';
            $salt = "abchefghjkmnpqrstuvwxyz0123456789";
            srand((double)microtime() * 1000000);
            $i = 0;
            while ($i <= 7) {
                $num = rand() % 33;
                $tmp = substr($salt, $num, 1);
                $sNewPw = $sNewPw . $tmp;
                $i++;
            }

            // update the new password in the database
            $aUpdateUser = array(
                'user_id' => $aUser['user_id'],
                'password' => $wb->doPasswordEncode($sNewPw),
                'last_reset' => time(),
            );
            $database->updateRow('{TP}users', 'user_id', $aUpdateUser);

            if ($database->is_error()) {
                // Error updating database
                $oMsgBox->error($database->get_error());
            } else {
                // Setup email to send
                $mail_to = $email;
                $mail_subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];

                // Replace placeholders from language variable with values
                $search = array('{LOGIN_DISPLAY_NAME}', '{LOGIN_WEBSITE_TITLE}', '{LOGIN_NAME}', '{LOGIN_PASSWORD}');
                $replace = array($aUser['display_name'], WEBSITE_TITLE, $aUser['username'], $sNewPw);

                $aTokenReplace = array(
                    '{LOGIN_DISPLAY_NAME}' => $aUser['display_name'],
                    '{LOGIN_NAME}' => $aUser['username'],
                    '{LOGIN_WEBSITE_TITLE}' => WEBSITE_TITLE,
                    '{LOGIN_PASSWORD}' => $sNewPw
                );


                $mail_message = strtr($MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT'], $aTokenReplace);

                // Try sending the email
                if ($admin->mail(SERVER_EMAIL, $mail_to, $mail_subject, $mail_message)) {
                    $oMsgBox->error($MESSAGE['FORGOT_PASS_PASSWORD_RESET']);
                    $display_form = false;
                } else {
                    $aUpdateUser = array(
                        'user_id' => $aUser['user_id'],
                        'password' => $sCurrentPw
                    );
                    $database->updateRow('{TP}users', 'user_id', $aUpdateUser);
                    $oMsgBox->error($MESSAGE['FORGOT_PASS_CANNOT_EMAIL']);
                }
            }
        }
    } else {
        // Email doesn't exist, so tell the user
        $oMsgBox->error($MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND']);
        // and delete the wrong Email
        $email = '';
    }
} else {
    $email = '';
}

if ($oMsgBox->hasErrors() == false) {
    $oMsgBox->info($MESSAGE['FORGOT_PASS_NO_DATA'], 0, 1);
}

// Create new phpLib Template object
$template = new Template(dirname($admin->correct_theme_source('login_forgot.htt')));
$template->set_file('page', 'login_forgot.htt');
$template->set_block('page', 'main_block', 'main');

$aTemplateVars = array(
    'SECTION_FORGOT' => $MENU['FORGOT'],
    'MESSAGE_COLOR' => '', //$message_color,
    'MESSAGE' => $oMsgBox->fetchDisplay(),
    'WB_URL' => WB_URL,
    'ADMIN_URL' => ADMIN_URL,
    'THEME_URL' => THEME_URL,
    'LANGUAGE' => strtolower(LANGUAGE),
    'TEXT_EMAIL' => $TEXT['EMAIL'],
    'TEXT_SEND_DETAILS' => $TEXT['SEND_DETAILS'],
    'TEXT_HOME' => $TEXT['HOME'],
    'TEXT_NEED_TO_LOGIN' => $TEXT['NEED_TO_LOGIN'],
    'EMAIL' => $email,
    'DISPLAY_FORM' => isset($display_form) ? 'display:none;' : '',
    'ACTION_URL' => defined('FRONTEND') ? 'forgot.php' : 'index.php',
    'LOGIN_URL' => defined('FRONTEND') ? WB_URL . '/account/login.php' : ADMIN_URL,
    'INTERFACE_URL' => ADMIN_URL . '/interface',
    'DEFAULT_CHARSET' => defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8',
    'CHARSET' => isset($charset) ? $charset : 'utf-8',
);
$template->set_var($aTemplateVars);

$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');
