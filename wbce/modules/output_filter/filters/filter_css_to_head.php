<?php
/**
    @brief Moves all free standing css definitions from <body> into <head> section. 
    @param string $content
    @return string
    
    To be more precise it moves them to the "<!--(PH) CSS HEAD BTM- -->" Placeholder.
    This is here for compatibility. 
*/
function doFilterCssToHead($sContent) {
    $sToPlaceHolder="<!--(PH) CSS HEAD BTM- -->";
    
    // move css definitions into head section
    $sPattern1 = '/(?:<\s*body.*?)(<\s*link[^>]*?\"text\/css\".*?\/?\s*>)/si';
    $sPattern3 = '/(?:<\s*body.*?)(<\s*link[^>]*?\"stylesheet\".*?\/?\s*>)/si';
    $sPattern2 = '/(?:<\s*body.*?)(<\s*style.*?<\s*\/\s*style\s*>)/si';    
     
    
    $aInsert = array();
    while(preg_match($sPattern1, $sContent, $aMatches)) {
        $aInsert[] = $aMatches[1];
        $sContent = str_replace($aMatches[1], '', $sContent); 
    }
    while(preg_match($sPattern3, $sContent, $aMatches)) {
        $aInsert[] = $aMatches[1];
        $sContent = str_replace($aMatches[1], '', $sContent); 
    }
    while(preg_match($sPattern2, $sContent, $aMatches)) {
        $aInsert[] = $aMatches[1];
        $sContent = str_replace($aMatches[1], '', $sContent);
    }
    $aInsert = array_unique($aInsert);
    if(sizeof($aInsert) > 0) {
        $sInsert = "\n".implode("\n", $aInsert)."\n".$sToPlaceHolder;
        $sContent = preg_replace('/'.preg_quote($sToPlaceHolder).'/si', $sInsert, $sContent, 1);
    }
        
    return $sContent;
}
