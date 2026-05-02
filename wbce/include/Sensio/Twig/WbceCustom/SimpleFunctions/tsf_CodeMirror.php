<?php

$oTwig->addFunction(new \Twig\TwigFunction("CodeMirror", 
    function ($id_attr="code", $syntax = 'js', $options = []) {         
        if(function_exists('registerCodeMirror')){
            return registerCodeMirror($id_attr, $syntax, $options);
            // see /modules/CodeMirror_Config/initialize.php
            // to access all possible options
        } else {
            return;
        }
    }
));