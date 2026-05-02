<?php

/**
 * Get a Config from {TP}settings DB table using setting name
 */
$oTwig->addFunction(new \Twig\TwigFunction("getSetting", 
    function ($sConfigName = '') {        
        return Settings::Get($sConfigName);
   }
));

