<?php
/**
 *
 * @category        admin
 * @package         settings
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: index.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/settings/index.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
 */

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');

if(isset($_GET['advanced']) && $_GET['advanced'] == 'yes') {
    $admin = new admin('Settings', 'settings_advanced');
} else {
    $admin = new admin('Settings', 'settings_basic');
}

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');
require_once(WB_PATH.'/framework/functions-utf8.php');

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('settings.htt')));
// $template->debug = true;
$template->set_file('page',        'settings.htt');
$template->set_block('page',       'main_block', 'main');
$template->set_var('FTAN', $admin->getFTAN());

$template->set_block('main_block', 'template_list_block',         'template_list');
$template->set_block('main_block', 'timezone_list_block',         'timezone_list');
$template->set_block('main_block', 'language_list_block',         'language_list');
$template->set_block('main_block', 'date_format_list_block',      'date_format_list');
$template->set_block('main_block', 'time_format_list_block',      'time_format_list');
$template->set_block('main_block', 'theme_list_block',            'theme_list');
$template->set_block('main_block', 'search_template_list_block',  'search_template_list');
$template->set_block('main_block', 'group_list_block',            'group_list');
$template->set_block('main_block', 'charset_list_block',          'charset_list');
$template->set_block('main_block', 'error_reporting_list_block',  'error_reporting_list');
$template->set_block('main_block', 'editor_list_block',           'editor_list');
$template->set_block('main_block', 'page_level_limit_list_block', 'page_level_limit_list');

$template->set_block('main_block', 'show_page_level_limit_block', 'show_page_level_limit');
$template->set_block('main_block', 'show_checkbox_1_block',       'show_checkbox_1');
$template->set_block('main_block', 'show_checkbox_2_block',       'show_checkbox_2');
$template->set_block('main_block', 'show_checkbox_3_block',       'show_checkbox_3');
$template->set_block('main_block', 'show_php_error_level_block',  'show_php_error_level');
$template->set_block('main_block', 'show_charset_block',          'show_charset');
$template->set_block('main_block', 'show_wysiwyg_block',          'show_wysiwyg');
$template->set_block('main_block', 'show_access_block',           'show_access');
$template->set_block('main_block', 'show_search_block',           'show_search');
$template->set_block('main_block', 'show_redirect_timer_block',   'show_redirect_timer');

// Query current settings in the db, then loop through them and print them
$query = "SELECT * FROM ".TABLE_PREFIX."settings";
$results = $database->query($query);
while($setting = $results->fetchRow())
{
    $setting_name = $setting['name'];
    $setting_value = ( $setting_name != 'wbmailer_smtp_password' ) ? htmlspecialchars($setting['value']) : $setting['value'];
    $template->set_var(strtoupper($setting_name),$setting_value);
}

