<?php
/**
 *
 * @category       modules
 * @package        ckeditor
 * @authors        WebsiteBaker Project, Michael Tenschert, Dietrich Roland Pehlke
 * @copyright      2009-2011, Website Baker Org. e.V.
 * @link           http://www.websitebaker2.org/
 * @license        http://www.gnu.org/licenses/gpl.html
 * @platform       WebsiteBaker 2.8.2
 * @requirements   PHP 5.2.2 and higher
 * @version        $Id: uninstall.php 20 2012-02-13 10:14:53Z Luisehahne $
 * @filesource     $HeadURL: http://webdesign:8080/svn/ckeditor-dev/branches/uninstall.php $
 * @lastmodified   $Date: 2012-02-13 11:14:53 +0100 (Mo, 13. Feb 2012) $
 *
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */
