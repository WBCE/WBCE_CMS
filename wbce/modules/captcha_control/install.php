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
Settings::Set ("enabled_captcha", true);
Settings::Set ("enabled_asp", true);
Settings::Set ("captcha_type", "calc_text");
Settings::Set ("asp_session_min_age", "20");
Settings::Set ("asp_view_min_age", "10");
Settings::Set ("asp_input_min_age", "5");
Settings::Set ("ct_text", "");



