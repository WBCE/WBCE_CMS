<?php
/**
 * WBCE CMS — Groups management (overview, modify, delete, duplicate)
 *
 * Slim bootstrap: renders access_groups.twig.
 * CRUD operations handled by form.php (add/edit) and action.php (delete/getrow).
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

require '../../config.php';
require ADMIN_PATH . '/access/functions.php';

$alerts = new Alerts();
$groups = new GroupManager();
$perms  = new PermissionManager();
$sPos   = 'groups';

// --- Determine action ---
$action  = 'overview';
$groupId = 0;

if (isset($_GET['delete']))    $action = 'delete';
elseif (isset($_GET['duplicate'])) $action = 'duplicate';

switch ($action) {

    case 'delete':
        $admin   = new Admin('Access', 'groups_delete', false);
        $groupId = intval($admin->checkIDKEY('group_id', 0, $_SERVER['REQUEST_METHOD']));

        if ($groupId > 1) {
            if ($groups->hasMembers($groupId)) {
                $alerts->error($MESSAGE['GROUP_HAS_MEMBERS'] ?? 'Group has members');
            } elseif ($groups->deleteGroup($groupId)) {
                $alerts->sessionToast('{MESSAGE:GROUPS_DELETED}', 'success');
                header('Location: ' . ADMIN_URL . '/groups/');
                exit;
            } else {
                $alerts->error($database->getError());
            }
        }
        break;

    case 'duplicate':
        $admin   = new Admin('Access', 'groups_add', false);
        $groupId = intval($admin->checkIDKEY('group_id', 0, $_SERVER['REQUEST_METHOD'], true));
        $newId   = $groups->duplicateGroup($groupId);

        if ($newId > 0) {
            $alerts->sessionToast('{TEXT:DUPLICATED}', 'success');
            header('Location: ' . ADMIN_URL . '/groups/?duplicated=' . $newId);
        } else {
            $alerts->error($database->getError());
            header('Location: ' . ADMIN_URL . '/groups/');
        }
        exit;

    default:
        $admin = new Admin('Access', $sPos);
}

// --- Build group list ---
$allGroups = $groups->getAllGroups();

// --- Render ---
$toTwig = [
    'MESSAGE_BOX'      => $alerts->render(),
    'TABS'             => renderAddonsTabs($sPos),
    'groups'           => $allGroups,
    'GROUP_ID'         => (isset($_GET['group_id']) && isset($_GET['modify'])) ? intval($admin->checkIDKEY('group_id', 0, 'GET', true)) : 0,
];

if (isset($_GET['duplicated'])) {
    $toTwig['duplicated'] = (int) $_GET['duplicated'];
}

$admin->getThemeFile('access_groups.twig', $toTwig);
$admin->print_footer();
