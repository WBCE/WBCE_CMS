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
 * @version         $Id: install.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/install.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

//frontend
Settings::Set('opf_droplets',1, false);
Settings::Set('opf_auto_placeholder',1, false); 
Settings::Set('opf_move_stuff',1, false);   
Settings::Set('opf_replace_stuff',1, false);   
Settings::Set('opf_css_to_head',1, false);
Settings::Set('opf_email_filter',1, false);
Settings::Set('opf_mailto_filter',1, false);
Settings::Set('opf_js_mailto',1, false);
Settings::Set('opf_at_replacement',"(at)", false);
Settings::Set('opf_dot_replacement',"(dot)", false);
Settings::Set('opf_wblink',1, false);
Settings::Set('opf_short_url',0, false);
Settings::Set('opf_sys_rel',1, false);
Settings::Set('opf_remove_comments',1, false);
Settings::Set('opf_basic_html_minify',1, false);

//backend
Settings::Set('opf_droplets_be',1, false);
Settings::Set('opf_auto_placeholder_be',1, false); 
Settings::Set('opf_move_stuff_be',1, false);   
Settings::Set('opf_replace_stuff_be',1, false); 
Settings::Set('opf_css_to_head_be',1);
Settings::Set('opf_remove_comments_be',1, false);
Settings::Set('opf_basic_html_minify_be',1, false);


//Setting version
include ("info.php");
Settings::Set("opf_version", $module_version);
