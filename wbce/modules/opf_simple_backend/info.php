<?php
/**
 *
 * @category        backend,hidden
 * @package         OpF Simple Backend
 * @version         1.3.0
 * @authors         Martin Hecht (mrbaseman)
 * @copyright       (c) 2018, Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/*
 *        CHANGELOG
 *
 *      1.3.0        2018-10-23      - if present use OpF intrinsic functions for filter update
 *
 *      1.2.3        2018-10-09      - bugfix: in basic view settings were only saved when switching to advanced view at the same time
 *
 *      1.2.2        2018-10-07      - change button text to hide advanced options
 *
 *      1.2.1        2018-10-02      - bugfix: saving filters after editing by mistake also used to trigger switching to basic view
 *
 *      1.2.0        2018-09-30      - remove all email related stuff and add a link to mod_opf_email instead
 *
 *      1.1.1        2018-09-22      - integration into OpF 1.5.6, several minor fixes
 *
 *      1.1.0        2018-09-15      - merge changes from WBCE 1.3.x branch
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


$module_directory       = 'opf_simple_backend';
$module_name            = 'OpF Simple Backend';
$module_function        = 'backend,hidden';
$module_version         = '1.3.0';
$module_platform        = 'WBCE 1.2.x';
$module_author          = 'Martin Hecht (mrbaseman)';
$module_license         = 'GNU GPL2 (or any later version)';
$module_description     = 'This tool provides a simple backend for outputfilter dashboard, similar to the one used by the former output_filter module';

