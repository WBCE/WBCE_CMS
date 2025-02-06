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

require('../../config.php');

// delete remember key of current user from database
if (isset($_SESSION['USER_ID']) && isset($database)) {
    $table = TABLE_PREFIX . 'users';
    $sql = "UPDATE `$table` SET `remember_key` = '' WHERE `user_id` = '" . (int)$_SESSION['USER_ID'] . "'";
    $database->query($sql);
}

// delete remember key cookie if set
if (isset($_COOKIE['REMEMBER_KEY'])) {
    setcookie('REMEMBER_KEY', '', time() - 3600, '/');
}

// delete most critical session variables manually
$_SESSION['USER_ID'] = null;
$_SESSION['GROUP_ID'] = null;
$_SESSION['GROUPS_ID'] = null;
$_SESSION['USERNAME'] = null;
$_SESSION['PAGE_PERMISSIONS'] = null;
$_SESSION['SYSTEM_PERMISSIONS'] = null;

// overwrite session array
$_SESSION = array();

// delete session cookie if set
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// delete the session itself
session_destroy();

// redirect to admin login
header('Location: ' . ADMIN_URL . '/login/index.php');
die;
