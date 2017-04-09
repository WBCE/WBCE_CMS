<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2017)
 * @category        opffilter
 * @package         OPF Sys Rel
 * @version         1.0.3
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x 
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


// function fetched from old filter routines
// this function whith if exits, may not be at the end ... 
/**
 * function to read the current filter settings
 * @global object $database
 * @global object $admin
 * @param void
 * @return array contains all settings
 */
if (!function_exists("_getOutputFilterSettings")) {
    function _getOutputFilterSettings() {
        global $database, $admin;
        // set default values
        $settings = array(
            'sys_rel'         => 1,
            'email_filter'    => 1,
            'mailto_filter'   => 1,
            'at_replacement'  => '(at)',
            'dot_replacement' => '(dot)'
        );

        // check if traditional database table exists
        $sql = "SHOW TABLES LIKE '".TABLE_PREFIX."mod_output_filter'";
        if(($res = $database->query($sql))) {
            if ($res->numRows() > 0 ) {
                // request settings from database
                $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_output_filter`';
                if(($res = $database->query($sql))) {
                    if(($rec = $res->fetchRow())) {
                        $settings = $rec;
                        $settings['at_replacement']  = $admin->strip_slashes($settings['at_replacement']);
                        $settings['dot_replacement'] = $admin->strip_slashes($settings['dot_replacement']);
                    }
                }
            }
        }
        // return array with filter settings
        return $settings;
    }
}

$msg = '';

// getting old Data
$data = _getOutputFilterSettings();

if(!class_exists('Settings')) return FALSE;


if (isset($data["sys_rel"]))       Settings::Set('opf_sys_rel',$data["sys_rel"], false);
else                               Settings::Set('opf_sys_rel',1, false);


return TRUE;
