<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          Christian Sommer, WB-Project, Werner v.d. Decken
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: index.php 1626 2012-02-29 22:45:20Z darkviper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/modules/output_filter/index.php $
 * @lastmodified    $Date: 2012-02-29 23:45:20 +0100 (Mi, 29. Feb 2012) $
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
require_once( dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
if(!defined('WB_PATH')) { throw new IllegalFileException(); }
/* -------------------------------------------------------- */

/* ************************************************************************** */
/**
 * execute the frontend output filter to modify email addresses
 * @param string actual content
 * @return string modified content
 */
    function executeFrontendOutputFilter($content) {
        // get output filter settings from database
        $filter_settings = getOutputFilterSettings();
        $sFilterDirectory = str_replace('\\', '/', dirname(__FILE__)).'/filters/';
        $output_filter_mode = 0;
        $output_filter_mode |= ($filter_settings['email_filter'] * pow(2, 0));  // n | 2^0
        $output_filter_mode |= ($filter_settings['mailto_filter'] * pow(2, 1)); // n | 2^1
        define('OUTPUT_FILTER_AT_REPLACEMENT', $filter_settings['at_replacement']);
        define('OUTPUT_FILTER_DOT_REPLACEMENT', $filter_settings['dot_replacement']);

/* ### filter type: execute droplets filter ################################# */
        if (file_exists($sFilterDirectory.'filterDroplets.php')) {
            require($sFilterDirectory.'filterDroplets.php');
            $content = doFilterDroplets($content);
        }
/* ### filter type: protect email addresses ################################# */
        if( ($output_filter_mode & pow(2, 0)) || ($output_filter_mode & pow(2, 1)) ) {
            if (file_exists($sFilterDirectory.'filterEmail.php')) {
                require($sFilterDirectory.'filterEmail.php');
                $content = doFilterEmail($content, $output_filter_mode);
            }
        }
/* ### filter type: change [wblinkxx] into real URLs ######################## */
        if (file_exists($sFilterDirectory.'filterWbLink.php')) {
            require($sFilterDirectory.'filterWbLink.php');
            $content = doFilterWbLink($content);
        }
/* ### filter type: full qualified URLs to relative URLs##################### */
        if($filter_settings['sys_rel'] == 1){
            if (file_exists($sFilterDirectory.'filterRelUrl.php')) {
                require($sFilterDirectory.'filterRelUrl.php');
                $content = doFilterRelUrl($content);
            }
        }
/* ### filter type: moves css definitions from <body> into <head> ########### */
        if (file_exists($sFilterDirectory.'filterCssToHead.php')) {
            require($sFilterDirectory.'filterCssToHead.php');
            $content = doFilterCssToHead($content);
        }

/* ### end of filters ####################################################### */
        return $content;
    }
/* ************************************************************************** */
/**
 * function to read the current filter settings
 * @global object $database
 * @global object $admin
 * @param void
 * @return array contains all settings
 */
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
    // be sure field 'sys_rel' is in table
        $database->field_add( TABLE_PREFIX.'mod_output_filter', 'sys_rel', 'INT NOT NULL DEFAULT \'0\' FIRST');
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
/* ************************************************************************** */
