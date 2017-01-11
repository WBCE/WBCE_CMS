<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2017)
 * @category        opffilter,tool
 * @package         OPF E-Mail
 * @version         1.0.0
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
if (!function_exists("getOutputFilterSettings")) {
    function getOutputFilterSettings() {
        global $database, $admin;
    // set default values
        $settings = array(
            'sys_rel'         => 0,
            'email_filter'    => 0,
            'mailto_filter'   => 0,
            'at_replacement'  => '(at)',
            'dot_replacement' => '(dot)'
        );
    // request settings from database
        $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_output_filter`';
        if(($res = $database->query($sql))) {
            if(($rec = $res->fetchRow())) {
                $settings = $rec;
                $settings['at_replacement']  = $admin->strip_slashes($settings['at_replacement']);
                $settings['dot_replacement'] = $admin->strip_slashes($settings['dot_replacement']);
            }
        }
    // return array with filter settings
        return $settings;
    }
}

$msg = '';

// getting old Data
$data = getOutputFilterSettings();

// Set old values if exists otherwise go for default 
if(class_exists('Settings')) {
if (isset($data["email_filter"]))  Settings::Set('opf_email_filter',$data["email_filter"], false);
else                               Settings::Set('opf_email_filter',1, false);    

if (isset($data["mailto_filter"])) Settings::Set('opf_mailto_filter',$data["mailto_filter"], false);
else                               Settings::Set('opf_mailto_filter',1, false);       

Settings::Set('opf_js_mailto',1, false);

if (isset($data["at_replacement"]))  Settings::Set('opf_at_replacement',$data["at_replacement"], false);
else                                 Settings::Set('opf_at_replacement',"(at)", false);      

if (isset($data["dot_replacement"])) Settings::Set('opf_dot_replacement',$data["dot_replacement"], false);
else                                 Settings::Set('opf_dot_replacement',"(dot)", false);


//finally delete the old table as its no longer needed
$table = TABLE_PREFIX .'mod_output_filter';
$database->query("DROP TABLE IF EXISTS `$table`");

return TRUE;
}

return FALSE;
