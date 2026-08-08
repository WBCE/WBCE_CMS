<?php
/**
 * WBCE CMS — Admin Tools overview
 *
 * Lists all installed admin tools the current user has permission for.
 * Handles settings save (htmx POST) for layout preferences.
 *
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require '../../config.php';
Lang::loadLanguage(__DIR__);
$admin  = new Admin('admintools', 'admintools', false);
$alerts = new Alerts(useSession: false);

// =========================================================================
//  Settings defaults
// =========================================================================
$defaults = [
    'columns'         => 1,
    'show_search'     => true,
    'most_used_first' => false,
];

$raw = Settings::get('admintools_config');
$cfg = $raw ? json_decode($raw, true) : [];
$cfg = array_merge($defaults, $cfg);


// =========================================================================
//  POST: Save settings (htmx)
// =========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_admintools_settings'])) {

    if (!$admin->checkFTAN()) {
        if (Alerts::isHtmx()) {
            $alerts->error($MESSAGE['GENERIC_SECURITY_ACCESS']);
            echo $alerts->renderFragment();
            exit;
        }
        $admin->print_header();
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL . '/admintools/');
    }

    $cfg = [
        'columns'         => (int) ($admin->get_post('columns') ?? 1) === 2 ? 2 : 1,
        'show_search'     => ($admin->get_post('show_search') !== null),
        'most_used_first' => ($admin->get_post('most_used_first') !== null),
    ];

    Settings::set('admintools_config', json_encode($cfg));

    if (Alerts::isHtmx()) {
        header('HX-Trigger: {"settingsSaved": true}');
        $alerts->toast('{MESSAGE:SETTINGS_SAVED}', 'success');
        echo '';
        exit;
    }
}

$admin->print_header();


// =========================================================================
//  Load tools list
// =========================================================================
$resTools = $database->fetchAll(
    "SELECT * FROM `{TP}addons`
      WHERE `type` = 'module' AND `function` LIKE '%tool%' AND `function` NOT LIKE '%hidden%'
      ORDER BY `name`"
);

$tools = [];
$tool_default_icon = "fa fa-graduation-cap";

foreach ($resTools as $arr) {
    $id      = $arr['addon_id'];
    $toolDir = $arr['directory'];

    // Permission check
    if (!$admin->isAdmin()) {
        if (!in_array($toolDir . '_tool', $admin->get_session('MODULE_PERMISSIONS'))) {
            continue;
        }
    }

    $iconData = $admin->get_module_icon($toolDir, $tool_default_icon);

    $tools[$id] = [
        'dir'      => $toolDir,
        'name'     => $admin->get_module_name($toolDir),
        'descr'    => $admin->get_module_description($toolDir),
        'icon'     => $iconData['icon'],
        'icon_svg' => $iconData['is_svg'],
    ];
}

$toTwig = [
    'admin_tools'          => $tools,
    'cfg'                  => $cfg,
    'can_change_settings'  => $admin->isAdmin() || $admin->get_permission('admintools_settings'),
];

$admin->getThemeFile('admintools.twig', $toTwig);
$admin->print_footer();
