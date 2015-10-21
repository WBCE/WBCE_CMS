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

// Default settings
Settings::Del ("enabled_captcha");
Settings::Del ("enabled_asp");
Settings::Del ("captcha_type");
Settings::Del ("asp_session_min_age");
Settings::Del ("asp_view_min_age");
Settings::Del ("asp_input_min_age");
Settings::Del ("ct_text");

$table = TABLE_PREFIX .'mod_captcha_control';
$database->query("DROP TABLE `$table`");
