<?php
/**
 * WBCE CMS — htmx endpoint: Group form fragment
 *
 * GET:  ?action=add / ?group_id={IDKEY} → load form
 * POST: save → return updated form + HX-Trigger for table row update
 *
 * @since 1.7.0
 */

require '../../config.php';
require ADMIN_PATH . '/access/functions.php';

// Non-htmx fallback
if (!MessageBox::isHtmx()) {
    $redirect = ADMIN_URL . '/groups/';
    if (isset($_GET['group_id'])) {
        $redirect .= 'index.php?group_id=' . urlencode($_GET['group_id']) . '&modify=1';
    }
    header('Location: ' . $redirect);
    exit;
}

$groups   = new GroupManager();
$perms    = new PermissionManager();
$alerts  = new Alerts(useSession: false);
$wasAdded = false;
$groupId  = 0;
$isNew    = false;
$hasError = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // =================================================================
    //  POST: Save
    // =================================================================
    $groupId = intval($_POST['group_id'] ?? 0);
    $isNew   = ($groupId === 0);

    $admin = new Admin('Access', $isNew ? 'groups_add' : 'groups_modify', false);

    if (!$admin->checkFTAN()) {
        $alerts->error($MESSAGE['GENERIC_SECURITY_ACCESS']);
        echo $alerts->renderFragment();
        exit;
    }

    if (!$isNew && $groupId < 2) {
        $alerts->error($MESSAGE['GENERIC_SECURITY_ACCESS']);
        echo $alerts->renderFragment();
        exit;
    }

    // Validate name
    $name = trim(strip_tags($admin->get_post('group_name') ?? ''));
    if ($name === '') {
        $alerts->error($MESSAGE['GROUPS_GROUP_NAME_BLANK']);
        $hasError = true;
    } elseif ($groups->isNameTaken($name, $groupId)) {
        $alerts->error($MESSAGE['GROUPS_GROUP_NAME_EXISTS']);
        $hasError = true;
    }

    if (!$hasError) {
        $data = [
            'name'        => $name,
            'description' => strip_tags($admin->get_post('description') ?? ''),
            ...$perms->parseFromPost($admin),
        ];

        if ($isNew) {
            $groupId = $groups->createGroup($data);
            if ($groupId > 0) {
                $wasAdded = true;
            }
        } else {
            $groups->updateGroup($groupId, $data);
        }

        if ($database->hasError()) {
            $alerts->error($database->getError());
            $hasError = true;
        }
    }

    // HX-Trigger for table row update FIRST, then toast merges
    if ($groupId > 0 && !$hasError) {
        $newIDKEY = $admin->getIDKEY($groupId);
        if ($wasAdded) {
            header('HX-Trigger: {"groupAdded": {"idkey": "' . $newIDKEY . '", "groupId": ' . $groupId . '}}');
        } else {
            header('HX-Trigger: {"groupSaved": {"idkey": "' . $newIDKEY . '", "groupId": ' . $groupId . '}}');
        }

        $label = $wasAdded ? '{MESSAGE:GROUPS_ADDED}' : '{MESSAGE:GROUPS_SAVED}';
        $alerts->toast($label, 'success');
    }

    // After add: switch to edit mode
    if ($wasAdded) {
        $isNew = false;
    }

} else {
    // =================================================================
    //  GET: Load form
    // =================================================================
    $isNew = (($_GET['action'] ?? '') === 'add');

    if ($isNew) {
        $admin = new Admin('Access', 'groups_add', false);
    } else {
        $admin   = new Admin('Access', 'groups_modify', false);
        $groupId = intval($admin->checkIDKEY('group_id', 0, 'GET', true));

        if ($groupId < 2) {
            $alerts->error($MESSAGE['GENERIC_SECURITY_ACCESS']);
            echo $alerts->renderFragment();
            exit;
        }
    }
}


// =====================================================================
//  Render form
// =====================================================================
$toTwig = [
    'MESSAGE_BOX'  => $alerts->renderFragment(),
    'group_id'     => $groupId,
    'GROUP_NAME'   => ($groupId > 0) ? $groups->getGroupName($groupId)        : '',
    'GROUP_DESC'   => ($groupId > 0) ? $groups->getGroupDescription($groupId) : '',
    'access_perms' => $perms->getStructure($groupId),
    'addon_list'   => [
        'page'     => $perms->getAddonList('page',     $groupId),
        'tool'     => $perms->getAddonList('tool',     $groupId),
        'setting'  => $perms->getAddonList('setting',  $groupId),
        'template' => $perms->getAddonList('template', $groupId),
        'theme'    => $perms->getAddonList('theme',    $groupId),
    ],
];

$admin->getThemeFile('access_groups_form.twig', $toTwig);