// Do the same for settings stored in config file as with ones in db
$database_type = '';
$is_advanced = (isset($_GET['advanced']) && $_GET['advanced'] == 'yes');
// Tell the browser whether or not to show advanced options
if($is_advanced)
{
    $template->set_var('DISPLAY_ADVANCED', '');
    $template->set_var('ADVANCED_FILE_PERMS_ID', 'file_perms_box');
    $template->set_var('BASIC_FILE_PERMS_ID', 'hide');
    $template->set_var('ADVANCED', 'yes');
    $template->set_var('ADVANCED_BUTTON', '&lt;&lt; '.$TEXT['HIDE_ADVANCED']);
    $template->set_var('ADVANCED_LINK', 'index.php?advanced=no');

} else {
    $template->set_var('DISPLAY_ADVANCED', ' style="display: none;"');
    $template->set_var('BASIC_FILE_PERMS_ID', 'file_perms_box');
    $template->set_var('ADVANCED_FILE_PERMS_ID', 'hide');

    $template->set_var('ADVANCED', 'no');
    $template->set_var('ADVANCED_BUTTON', $TEXT['SHOW_ADVANCED'].' &gt;&gt;');
    $template->set_var('ADVANCED_LINK', 'index.php?advanced=yes');
}

    $query = "SELECT * FROM ".TABLE_PREFIX."search WHERE extra = ''";
    $results = $database->query($query);

    // Query current settings in the db, then loop through them and print them
    while($setting = $results->fetchRow())
    {
        $setting_name = $setting['name'];
        $setting_value = htmlspecialchars(($setting['value']));
        switch($setting_name) {
            // Search header
            case 'header':
                $template->set_var('SEARCH_HEADER', $setting_value);
            break;
            // Search results header
            case 'results_header':
                $template->set_var('SEARCH_RESULTS_HEADER', $setting_value);
            break;
            // Search results loop
            case 'results_loop':
                $template->set_var('SEARCH_RESULTS_LOOP', $setting_value);
            break;
            // Search results footer
            case 'results_footer':
                $template->set_var('SEARCH_RESULTS_FOOTER', $setting_value);
            break;
            // Search no results
            case 'no_results':
                $template->set_var('SEARCH_NO_RESULTS', $setting_value);
            break;
            // Search footer
            case 'footer':
                $template->set_var('SEARCH_FOOTER', $setting_value);
            break;
            // Search module-order
            case 'module_order':
                $template->set_var('SEARCH_MODULE_ORDER', $setting_value);
            break;
            // Search max lines of excerpt
            case 'max_excerpt':
                $template->set_var('SEARCH_MAX_EXCERPT', $setting_value);
            break;
            // time-limit
            case 'time_limit':
                $template->set_var('SEARCH_TIME_LIMIT', $setting_value);
            break;
            // Search template
            case 'template':
                $search_template = $setting_value;
            break;
        }
    }

    $template->set_var(array(
                        'WB_URL' => WB_URL,
                        'THEME_URL' => THEME_URL,
                        'ADMIN_URL' => ADMIN_URL,
                     ));

    // Insert language values
    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'language' ORDER BY directory");
    if($result->numRows() > 0)
    {
        while($addon = $result->fetchRow()) {
            $langIcons = (empty($addon['directory'])) ? 'none' : strtolower($addon['directory']);

            $template->set_var('CODE',        $addon['directory']);
            $template->set_var('NAME',        $addon['name']);
            $template->set_var('FLAG',        THEME_URL.'/images/flags/'.$langIcons);
            $template->set_var('SELECTED',    (DEFAULT_LANGUAGE == $addon['directory'] ? ' selected="selected"' : '') );
            $template->parse('language_list', 'language_list_block', true);
        }
    }

    // Insert default timezone values
    require(ADMIN_PATH.'/interface/timezones.php');
    foreach($TIMEZONES AS $hour_offset => $title)
    {
        // Make sure we dont list "System Default" as we are setting this value!
        if($hour_offset != '-20') {
            $template->set_var('VALUE', $hour_offset);
            $template->set_var('NAME', $title);
            if(DEFAULT_TIMEZONE == $hour_offset*60*60) {
                $template->set_var('SELECTED', ' selected="selected"');
            } else {
                $template->set_var('SELECTED', '');
            }
            $template->parse('timezone_list', 'timezone_list_block', true);
        }
    }

    // Insert default charset values
    require(ADMIN_PATH.'/interface/charsets.php');
    foreach($CHARSETS AS $code => $title) {
        $template->set_var('VALUE', $code);
        $template->set_var('NAME', $title);
        if(DEFAULT_CHARSET == $code) {
            $template->set_var('SELECTED', ' selected="selected"');
        } else {
            $template->set_var('SELECTED', '');
        }
        $template->parse('charset_list', 'charset_list_block', true);
    }


    // Insert date format list
    require(ADMIN_PATH.'/interface/date_formats.php');
    foreach($DATE_FORMATS AS $format => $title) {
        $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
        if($format != 'system_default') {
            $template->set_var('VALUE', $format);
        } else {
            $template->set_var('VALUE', '');
        }
        $template->set_var('NAME', $title);
        if(DEFAULT_DATE_FORMAT == $format) {
            $template->set_var('SELECTED', ' selected="selected"');
        } else {
            $template->set_var('SELECTED', '');
        }
        $template->parse('date_format_list', 'date_format_list_block', true);
    }

    // Insert time format list
    require(ADMIN_PATH.'/interface/time_formats.php');
    foreach($TIME_FORMATS AS $format => $title) {
        $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
        if($format != 'system_default') {
            $template->set_var('VALUE', $format);
        } else {
            $template->set_var('VALUE', '');
        }
        $template->set_var('NAME', $title);
        if(DEFAULT_TIME_FORMAT == $format) {
            $template->set_var('SELECTED', ' selected="selected"');
        } else {
            $template->set_var('SELECTED', '');
        }
        $template->parse('time_format_list', 'time_format_list_block', true);
    }

    // Insert templates
    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function != 'theme' ORDER BY name");
    if($result->numRows() > 0) {
        while($addon = $result->fetchRow()) {
            $template->set_var('FILE', $addon['directory']);
            $template->set_var('NAME', $addon['name']);
            if(($addon['directory'] == DEFAULT_TEMPLATE) ? $selected = ' selected="selected"' : $selected = '');
            $template->set_var('SELECTED', $selected);
            $template->parse('template_list', 'template_list_block', true);
        }
    }

    // Insert backend theme
    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function = 'theme' ORDER BY name");
    if($result->numRows() > 0) {
        while($addon = $result->fetchRow()) {
            $template->set_var('FILE', $addon['directory']);
            $template->set_var('NAME', $addon['name']);
            if(($addon['directory'] == DEFAULT_THEME) ? $selected = ' selected="selected"' : $selected = '');
            $template->set_var('SELECTED', $selected);
            $template->parse('theme_list', 'theme_list_block', true);
        }
    }

    // Insert WYSIWYG modules
    $file='none';
    $module_name=$TEXT['NONE'];
    $template->set_var('FILE', $file);
    $template->set_var('NAME', $module_name);
    $selected = (!defined('WYSIWYG_EDITOR') || $file == WYSIWYG_EDITOR) ? ' selected="selected"' : '';
    $template->set_var('SELECTED', $selected);
    $template->parse('editor_list', 'editor_list_block', true);
    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'wysiwyg' ORDER BY name");
    if($result->numRows() > 0)
    {
        while($addon = $result->fetchRow())
        {
            $template->set_var('FILE', $addon['directory']);
            $template->set_var('NAME', $addon['name']);
            $selected = (!defined('WYSIWYG_EDITOR') || $addon['directory'] == WYSIWYG_EDITOR) ? ' selected="selected"' : '';
            $template->set_var('SELECTED', $selected);
            $template->parse('editor_list', 'editor_list_block', true);
        }
    }

