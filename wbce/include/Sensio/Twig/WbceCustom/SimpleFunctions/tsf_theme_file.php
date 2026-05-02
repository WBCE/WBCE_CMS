<?php
defined('WB_PATH') or exit('sorry, no sufficient privileges.');

$oTwig->addFunction(new \Twig\TwigFunction("theme_file", 
    function ($sFileLoc = 'none') {
        return theme_file($sFileLoc);
   }
));