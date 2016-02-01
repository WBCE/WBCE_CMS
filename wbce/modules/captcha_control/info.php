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

$module_directory 	= 'captcha_control';
$module_name        = 'Captcha and Advanced-Spam-Protection (ASP) Control';
$module_function    = 'tool';
$module_version     = '2.0.1';
$module_platform    = '1.1.0';
$module_author      = 'Thorn, Luise Hahne, Norbert Heimsath';
$module_license     = 'GPLv2';
$module_description = 'Admin-Tool to control CAPTCHA and ASP';
$module_icon        = 'fa fa-check-circle';


