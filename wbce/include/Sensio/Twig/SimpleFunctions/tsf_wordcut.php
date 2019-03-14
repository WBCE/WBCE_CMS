<?php

/**
 * conversion of wordwrap2 from the WB News module
 */
$oTwig->addFunction(new Twig_SimpleFunction("wordcut", 
   function ($sStr, $iLength = 200, $sBreak = '\n', $bCut = true) {
       $sTmp = strip_tags($sStr);
       // consider start position if short content starts with <p> or <div>
       $sStr = (preg_match('#^(<(p|div)>)#', $sTmp, $aMatches)) ? strlen($aMatches[0]) : $sTmp;
       $sStr = html_entity_decode($sStr); //first decode
       $sOut = wordwrap($sStr, $iLength, $sBreak, $bCut); //now wordwrap
       $sOut = htmlentities($sOut); //re-encode the entities
       $sOut = str_replace(htmlentities($sBreak), $sBreak, $sOut); //put back the break
       // return the first line
       $aTmp = explode($sBreak, $sOut);
       return $aTmp[0] . (strlen($aTmp[0]) > $iLength ? '... ' : '');
   }
));   

$oTwig->addFunction(new Twig_SimpleFunction("debug_dump", 
   function ($mVar = '', $sCaption = '', $bVarDump = false) use ($oTwig) {
       return debug_dump($mVar, $sCaption, $bVarDump, $oTwig->getCompiler()->getFilename());
   }
));   