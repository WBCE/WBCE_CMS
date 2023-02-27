<?php

/**
 * 
 * @param  string $sFileLoc
 * @return string
 */
function render_md_file($sFileLoc) {    
    include __DIR__ . '/Parsedown/ParsedownWbce.php';
    $oParsedown = new ParsedownWbce();
    $sMarkdown = file_get_contents($sFileLoc);
    $sHtml = $oParsedown->text($sMarkdown);

    // do some filtering with DOM Document
    // we need to replace image src to render images using the md_reader.php 
    //    and on GitHub as well from the same markdown file
    if (strpos($sHtml, '<img') !== FALSE) {
        // start DOMDocument only if images are contained in the $sHtml
        $dom = new DOMDocument();                
        $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$sHtml);
        $aImages = $dom->getElementsByTagName('img');
        foreach ($aImages as $image) {
            $src = $image->getAttribute('src'); 

            $sNewSource = $src;
            if(! filter_var($src, FILTER_VALIDATE_URL)){    
                $sNewSource = dirname($sFileLoc).$src;
            }
            $image->setAttribute('src', $sNewSource);
            $arr[] = $sNewSource;
        }
        $sHtml = $dom->saveHTML();
    }
        


    return $sHtml;
}
/**
 * 
 * @param  string $sMdString
 * @return string
 */
function render_md_string($sMdString) {
    include __DIR__ . '/Parsedown/ParsedownWbce.php';
    $oParsedown = new ParsedownWbce();
    return $oParsedown->text($sMdString);
}