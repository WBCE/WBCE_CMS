<?php
/**
 * WBCE CMS — htmx endpoint for user actions (toggle, delete, getrow)
 *
 * @since 1.7.0
 */

require '../../config.php';

$action = $_GET['do'] ?? '';
$admin  = new Admin('Access', 'users_modify', false);
$users  = new UserManager();
$alerts = new Alerts(useSession: false);
$userId = intval($admin->checkIDKEY('user_id', 0, 'GET', true));

// Non-htmx fallback
if (!MessageBox::isHtmx()) {
    $param = ($action === 'delete') ? 'delete' : 'activation';
    header('Location: ' . ADMIN_URL . '/users/index.php?user_id=' . urlencode($_GET['user_id'] ?? '') . '&' . $param . '=1');
    exit;
}

if ($userId < 2) {
    http_response_code(422);
    $alerts->error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    echo $alerts->renderFragment();
    exit;
}


// --- Get row ---
if ($action === 'getrow') {
    renderUserRow($userId, $admin, $users, 'empha-last-edit');
    exit;
}

// --- Toggle active ---
if ($action === 'toggle') {
    $newState = $users->toggleActive($userId);

    if ($newState === null) {
        http_response_code(500);
        $alerts->error($database->getError());
        echo $alerts->renderFragment();
        exit;
    }

    $label = $newState ? '{TEXT:ENABLED}' : '{TEXT:DISABLED}';
    $alerts->toast('{TEXT:USER} ' . $label, 'success');

    renderUserRow($userId, $admin, $users);
    exit;
}

// --- Delete (two-stage) ---
if ($action === 'delete') {
    $result = $users->deleteUser($userId);

    if ($result === false) {
        http_response_code(500);
        $alerts->error($database->getError());
        echo $alerts->renderFragment();
        exit;
    }

    if ($result === 'disabled') {
        $alerts->toast('{TEXT:USER} {TEXT:DISABLED}', 'warning');
        renderUserRow($userId, $admin, $users);
    } else {
        $alerts->toast('{MESSAGE:USERS_DELETED}', 'success');
        echo '';
    }
    exit;
}

http_response_code(400);
echo 'Unknown action';


/**
 * Render a single user row using the Twig template.
 */
function renderUserRow($userId, $admin, $users, $extraClass = ''): void
{
    $rec = $users->getUser($userId);
    if ($rec === null) {
        echo '';
        return;
    }

    $toTwig = [
        'rec'         => $rec,
        'extra_class' => $extraClass,
    ];

    echo $admin->getThemeFile('access_users_tablerow.twig', $toTwig);
}
