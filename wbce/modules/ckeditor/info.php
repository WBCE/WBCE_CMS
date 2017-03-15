<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$module_directory   = 'ckeditor';
$module_name        = 'CKEditor';
$module_function    = 'WYSIWYG';
$module_version     = '4.6.2.1';
$module_platform    = '1.0.0';
$module_author      = 'diverse, cwsoft, Norhei, Colinax';
$module_license		= '<a target="_blank" href="http://www.gnu.org/licenses/lgpl.html">LGPL</a>';
$module_description = 'includes CKEditor 4.6.2 Standard Package and some other Plugins, CKE allows editing content and can be integrated in frontend and backend modules.';
$module_home        = 'http://www.wbce.org';