// Insert templates for search settings
    $search_template = ( ($search_template == DEFAULT_TEMPLATE) || ($search_template == '') ) ? '' : $search_template;
    $selected = ( ($search_template != DEFAULT_TEMPLATE) ) ?  ' selected="selected"' : $selected = '';

    $template->set_var(array(
            'FILE' => '',
            'NAME' => $TEXT['SYSTEM_DEFAULT'],
            'SELECTED' => $selected
        ));
    $template->parse('search_template_list', 'search_template_list_block', true);

    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function = 'template' ORDER BY name");
    if($result->numRows() > 0)
    {
        while($addon = $result->fetchRow())
        {
            $template->set_var('FILE', $addon['directory']);
            $template->set_var('NAME', $addon['name']);
            $selected = ($addon['directory'] == $search_template) ? ' selected="selected"' :  $selected = '';
            $template->set_var('SELECTED', $selected);
            $template->parse('search_template_list', 'search_template_list_block', true);

        }
    }

    // Insert default error reporting values
    require(ADMIN_PATH.'/interface/er_levels.php');
    foreach($ER_LEVELS AS $value => $title)
    {
        $template->set_var('VALUE', $value);
        $template->set_var('NAME', $title);
        $selected = (ER_LEVEL == $value) ? ' selected="selected"' : '';
        $template->set_var('SELECTED', $selected);
        $template->parse('error_reporting_list', 'error_reporting_list_block', true);
    }

    // Insert permissions values
    if($admin->get_permission('settings_advanced') != true)
    {
        $template->set_var('DISPLAY_ADVANCED_BUTTON', 'hide');
    }

    // Insert page level limits
    for($i = 1; $i <= 10; $i++)
    {
        $template->set_var('NUMBER', $i);
        if(PAGE_LEVEL_LIMIT == $i)
        {
            $template->set_var('SELECTED', ' selected="selected"');
        } else {
            $template->set_var('SELECTED', '');
        }
        $template->parse('page_level_limit_list', 'page_level_limit_list_block', true);
    }

    // Work-out if multiple menus feature is enabled
    if(defined('MULTIPLE_MENUS') && MULTIPLE_MENUS == true)
    {
        $template->set_var('MULTIPLE_MENUS_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('MULTIPLE_MENUS_DISABLED', ' checked="checked"');
    }

    // Work-out if page languages feature is enabled
    if(defined('PAGE_LANGUAGES') && PAGE_LANGUAGES == true)
    {
            $template->set_var('PAGE_LANGUAGES_ENABLED', ' checked="checked"');
    } else {
            $template->set_var('PAGE_LANGUAGES_DISABLED', ' checked="checked"');
    }

    // Work-out if warn_page_leave feature is enabled
    if (defined('WARN_PAGE_LEAVE') && WARN_PAGE_LEAVE == true)
    {
        $template->set_var('WARN_PAGE_LEAVE_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('WARN_PAGE_LEAVE_DISABLED', ' checked="checked"');
    }

    // Work-out if smart login feature is enabled
    if(defined('SMART_LOGIN') && SMART_LOGIN == true)
    {
        $template->set_var('SMART_LOGIN_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('SMART_LOGIN_DISABLED', ' checked="checked"');
    }

    /* Make's sure GD library is installed */
    if(extension_loaded('gd') && function_exists('imageCreateFromJpeg'))
    {
        $template->set_var('GD_EXTENSION_ENABLED', '');
    } else {
        $template->set_var('GD_EXTENSION_ENABLED', ' style="display: none;"');
    }

    // Work-out if section blocks feature is enabled
    if(defined('SECTION_BLOCKS') && SECTION_BLOCKS == true)
    {
        $template->set_var('SECTION_BLOCKS_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('SECTION_BLOCKS_DISABLED', ' checked="checked"');
    }

    // Work-out if homepage redirection feature is enabled
    if(defined('HOMEPAGE_REDIRECTION') && HOMEPAGE_REDIRECTION == true)
    {
        $template->set_var('HOMEPAGE_REDIRECTION_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('HOMEPAGE_REDIRECTION_DISABLED', ' checked="checked"');
    }

    // Work-out which server os should be checked
    if(OPERATING_SYSTEM == 'linux')
    {
        $template->set_var('LINUX_SELECTED', ' checked="checked"');
    } elseif(OPERATING_SYSTEM == 'windows') {
        $template->set_var('WINDOWS_SELECTED', ' checked="checked"');
    }

    // Work-out if manage sections feature is enabled
    if(MANAGE_SECTIONS)
    {
        $template->set_var('MANAGE_SECTIONS_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('MANAGE_SECTIONS_DISABLED', ' checked="checked"');
    }

    // Work-out which wbmailer routine should be checked
    $template->set_var(array(
                'TEXT_WBMAILER_DEFAULT_SETTINGS_NOTICE' => $TEXT['WBMAILER_DEFAULT_SETTINGS_NOTICE'],
                'TEXT_WBMAILER_DEFAULT_SENDER_MAIL' => $TEXT['WBMAILER_DEFAULT_SENDER_MAIL'],
                'TEXT_WBMAILER_DEFAULT_SENDER_NAME' => $TEXT['WBMAILER_DEFAULT_SENDER_NAME'],
                'TEXT_WBMAILER_NOTICE' => $TEXT['WBMAILER_NOTICE'],
                'TEXT_WBMAILER_FUNCTION' => $TEXT['WBMAILER_FUNCTION'],
                'TEXT_WBMAILER_SMTP_HOST' => $TEXT['WBMAILER_SMTP_HOST'],
                'TEXT_WBMAILER_PHP' => $TEXT['WBMAILER_PHP'],
                'TEXT_WBMAILER_SMTP' => $TEXT['WBMAILER_SMTP'],
                'TEXT_WBMAILER_SMTP_AUTH' => $TEXT['WBMAILER_SMTP_AUTH'],
                'TEXT_WBMAILER_SMTP_AUTH_NOTICE' => $TEXT['REQUIRED'].' '.$TEXT['WBMAILER_SMTP_AUTH'],
                'TEXT_WBMAILER_SMTP_USERNAME' => $TEXT['WBMAILER_SMTP_USERNAME'],
                'TEXT_WBMAILER_SMTP_PASSWORD' => $TEXT['WBMAILER_SMTP_PASSWORD'],
                'SMTP_AUTH_SELECTED' => ' checked="checked"'
                ));
    if(WBMAILER_ROUTINE == 'phpmail')
    {
        $template->set_var('PHPMAIL_SELECTED', ' checked="checked"');
        $template->set_var('SMTP_VISIBILITY', ' style="display: none;"');
        $template->set_var('SMTP_VISIBILITY_AUTH', '');
        // $template->set_var('SMTP_AUTH_SELECTED', '');
    } elseif(WBMAILER_ROUTINE == 'smtp')
    {
        $template->set_var('SMTPMAIL_SELECTED', ' checked="checked"');
        $template->set_var('SMTP_VISIBILITY', '');
        $template->set_var('SMTP_VISIBILITY_AUTH', '');
    }
/* deprecated
    // Work-out if SMTP authentification should be checked
    if(WBMAILER_SMTP_AUTH)
    {
        $template->set_var('SMTP_AUTH_SELECTED', ' checked="checked"');
        if(WBMAILER_ROUTINE == 'smtp')
        {
            $template->set_var('SMTP_VISIBILITY_AUTH', '');

        } else {
            $template->set_var('SMTP_VISIBILITY_AUTH', ' style="display: none;"');
        }
    } else {
        $template->set_var('SMTP_VISIBILITY_AUTH', ' style="display: none;"');
    }
*/
    // Work-out if intro feature is enabled
    if(INTRO_PAGE)
    {
        $template->set_var('INTRO_PAGE_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('INTRO_PAGE_DISABLED', ' checked="checked"');
    }

    // Work-out if frontend login feature is enabled
    if(FRONTEND_LOGIN)
    {
        $template->set_var('PRIVATE_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('PRIVATE_DISABLED', ' checked="checked"');
    }

    // Work-out if page trash feature is disabled, in-line, or separate
    if(PAGE_TRASH == 'disabled')
    {
        $template->set_var('PAGE_TRASH_DISABLED', ' checked="checked"');
        $template->set_var('DISPLAY_PAGE_TRASH_SEPARATE', 'display: none;');
    } elseif(PAGE_TRASH == 'inline')
    {
        $template->set_var('PAGE_TRASH_INLINE', ' checked="checked"');
        $template->set_var('DISPLAY_PAGE_TRASH_SEPARATE', 'display: none;');
    } elseif(PAGE_TRASH == 'separate')
    {
        $template->set_var('PAGE_TRASH_SEPARATE', ' checked="checked"');
        $template->set_var('DISPLAY_PAGE_TRASH_SEPARATE', 'display: inline;');
    }

    // Work-out if media home folde feature is enabled
    if(HOME_FOLDERS)
    {
        $template->set_var('HOME_FOLDERS_ENABLED', ' checked="checked"');
    } else {
        $template->set_var('HOME_FOLDERS_DISABLED', ' checked="checked"');
    }

    // Insert search select
    if(SEARCH == 'private')
    {
        $template->set_var('PRIVATE_SEARCH', ' selected="selected"');
    } elseif(SEARCH == 'registered') {
        $template->set_var('REGISTERED_SEARCH', ' selected="selected"');
    } elseif(SEARCH == 'none') {
        $template->set_var('NONE_SEARCH', ' selected="selected"');
    }

    // Work-out if 777 permissions are set
    if(STRING_FILE_MODE == '0777' AND STRING_DIR_MODE == '0777')
    {
        $template->set_var('WORLD_WRITEABLE_SELECTED', ' checked="checked"');
    }

    // Work-out which file mode boxes are checked
    if(extract_permission(STRING_FILE_MODE, 'u', 'r'))
    {
        $template->set_var('FILE_U_R_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'u', 'w'))
    {
        $template->set_var('FILE_U_W_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'u', 'e'))
    {
        $template->set_var('FILE_U_E_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'g', 'r'))
    {
        $template->set_var('FILE_G_R_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'g', 'w'))
    {
        $template->set_var('FILE_G_W_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'g', 'e'))
    {
        $template->set_var('FILE_G_E_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'o', 'r'))
    {
        $template->set_var('FILE_O_R_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'o', 'w'))
    {
        $template->set_var('FILE_O_W_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_FILE_MODE, 'o', 'e'))
    {
        $template->set_var('FILE_O_E_CHECKED', ' checked="checked"');
    }
    // Work-out which dir mode boxes are checked
    if(extract_permission(STRING_DIR_MODE, 'u', 'r'))
    {
        $template->set_var('DIR_U_R_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'u', 'w'))
    {
        $template->set_var('DIR_U_W_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'u', 'e'))
    {
        $template->set_var('DIR_U_E_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'g', 'r'))
    {
        $template->set_var('DIR_G_R_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'g', 'w'))
    {
        $template->set_var('DIR_G_W_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'g', 'e'))
    {
        $template->set_var('DIR_G_E_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'o', 'r'))
    {
        $template->set_var('DIR_O_R_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'o', 'w'))
    {
        $template->set_var('DIR_O_W_CHECKED', ' checked="checked"');
    }
    if(extract_permission(STRING_DIR_MODE, 'o', 'e'))
    {
        $template->set_var('DIR_O_E_CHECKED', ' checked="checked"');
    }

    $template->set_var(array(
                        'PAGES_DIRECTORY' => PAGES_DIRECTORY,
                        'MEDIA_DIRECTORY' => MEDIA_DIRECTORY,
                        'PAGE_EXTENSION' => PAGE_EXTENSION,
                        'PAGE_SPACER' => PAGE_SPACER,
                        'TABLE_PREFIX' => TABLE_PREFIX
                     ));

    // Insert Server Email value into template
    $template->set_var('SERVER_EMAIL', SERVER_EMAIL);

    // Insert groups into signup list
    $results = $database->query("SELECT group_id, name FROM ".TABLE_PREFIX."groups WHERE group_id != '1'");
    if($results->numRows() > 0)
    {
        while($group = $results->fetchRow())
        {
            $template->set_var('ID', $group['group_id']);
            $template->set_var('NAME', $group['name']);
            if(FRONTEND_SIGNUP == $group['group_id'])
            {
                $template->set_var('SELECTED', ' selected="selected"');
            } else {
                $template->set_var('SELECTED', '');
            }
            $template->parse('group_list', 'group_list_block', true);
        }
    } else {
        $template->set_var('ID', 'disabled');
        $template->set_var('NAME', $MESSAGE['GROUPS']['NO_GROUPS_FOUND']);
        $template->parse('group_list', 'group_list_block', true);
    }

    // Insert language headings
    $template->set_var(array(
                    'HEADING_GENERAL_SETTINGS' => $HEADING['GENERAL_SETTINGS'],
                    'HEADING_DEFAULT_SETTINGS' => $HEADING['DEFAULT_SETTINGS'],
                    'HEADING_SEARCH_SETTINGS' => $HEADING['SEARCH_SETTINGS'],
                    'HEADING_SERVER_SETTINGS' => $HEADING['SERVER_SETTINGS'],
                    'HEADING_WBMAILER_SETTINGS' => $HEADING['WBMAILER_SETTINGS'],
                    'HEADING_ADMINISTRATION_TOOLS' => $HEADING['ADMINISTRATION_TOOLS']
                    )
            );
    // Insert language text and messages
    $template->set_var(array(
                    'TEXT_WEBSITE_TITLE' => $TEXT['WEBSITE_TITLE'],
                    'TEXT_WEBSITE_DESCRIPTION' => $TEXT['WEBSITE_DESCRIPTION'],
                    'TEXT_WEBSITE_KEYWORDS' => $TEXT['WEBSITE_KEYWORDS'],
                    'TEXT_WEBSITE_HEADER' => $TEXT['WEBSITE_HEADER'],
                    'TEXT_WEBSITE_FOOTER' => $TEXT['WEBSITE_FOOTER'],
                    'TEXT_HEADER' => $TEXT['HEADER'],
                    'TEXT_FOOTER' => $TEXT['FOOTER'],
                    'TEXT_VISIBILITY' => $TEXT['VISIBILITY'],
                    'TEXT_RESULTS_HEADER' => $TEXT['RESULTS_HEADER'],
                    'TEXT_RESULTS_LOOP' => $TEXT['RESULTS_LOOP'],
                    'TEXT_RESULTS_FOOTER' => $TEXT['RESULTS_FOOTER'],
                    'TEXT_NO_RESULTS' => $TEXT['NO_RESULTS'],
                    'TEXT_TEXT' => $TEXT['TEXT'],
                    'TEXT_DEFAULT' => $TEXT['DEFAULT'],
                    'TEXT_LANGUAGE' => $TEXT['LANGUAGE'],
                    'TEXT_TIMEZONE' => $TEXT['TIMEZONE'],
                    'TEXT_CHARSET' => $TEXT['CHARSET'],
                    'TEXT_DATE_FORMAT' => $TEXT['DATE_FORMAT'],
                    'TEXT_TIME_FORMAT' => $TEXT['TIME_FORMAT'],
                    'TEXT_TEMPLATE' => $TEXT['TEMPLATE'],
                    'TEXT_THEME' => $TEXT['THEME'],
                    'TEXT_WYSIWYG_EDITOR' => $TEXT['WYSIWYG_EDITOR'],
                    'TEXT_PAGE_LEVEL_LIMIT' => $TEXT['PAGE_LEVEL_LIMIT'],
                    'TEXT_INTRO_PAGE' => $TEXT['INTRO_PAGE'],
                    'TEXT_FRONTEND' => $TEXT['FRONTEND'],
                    'TEXT_LOGIN' => $TEXT['LOGIN'],
                    'TEXT_REDIRECT_AFTER' => $TEXT['REDIRECT_AFTER'],
                    'TEXT_SIGNUP' => $TEXT['SIGNUP'],
                    'TEXT_PHP_ERROR_LEVEL' => $TEXT['PHP_ERROR_LEVEL'],
                    'TEXT_PAGES_DIRECTORY' => $TEXT['PAGES_DIRECTORY'],
                    'TEXT_MEDIA_DIRECTORY' => $TEXT['MEDIA_DIRECTORY'],
                    'TEXT_PAGE_EXTENSION' => $TEXT['PAGE_EXTENSION'],
                    'TEXT_PAGE_SPACER' => $TEXT['PAGE_SPACER'],
                    'TEXT_RENAME_FILES_ON_UPLOAD' => $TEXT['RENAME_FILES_ON_UPLOAD'],
                    'TEXT_APP_NAME' => $TEXT['APP_NAME'],
                    'TEXT_SESSION_IDENTIFIER' => $TEXT['SESSION_IDENTIFIER'],
                    'TEXT_SEC_ANCHOR' => $TEXT['SEC_ANCHOR'],
                    'TEXT_SERVER_OPERATING_SYSTEM' => $TEXT['SERVER_OPERATING_SYSTEM'],
                    'TEXT_LINUX_UNIX_BASED' => $TEXT['LINUX_UNIX_BASED'],
                    'TEXT_WINDOWS' => $TEXT['WINDOWS'],
                    'TEXT_ADMIN' => $TEXT['ADMIN'],
                    'TEXT_TYPE' => $TEXT['TYPE'],
                    'TEXT_DATABASE' => $TEXT['DATABASE'],
                    'TEXT_HOST' => $TEXT['HOST'],
                    'TEXT_USERNAME' => $TEXT['USERNAME'],
                    'TEXT_PASSWORD' => $TEXT['PASSWORD'],
                    'TEXT_NAME' => $TEXT['NAME'],
                    'TEXT_TABLE_PREFIX' => $TEXT['TABLE_PREFIX'],
                    'TEXT_SAVE' => $TEXT['SAVE'],
                    'TEXT_RESET' => $TEXT['RESET'],
                    'TEXT_CHANGES' => $TEXT['CHANGES'],
                    'TEXT_ENABLED' => $TEXT['ENABLED'],
                    'TEXT_DISABLED' => $TEXT['DISABLED'],
                    'TEXT_MANAGE_SECTIONS' => $HEADING['MANAGE_SECTIONS'],
                    'TEXT_MANAGE' => $TEXT['MANAGE'],
                    'TEXT_SEARCH' => $TEXT['SEARCH'],
                    'TEXT_PUBLIC' => $TEXT['PUBLIC'],
                    'TEXT_PRIVATE' => $TEXT['PRIVATE'],
                    'TEXT_REGISTERED' => $TEXT['REGISTERED'],
                    'TEXT_NONE' => $TEXT['NONE'],
                    'TEXT_FILES' => strtoupper(substr($TEXT['FILES'], 0, 1)).substr($TEXT['FILES'], 1),
                    'TEXT_DIRECTORIES' => $TEXT['DIRECTORIES'],
                    'TEXT_FILESYSTEM_PERMISSIONS' => $TEXT['FILESYSTEM_PERMISSIONS'],
                    'TEXT_USER' => $TEXT['USER'],
                    'TEXT_GROUP' => $TEXT['GROUP'],
                    'TEXT_OTHERS' => $TEXT['OTHERS'],
                    'TEXT_READ' => $TEXT['READ'],
                    'TEXT_WRITE' => $TEXT['WRITE'],
                    'TEXT_EXECUTE' => $TEXT['EXECUTE'],
                    'TEXT_WARN_PAGE_LEAVE' => '',
                    'TEXT_SMART_LOGIN' => $TEXT['SMART_LOGIN'],
                    'TEXT_MULTIPLE_MENUS' => $TEXT['MULTIPLE_MENUS'],
                    'TEXT_HOMEPAGE_REDIRECTION' => $TEXT['HOMEPAGE_REDIRECTION'],
                    'TEXT_SECTION_BLOCKS' => $TEXT['SECTION_BLOCKS'],
                    'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
                    'TEXT_PAGE_TRASH' => $TEXT['PAGE_TRASH'],
                    'TEXT_PAGE_LANGUAGES' => $TEXT['PAGE_LANGUAGES'],
                    'TEXT_INLINE' => $TEXT['INLINE'],
                    'TEXT_SEPARATE' => $TEXT['SEPARATE'],
                    'TEXT_HOME_FOLDERS' => $TEXT['HOME_FOLDERS'],
                    'TEXT_WYSIWYG_STYLE' => $TEXT['WYSIWYG_STYLE'],
                    'TEXT_WORLD_WRITEABLE_FILE_PERMISSIONS' => $TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'],
                    'MODE_SWITCH_WARNING' => $MESSAGE['SETTINGS']['MODE_SWITCH_WARNING'],
                    'WORLD_WRITEABLE_WARNING' => $MESSAGE['SETTINGS']['WORLD_WRITEABLE_WARNING'],
                    'TEXT_MODULE_ORDER' => $TEXT['MODULE_ORDER'],
                    'TEXT_MAX_EXCERPT' => $TEXT['MAX_EXCERPT'],
                    'TEXT_TIME_LIMIT' => $TEXT['TIME_LIMIT']
                    ));

if($is_advanced)
{
    $template->parse('show_page_level_limit', 'show_page_level_limit_block', true);
    $template->parse('show_checkbox_1',       'show_checkbox_1_block', true);
     $template->parse('show_checkbox_2',       'show_checkbox_2_block', true);
    $template->parse('show_checkbox_3',       'show_checkbox_3_block', true);
    $template->parse('show_php_error_level',  'show_php_error_level_block', true);
    $template->parse('show_charset',          'show_charset_block', true);
    $template->parse('show_wysiwyg',          'show_wysiwyg_block', true);
    $template->parse('show_search',           'show_search_block', false);
    $template->parse('show_redirect_timer',   'show_redirect_timer_block', true);
}else {
    $template->set_block('show_page_level_limit', '');
    $template->set_block('show_checkbox_1', '');
    $template->set_block('show_checkbox_2', '');
    $template->set_block('show_checkbox_3', '');
    $template->set_block('show_php_error_level', '');
    $template->set_block('show_charset', '');
    $template->set_block('show_wysiwyg', '');
    $template->set_block('show_search', '');
    $template->set_block('show_redirect_timer', '');
}
if($is_advanced && $admin->get_user_id()=='1')
{
    $template->parse('show_access', 'show_access_block', true);
}else {
    $template->set_block('show_access', '');
}

// Parse template objects output
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

$admin->print_footer();
