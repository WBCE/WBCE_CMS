<?php

$oTwig->addFunction(new \Twig\TwigFunction("CodeMirror", 
    function ($id_attr = "code", $syntax = 'js', $options = []) {         
        if (class_exists('CodeEditor')) {
            CodeEditor::init($id_attr, $syntax, $options);
        }
    }
));

$oTwig->addFunction(new \Twig\TwigFunction("CodeEditor",
    function (string $id_attr = 'code', string $syntax = 'php', array $options = []) {
        if (class_exists('CodeEditor')) {
            CodeEditor::init($id_attr, $syntax, $options);
        }
    }
));