<?php
/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * ajax/save.php
 * This file gets $_POST Data sent by ajax and executes DB updates on fields
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

require('../../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');

$bAdminHeader = FALSE; // suppress to print the header, so no new FTAN will be set
$admin = new admin('Pages', 'pages_settings', $bAdminHeader);
// check if user can change things to avoid any submission from a logged in not admin user
if($admin->get_permission('pages_modify') == false ) { 
	exit; 
}

// Create the Fields from Submission
$aFromString = explode ( "-",$_POST['id']);
$sDbField    = $aFromString[0];
$iPageId     = intval($aFromString[1]);
//sanitize new value to update
$sNewValue = str_replace(array("[[", "]]", "\n", "\t"), '', htmlspecialchars($admin->add_slashes($admin->get_post('value'))));
$aCheckPagesFields = array('page_title', 'description', 'keywords');

//	GET TOOL SETTINGS FROM DB (Json Array)
$jsonSettings = $database->get_one("SELECT `settings_json` FROM `".TABLE_PREFIX."mod_page_seo_tool`");
$aSettings = json_decode($jsonSettings, TRUE);

if(!defined('REWRITE_URL') && $aSettings['rewriteUrl']['use'] == TRUE ){
	define('REWRITE_URL', $aSettings['rewriteUrl']['dbString']);
	array_push($aCheckPagesFields, REWRITE_URL);
}

// UPDATE the DB Field
 if(isset($_POST['value']) && in_array($sDbField, $aCheckPagesFields)){
	// Update page settings in the pages table
	$sUpdateQuery  = 'UPDATE `'.TABLE_PREFIX.'pages` SET `'.$sDbField.'` = "'.$sNewValue.'" WHERE `page_id` = '.$iPageId;
	$database->query($sUpdateQuery);
}
if($database->is_error() == FALSE) {
	echo $sNewValue;
}
exit;