<?php
/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * uninstall.php
 * This file executes a DROP TABLE query while the module is being uninstalled
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

$sTable = TABLE_PREFIX . 'mod_page_seo_tool';
// remove $sTable from WB database
if($database->query("DROP TABLE IF EXISTS `".$sTable."`")){
	echo "<code>".$sTable." was dropped successfully!</code>";
}