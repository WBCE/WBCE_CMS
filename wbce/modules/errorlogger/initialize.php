<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga · www.dev4me.com (https://dev4me.com/)
 * @author          Christian M. Stefan  (https://www.wbEasy.de)
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.7.x
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
