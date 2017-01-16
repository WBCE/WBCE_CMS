<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2017)
 * @category        backend,hidden
 * @package         OpF Simple Backend
 * @version         1.0.0
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x 
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/*
 *        CHANGELOG
 *
 *      1.0.0        2017-01-12      - extract backend tool from classical output filter module
 *
 */


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


$module_directory        = 'opf_simple_backend';
$module_name                = 'OpF Simple Backend';
$module_function        = 'backend,hidden';
$module_version                = '1.0.0';
$module_platform        = 'WBCE 1.2.x ';
$module_author                = 'Martin Hecht (mrbaseman)';
$module_license                = 'GNU GPL2 (or any later version)';
$module_description        = 'This tool provides a simple backend for outputfilter dashboard, similar to the one used by the former output_filter module';
$module_icon            = 'fa fa-filter';
$module_level           = 'core';
