<?php
/**
 *
 * @category        module
 * @package         Form
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: RU.php 1595 2012-02-03 23:42:18Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/modules/form/view.php $
 * @lastmodified    $Date: 2011-12-31 16:03:03 +0100 (Sa, 31. Dez 2011) $
 * @description
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
	die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

//Modul Description
$module_description = '&#1052;&#1086;&#1076;&#1091;&#1083;&#1100; &#1087;&#1086;&#1079;&#1074;&#1086;&#1083;&#1103;&#1077;&#1090; &#1089;&#1086;&#1079;&#1076;&#1072;&#1074;&#1072;&#1090;&#1100; &#1088;&#1072;&#1079;&#1083;&#1080;&#1095;&#1085;&#1099;&#1077; &#1085;&#1072;&#1089;&#1090;&#1088;&#1072;&#1080;&#1074;&#1072;&#1077;&#1084;&#1099;&#1077; &#1092;&#1086;&#1088;&#1084;&#1099;, &#1085;&#1072;&#1087;&#1088;&#1080;&#1084;&#1077;&#1088; &#1092;&#1086;&#1088;&#1084;&#1099; &#1086;&#1073;&#1088;&#1072;&#1090;&#1085;&#1086;&#1081; &#1089;&#1074;&#1103;&#1079;&#1080;. Rudolph Lartey &#1087;&#1086;&#1084;&#1086;&#1075; &#1091;&#1083;&#1091;&#1095;&#1096;&#1080;&#1090;&#1100; &#1076;&#1072;&#1085;&#1085;&#1099;&#1081; &#1084;&#1086;&#1076;&#1091;&#1083;&#1100;.';

//Variables for the  backend
$MOD_FORM['SETTINGS'] = 'Form Settings';
$MOD_FORM['CONFIRM'] = 'Confirmation';
$MOD_FORM['SUBMIT_FORM'] = 'Submit';
$MOD_FORM['EMAIL_SUBJECT'] = 'Delivering a message from '.WEBSITE_TITLE;
$MOD_FORM['SUCCESS_EMAIL_SUBJECT'] = 'You have submitted a message to '.WEBSITE_TITLE;

$MOD_FORM['SUCCESS_EMAIL_TEXT'] = 'Thank you for sending your message to '.WEBSITE_TITLE;
$MOD_FORM['SUCCESS_EMAIL_TEXT_GENERATED'] = PHP_EOL.PHP_EOL.PHP_EOL
.'****************************************************************************'.PHP_EOL
.'This is an automatically generated e-mail. The sender\'s address of this e-mail'.PHP_EOL
.'is furnished only for dispatch, not to receive messages!'.PHP_EOL
.'If you have received this e-mail by mistake, please contact us and delete this message'.PHP_EOL
.'****************************************************************************'.PHP_EOL;

$MOD_FORM['FROM'] = 'Sender';
$MOD_FORM['TO'] = 'Recipient';

$MOD_FORM['EXCESS_SUBMISSIONS'] = 'Sorry, this form has been submitted too many times so far this hour. Please retry in the next hour.';
$MOD_FORM['INCORRECT_CAPTCHA'] = 'The verification number (also known as Captcha) that you entered is incorrect. If you are having problems reading the Captcha, please email to the <a href="mailto:{{webmaster_email}}">webmaster</a>';
$MOD_FORM['REQUIRED_FIELDS'] = 'You must enter details for the following fields';
