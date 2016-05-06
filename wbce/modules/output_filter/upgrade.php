<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          Christian Sommer, WB-Project, Werner v.d. Decken, Norbert Heimsath(heimsath.org)
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: upgrade.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/upgrade.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */
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
Settings::Set('wb_suppress_old_opf',0, false);
Settings::Set('opf_droplets',1, false);
Settings::Set('opf_wblink',1, false);
Settings::Set('opf_insert',1, false);  

if (isset($data["sys_rel"]))       Settings::Set('opf_sys_rel',$data["sys_rel"], false);
else                               Settings::Set('opf_sys_rel',1, false);

if (isset($data["email_filter"]))  Settings::Set('opf_email_filter',$data["email_filter"], false);
else                               Settings::Set('opf_email_filter',1, false);    

if (isset($data["mailto_filter"])) Settings::Set('opf_mailto_filter',$data["mailto_filter"], false);
else                               Settings::Set('opf_mailto_filter',1, false);       

Settings::Set('opf_js_mailto',1, false);
Settings::Set('opf_short_url',0, false);
Settings::Set('opf_css_to_head',1, false);

if (isset($data["at_replacement"]))  Settings::Set('opf_at_replacement',$data["at_replacement"], false);
else                                 Settings::Set('opf_at_replacement',"(at)", false);      

if (isset($data["dot_replacement"])) Settings::Set('opf_dot_replacement',$data["dot_replacement"], false);
else                                 Settings::Set('opf_dot_replacement',"(dot)", false);


//finally delete the old table as its no longer needed
$table = TABLE_PREFIX .'mod_output_filter';
$database->query("DROP TABLE IF EXISTS `$table`");

//Setting Version
include ("info.php");
Settings::Set("opf_version", $module_version) ;

Settings::Set('opf_insert_be',1); 
Settings::Set('opf_css_to_head_be',1);


