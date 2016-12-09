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

//Validate and sanitize the input

// Display Name
if ($admin->get_post("display_name")) $displayName = $admin->get_post("display_name");
else                                  $displayName = $userDisplayName; 
$displayName = trim(strip_tags($displayName));
$displayName = preg_replace ("[^\w\ \-\_]", "",  $displayName);
$displayName = $database->escapeString($displayName);

// check that display_name is unique in whoole system (prevents from User-faking)
$sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'users` ';
$sql .= 'WHERE `user_id` <> '.(int)$admin->get_user_id().' AND `display_name` LIKE "'.$displayName.'"';
if( $database->get_one($sql) > 0 ){ $error[] = $MESSAGE['USERS_USERNAME_TAKEN']; }

//language
$language         = strtoupper($admin->get_post('language'));
$language         = (preg_match('/^[A-Z]{2}$/', $language) ? $language : DEFAULT_LANGUAGE);
$language         = $database->escapeString($language);

// timezone must be between -12 and +13  or -20 as system_default
$timezone         = $admin->get_post('timezone');
$timezone         = (is_numeric($timezone) ? $timezone : -20);
$timezone         = ( ($timezone >= -12 && $timezone <= 13) ? $timezone : -20 ) * 3600;
$timezone         =$database->escapeString($timezone);

// date_format must be a key from /interface/date_formats
$date_format      = $admin->get_post('date_format');
$date_format_key  = str_replace(' ', '|', $date_format);
$user_time = true;
$date_format = (array_key_exists($date_format_key, $DATE_FORMATS) ? $date_format : 'system_default');
$date_format = ($date_format == 'system_default' ? '' : $date_format);
$date_format = $database->escapeString($date_format);

// time_format must be a key from /interface/time_formats    
$time_format      = $admin->get_post('time_format');
$time_format_key  = str_replace(' ', '|', $time_format);
$user_time = true;
$time_format = (array_key_exists($time_format_key, $TIME_FORMATS) ? $time_format : 'system_default');
$time_format = ($time_format == 'system_default' ? '' : $time_format);
$time_format =$database->escapeString($time_format);

// email should be validatet by core
$email = trim( $admin->get_post('email') == null ? '' : $admin->get_post('email') );
if( !$admin->validate_email($email) )
{
    $email = '';
    $error[] = $MESSAGE['USERS_INVALID_EMAIL'];
}else {
    if($email != '') {
        $email=$database->escapeString($email);
        
    // check that email is unique in whoole system
        $sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'users` ';
        $sql .= 'WHERE `user_id` <> '.(int)$admin->get_user_id().' AND `email` LIKE "'.$email.'"';
        $dbcount=$database->get_one($sql);
        if( $dbcount > 0 ){ $error[] = $MESSAGE['USERS_EMAIL_TAKEN']; }
    }
}

// receive password vars and calculate needed action
// pasword action only taken if new_password_1 is set at all
if ($admin->get_post('new_password_1')) {
    $passwordNew = $admin->get_post('new_password_1');
    
    if ($admin->get_post('new_password_2')!=$passwordNew) $error[]=$TEXT['NEW_PASSWORD']." : ".$MESSAGE['USERS_PASSWORD_MISMATCH'];
    
    $result= $Preferences->CheckPass($passwordNew);
    if ($result) $error[]=$TEXT['NEW_PASSWORD']." : ".$result;
    
    // current password is set
    if ($admin->get_post('current_password')){ 
        $passwordOld=$admin->get_post('current_password');
        
        $result= $Preferences->CheckPass($passwordOld, $admin->get_user_id());
        if ($result) $error[]=$TEXT['CURRENT_PASSWORD']." : ".$result;
        
    }
    else  $error[]=$TEXT['CURRENT_PASSWORD']." : ".$TEXT['NEED_CURRENT_PASSWORD'];  
    
    if (empty($error)) {
        // using md5 als Login class does not use CheckPass() right now 
        $hashNew= $Preferences->HashPass($passwordNew, "md5");
    }
}


//echo "<pre>"; print_r($error); echo "</pre>";








