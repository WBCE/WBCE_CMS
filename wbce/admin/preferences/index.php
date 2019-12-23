<?php
/**
 *
 * @category        admin
 * @package         preferences
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: index.php 5 2015-04-27 08:02:19Z luisehahne $
 * @filesource      $HeadURL: https://localhost:8443/svn/wb283Sp4/SP4/branches/wb/admin/preferences/index.php $
 * @lastmodified    $Date: 2015-04-27 10:02:19 +0200 (Mo, 27. Apr 2015) $
 *
 */

// prevent this file from being accessed directly
//if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
//Workaround if this is first page (WBAdmin in use)

// put all inside a function to prevent global vars
function build_page( &$admin, &$database )
{
    global $HEADING, $TEXT;
    include_once WB_PATH.'/framework/functions-utf8.php';
    
    // Setup template object, parse vars to it, then parse it
    // Create new template object
    $oTemplate = new Template(dirname($admin->correct_theme_source('preferences.htt')));
    $oTemplate->set_file( 'page', 'preferences.htt' );
    $oTemplate->set_block( 'page', 'main_block', 'main' );
    
    // read user-info from table users and assign it to template
    $sSql  = 'SELECT `display_name`, `username`, `email` FROM `{TP}users` WHERE `user_id` = %s';
    if ($res_user = $database->query(sprintf($sSql, (int)$admin->get_user_id()))){
        if ($rec_user = $res_user->fetchRow(MYSQL_ASSOC) ) {
            $oTemplate->set_var(
                array(
                    'DISPLAY_NAME' => $rec_user['display_name'],
                    'USERNAME'     => $rec_user['username'],
                    'EMAIL'        => $rec_user['email'],
                    'ADMIN_URL'    => ADMIN_URL               
                )
            ); 
        }
    }
    
    // read available languages from table addons and assign it to the template
    $sSql  = "SELECT * FROM `{TP}addons` WHERE `type` = 'language' ORDER BY `directory`";
    if ($res_lang = $database->query($sSql)) {
        $oTemplate->set_block('main_block', 'language_list_block', 'language_list');
        while($rec_lang = $res_lang->fetchRow(MYSQL_ASSOC)){
            $langIcons = (empty($rec_lang['directory'])) ? 'none' : strtolower($rec_lang['directory']);
            $oTemplate->set_var(
                array(
                    'CODE'     => $rec_lang['directory'],
                    'NAME'     => '['.$rec_lang['directory'].'] '.$rec_lang['name'],
                    'FLAG'     => WB_URL.'/languages/'.$langIcons,
                    'SELECTED' => LANGUAGE == $rec_lang['directory'] ? ' selected="selected"' : ''               
                )
            ); 
            $oTemplate->parse('language_list', 'language_list_block', true);
        }
    }
    
    // Insert default timezone values
    $user_time = true;
    include_once ADMIN_PATH.'/interface/timezones.php';
    $oTemplate->set_block('main_block', 'timezone_list_block', 'timezone_list');
    foreach($TIMEZONES as $hour_offset => $title){
        $oTemplate->set_var(
            array(
                'VALUE'     => $hour_offset,
                'NAME'      => $title,
                'SELECTED'  => $admin->get_timezone() == ($hour_offset * 3600) ? ' selected="selected"' : ''
            )
        );
        $oTemplate->parse('timezone_list', 'timezone_list_block', true);
    }
    
    // Insert date format list
    include_once ADMIN_PATH.'/interface/date_formats.php';
    $oTemplate->set_block('main_block', 'date_format_list_block', 'date_format_list');
    foreach ($DATE_FORMATS as $format => $title )
    {
        $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
        $oTemplate->set_var( 'VALUE', ($format != 'system_default' ? $format : 'system_default') );
        $oTemplate->set_var( 'NAME',  $title );
        if ((DATE_FORMAT == $format && !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) ||
            ('system_default' == $format && isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) )
        {
            $oTemplate->set_var('SELECTED', ' selected="selected"');
        }else {
            $oTemplate->set_var('SELECTED', '');
        }
        $oTemplate->parse('date_format_list', 'date_format_list_block', true);
    }
    
    // Insert time format list
    include_once ADMIN_PATH.'/interface/time_formats.php';
    $oTemplate->set_block('main_block', 'time_format_list_block', 'time_format_list');
    foreach ($TIME_FORMATS as $format => $title )
    {
        $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
        $oTemplate->set_var('VALUE', $format != 'system_default' ? $format : 'system_default' );
        $oTemplate->set_var('NAME',  $title);
        if ((TIME_FORMAT == $format && !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) ||
            ('system_default' == $format && isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) )
        {
            $oTemplate->set_var('SELECTED', ' selected="selected"');
        } else {
            $oTemplate->set_var('SELECTED', '');
        }
        $oTemplate->parse('time_format_list', 'time_format_list_block', true);
    }

    $oTemplate->set_var(
        array( 
            // assign systemvars to template
            'ADMIN_URL'          => ADMIN_URL,
            'WB_URL'             => WB_URL,
            'THEME_URL'          => THEME_URL,
            'ACTION_URL'         => ADMIN_URL.'/preferences/save.php',
            'FTAN'               => $admin->getFTAN(),
            'FORM_NAME'          => 'preferences_save',
            'INPUT_NEW_PASSWORD' => $admin->passwordField('new_password_1'),

            // assign language vars
            'HEADING_MY_SETTINGS'        => $HEADING['MY_SETTINGS'],
            'HEADING_MY_EMAIL'           => $HEADING['MY_EMAIL'],
            'HEADING_MY_PASSWORD'        => $HEADING['MY_PASSWORD'],
            'TEXT_SAVE'                  => $TEXT['SAVE'],
            'TEXT_RESET'                 => $TEXT['RESET'],
            'TEXT_DISPLAY_NAME'          => $TEXT['DISPLAY_NAME'],
            'TEXT_USERNAME'              => $TEXT['USERNAME'],
            'TEXT_EMAIL'                 => $TEXT['EMAIL'],
            'TEXT_LANGUAGE'              => $TEXT['LANGUAGE'],
            'TEXT_TIMEZONE'              => $TEXT['TIMEZONE'],
            'TEXT_DATE_FORMAT'           => $TEXT['DATE_FORMAT'],
            'TEXT_TIME_FORMAT'           => $TEXT['TIME_FORMAT'],
            'TEXT_CURRENT_PASSWORD'      => $TEXT['CURRENT_PASSWORD'],
            'TEXT_NEW_PASSWORD'          => $TEXT['NEW_PASSWORD'],
            'TEXT_RETYPE_NEW_PASSWORD'   => $TEXT['RETYPE_NEW_PASSWORD'],
            'TEXT_NEW_PASSWORD'          => $TEXT['NEW_PASSWORD'],
            'TEXT_RETYPE_NEW_PASSWORD'   => $TEXT['RETYPE_NEW_PASSWORD'],
            'TEXT_NEED_CURRENT_PASSWORD' => $TEXT['NEED_CURRENT_PASSWORD'],
            'EMPTY_STRING'               => ''
        )
    );
    
    // Parse template for preferences form
    $oTemplate->parse('main', 'main_block', false);
    $output = $oTemplate->finish($oTemplate->parse('output', 'page'));
    return $output;
}

// test if valid $admin-object already exists (bit complicated about PHP4 Compatibility)
if (!(isset($admin) && is_object($admin) && (get_class($admin) == 'admin')) )
{
    require '../../config.php';
    require_once WB_PATH.'/framework/class.admin.php';
    $admin = new admin('Preferences');
}
echo build_page($admin, $database);
$admin->print_footer();
