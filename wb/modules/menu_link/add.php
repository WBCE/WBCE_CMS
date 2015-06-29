<?php
/**
 *
 * @category        modules
 * @package         menu_link
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: add.php 1457 2011-06-25 17:18:50Z Luisehahne $
 * @filesource		$HeadURL: http://svn.websitebaker2.org/branches/2.8.x/wb/modules/wysiwyg/modify.php $
 * @lastmodified    $Date: 2011-01-11 20:29:52 +0100 (Di, 11 Jan 2011) $
 *
 */


// prevent this file from being accesses directly
if(defined('WB_PATH') == false) {
	exit("Cannot access this file directly"); 
}

$table = TABLE_PREFIX ."mod_menu_link";
$database->query("INSERT INTO `$table` (`page_id`, `section_id`, `target_page_id`, `anchor`, `extern`) VALUES ('$page_id', '$section_id', '0', '0', '')");
