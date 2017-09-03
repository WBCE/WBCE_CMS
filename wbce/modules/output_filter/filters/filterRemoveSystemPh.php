<?php 
 function doFilterRemoveSystemPh($content) {
    $content=preg_replace("/<!--\(PH\).*?-->/s" ,"", $content);
    return $content;
 }