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
//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

/**
 * execute the frontend output filter to modify email addresses
 * @param string actual content
 * @return string modified content
 */
    function executeFrontendOutputFilter($content) {
      
        $sFilterDirectory = str_replace('\\', '/', dirname(__FILE__)).'/filters/';
      

/* ### filter type: execute droplets filter ################################# */
        if (OPF_DROPLETS){
            if (file_exists($sFilterDirectory.'filterDroplets.php')) {
                require_once($sFilterDirectory.'filterDroplets.php');
                $content = doFilterDroplets($content);
            }
        }
                
/* ### filter type: fill out placeholders for Javascript, CSS, Metas and Title  ################################# */       
        if (OPF_INSERT){
            if (class_exists ("I")) {
                $content = I::Filter($content);
            }
        }
    
/* ### filter type: protect email addresses ################################# */
        if (OPF_MAILTO_FILTER || OPF_EMAIL_FILTER ) {
            if (file_exists($sFilterDirectory.'filterEmail.php')) {
                require_once($sFilterDirectory.'filterEmail.php');
                $content = doFilterEmail($content);
            }
        }
        
/* ### filter type: change [wblinkxx] into real URLs ######################## */
        if (OPF_WBLINK){
            if (file_exists($sFilterDirectory.'filterWbLink.php')) {
                require_once($sFilterDirectory.'filterWbLink.php');
                $content = doFilterWbLink($content);
            }
        }
        
/* ### filter type: short url (instead of a droplet) ########### */
        if (OPF_SHORT_URL){
            if (file_exists($sFilterDirectory.'filter_short_url.php')) {
                require_once($sFilterDirectory.'filter_short_url.php');
                $content = doFilterShortUrl($content);
            }
        }                
        
/* ### filter type: full qualified URLs to relative URLs##################### */
        if(OPF_SYS_REL){
            if (file_exists($sFilterDirectory.'filterRelUrl.php')) {
                require_once($sFilterDirectory.'filterRelUrl.php');
                $content = doFilterRelUrl($content);
            }
        }
        
/* ### filter type: moves css definitions from <body> into <head> ########### */
        if (OPF_CSS_TO_HEAD){
            if (file_exists($sFilterDirectory.'filterCssToHead.php')) {
                require_once($sFilterDirectory.'filterCssToHead.php');
                $content = doFilterCssToHead($content);
            }
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
        //fetch settings whith default values
        $settings = array(
            'sys_rel'         => Settings::Get("opf_sys_rel", 0),
            'email_filter'    => Settings::Get("opf_email_filter", 0),
            'mailto_filter'   => Settings::Get("opf_mailto_filter", 0),
            'at_replacement'  => Settings::Get("opf_mailto_filter",'(at)'),
            'dot_replacement' => Settings::Get("opf_mailto_filter",'(dot)')
        );
        return $settings;
    }