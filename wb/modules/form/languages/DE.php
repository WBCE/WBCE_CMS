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
 * @version         $Id: DE.php 1595 2012-02-03 23:42:18Z Luisehahne $
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

//Modulbeschreibung
$module_description = 'Mit diesem Modul k&ouml;nnen sie ein beliebiges Formular f&uuml;r ihre Seite erzeugen';

//Variablen fuer backend Texte
$MOD_FORM['SETTINGS'] = 'Formular Einstellungen';
$MOD_FORM['CONFIRM'] = 'Best&auml;tigung';
$MOD_FORM['SUBMIT_FORM'] = 'Absenden';
$MOD_FORM['EMAIL_SUBJECT'] = 'Erhalten einer Nachricht von '.WEBSITE_TITLE;
$MOD_FORM['SUCCESS_EMAIL_SUBJECT'] = 'Sie haben ein Forumlar an '.WEBSITE_TITLE.' gesendet';

$MOD_FORM['SUCCESS_EMAIL_TEXT'] = 'Vielen Dank f&uuml;r die &Uuml;bermittlung Ihrer Nachricht an '.WEBSITE_TITLE.'';
$MOD_FORM['SUCCESS_EMAIL_TEXT_GENERATED'] = PHP_EOL.PHP_EOL.PHP_EOL
.'*************************************************************'.PHP_EOL
.'Dies ist eine automatisch generierte E-Mail. Die Absenderadresse dieser E-Mail'.PHP_EOL
.'ist nur zum Versand, und nicht zum Empfang von Nachrichten eingerichtet!'.PHP_EOL
.'Falls Sie diese E-Mail versehentlich erhalten haben, setzen Sie sich bitte'.PHP_EOL
.'mit uns in Verbindung und l&ouml;schen diese Nachricht von Ihrem Computer.'.PHP_EOL
.'**************************************************************'.PHP_EOL;

$MOD_FORM['FROM'] = 'Absender';
$MOD_FORM['TO'] = 'Empf&auml;nger';

$MOD_FORM['EXCESS_SUBMISSIONS'] = 'Dieses Formular wurde zu oft aufgerufen. Bitte versuchen Sie es in einer Stunde noch einmal.';
$MOD_FORM['INCORRECT_CAPTCHA'] = 'Die eingegebene Pr&uuml;fziffer stimmt nicht &uuml;berein. Wenn Sie Probleme mit dem Lesen der Pr&uuml;fziffer haben, bitte schreiben Sie eine E-Mail an den <a href="mailto:{{webmaster_email}}">Webmaster</a>';
$MOD_FORM['REQUIRED_FIELDS'] = 'Bitte folgende Angaben erg&auml;nzen';
