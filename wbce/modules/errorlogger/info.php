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

// Must include code to stop this file being access directly
if (defined('WB_PATH') == false) {
    die("Cannot access this file directly");
}

$module_directory   = 'errorlogger';
$module_name        = 'Errorlog viewer';
$module_function 	= 'snippet';
$module_version     = '1.1.4.1';
$module_platform 	= '2.8';
if(defined('WBCE_VERSION')) {
	$module_function    = 'tool,preinit,initialize';
	$module_platform = '1.3.0';
}
$module_author      = 'Ruud Eisinga - Dev4me';
$module_license	    = 'GNU General Public License';
$module_description = 'Catch PHP warnings and errors into a logfile and view them using this tool.';
$module_icon        = 'fa fa-tasks';
//$module_level       = 'core';

/** 
 * Note: IN WB this module will only install a snippet that modifies the errorhandler.
 * Due to the way WB activates the snippets, some errors might be still handled by the core WB errorhandlers.
 * The only effect will be that there is no caller url added to the log for those errors.
*/

/**
 * DEVELOPMENT HISTORY (Change Log):
 *
 * v.1.1.4.1 2022-07-30 ruud / dev4me
 *		[!] Fixed a deprecated notice on PHP8.1
 *
 * v.1.1.4 2022-07-27 ruud / dev4me
 *		[+] added the called url when an error is detected. Helps in locating and fixing the error.
 *		[+] The module can now also be installed as snippet in WB. Note that errors in other snippets might not add the extra info.
 *		[!] Fixed some more errors in the table view
 *
 * v.1.1.3 2022-01-04 ruud / dev4me
 *		[!] fix for notices that should be suppressed by @ in php8+
 *		[!] fix for tableview generating errors on some loglines
 *		[!] fix for deleted /var/logs/ directory
 *
 * v.1.1.2 2021-09-05 stefanek
 *      [!] fix for issue 508 (empty log creates issues itself)
 *
 * v.1.1.1 2021-05-29 Colinax
 *      [+] add upgrade.php
 *      [c] cs fixed files
 *
 * v.1.1.0 2020-07-23 Christian M. Stefan (Stefanek)
 *      [+] Implementation of Table View
 *      [c] changes to CSS file adding styles for the Table View
 *
 */
