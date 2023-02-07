<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright    Ryan Djurovich (2004-2009)
 * @copyright    WebsiteBaker Org. e.V. (2009-2015)
 * @copyright    WBCE Project (2015-)
 * @category     tool
 * @package      OPF E-Mail
 * @version      1.1.7
 * @authors      Martin Hecht (mrbaseman)
 * @link         https://forum.wbce.org/viewtopic.php?id=176
 * @license      GNU GPL2 (or any later version)
 * @platform     WBCE 1.x
 * @requirements OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/**
 *      CHANGELOG
 *
 *      1.1.7   2023-02-7       - add improved obfuscation for emails (Stefek)
 *      1.1.7   2021-06-12      - fix fatal error in mailto links (colinax)
 *                              - cs fixed and formatted files (colinax)
 *      1.1.6   2021-05-31      - fix mailto links with font awesome (florian)
 *      1.1.5   2021-03-15      - support title attribute (benvo)
 *      1.1.4   2020-01-20      - Accept/Recognize new TLD email addresses (florian)
 *      1.1.3   2019-12-19      - remove obsolete strip_slashes
 *      1.1.2   2019-07-05      - by default enable filter on searchresults
 *      1.1.1   2019-04-22      - include opf functions in upgrade script
 *      1.1.0   2019-03-18      - use insert class for inserting mdcr.js
 *      1.0.10  2019-03-09      - bugfix in install/upgrade
 *      1.0.9   2019-03-07      - reorder filters into new categories
 *      1.0.8   2019-02-18      - Correct link to mcdr.js and add support for style attr in mailto links
 *      1.0.7   2018-10-07      - fix obsolete brace and update (in/)active logic
 *      1.0.6   2018-10-07      - enforce consistency of active/inactive state
 *      1.0.5   2018-09-20      - do not use undefined template param FRONTEND
 *      1.0.4   2018-09-12      - merge changes from WBCE 1.3 into filter.php
 *      1.0.3   2017-04-11      - make install/upgrade work w/o classical output_filters
 *      1.0.2   2017-04-07      - fix upgrade script to avoid function name collision
 *      1.0.1   2017-04-06      - change type back to tool in order to reenable backend
 *      1.0.0   2017-01-23      - turn classical outputfilter to an OpF filter module
 *
 */


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
    // Stop this file being access directly
    if (!headers_sent()) {
        header("Location: ../index.php", true, 301);
    }
    die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


$module_directory   = 'mod_opf_email';
$module_name        = 'OPF E-Mail';
$module_function    = 'tool';
$module_version     = '1.1.8';
$module_platform    = 'WBCE 1.x';
$module_author      = 'Martin Hecht (mrbaseman)';
$module_license     = 'GNU GPL2 (or any later version)';
$module_description = 'settings for the output filter to protect email addresses in text, mailto links, and javascript';
$module_icon        = 'fa fa-at';
$module_level       = 'core';
