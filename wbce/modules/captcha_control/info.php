<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

$core = true;

$module_directory   = 'captcha_control';
$module_name        = 'Captcha and Advanced-Spam-Protection (ASP) Control';
$module_function    = 'tool,initialize';
$module_version     = '3.0.1';
$module_platform    = '1.7.0';
$module_author      = 'Thorn, Luise Hahne, Norbert Heimsath, Christian M. Stefan';
$module_license     = 'GNU GPL2';
$module_description = 'Configuration and customization of the ALTCHA-Captcha';
$module_icon        = 'fa fa-shield';

/**
 * Version history
 * 
 * 3.0.1 - Implementation of customization means for the ALTCHA-Captcha skin
 *         (Christian M. Stefan)
 * 
 * 3.0.0 - Introduction of ALTCHA-Captcha as the only captcha mode available
 *         Intoduction of class Captcha, globally available via the initialize.php
 *         (Christian M. Stefan)
 * 
 * 2.0.6 - set $core var, remove deprecated $module_level var
 * 
 * 2.0.5 - php 8.1 fixes
 * 
 * 2.0.4 - cs fixed files
 *
 * 2.0.3 - Add module_level core status
 *       - Update module_platform 
 * 
 * 2.0.2 - Add Admintool Icon
 *
 * 2.0.1 - Add module_name translation
 *
 **/
