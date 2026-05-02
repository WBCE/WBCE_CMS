<?php

$oTwig->addFunction(new \Twig\TwigFunction("uniqid", 
    function ($sPrefix="") { 
        return uniqid($sPrefix); 
   }
)); 
