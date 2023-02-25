<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
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

$sql  = 'DROP TABLE IF EXISTS `{TP}mod_droplets` ';
$database->query($sql);

Settings::Del('opf_droplets');
Settings::Del('opf_droplets_be');

// check whether outputfilter-module is installed {
if(file_exists($sOpfFile = WB_PATH.'/modules/outputfilter_dashboard/functions.php')) {
  require_once $sOpfFile;
  // un-install filter
  opf_unregister_filter('Droplets');
}