<?php
$oTwig->addFunction( new \Twig\TwigFunction("Ln_",
    function ($str, ...$args){ 
        // use WBCE Ln_ function
        return Ln_($str, ...$args);         
    }
));