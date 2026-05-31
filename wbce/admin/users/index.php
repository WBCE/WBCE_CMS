<?php
/**
 * WBCE CMS — User management (overview)
 *
 * Slim bootstrap: renders access_users.twig.
 * CRUD operations handled by form.php (add/edit) and action.php (toggle/delete/getrow).
 *
 * Non-htmx fallbacks for delete/activation are handled here with redirects.
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

require '../../config.php';
require ADMIN_PATH . '/access/functions.php';
require_once ADMIN_PATH . '/interface/timezones.php';
require_once ADMIN_PATH . '/interface/languages.php';

$sPos         = 'users';
$admin        = new Admin('Access', $sPos, false);
$alerts       = new Alerts();
$users        = new UserManager();
$isSuperAdmin = $admin->isAdmin();

// --- Non-htmx fallback: Delete ---
if (isset($_GET['delete'])) {
    $admin  = new Admin('Access', 'users_delete', false);
    $userId = intval($admin->checkIDKEY('user_id', 0, $_SERVER['REQUEST_METHOD']));

    if ($userId > 1) {
        $result = $users->deleteUser($userId);

        match ($result) {
            'disabled' => $alerts->sessionToast(L_('{TEXT:USER} {TEXT:DISABLED}'), 'warning'),
            'deleted'  => $alerts->sessionToast('{MESSAGE:USERS_DELETED}', 'success'),
            false      => $alerts->error($database->getError()),
        };
    }

    header('Location: ' . ADMIN_URL . '/users/?hilite=' . $userId . '#uid_' . $userId);
    exit;
}

// --- Non-htmx fallback: Toggle active ---
if (isset($_GET['activation'])) {
    $admin  = new Admin('Access', 'users_modify', false);
    $userId = intval($admin->checkIDKEY('user_id', 0, $_SERVER['REQUEST_METHOD']));

    if ($userId > 1) {
        $newState = $users->toggleActive($userId);

        if ($newState !== null) {
            $label = $newState ? '{TEXT:ENABLED}' : '{TEXT:DISABLED}';
            $alerts->sessionToast('{TEXT:USER} ' . $label, 'success');
        } else {
            $alerts->error($database->getError());
        }
    }

    header('Location: ' . ADMIN_URL . '/users/?hilite=' . $userId . '#uid_' . $userId);
    exit;
}

// --- Determine if modify view should be pre-loaded ---
$isModifyView = false;
$modifyUserId = 0;

if (isset($_GET['user_id']) && isset($_GET['modify'])) {
    $admin        = new Admin('Access', 'users_modify', false);
    $modifyUserId = intval($admin->checkIDKEY('user_id', 0, 'GET', true));

    if ($modifyUserId >= 2) {
        $isModifyView = true;
    }
}

$admin->print_header();

// Auto-cleanup unconfirmed signups
$users->autoCleanup();

// --- Render ---
$toTwig = [
    'MESSAGE_BOX'        => $alerts->render(),
    'TABS'               => renderAddonsTabs($sPos),
    'do_modify_user'     => $isModifyView,
    'ACTION_URL'         => ADMIN_URL . '/users/form.php',
    'use_home_folders'   => HOME_FOLDERS,
    'USERLIST'           => $users->getAllUsers(false, $isSuperAdmin),
    'TIME_ZONES'         => getTimeZonesArray($TIMEZONES ?? [], true),
    'LANGUAGES'          => getLanguagesArray(),
];

// Pre-load user data if modify view
if ($isModifyView && $modifyUserId > 0) {
    $toTwig['user'] = $users->getUser($modifyUserId) ?? [];
    $toTwig['groups'] = $users->getGroupsForForm(
        $modifyUserId,
        [],
        in_array(1, $admin->get_groups_id())
    );
    $toTwig['home_folders'] = $users->getHomeFolders($modifyUserId);
    $toTwig['change_pswd']  = isset($_GET['duplicated']);
    $toTwig['INPUT_NEW_PASSWORD'] = $admin->passwordField('password');
}

if (isset($_GET['hilite'])) {
    $toTwig['hilite'] = (int) $_GET['hilite'];
}

$admin->getThemeFile('access_users.twig', $toTwig);
$admin->print_footer();
