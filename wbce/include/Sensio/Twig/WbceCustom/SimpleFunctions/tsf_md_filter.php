<?php

$oTwig->addFunction(new \Twig\TwigFunction("parse_simple_md_tags", 
    function ($str) {
        return parse_simple_md_tags($str);
    }
));

$oTwig->addFunction(new \Twig\TwigFunction("md_filter", 
    function ($str) {
        return parse_simple_md_tags($str);
    }
));

if(function_exists('parse_simple_md_tags') == false){
    function parse_simple_md_tags($string) {
        $mdTags = [
            '/\*{3}(.*?)\*{3}/' => '<strong><em>$1</em></strong>', // ***strong with emphasis***
            '/\*{2}(.*?)\*{2}/' => '<strong>$1</strong>',          // **strong**
            '/\*(.*?)\*/'       => '<em>$1</em>',                  // *emphasis*
            '/_(.*?)_/'         => '<i>$1</i>',                    // _italic_
            '/_(.*?)_/'         => '<u>$1</u>',                    // _underscore_
            '/\`(.*?)\`/'       => '<tt>$1</tt>',                  // `variable`
        ];

        foreach ($mdTags as $find => $replace) {
            $string = preg_replace($find, $replace, $string);
        }

        return $string;
    }
}