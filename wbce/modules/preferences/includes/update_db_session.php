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

//Update DB and Session

$sql  = 'UPDATE `'.TABLE_PREFIX.'users` ';
$sql .= 'SET `display_name`=\''.$displayName.'\', ';
if(isset($hashNew) and !empty($hashNew)) {
    $sql .=     '`password`=\''.$hashNew.'\', ';
}
if($email != '') {
    $sql .=     '`email`=\''.$email.'\', ';
}
$sql .=     '`language`=\''.$language.'\', ';
$sql .=     '`timezone`=\''.$timezone.'\', ';
$sql .=     '`date_format`=\''.$date_format.'\', ';
$sql .=     '`time_format`=\''.$time_format.'\' ';
$sql .= 'WHERE `user_id`='.(int)$admin->get_user_id();
//echo $sql;
if( $database->query($sql) ) {


    $message[]="Successfully updated DB , now updating session!" ;

    // update successfull, takeover values into the session
    $_SESSION['DISPLAY_NAME'] = $displayName;
    $_SESSION['LANGUAGE'] = $language; 
    $_SESSION['TIMEZONE'] = $timezone; 
    $_SESSION['EMAIL'] = $email;
    echo $date_format;
    // Update date format
    if($date_format != '') {
        $_SESSION['DATE_FORMAT'] = $date_format;
        if(isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) { unset($_SESSION['USE_DEFAULT_DATE_FORMAT']); }
    } else {
        $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
        if(isset($_SESSION['DATE_FORMAT'])) { unset($_SESSION['DATE_FORMAT']); }
    }
    
    // Update time format
    if($time_format != '') {
        $_SESSION['TIME_FORMAT'] = $time_format;
        if(isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) { unset($_SESSION['USE_DEFAULT_TIME_FORMAT']); }
    } else {
        $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
        if(isset($_SESSION['TIME_FORMAT'])) { unset($_SESSION['TIME_FORMAT']); }
    }
} else {
    $error[] = 'invalid database UPDATE call in '.__FILE__.' before line '.__LINE__;
}