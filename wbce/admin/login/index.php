<?php
/**
 * WBCE CMS — Backend login entry point
 *
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require_once '../../config.php';

$admin   = new Admin('Start', '', false, false);
$warnUrl = ADMIN_URL . '/login/login_warning.php';

new Login([
    'MAX_ATTEMPTS'          => 6,
    'CAPTCHA_THRESHOLD'     => 2,
    'TIMEFRAME'             => 600,
    'LOGIN_DELAY'           => 60,
    'WARNING_URL'           => $warnUrl,
    'USERNAME_FIELDNAME'    => 'username',
    'PASSWORD_FIELDNAME'    => 'password',
    'MAX_USERNAME_LEN'      => 30,
    'MAX_PASSWORD_LEN'      => 30,
    'LOGIN_URL'             => ADMIN_URL . '/login/index.php',
    'DEFAULT_URL'           => ADMIN_URL . '/start/index.php',
    'FORGOTTEN_DETAILS_APP' => ADMIN_URL . '/login/forgot/index.php',
    'FRONTEND'              => false,
]);
