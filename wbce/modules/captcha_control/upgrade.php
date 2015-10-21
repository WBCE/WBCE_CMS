<?php
/**
 *
 * @category        modules
 * @package         captcha_control
 * @author          WBCE Project
 * @copyright       Thorn, Luise Hahne, Norbert Heimsath
 * @license         GPLv2 or any later
 */

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);


// get CAPTCHA and ASP settings from old table
$sql = 'SELECT * FROM `' . TABLE_PREFIX . 'mod_captcha_control`';
if (($get_settings = $database->query($sql)) && ($setting = $get_settings->fetchRow(MYSQLI_ASSOC))) {
    Settings::Set ("enabled_captcha", (($setting['enabled_captcha'] == '1') ? true : false));
    Settings::Set ("enabled_asp", (($setting['enabled_asp'] == '1') ? true : false));
    Settings::Set ("captcha_type", $setting['captcha_type']);
    Settings::Set ("asp_session_min_age", $setting['asp_session_min_age']);
    Settings::Set ("asp_view_min_age", $setting['asp_view_min_age']);
    Settings::Set ("asp_input_min_age", $setting['asp_input_min_age']);
    Settings::Set ("ct_text", $setting['ct_text']);
} else {
    die('CAPTCHA-Settings not found');
}


// Delete old tabe construct
$table = TABLE_PREFIX .'mod_captcha_control';
if(!$database->query("DROP TABLE `$table`")) {
    $msg = $database->get_error();
}


