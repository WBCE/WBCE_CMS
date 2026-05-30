<?php
/**
 * WBCE CMS — Access settings (home folders, frontend login/signup)
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

require '../../config.php';

$alerts  = new Alerts();
$groups  = new GroupManager();

$aSettings    = ['home_folders', 'frontend_login', 'frontend_signup'];
$bShowSettings = false;

// --- Handle POST: save settings ---
if (isset($_POST['save_settings'])) {
    $admin     = new Admin('Settings', 'settings_advanced', false);
    $sRedirect = ADMIN_URL . '/access';

    if (!$admin->checkFTAN()) {
        if (Alerts::isHtmx()) {
            $alerts->error(L_('MESSAGE:GENERIC_SECURITY_ACCESS'));
            echo $alerts->renderFragment();
            exit;
        }
        $alerts->error(L_('MESSAGE:GENERIC_SECURITY_ACCESS'));
        $alerts->redirect($sRedirect);
    }

    // Switches: unchecked = not in POST = 'false'
    Settings::Set('home_folders',   $admin->get_post('home_folders')   ? 'true' : 'false');
    Settings::Set('frontend_login', $admin->get_post('frontend_login') ? 'true' : 'false');
    Settings::Set('frontend_signup', $admin->get_post('frontend_signup') ?? 'false');

    $bShowSettings = true;

    if (Alerts::isHtmx()) {
        $alerts->toast('{MESSAGE:RECORD_MODIFIED_SAVED}', 'success');
        echo '';
        exit;
    }

    $alerts->success($MESSAGE['RECORD_MODIFIED_SAVED']);
}

require __DIR__ . '/functions.php';

// --- Build settings values ---
$cfg = [];
foreach ($aSettings as $key) {
    $cfg[$key] = Settings::Get($key);
}

$sPos  = 'access';
$admin = new Admin('Access', $sPos);

$toTwig = [
    'MESSAGE_BOX'   => $alerts->render(),
    'POSITION'      => $sPos,
    'TABS'          => renderAddonsTabs($sPos),
    'cfg'           => $cfg,
    'SIGNUP_GROUPS' => $groups->getGroupsSimpleList(),
    'show_settings' => $bShowSettings,
];

$admin->getThemeFile('access.twig', $toTwig);
$admin->print_footer();
