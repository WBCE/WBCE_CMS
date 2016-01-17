<?php
/**
 *
 * @category       modules
 * @package        ckeditor
 * @authors        WebsiteBaker Project, Michael Tenschert, Dietrich Roland Pehlke, Dietmar Wöllbrink
 * @copyright      WebsiteBaker Org. e.V.
 * @link           http://websitebaker.org/
 * @license        http://www.gnu.org/licenses/gpl.html
 * @platform       WebsiteBaker 2.8.3
 * @requirements   PHP 5.3.6 and higher
 * @version        $Id: info.php 137 2012-03-17 23:29:07Z Luisehahne $
 * @filesource     $HeadURL: http://webdesign:8080/svn/ckeditor-dev/branches/info.php $
 *
 *
 *
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

    require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
    throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$module_directory   = 'ckeditor';
$module_name        = 'CKEditor v4.5.5';
$module_function    = 'WYSIWYG';
$module_version     = '4.5.5';
$module_platform    = '2.8.3 SP5';
$module_author      = 'Michael Tenschert, Dietrich Roland Pehlke, erpe, WebBird, Marmot, Luisehahne';
$module_license     = '<a target="_blank" href="http://www.gnu.org/licenses/lgpl.html">LGPL</a>';
$module_description = 'includes CKEditor 4.5.5, CKE allows editing content and can be integrated in frontend and backend modules.';
