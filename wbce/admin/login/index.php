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

require_once("../../config.php");
require_once(WB_PATH . "/framework/class.login.php");


$username_fieldname = 'username';
$password_fieldname = 'password';




$admin = new admin('Start', '', false, false);

$WarnUrl = str_replace(WB_PATH, WB_URL, $admin->correct_theme_source('warning.html'));
// Setup template object, parse vars to it, then parse it
$ThemePath = dirname($admin->correct_theme_source('login.htt'));





$thisApp = new Login(
    array(
        'MAX_ATTEMPTS' => "3",
        'TIMEFRAME' => "600",
        'LOGIN_DELAY' => "60",
        'WARNING_URL' => $WarnUrl,
        'USERNAME_FIELDNAME' => $username_fieldname,
        'PASSWORD_FIELDNAME' => $password_fieldname,
        'REMEMBER_ME_OPTION' => SMART_LOGIN,
        'MIN_USERNAME_LEN' => "2",
        'MIN_PASSWORD_LEN' => "3",
        'MAX_USERNAME_LEN' => "30",
        'MAX_PASSWORD_LEN' => "30",
        'LOGIN_URL' => ADMIN_URL . "/login/index.php",
        'DEFAULT_URL' => ADMIN_URL . "/start/index.php",
        'TEMPLATE_DIR' => $ThemePath,
        'TEMPLATE_FILE' => "login.htt",
        'FRONTEND' => false,
        'FORGOTTEN_DETAILS_APP' => ADMIN_URL . "/login/forgot/index.php",
        'USERS_TABLE' => TABLE_PREFIX . "users",
        'GROUPS_TABLE' => TABLE_PREFIX . "groups",		
    )
);
