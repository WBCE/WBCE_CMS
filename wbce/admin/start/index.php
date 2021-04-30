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
require_once(WB_PATH . '/framework/class.admin.php');
$admin = new admin('Start', 'start');
// ---------------------------------------

if (defined('FINALIZE_SETUP')) {
    require_once(WB_PATH . '/framework/functions.php');
    $dirs = array('modules' => WB_PATH . '/modules/',
        'templates' => WB_PATH . '/templates/',
        'languages' => WB_PATH . '/languages/'
    );
    foreach ($dirs as $type => $dir) {
        if (($handle = opendir($dir))) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '' and substr($file, 0, 1) != '.' and $file != 'admin.php' and $file != 'index.php') {
                    // Get addon type
                    if ($type == 'modules') {
                        load_module($dir . '/' . $file, true);
                        // Pretty ugly hack to let modules run $admin->set_error
                        // See dummy class definition admin_dummy above
                        if (isset($admin->error) && $admin->error != '') {
                            $admin->print_error($admin->error);
                        }
                    } elseif ($type == 'templates') {
                        load_template($dir . '/' . $file);
                    } elseif ($type == 'languages') {
                        load_language($dir . '/' . $file);
                    }
                }
            }
            closedir($handle);
        }
    }
    $sql = 'DELETE FROM `' . TABLE_PREFIX . 'settings` WHERE `name`=\'FINALIZE_SETUP\'';
    if ($database->query($sql)) {
    }
}
// ---------------------------------------
$msg = '<br />';

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('start.htt')));
$template->set_file('page', 'start.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values into the template object
$template->set_var(
    array(
        'WELCOME_MESSAGE' => $MESSAGE['START_WELCOME_MESSAGE'],
        'CURRENT_USER' => $MESSAGE['START_CURRENT_USER'],
        'DISPLAY_NAME' => $admin->get_display_name(),
        'ADMIN_URL' => ADMIN_URL,
        'WB_URL' => WB_URL,
        'THEME_URL' => THEME_URL,
        'WB_VERSION' => WB_VERSION
    )
);

// Insert permission values into the template object
if ($admin->get_permission('pages') != true) {
    $template->set_var('DISPLAY_PAGES', 'display:none;');
}
if ($admin->get_permission('media') != true) {
    $template->set_var('DISPLAY_MEDIA', 'display:none;');
}
if ($admin->get_permission('addons') != true) {
    $template->set_var('DISPLAY_ADDONS', 'display:none;');
}
if ($admin->get_permission('access') != true) {
    $template->set_var('DISPLAY_ACCESS', 'display:none;');
}
if ($admin->get_permission('settings') != true) {
    $template->set_var('DISPLAY_SETTINGS', 'display:none;');
}
if ($admin->get_permission('admintools') != true) {
    $template->set_var('DISPLAY_ADMINTOOLS', 'display:none;');
}

$msg .= (file_exists(WB_PATH . '/install/')) ? $MESSAGE['START_INSTALL_DIR_EXISTS'] : '';

// Check if installation directory still exists and delete the files
if (file_exists(WB_PATH . '/install/') || file_exists(WB_PATH . '/upgrade-script.php')) {
    if (!function_exists('rm_full_dir')) {
        @require_once(WB_PATH . '/framework/functions.php');
    }
    if (file_exists(WB_PATH . '/upgrade-script.php')) {
        unlink(WB_PATH . '/upgrade-script.php');
    }
    if (file_exists(WB_PATH . '/install/')) {
        rm_full_dir(WB_PATH . '/install/');
    }
}
$template->set_var('DISPLAY_WARNING', 'display:none;');

if (function_exists('curl_version') && (!defined('SHOW_UPDATE_INFO') || SHOW_UPDATE_INFO != false)) {
    include WB_PATH . '/include/GitHubApiClient/GitHubApiClient.php';
    $gitHubApiClient = new \Neoflow\GitHubApiClient();
    $response = json_decode($gitHubApiClient->call('/repos/WBCE/WBCE_CMS/releases/latest', [CURLOPT_USERAGENT => 'WBCE_CMS']), true);
    if (isset($response['tag_name'])) {
        $wbce_latest_release = $response['tag_name'];
    }
    if ($wbce_latest_release > NEW_WBCE_VERSION) {
        echo $TEXT['OLDWBCE'];
        echo '<b style="color:red">' . NEW_WBCE_VERSION . '</b><br>';
    }
}

// Insert "Add-ons" section overview (pretty complex compared to normal)
$addons_overview = $TEXT['MANAGE'] . ' ';
$addons_count = 0;
if ($admin->get_permission('modules') == true) {
    $addons_overview .= '<a href="' . ADMIN_URL . '/modules/index.php">' . $MENU['MODULES'] . '</a>';
    $addons_count = 1;
}
if ($admin->get_permission('templates') == true) {
    if ($addons_count == 1) {
        $addons_overview .= ', ';
    }
    $addons_overview .= '<a href="' . ADMIN_URL . '/templates/index.php">' . $MENU['TEMPLATES'] . '</a>';
    $addons_count = 1;
}
if ($admin->get_permission('languages') == true) {
    if ($addons_count == 1) {
        $addons_overview .= ', ';
    }
    $addons_overview .= '<a href="' . ADMIN_URL . '/languages/index.php">' . $MENU['LANGUAGES'] . '</a>';
}

// Insert "Access" section overview (pretty complex compared to normal)
$access_overview = $TEXT['MANAGE'] . ' ';
$access_count = 0;
if ($admin->get_permission('users') == true) {
    $access_overview .= '<a href="' . ADMIN_URL . '/users/index.php">' . $MENU['USERS'] . '</a>';
    $access_count = 1;
}
if ($admin->get_permission('groups') == true) {
    if ($access_count == 1) {
        $access_overview .= ', ';
    }
    $access_overview .= '<a href="' . ADMIN_URL . '/groups/index.php">' . $MENU['GROUPS'] . '</a>';
    $access_count = 1;
}

// Insert section names and descriptions
$template->set_var(
    array(
        'PAGES' => $MENU['PAGES'],
        'MEDIA' => $MENU['MEDIA'],
        'ADDONS' => $MENU['ADDONS'],
        'ACCESS' => $MENU['ACCESS'],
        'PREFERENCES' => $MENU['PREFERENCES'],
        'SETTINGS' => $MENU['SETTINGS'],
        'ADMINTOOLS' => $MENU['ADMINTOOLS'],
        'HOME_OVERVIEW' => $OVERVIEW['START'],
        'PAGES_OVERVIEW' => $OVERVIEW['PAGES'],
        'MEDIA_OVERVIEW' => $OVERVIEW['MEDIA'],
        'ADDONS_OVERVIEW' => $addons_overview,
        'ACCESS_OVERVIEW' => $access_overview,
        'PREFERENCES_OVERVIEW' => $OVERVIEW['PREFERENCES'],
        'SETTINGS_OVERVIEW' => $OVERVIEW['SETTINGS'],
        'ADMINTOOLS_OVERVIEW' => $OVERVIEW['ADMINTOOLS']
    )
);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
