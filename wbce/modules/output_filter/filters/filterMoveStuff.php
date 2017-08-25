<?php
/**
    @brief Moves tagged content around to all the right places 
    @param string $content
    @return string   
 */
function doFilterMoveStuff($sContent) {

    ///@todo remove this in production enviroment
    // Do we have any stuff to move, if not abort?
    if (strpos($sContent,'<!--(MOVE)') === false) {return $sContent;}

    // Does the stuf has at least one end, if not abort?
    if (strpos($sContent,'<!--(END)-->') === false) {return $sContent;}

    // As recursion is not allowed and makes no sense anyway , we can fetch all moves in one regex. 
    
    // The regex fetches this:
    // <!--(MOVE)(Content1) -->(Content2)<!--(END)-->  
    // Arbeitet auf dem gesamten String(s)->Zeilen werden nicht beachtet und ist Ungreedy
    $sRegex = '/\<\!\-\-\(MOVE\)\ (.+([\+\-]))\ \-\-\>(.+)\<\!\-\-\(END\)\-\-\>/sU'; 
    preg_match_all($sRegex, $sContent, $aMatches);
    
    //print_r($aMatches);
    
    // Runn through the Array 
    foreach ($aMatches[0] as $iKey=>$sOldEntry) {
    
        // Remove the Old Entry
        $sContent = str_replace($sOldEntry, '', $sContent);
        
        // fetch the right Placeholder
        $sToPlaceHolder="<!--(PH) ".$aMatches[1][$iKey]." -->";
        
        // Add at begin or end of placeholder block
        if ($aMatches[2][$iKey]=="+") {
   
            // here we add to the beginning of the block 
            $sInsert = $sToPlaceHolder.
                       "\n".
                       trim($aMatches[3][$iKey]);
   
        } else {
            
            // here we add to the end of the block
           $sInsert = trim($aMatches[3][$iKey]).
                      "\n".
                      $sToPlaceHolder;
        
        }
        $sContent = preg_replace('/'.preg_quote($sToPlaceHolder).'/s', $sInsert, $sContent,1);  
        
    }
    return $sContent; 
}

