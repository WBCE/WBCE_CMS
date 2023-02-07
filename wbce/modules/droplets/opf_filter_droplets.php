<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       Ryan Djurovich (2004-2009)
 * @copyright       WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2019)
 * @category        opffilter
 * @package         Droplets
 * @version         1.1.6
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.6.x
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


/**
 * execute droplets
**/


function opff_droplets (&$content, $page_id, $section_id, $module, $wb) {
    if (!class_exists('Settings')
        || (Settings::Get('opf_droplets', true) && ($page_id != 'backend'))
        || (Settings::Get('opf_droplets_be', true) && ($page_id == 'backend'))){

        // check file and include
        $sFile = WB_PATH .'/modules/droplets/droplets.php';
        if (file_exists($sFile)) {
                include_once $sFile;


                if (strpos($content, '<p>[') !== false) {
                    // remove <p> tags that are added by editor every
                    // time you have a droplet in an empty line
                    $content = strtr($content, [
                        '<p>[#['  =>  '[#[',
                        '<p>[\['  =>  '[\[',
                        '<p>[['   =>  '[[',
                        ']]</p>'  =>  ']]'
                    ]);
                }

                if(strpos($content, '[#[') !== false){
                    //remove commented droplets
                    // example: [#[Lorem?blocks=6]]
                    // the hash symbol will cause the droplet to be excluded from output
                    $content = preg_replace('/\[\#\[[^]]*]]/', '', $content);
                }
         
                // load filter function
                if(function_exists('evalDroplets')) {
                   $content = evalDroplets(
                           $content, 
                           (($page_id === 'backend') ? 'backend' : 'frontend'));
                }
                
                if (strpos($content, '[\[') !== false) {
                    // Replace escaped droplets and display them just like correct Droplet calls			
                    // example FE: [\[Lorem?blocks=6]]
                    // the backslash symbol will cause the droplet to be shown as is
                    $content = preg_replace('/\[\\\\\[(\S+)]*]]/', '[[${1}]]', $content); 					
                } 
        }
    }
    return(TRUE);
}
