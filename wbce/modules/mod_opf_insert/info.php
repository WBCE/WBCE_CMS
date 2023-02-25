<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-)
 * @category        opffilter
 * @package         OPF Insert
 * @version         1.0.8
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.4.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/*
 *      CHANGELOG
 *
 *		1.0.8	2023-02-25		- update description (florian)
 *      1.0.7   2019-07-05      - by default enable filter on searchresults
 *      1.0.6   2019-04-22      - include opf functions in upgrade script
 *      1.0.5   2019-03-26      - update requirements
 *      1.0.4   2019-03-22      - change filter type to page (last)
 *      1.0.3   2019-03-09      - bugfix in install/upgrade
 *      1.0.2   2019-03-07      - reorder filters into new categories
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


$module_directory       = 'mod_opf_insert';
$module_name            = 'OPF Insert';
$module_function        = 'opffilter';
$module_version         = '1.0.8';
$module_platform        = 'WBCE 1.4.x ';
$module_author          = 'Martin Hecht (mrbaseman)';
$module_license         = 'GNU GPL2 (or any later version)';
$module_description     = 'fill out placeholders for Javascript, CSS, Metas and Title';
$module_level           = 'core';
