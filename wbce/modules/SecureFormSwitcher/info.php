<?php
/**
 * @category        modules
 * @package         More Security Settings (SecureFormSwitcher)
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */

$module_directory = 'SecureFormSwitcher';
$module_name = 'More Security Settings';
$module_function = 'tool';
$module_version = '1.3.4';
$module_platform = '1.1.0';
$module_author = 'Complete rewrite of Secure Form Switcher by  Norbert Heimsath(heimsath.org)';
$module_license = 'GPLv2 or any later';
$module_description = 'This tool provides some additional security settings';
$module_icon = 'fa fa-lock';
$module_level = 'core';

/**
 * Version history
 *
 * 1.3.4 - use Settings::set("wb_secform_secret", bin2hex(random_bytes(12)));
 *             to avoid having a known secret token in use on live installations
 *         adopt new PSR-4 method names of class Settings
 * 
 * 1.3.3 - cs fixed files
 *
 * 1.3.2 - Add module_level core status
 *
 * 1.3.1 - Add Add Admintool Icon
 *
 **/
