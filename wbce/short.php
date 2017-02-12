<?php
/*

Short.php & .htaccess example & Dropletcode
Version 4.1.1 - Feb 02, 2017
Developer - Ruud Eisinga / www.dev4me.nl
Special thanks to: Norbert Heimsath

*/

// Configurable options, make sure it matches your WB config
$_pages = "/pages";
$_ext = ".php";
define('ERROR_PAGE', '/'); //Change this to point to your existing 404 page without the /pages/ and .php extension!


// Do not change anything below this line!
if (isset($_GET['_wb'])) {

    // Read the name of the page to be opened 
    $page = trim($_GET['_wb'], '/');
    unset($_GET['_wb']);
	
    // do some Basic Regex filter stopping ". at beginning" , ".. somewhere in path",  
    // or "\ somewhere in path"
    // or "// somewhere in path" and "% chars"
    if (preg_match ("/(^\.|\.\.|\\\\|\/\/|\%)/s", $page)) {
        header('Location: ' . ERROR_PAGE ); 
        exit;
    }
    
    // construct the path to call 
    $fullPagePath = __DIR__ . $_pages . '/' . $page . $_ext;
    
    // We need additional steps to make sure we stay in side the WB Directory
    // in case someone managed to hide some ".." or "//" by using some encoding tricks
	// Note: If the path is not existing, realpath will return false
    if($fullPagePath = realpath($fullPagePath)) {
		// Make sure the path is not outside the current directory (open_basedir simulation)
		$rex="/^".preg_quote(realpath(__DIR__),"/")."/s";
		if (!preg_match($rex, $fullPagePath)){
			$fullPagePath = false;
		}
	}
	
	// create safe PHP_SELF and SCRIPT_NAME for modules using them
    $parsed = parse_url($_SERVER['REQUEST_URI']);
    $scriptName = preg_replace("/(.php|.html|.htm).+$/u","$1",$parsed["path"]);
	$_SERVER['PHP_SELF'] = $scriptName;
    $_SERVER['SCRIPT_NAME'] = $scriptName;

	// find page to show
    if ($fullPagePath !== false && file_exists($fullPagePath)) {
        chdir(dirname($fullPagePath));
        include $fullPagePath;
    } else {
        $page = trim(ERROR_PAGE, '/');
		$fullPagePath = __DIR__ . $_pages . '/' . $page . $_ext;
        if (file_exists($fullPagePath)) {
            chdir(dirname($fullPagePath));
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found...");
            include $fullPagePath;
        } else {
            header('Location: ' . ERROR_PAGE);
        }
    }
} else {
    header('Location: ' . ERROR_PAGE);
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


