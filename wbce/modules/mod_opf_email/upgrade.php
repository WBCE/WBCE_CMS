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


//no direct file access
if (count(get_included_files())==1) {
    die(header("Location: ../index.php", true, 301));
}

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
    function _getOutputFilterSettings()
    {
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
        if (($res = $database->query($sql))) {
            if ($res->numRows() > 0) {
                // request settings from database
                $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_output_filter`';
                if (($res = $database->query($sql))) {
                    if (($rec = $res->fetchRow())) {
                        $settings = $rec;
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

if (!class_exists('Settings')) {
    return false;
}

// Set old values if exists otherwise go for default
if (isset($data["email_filter"])) {
    Settings::Set('opf_email_filter', $data["email_filter"], false);
} else {
    Settings::Set('opf_email_filter', 1, false);
}

if (isset($data["mailto_filter"])) {
    Settings::Set('opf_mailto_filter', $data["mailto_filter"], false);
} else {
    Settings::Set('opf_mailto_filter', 1, false);
}

Settings::Set('opf_js_mailto', 1, false);

if (isset($data["at_replacement"])) {
    Settings::Set('opf_at_replacement', $data["at_replacement"], false);
} else {
    Settings::Set('opf_at_replacement', "(at)", false);
}

if (isset($data["dot_replacement"])) {
    Settings::Set('opf_dot_replacement', $data["dot_replacement"], false);
} else {
    Settings::Set('opf_dot_replacement', "(dot)", false);
}

Settings::Set('opf_email', 1, false);

include_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');

if (!opf_is_registered('E-Mail')) {
    return false;
}

if (opf_get_type('E-Mail', false) != OPF_TYPE_PAGE) {
    return opf_unregister_filter('E-Mail')
    && require(WB_PATH.'/modules/mod_opf_email/install.php');
}

return true;
