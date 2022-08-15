<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga - www.dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.4+ / WB2.10+
 * @version         1.1.4.1
 * @lastmodified    July 30, 2022
 *
 */

/**
 * preinit.php is used to activate the errorhandler
 *
 * initialize.php is used to set the errorlevel to E_ALL regardless the WB setting
 *
 */

ini_set("display_errors", "off");
ini_set('log_errors', 0);
ini_set('error_reporting', E_ALL);	// Same as error_reporting(E_ALL);
