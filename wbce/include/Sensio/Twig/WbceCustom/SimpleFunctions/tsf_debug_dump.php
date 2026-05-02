<?php

$oTwig->addFunction(new \Twig\TwigFunction("debug_dump", 
   function ($var = '', $caption = '', $isVarDump = false) use ($oTwig) {
       return debug_dump($var, $caption, $isVarDump, true);
   }
));

