<?php
/**
 *
 * @category        admin
 * @package         interface
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: charsets.php 1374 2011-01-10 12:21:47Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/interface/charsets.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10. Jan 2011) $
 *
 * Charset list file
 * This file is used to generate a list of charsets for the user to select
 *
 */

if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

// Create array
$CHARSETS = array();
$CHARSETS['utf-8'] = 'Unicode (utf-8)';
$CHARSETS['iso-8859-1'] = 'Latin-1 Western European (iso-8859-1)';
$CHARSETS['iso-8859-2'] = 'Latin-2 Central European (iso-8859-2)';
$CHARSETS['iso-8859-3'] = 'Latin-3 Southern European (iso-8859-3)';
$CHARSETS['iso-8859-4'] = 'Latin-4 Baltic (iso-8859-4)';
$CHARSETS['iso-8859-5'] = 'Cyrillic (iso-8859-5)';
$CHARSETS['iso-8859-6'] = 'Arabic (iso-8859-6)';
$CHARSETS['iso-8859-7'] = 'Greek (iso-8859-7)';
$CHARSETS['iso-8859-8'] = 'Hebrew (iso-8859-8)';
$CHARSETS['iso-8859-9'] = 'Latin-5 Turkish (iso-8859-9)';
$CHARSETS['iso-8859-10'] = 'Latin-6 Nordic (iso-8859-10)';
$CHARSETS['iso-8859-11'] = 'Thai (iso-8859-11)';
$CHARSETS['gb2312'] = 'Chinese Simplified (gb2312)';
$CHARSETS['big5'] = 'Chinese Traditional (big5)';
$CHARSETS['iso-2022-jp'] = 'Japanese (iso-2022-jp)';
$CHARSETS['iso-2022-kr'] = 'Korean (iso-2022-kr)';

?>