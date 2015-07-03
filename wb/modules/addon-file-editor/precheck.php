<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file contains the installation checks executed prior to the installation of the module
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     cwsoft-addon-file-editor
 * @author      cwsoft (http://cwsoft.de)
 * @copyright   cwsoft
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */

// prevent this file from being accessed directly
if (defined('WB_PATH') == false) {
	exit("Cannot access this file directly");
}

/**
 * Check if minimum requirements for this module are fullfilled
 * Only checked in Website Baker 2.8 or higher
 */
$PRECHECK = array( 
	// requires WebsiteBaker 2.8.x (from 2.8.1 onwards)
	'WB_VERSION' => array('VERSION' => '2.8.1', 'OPERATOR' => '>='), 
	// make sure PHP version is 5.2.2 or higher
	'PHP_VERSION' => array('VERSION' => '5.2.2', 'OPERATOR' => '>=')
);
