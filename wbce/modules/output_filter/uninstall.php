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
 * @version         $Id: uninstall.php 1520 2011-11-09 00:12:37Z darkviper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/uninstall.php $
 * @lastmodified    $Date: 2011-11-09 01:12:37 +0100 (Mi, 09. Nov 2011) $
 *
 */
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Commented out , cause of new OPF dashboard still needs those settings.  
Settings::Del('wb_suppress_old_opf');
//frontend
Settings::Del('opf_droplets');
Settings::Del('opf_auto_placeholder'); 
Settings::Del('opf_move_stuff');   
Settings::Del('opf_replace_stuff');   
Settings::Del('opf_css_to_head');
Settings::Del('opf_email_filter');
Settings::Del('opf_mailto_filter');
Settings::Del('opf_js_mailto');
Settings::Del('opf_at_replacement',"(at)");
Settings::Del('opf_dot_replacement',"(dot)");
Settings::Del('opf_wblink');
Settings::Del('opf_short_url');
Settings::Del('opf_sys_rel');
Settings::Set("opf_remove_system_ph")    ;

//backend
Settings::Del('opf_droplets_be');
Settings::Del('opf_auto_placeholder_be'); 
Settings::Del('opf_move_stuff_be');   
Settings::Del('opf_replace_stuff_be'); 
Settings::Del('opf_css_to_head_be');
Settings::Set("opf_remove_system_ph_be")    ;

// deleting version too 
Settings::Del("opf_version") ;
