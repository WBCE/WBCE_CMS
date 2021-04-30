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

require_once dirname(__DIR__) . '/config.php';

if (isset($_COOKIE['REMEMBER_KEY'])) {
    setcookie('REMEMBER_KEY', '', time() - 3600, '/');
}

// unset the session data
$_SESSION['USER_ID'] = null;
$_SESSION['GROUP_ID'] = null;
$_SESSION['GROUPS_ID'] = null;
$_SESSION['USERNAME'] = null;
$_SESSION['PAGE_PERMISSIONS'] = null;
$_SESSION['SYSTEM_PERMISSIONS'] = null;
$_SESSION = array();
session_unset();
unset($_COOKIE[session_name()]);
session_destroy();

// workout the redirect
$redirect = ((isset($_SESSION['HTTP_REFERER']) && $_SESSION['HTTP_REFERER'] != '') ? $_SESSION['HTTP_REFERER'] : WB_URL . '/index.php');

if (INTRO_PAGE) {
    header('Location: ' . WB_URL . PAGES_DIRECTORY . '/index.php');
} else {
    header('Location: ' . $redirect);
}
