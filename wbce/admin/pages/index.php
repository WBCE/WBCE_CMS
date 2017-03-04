<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright WebsiteBaker Org. e.V. (2016)
 * @license GNU GPL2 (or any later version)
 */

if(!defined('WB_PATH')){
	require('../../config.php');
}
$sArea = 'index';
// dont arm this yet
$sFile = WB_PATH.'/modules/backend_pages/tool_not_yet.php';
if(is_readable($sFile)){
	
	$_GET['tool'] = "backend_pages"; 
	$_GET['area'] = $sArea;
	
	// call tool object and set parameters 
	$tool = new tool("backend", "backend_pages");	
	$tool->adminSection = "pages";
	$tool->adminAccess  = "start";
	$tool->returnUrl    = ADMIN_URL.'/pages/index.php';	
	$tool->Process(true);
	
}else{
	include(ADMIN_PATH.'/pages/legacy.index.php');
}
