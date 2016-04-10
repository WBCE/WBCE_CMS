<?php
/**
 * @category        modules
 * @package         preferences
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2
 */
 
// no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Load utf functions
include_once(WB_PATH.'/framework/functions-utf8.php');

// read user-info from table users and assign it to template
// read em from DB as session data possibly not changed after saving this form!!
$sql  = 'SELECT `display_name`, `username`, `email` FROM `'.TABLE_PREFIX.'users` ';
$sql .= 'WHERE `user_id` = '.(int)$admin->get_user_id();
if( $res_user = $database->query($sql) )
{
    if( $rec_user = $res_user->fetchRow() )
    {
        $userDisplayName = $rec_user['display_name']; 
        $userName        = $rec_user['username'];
        $userMail        = $rec_user['email'];
    }
}

// read available languages from table addons and assign it to the template
$languageData=array();
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'addons` ';
$sql .= 'WHERE `type` = \'language\' ORDER BY `directory`';
if( $res_lang = $database->query($sql) )
{
    while( $rec_lang = $res_lang->fetchRow() )
    {
        $ld=array(
            "code"     => $rec_lang['directory'],
            "name"     => $rec_lang['name'],
            "selected" => ($_SESSION['LANGUAGE'] == $rec_lang['directory'] ? ' selected="selected"' : '')                    
        );
        $languageData[]=$ld;
    }
}    

// Fetch default timezone values
$timezoneData=array();
$user_time = true;
// fetch the actual data array
include_once( ADMIN_PATH.'/interface/timezones.php' );

foreach( $TIMEZONES AS $hour_offset => $title ) {
    $td=array(
        "value"    => $hour_offset,
        "name"     => $title, 
        "selected" => ($admin->get_timezone() == ($hour_offset * 3600) ? ' selected="selected"' : '')
    );
    $timezoneData[]=$td;
}   

// Insert date format list
$dateData=array();
// fetch the actual data array
include_once( ADMIN_PATH.'/interface/date_formats.php' );
foreach( $DATE_FORMATS AS $format => $title )
{
    $dd=array();
    
    $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
    
    $dd['value'] =($format != 'system_default' ? $format : 'system_default');
    $dd['name'] =  $title;
    
    if( (!isset($_SESSION['USE_DEFAULT_DATE_FORMAT']) && $_SESSION['DATE_FORMAT'] == $format) ||
        ('system_default' == $format && isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) )
    {
        $dd['selected'] = ' selected="selected"';
    } else {
        $dd['selected'] = '';
    }
    
    $dateData[]=$dd;
}
    
    
// Insert time format list
$timeformData=array();
// fetch the actual data array
include_once( ADMIN_PATH.'/interface/time_formats.php' );
foreach( $TIME_FORMATS AS $format => $title )
{
    $td=array();
    
    $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)

    $td['value'] = ($format != 'system_default' ? $format : 'system_default' );
    $td['name'] =  $title;
    
    if( ( !isset($_SESSION['USE_DEFAULT_TIME_FORMAT']) && $_SESSION['TIME_FORMAT'] == $format) ||
        ('system_default' == $format && isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) )
    {
        $td['selected'] = ' selected="selected"';
    } else {
        $td['selected'] = '';
    }
    $timeformData[]=$td;
}
    
    





  
