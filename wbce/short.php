<?php

/*
Short.php & .htaccess example & Dropletcode
Version 3.0 - June 19, 2013
Developer - Ruud Eisinga / www.dev4me.nl

 */

// Pages Directory
$_pages = "/pages";

// file ending
$_ext = ".php";

//Change this to point to your existing 404 page.
define('ERROR_PAGE', '/'); 

if (isset($_GET['_wb'])) {
    
    // Stopping some unwanted behavior
    // One Directory up ../  (\.\.\/) 
    // Home ./ (\.\/)
    // Link to external file // (\/\/)
    if (preg_match ("/(\.\.\/|\.\/|\/\/)/", $_GET['_wb'])) {
        header('Location: ' . ERROR_PAGE); exit;    
    }

    $parsed=parse_url($_SERVER['REQUEST_URI']);
    $pathed=pathinfo($parsed["path"]);
    $scriptName=preg_replace("/(".$_ext.").+$/u","$1",$parsed["path"]);
    
    $_SERVER['PHP_SELF'] = $scriptName;
    $_SERVER['SCRIPT_NAME'] = $scriptName;
    $page = trim($_GET['_wb'], '/');
    $fullpag = dirname(__FILE__) . $_pages . '/' . $page . $_ext;
    if (file_exists($fullpag)) {
        chdir(dirname($fullpag));
        include $fullpag;
    } else {
        $page = trim(ERROR_PAGE, '/');
        $fullpag = dirname(__FILE__) . $_pages . '/' . $page . $_ext;
        if (file_exists($fullpag)) {
            chdir(dirname($fullpag));
            include $fullpag;
        } else {
            header('Location: ' . ERROR_PAGE); exit;
        }
    }
} else {
    header('Location: ' . ERROR_PAGE); exit;
}

