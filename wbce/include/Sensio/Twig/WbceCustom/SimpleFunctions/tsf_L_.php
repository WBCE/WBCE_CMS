<?php
$oTwig->addFunction( new \Twig\TwigFunction("L_",
    function ($str, ...$args){ 
        // use WBCE L_ function
        return L_($str, ...$args);         
    }
));
