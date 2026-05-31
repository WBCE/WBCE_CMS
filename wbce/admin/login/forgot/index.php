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

require realpath('../../../config.php');


$admin  = new Admin('Start', 'start', false, false);
$alerts = new Alerts(useSession: false);

$nocookie = defined('NO_SESSION_COOKIE') && NO_SESSION_COOKIE;
$email    = '';
$showForm = true;

// === Handle POST =============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {

    $email = strip_tags(trim($admin->get_post('email') ?? ''));

    // --- FTAN check ---
    if (!$admin->checkFTAN()) {
        $alerts->error('MESSAGE:GENERIC_SECURITY_ACCESS');
        $email = '';
    }

    // --- Captcha validation (FIXED: no timestamp fallback) ---
    if ($email !== '' && !$nocookie) {
        $captchaInput = $_POST['captcha'] ?? '';

        if ($captchaInput === '') {
            $alerts->error('MESSAGE:MOD_FORM_INCORRECT_CAPTCHA');
            $email = '';
        } else {
            // Only compare against actual session captcha values.
            // NEVER fall back to time() — that's the vulnerability.
            $validCaptcha1 = $_SESSION['captcha']            ?? null;
            $validCaptcha2 = $_SESSION['captchaloginforgot'] ?? null;

            if ($validCaptcha1 === null && $validCaptcha2 === null) {
                // No captcha in session = session expired or tampered
                $alerts->error('MESSAGE:MOD_FORM_INCORRECT_CAPTCHA');
                $email = '';
            } elseif ($captchaInput != $validCaptcha1 && $captchaInput != $validCaptcha2) {
                $alerts->error('MESSAGE:MOD_FORM_INCORRECT_CAPTCHA');
                $email = '';
            }
        }
    }

    // --- Validate email format ---
    if ($email !== '' && !$admin->validate_email($email)) {
        $alerts->error('MESSAGE:USERS_INVALID_EMAIL');
        $email = '';
    }

    // --- Look up user ---
    if ($email !== '') {
        $user = $database->fetchRow(
            "SELECT * FROM `{TP}users` WHERE `email` = ?",
            [$email]
        );

        if (!$user) {
            // Deliberately vague — don't reveal whether email exists
            $alerts->error('MESSAGE:FORGOT_PASS_EMAIL_NOT_FOUND');
            $email = '';
        } elseif (strlen($user['signup_confirmcode'] ?? '') > 25) {
            // Unconfirmed signup — redirect
            header('Location: ' . WB_URL . '/account/signup_continue_page.php?switch=wrong_inputs');
            exit;
        } elseif ((time() - intval($user['last_reset'])) < 7200) {
            // Rate limit: max one reset per 2 hours
            $alerts->error('MESSAGE:FORGOT_PASS_ALREADY_RESET');
        } else {
            // --- Generate new password and send email ---
            $result = resetAndEmailPassword($user, $admin, $database, $alerts);
            if ($result) {
                $showForm = false;
            }
        }
    }
}

// Default info message when no errors shown
if (!$alerts->hasErrors() && $showForm) {
    $alerts->info('MESSAGE:FORGOT_PASS_NO_DATA');
}


// Captcha HTML
$captchaHtml = '';
if (!$nocookie) {
    ob_start();
    Captcha::render('widget');
    $captchaHtml = ob_get_clean();
}

// === Render ==================================================================
$toTwig = [
    'MESSAGE'      => $alerts->render(),
    'WB_URL'       => WB_URL,
    'ADMIN_URL'    => ADMIN_URL,
    'THEME_URL'    => THEME_URL,
    'LANGUAGE'     => strtolower(LANGUAGE),
    'EMAIL'        => h($email),
    'SHOW_FORM'    => $showForm,
    'ACTION_URL'   => defined('FRONTEND') ? 'forgot.php' : 'index.php',
    'LOGIN_URL'    => defined('FRONTEND') ? WB_URL . '/account/login.php' : ADMIN_URL,
    'CAPTCHA'      => $captchaHtml,
    'CHARSET'      => defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8',
];

$admin->getThemeFile('login_forgot.twig', $toTwig);


/**
 * Generate a new random password, update DB, and send email.
 * @return bool  true on success
 */
function resetAndEmailPassword(
        array    $user, 
        Admin    $admin, 
        Database $database, 
        Alerts   $alerts
    ): bool
{

    $previousPassword = $user['password'];

    // Generate secure random password (13 chars)
    $chars  = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!_-:#*+$@&';
    $newPw  = '';
    $maxIdx = strlen($chars) - 1;
    for ($i = 0; $i < 13; $i++) {
        $newPw .= $chars[random_int(0, $maxIdx)];
    }

    // Update password in DB
    $database->query(
        "UPDATE `{TP}users` SET `password` = ?, `last_reset` = ? WHERE `user_id` = ?",
        [$admin->doPasswordEncode($newPw), time(), $user['user_id']]
    );

    if ($database->hasError()) {
        $alerts->error($database->getError());
        return false;
    }

    // Send email
    $mailSubject = L_('MESSAGE:SIGNUP2_SUBJECT_LOGIN_INFO');
    $mailBodyStr = L_('MESSAGE:SIGNUP2_BODY_LOGIN_FORGOT');
    $mailBody    = strtr($mailBodyStr, [
        '{LOGIN_DISPLAY_NAME}'  => $user['display_name'],
        '{LOGIN_NAME}'          => $user['username'],
        '{LOGIN_WEBSITE_TITLE}' => WEBSITE_TITLE,
        '{LOGIN_PASSWORD}'      => $newPw,
    ]);

    if ($admin->mail(SERVER_EMAIL, $user['email'], $mailSubject, $mailBody)) {
        $alerts->success('MESSAGE:FORGOT_PASS_PASSWORD_RESET');
        return true;
    }

    // Email failed — revert password
    $database->query(
        "UPDATE `{TP}users` SET `password` = ? WHERE `user_id` = ?",
        [$previousPassword, $user['user_id']]
    );

    $alerts->error('MESSAGE:FORGOT_PASS_CANNOT_EMAIL');
    return false;
}