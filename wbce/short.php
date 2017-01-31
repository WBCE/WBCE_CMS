<?php

/*
Short.php & .htaccess example & Dropletcode
Version 4.0 - November 24, 2015
Developer - Ruud Eisinga / www.dev4me.nl
Special thanks to: Norbert Heimsath

 */

$_pages = "/pages";
$_ext = ".php";

define('ERROR_PAGE', '/'); //Change this to point to your existing 404 page without the /pages/ and .php extension!

if (isset($_GET['_wb'])) {
	// Stopping some unwanted behavior
	// - One Directory up   ../   (\.\.\/)
	// - Home   ./   (\.\/)
	// - Link to external file   //   (\/\/)
	if (preg_match ("/(^\.|\.\.|\\\\|\/\/)/s", $_GET['_wb'])) {
		header('Location: ' . ERROR_PAGE); 
		exit;
	}

    // We need additional steps to make sure we stay in side the WB Directory
    $_GET['_wb'] = realpath($_GET['_wb']);

    // file does not exist
    if ($_GET['_wb'] === false) {
        header('Location: ' . ERROR_PAGE); 
		exit;
	}
    
    // WB Pfad enthalten
    if (!preg_match("/^".preg_quote(realpath(__DIR__))."/s", $_GET['_wb'])){
        header('Location: ' . ERROR_PAGE); 
		exit;
	}

	
	// create safe PHP_SELF and SCRIPT_NAME for modules using them
    $parsed = parse_url($_SERVER['REQUEST_URI']);
    $pathed = pathinfo($parsed["path"]);
    $scriptName = preg_replace("/(.php|.html|.htm).+$/u","$1",$parsed["path"]);
	$_SERVER['PHP_SELF'] = $scriptName;
    $_SERVER['SCRIPT_NAME'] = $scriptName;

	// find page to show
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
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found...");
            include $fullpag;
        } else {
            header('Location: ' . ERROR_PAGE,true,404);
        }
    }
} else {
    header('Location: ' . ERROR_PAGE,true,404);
}



/* droplet code
global $wb;
$wb->preprocess( $wb_page_data);
$linkstart = WB_URL.PAGES_DIRECTORY;
$linkend = PAGE_EXTENSION;
$nwlinkstart = WB_URL;
$nwlinkend = '/';

preg_match_all('~'.$linkstart.'(.*?)\\'.$linkend.'~', $wb_page_data, $links);
foreach ($links[1] as $link) {
    $wb_page_data = str_replace($linkstart.$link.$linkend, $nwlinkstart.$link.$nwlinkend, $wb_page_data);
}
return true;
-- END droplet code */

/* .htaccess
RewriteEngine On
# If called directly - redirect to short url version
RewriteCond %{REQUEST_URI} !/pages/intro.php
RewriteCond %{REQUEST_URI} /pages
RewriteRule ^pages/(.*).php$ /$1/ [R=301,L]

# Send the request to the short.php for processing
RewriteCond %{REQUEST_URI} !^/(pages|admin|framework|include|languages|media|account|search|temp|templates/.*)$
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([\/\sa-zA-Z0-9._-]+)$ /short.php?_wb=$1 [QSA,L]
-- END .htaccess */
