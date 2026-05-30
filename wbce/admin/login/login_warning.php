<?php
/**
 * WBCE CMS — Login warning page
 *
 * Shown when the maximum number of failed login attempts has been exceeded.
 * Replaces the static warning.html files in individual themes with a themed,
 * multilingual Twig-rendered page.
 *
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require_once '../../config.php';

$admin = new Admin('Start', '', false, false);
$admin->getThemeFile('login_warning.twig', [
    'WB_URL'    => WB_URL,
    'THEME_URL' => THEME_URL,
    'CHARSET'   => defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8',
    'LANGUAGE'  => strtolower(LANGUAGE),
    'LOGIN_URL' => ADMIN_URL . '/login/index.php',
]);
