<?php 
 function doFilterRemoveSystemPh($content) {
    $content=preg_replace("/<!--\(PH\).*?-->/s" ,"", $content);
    // remove any empty lines in the content
    $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
    return $content;
 }
