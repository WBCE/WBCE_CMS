<?php
/**
 * insertFile  
 * insert a single JS or CSS File
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertFile", 
    function ($sFileLoc = '', $sDomPos = '', $sType = '') {
        if($sFileLoc == '') return; // return early if no loc is set
        if($sType == ''){
          $sType = pathinfo($sFileLoc, PATHINFO_EXTENSION);
        }
        if($sDomPos == ''){
            $sDomPos == 'BODY BTM-';
            if(strtolower($sType) == 'css'){
                $sDomPos == 'HEAD BTM-';
            }
        }
        $call = 'insert'.strtolower(ucfirst($sType)).'File';
        I::$call($sFileLoc, $sDomPos); 
    }
));

/**
 * insertJsFile  // Twig adaptation of the Insert class method
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertJsFile", 
    function ($uFileLoc = '', $sDomPos = 'BODY BTM-', $sID = '') {
        if(!is_array($uFileLoc)){    
           if ($uFileLoc != '') {
               I::insertJsFile($uFileLoc, $sDomPos, $sID);
           } else {
               return;
           }
       } else {
           foreach ($uFileLoc as $sLoc) {
               I::insertJsFile($sLoc, $sDomPos, $sID);
           }
           return;
       }
   }
));

$oTwig->addFunction(new \Twig\TwigFunction("insertJsCode", 
    function ($sCode = '', $sDomPos = 'HEAD BTM-', $sID = '') {
        I::insertJsCode($sCode, $sDomPos, $sID);
        return;
    }
));

/**
 * insertCssFile  // Twig adaptation of the Insert class method
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertCssFile", 
    function ($uFileLoc = '', $sDomPos = 'HEAD BTM-', $sID = '') {
        if(!is_array($uFileLoc)){        
            if ($uFileLoc != '') {
                I::insertCssFile($uFileLoc, $sDomPos, $sID);
            } else {
                return;
            }
        }else{
            foreach ($uFileLoc as $sLoc) {
                I::insertCssFile($sLoc, $sDomPos, $sID);
            }
        }
        return;
    }
));

$oTwig->addFunction(new \Twig\TwigFunction("insertCssCode", 
    function ($sCode = '', $sDomPos = 'HEAD BTM-', $sID = '') {
        I::insertCssCode($sCode, $sDomPos, $sID);
        return;
    }
));