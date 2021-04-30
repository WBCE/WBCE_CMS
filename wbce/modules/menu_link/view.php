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

// Must include code to stop this file being access directly
defined('WB_PATH') or die("Cannot access this file directly");


// get target_page_id
$sSql  = 'SELECT * FROM `{TP}mod_menu_link` WHERE `page_id` = '.(int)PAGE_ID;
$rQueryPageData = $database->query($sSql);

if ($rQueryPageData->numRows() == 1) {
	$aPageData = $rQueryPageData->fetchRow(MYSQL_ASSOC); // generate assoc array from query

	if ($aPageData['redirect_type'] == 301) {	
		@header('HTTP/1.1 301 Moved Permanently');	// 301 redirect	
	} 	
	
	if ($aPageData['target_page_id'] == "-1") {
		if ($aPageData['extern'] != '') {
			$sTargetUrl = $aPageData['extern'];
			$sTargetUrl = str_replace('[WB_URL]', WB_URL, $sTargetUrl);
			header('Location: '.$sTargetUrl);
			exit;
		}
	} else {		
		// generate anchor if anchor is set
		$sAnchor = '';
		if($aPageData['anchor'] != '0'){		
			$iSectionID = (int) filter_var($aPageData['anchor'], FILTER_SANITIZE_NUMBER_INT);
			$sAnchor = '#'.SEC_ANCHOR.$iSectionID;
		}
		
		// get link of target-page
		$sSql  = 'SELECT `link` FROM `{TP}pages` WHERE `page_id` = '.$aPageData['target_page_id'];
		if ($sTargetPageLink = $database->get_one($sSql)) {
			$sTargetUrl = WB_URL.PAGES_DIRECTORY.$sTargetPageLink.PAGE_EXTENSION.$sAnchor;
			header('Location: '.$sTargetUrl);
			exit;
		}
	}
}
