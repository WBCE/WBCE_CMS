<?php
/**
    @brief Replaces certain tagged content like title or keywords 
    @param string $content
    @return string

*/

function doFilterReplaceStuff($sContent) { 

    // Template does not want any replacement ?
    if (strpos($sContent,'<!--(NO REPLACE)-->') !== false) {return $sContent;}
    
    // Do we have any placeholders to move to ?
    if (strpos($sContent,'<!--(PH)') === false) {return $sContent;}
    
    // Do we have any stuff to move, if not abort?
    if (strpos($sContent,'<!--(REPLACE)') === false) {return $sContent;}

    // Does the stuf has at least one end, if not abort?
    if (strpos($sContent,'<!--(END)-->') === false) {return $sContent;}

    // As recursion is not allowed and makes no sense anyway , we can fetch all replacements in one regex. 
    
    // The regex fetches this:
    // <!--(MOVE)(Content1) -->(Content2)<!--(END)-->  
    // Arbeitet auf dem gesamten String(s)->Zeilen werden nicht beachtet und ist Ungreedy
    $sRegex = '/\s*?\<\!\-\-\(REPLACE\)\ (.+)\ \-\-\>(.+)\<\!\-\-\(END\)\-\-\>\s*?/sU'; 
    preg_match_all($sRegex, $sContent, $aMatches);
    
    //print_r($aMatches);
    
    // Runn through the Array 
    foreach ($aMatches[0] as $iKey=>$sOldEntry) {
    
        // Remove the Old Entry
        $sContent = str_replace($sOldEntry, '', $sContent);
        
        // fetch the right Placeholder
        $sPlaceHolder1="<!--(PH) ".$aMatches[1][$iKey]."+ -->";
        $sPlaceHolder2="<!--(PH) ".$aMatches[1][$iKey]."- -->";
        
        $sInsert = $sPlaceHolder1.trim($aMatches[2][$iKey]).$sPlaceHolder2 ;
                     
        $sContent = preg_replace('/'.preg_quote($sPlaceHolder1).'.*'.preg_quote($sPlaceHolder2).'/sU', $sInsert, $sContent,1);  
        
    }
   
    return $sContent;    
}
