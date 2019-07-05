<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       Ryan Djurovich (2004-2009)
 * @copyright       WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2019)
 * @category        opffilter
 * @package         OPF Droplets
 * @version         1.1.6
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
 *      1.1.6   2019-07-05      - by default enable filter on searchresults
 *      1.1.5   2019-07-04      - bugfix: make droplets work in search results again
 *      1.1.4   2019-04-22      - include opf functions in upgrade script
 *      1.1.3   2019-03-09      - bugfix in install/upgrade
 *      1.1.2   2019-03-07      - reorder filters into new categories
 *      1.1.1   2018-11-12      - correctly set the where parameter
 *      1.1.0   2018-09-11      - merge WBCE 1.3.x: allow commenting out droplets
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


$module_directory       = 'mod_opf_droplets';
$module_name            = 'OPF Droplets';
$module_function        = 'opffilter';
$module_version         = '1.1.6';
$module_platform        = 'WBCE 1.3.x ';
$module_author          = 'Martin Hecht (mrbaseman)';
$module_license         = 'GNU GPL2 (or any later version)';
$module_description     = 'insert droplets into html output';
$module_level           = 'core';
