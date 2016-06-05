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

require('../../config.php');

// call in for a fixed Module 
$tool = new tool("backend", "preferences") ;

// Seting class admin params for this page module
$tool->adminSection="preferences";
$tool->adminAccess="start";
$tool->returnUrl=ADMIN_URL."/preferences/index.php";

$tool->Process(true);