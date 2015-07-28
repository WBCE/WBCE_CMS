<?php
/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2009, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
/*
  Short.php & .htaccess example & Dropletcode
  Version 3.0 - June 19, 2013
  Developer - Ruud Eisinga / www.dev4me.nl
  
*/
 
$_pages = "/pages";
$_ext = ".php";
define('ERROR_PAGE' , '/'); //Change this to point to your existing 404 page.

if (isset($_GET['_wb'])) {
	$_SERVER['PHP_SELF'] = $_SERVER['REQUEST_URI'];  
	$_SERVER['SCRIPT_NAME'] = $_SERVER['REQUEST_URI'];
	$page = trim($_GET['_wb'],'/');
	$fullpag = dirname(__FILE__).$_pages.'/'.$page.$_ext;
	if(file_exists($fullpag)) {
		chdir(dirname($fullpag));
		include ($fullpag);
	} else {
		$page = trim(ERROR_PAGE,'/');
		$fullpag = dirname(__FILE__).$_pages.'/'.$page.$_ext;
		if(file_exists($fullpag)) {
			chdir(dirname($fullpag));
			include ($fullpag);
		} else {	
			header('Location: '.ERROR_PAGE);
		}
	}
} else {	
	header('Location: '.ERROR_PAGE);
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

# Send the request to the wb.php for processing
RewriteCond %{REQUEST_URI} !^/(pages|admin|framework|include|languages|media|account|search|temp|templates/.*)$
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([\/\sa-zA-Z0-9._-]+)$ /wb.php?_wb=$1 [QSA,L]
-- END .htaccess */


