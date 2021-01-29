<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.21.0
 * @lastmodified    July 22, 2019
 *
 * v0.23 - fix for missing captcha in linked forms					  (Ruud)
 * v0.22 - MYSQL_ASSOC -> MYSQLI_ASSOC								  (Bernd)
 * v0.21 - Move 'modify template' to separate page.                   (Stefanek)
 * v0.20 - Handles a bug with save file reported by user colinax.     (Stefanek)
 * v0.19 - Implement AdminTool functionality (WBCE CMS only).         (Stefanek)
 * v0.18 - Small correction in Ajax delete function.                  (Stefanek)
 *         Added support for Bootstrap alerts.                        (Stefanek)
 * v0.17 - Ajax integration to "delete" messages without page reload. (Stefanek)
 * v0.16 - Ajax integration to "load more" messages in the backend.   (Stefanek)
 * v0.15 - fixed bug when quotes are used in values
 *       - fixed compatibility issue with WBCE 1.4
 * v0.14 - fixed ajax file-upload issue IOS
 * v0.13 - fixed ajax caching problem for IOS
 * 	       added referer for email-template. use {REFERER}
 * v0.12 - added sender_email, session-data storing, removed autoTLS disable
 * v0.11 - added ajax handling
 *
 */

$module_directory = 'miniform';
$module_name = 'MiniForm';
$module_function = 'page';
$module_version = '0.23.0';
$module_platform = '1.4.x';
$module_author = 'Ruud / Dev4me';
$module_license = 'GNU General Public License';
$module_description = 'This module allows you to create a quick and simple form without complicated settings.';

if(defined('WBCE_VERSION')){
	// Additional vars for WBCE CMS complementing the Page Module with AdminTool functionality
	$module_function = 'page, tool';
	$tool_name = 'MiniForm Overview';
	$tool_description = 'View Settings and Entries of all MiniForm Sections.';
}
