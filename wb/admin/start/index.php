<?php
/**
 *
 * @category        admin
 * @package         start
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: index.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/start/index.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
*/

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Start','start');
// ---------------------------------------

if(defined('FINALIZE_SETUP')) {
    require_once(WB_PATH.'/framework/functions.php');
    $dirs = array( 'modules'   => WB_PATH.'/modules/',
                   'templates' => WB_PATH.'/templates/',
                   'languages' => WB_PATH.'/languages/'
                 );
    foreach($dirs AS $type => $dir) {
        if( ($handle = opendir($dir)) ) {
            while(false !== ($file = readdir($handle))) {
                if($file != '' AND substr($file, 0, 1) != '.' AND $file != 'admin.php' AND $file != 'index.php') {
                    // Get addon type
                    if($type == 'modules') {
                        load_module($dir.'/'.$file, true);
                        // Pretty ugly hack to let modules run $admin->set_error
                        // See dummy class definition admin_dummy above
                        if(isset($admin->error) && $admin->error != '') {
                            $admin->print_error($admin->error);
                        }
                    } elseif($type == 'templates') {
                        load_template($dir.'/'.$file);
                    } elseif($type == 'languages') {
                        load_language($dir.'/'.$file);
                    }
                }
            }
        closedir($handle);
        }
    }
    $sql = 'DELETE FROM `'.TABLE_PREFIX.'settings` WHERE `name`=\'FINALIZE_SETUP\'';
    if($database->query($sql)) { }
}
// ---------------------------------------
$msg = '<br />';
// check if it is neccessary to start the uograde-script
if(($admin->get_user_id()==1) && file_exists(WB_PATH.'/upgrade-script.php')) {
    // check if it is neccessary to start the uograde-script
    $sql = 'SELECT `value` FROM `'.TABLE_PREFIX.'settings` WHERE `name`=\'wb_revision\'';
    if($wb_revision=$database->get_one($sql)) {
    }
    if (version_compare($wb_revision, REVISION ) < 0) {
        if(!headers_sent()) {
            header('Location: '.WB_URL.'/upgrade-script.php');
            exit;
        } else {
            echo "<p style=\"text-align:center;\"> The <strong>upgrade script</strong> could not be start automatically.\n" .
                 "Please click <a style=\"font-weight:bold;\" " .
                 "href=\"".WB_URL."/upgrade-script.php\">on this link</a> to start the script!</p>\n";
            exit;
        }
    }
    $msg .= ''.$MESSAGE['START_UPGRADE_SCRIPT_EXISTS'].'<br />';
}

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('start.htt')));
$template->set_file('page', 'start.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values into the template object
$template->set_var(array(
                    'WELCOME_MESSAGE' => $MESSAGE['START']['WELCOME_MESSAGE'],
                    'CURRENT_USER' => $MESSAGE['START']['CURRENT_USER'],
                    'DISPLAY_NAME' => $admin->get_display_name(),
                    'ADMIN_URL' => ADMIN_URL,
                    'WB_URL' => WB_URL,
                    'THEME_URL' => THEME_URL,
                    'WB_VERSION' => WB_VERSION
                )
            );

// Insert permission values into the template object
if($admin->get_permission('pages') != true)
{
    $template->set_var('DISPLAY_PAGES', 'display:none;');
}
if($admin->get_permission('media') != true)
{
    $template->set_var('DISPLAY_MEDIA', 'display:none;');
}
if($admin->get_permission('addons') != true)
{
    $template->set_var('DISPLAY_ADDONS', 'display:none;');
}
if($admin->get_permission('access') != true)
{
    $template->set_var('DISPLAY_ACCESS', 'display:none;');
}
if($admin->get_permission('settings') != true)
{
    $template->set_var('DISPLAY_SETTINGS', 'display:none;');
}
if($admin->get_permission('admintools') != true)
{
    $template->set_var('DISPLAY_ADMINTOOLS', 'display:none;');
}

$msg .= (file_exists(WB_PATH.'/install/')) ?  $MESSAGE['START']['INSTALL_DIR_EXISTS'] : '';

// Check if installation directory still exists
if(file_exists(WB_PATH.'/install/') || file_exists(WB_PATH.'/upgrade-script.php') ) {
    // Check if user is part of Adminstrators group
    if(in_array(1, $admin->get_groups_id()))
    {
        $template->set_var('WARNING', $msg );
    } else {
        $template->set_var('DISPLAY_WARNING', 'display:none;');
    }
} else {
    $template->set_var('DISPLAY_WARNING', 'display:none;');
}

// Insert "Add-ons" section overview (pretty complex compared to normal)
$addons_overview = $TEXT['MANAGE'].' ';
$addons_count = 0;
if($admin->get_permission('modules') == true)
{
    $addons_overview .= '<a href="'.ADMIN_URL.'/modules/index.php">'.$MENU['MODULES'].'</a>';
    $addons_count = 1;
}
if($admin->get_permission('templates') == true)
{
    if($addons_count == 1) { $addons_overview .= ', '; }
    $addons_overview .= '<a href="'.ADMIN_URL.'/templates/index.php">'.$MENU['TEMPLATES'].'</a>';
    $addons_count = 1;
}
if($admin->get_permission('languages') == true)
{
    if($addons_count == 1) { $addons_overview .= ', '; }
    $addons_overview .= '<a href="'.ADMIN_URL.'/languages/index.php">'.$MENU['LANGUAGES'].'</a>';
}

// Insert "Access" section overview (pretty complex compared to normal)
$access_overview = $TEXT['MANAGE'].' ';
$access_count = 0;
if($admin->get_permission('users') == true) {
    $access_overview .= '<a href="'.ADMIN_URL.'/users/index.php">'.$MENU['USERS'].'</a>';
    $access_count = 1;
}
if($admin->get_permission('groups') == true) {
    if($access_count == 1) { $access_overview .= ', '; }
    $access_overview .= '<a href="'.ADMIN_URL.'/groups/index.php">'.$MENU['GROUPS'].'</a>';
    $access_count = 1;
}

// Insert section names and descriptions
$template->set_var(array(
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
