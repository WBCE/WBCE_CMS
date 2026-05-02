<?php

// getIDKEY  // use IDKEYs in Twig Templates directly. No need to hand them over anymore.
$oTwig->addFunction(new \Twig\TwigFunction("getIDKEY", 
    function ($uID) {        
        $oEngine = isset($GLOBALS['wb']) ? $GLOBALS['wb'] : $GLOBALS['admin']; 
        return $oEngine->getIDKEY($uID);
    }
));

