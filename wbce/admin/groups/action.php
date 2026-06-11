<?php
/**
 * WBCE CMS — htmx endpoint for group actions (delete, getrow)
 *
 * @since 1.7.0
 */

require '../../config.php';

$action  = $_GET['do'] ?? 'delete';
$admin   = new Admin('Access', ($action === 'getrow') ? 'groups_view' : 'groups_delete', false);
$groups  = new GroupManager();
$perms   = new PermissionManager();
$alerts = new Alerts(useSession: false);
$groupId = intval($admin->checkIDKEY('group_id', 0, 'GET', true));

// Non-htmx fallback
if (!MessageBox::isHtmx()) {
    header('Location: ' . ADMIN_URL . '/groups/index.php?group_id=' . urlencode($_GET['group_id'] ?? '') . '&delete=1');
    exit;
}

if ($groupId < 2) {
    http_response_code(422);
    $alerts->error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    echo '<tr><td colspan="5">' . $alerts->renderFragment() . '</td></tr>';
    exit;
}


// --- Get row (for new or updated group) ---
if ($action === 'getrow') {
    renderGroupRow($groupId, $admin, $groups, $perms, 'empha-last-edit');
    exit;
}

// --- Delete ---
if ($groups->hasMembers($groupId)) {
    http_response_code(422);
    $alerts->error($MESSAGE['GROUP_HAS_MEMBERS'] ?? 'Group has members');
    echo '<tr><td colspan="5">' . $alerts->renderFragment() . '</td></tr>';
    exit;
}

if ($groups->deleteGroup($groupId)) {
    $alerts->toast('{MESSAGE:GROUPS_DELETED}', 'success');
    echo '';
} else {
    http_response_code(500);
    $alerts->error($database->getError());
    echo '<tr><td colspan="5">' . $alerts->renderFragment() . '</td></tr>';
}


/**
 * Render a single group row via Twig template.
 */
function renderGroupRow(
    int $groupId,
    Admin $admin,
    GroupManager $groups,
    PermissionManager $perms,
    string $extraClass = ''
): void {
    $rec = $groups->getGroup($groupId);
    if ($rec === null) {
        echo '';
        return;
    }

    $usercount = 0;
    if ($groups->hasMembers($groupId)) {
        $usercount = (int) $GLOBALS['database']->fetchValue(
            "SELECT COUNT(*) FROM `{TP}users`
              WHERE `group_id` = ? OR FIND_IN_SET(?, `groups_id`) > 0",
            [$groupId, $groupId]
        );
    }

    $states = $perms->getAreaStates($groupId);
    if ($groupId === 1) {
        $states = array_fill_keys(array_keys($states), 'full');
    }

    $signupGroupId = defined('FRONTEND_SIGNUP') ? (int) FRONTEND_SIGNUP : 0;

    $toTwig = [
        'rec'             => $rec,
        'states'          => $states,
        'usercount'       => $usercount,
        'extra_class'     => $extraClass,
        'signup_group_id' => $signupGroupId,
    ];

    $admin->getThemeFile('access_groups_tablerow.twig', $toTwig);
}
