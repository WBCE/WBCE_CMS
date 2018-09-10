<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-2018)
 * @category        opffilter
 * @package         OPF Auto Placeholder
 * @version         1.2.1
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.3.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/*
 *      CHANGELOG
 *
 *      1.2.1   2018-11-09      - merge updated filter function as fallback when insert class is unavailable
 *      1.2.0   2018-11-05      - support insert class
 *      1.1.1   2018-10-07      - during installation switch filter on by default
 *      1.1.0   2018-09-11      - merge WBCE 1.3.x where insert is not yet a class
 *      1.0.2   2017-04-20      - invert logic of settings
 *      1.0.1   2017-04-11      - make install/upgrade work w/o classical output_filters
 *      1.0.0   2017-01-23      - turn classical outputfilter to an OpF filter module
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


$module_directory       = 'mod_opf_auto_placeholder';
$module_name            = 'OPF Auto Placeholder';
$module_function        = 'opffilter';
$module_version         = '1.2.1';
$module_platform        = 'WBCE 1.3.x ';
$module_author          = 'Martin Hecht (mrbaseman)';
$module_license         = 'GNU GPL2 (or any later version)';
$module_description     = 'Auto Add Placeholders for Javascript, CSS, Metas and Title';
$module_level           = 'core';
