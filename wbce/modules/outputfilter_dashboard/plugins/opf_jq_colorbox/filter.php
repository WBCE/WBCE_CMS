<?php
/*
        FILTER: jQuery Colorbox
*/
// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../index.php'));

function opff_jq_colorbox(&$content, $page_id, $section_id, $module, $wb) {
    global $TEXT;

    $TEXT['STOP'] = (LANGUAGE == 'DE') ? 'Anhalten' : 'Stop';
    $TEXT['OF']   = strtolower($TEXT['OF']);

    $pluginDIR  = 'opf_jq_colorbox';
    $pluginPATH = WB_URL.'/modules/outputfilter_dashboard/plugins/'.$pluginDIR ;

    $chek = array();


    $chek['cb']            = opf_find_class($content, 'cb');
    $chek['iframe']        = opf_find_class($content, 'iframe');
    $chek['youtube']       = opf_find_class($content, 'youtube');
    $chek['colorbox']      = opf_find_class($content, 'colorbox');
    $chek['tp_editlink']   = opf_find_class($content, 'tp_editlink');
        $chek['itemz_editlink']   = opf_find_class($content, 'itemz_editlink');

    $chek['cslide']        = opf_find_class($content, 'cslide',  'a', 'rel');
    $chek['cfade']         = opf_find_class($content, 'cfade',   'a', 'rel');
    $chek['csingle']       = opf_find_class($content, 'csingle', 'a', 'rel');

    // Collect the needed jQuery
    $sToJs = '';

    # normal colorbox effect
    if ($chek['cb'] != false) {
        $sToJs .= "$('.cb').colorbox();\n\t";
    }

    # iframe for webpages with bigger frame
    if ($chek['iframe'] != false) {
        $sToJs .= "$('.iframe').colorbox({
            loop:true,
            width:'90%',
            height:'90%',
            iframe:true
        });\n\t";
    }

    # youtube direct link window
    if ($chek['youtube'] != false) {
        $sToJs .= "$('.youtube').colorbox({
            loop:false,
            iframe:true,
            width:650,
            height:550
        });\n\t";
    }

    if ($chek['csingle'] != false) {
        $sToJs .= "$('a[rel=\"csingle\"]').colorbox({
            loop:false,
            opacity: '0.7',
            maxWidth:'90%',
            maxHeight:'90%',
            current: '".$TEXT['IMAGE']." {current} ".$TEXT['OF']." {total}'
        });  \n\t";
    }

    if ($chek['cfade'] != false) {
        $sToJs .= "$('a[rel=\"cfade\"]').colorbox({
            loop:false,
            transition:'fade',
            opacity: '0.7',
            maxWidth:'90%',
            maxHeight:'90%',
            current: '".$TEXT['IMAGE']." {current} ".$TEXT['OF']." {total}',
            speed: 800
        });\n\t";
    }

    if ($chek['cslide'] != false) {
        $sToJs .= "$('a[rel=\"cslide\"]').colorbox({
            slideshow:true,
            loop:true,
            slideshowSpeed:6000,
            slideshowAuto:true,
            transition:'elastic',
            opacity: '0.7',
            maxWidth:'90%',
            maxHeight:'90%',
            previous:'".$TEXT['BACK']."',
            next:'".$TEXT['NEXT']."',
            close:'".$TEXT['CLOSE']."',
            current: '".$TEXT['IMAGE']." {current} ".$TEXT['OF']." {total}',
            slideshowStart: '".$TEXT['START']."',
            slideshowStop: '".$TEXT['STOP']."'
        });\n\t";
    }

    if ($chek['colorbox'] != false){
        $sToJs .= "$('.colorbox').colorbox({
            loop:false,
            maxWidth:'90%',
            maxHeight:'90%',
            opacity: '0.7',
            current: '".$TEXT['IMAGE']." {current} ".$TEXT['OF']." {total}'
        });\n\t";
    }

    if ($chek['tp_editlink'] != false){
        $sToJs .= "$('.tp_editlink').colorbox({
            loop:true, width:'90%',
            height:'90%',
            iframe:true
        });\n\t";
    }

        if ($chek['itemz_editlink'] != false){
        $sToJs .= "$('.itemz_editlink').colorbox({
            loop:true, width:'90%',
            height:'90%',
            iframe:true
        });\n\t";
    }

    // Check if we need to load the scripts and stylesheet
    if ($sToJs != '') {
        $sJavascript  = "\t// Colorbox Setup\n\t";
        $sJavascript .= "$(document).ready(function() {\n\t";
        $sJavascript .= $sToJs;
        $sJavascript .= "});\n"; // close document ready function

        $values = opf_filter_get_additional_values();
        I::insertJsFile(WB_URL.'/include/jquery/jquery-min.js', 'HEAD TOP-');
        I::insertJsFile($pluginPATH.'/colorbox/jquery.colorbox-min.js', ($values['places'] == 'body') ? 'BODY BTM-' : 'HEAD BTM-', 'colorbox');
        I::insertJsCode($sToJs, ($values['places'] == 'body') ? 'BODY BTM-' : 'HEAD BTM-', 'colorbox');
        I::insertCssFile($pluginPATH.'/colorbox/'.$values['cb_design'].'/colorbox.css', 'HEAD BTM+', 'colorbox');
    }

    return (TRUE);
}