<?php

// $Id: index.php 789 2008-04-02 19:49:18Z doc $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

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

$starttime = array_sum(explode(" ",microtime()));

// Include config file
//require_once('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }


// Create new frontend object
$wb = new frontend();

//--------------------------------------------------------
// Brauchen wir hier was davon?


/*
// Figure out which page to display
// Stop processing if intro page was shown
$wb->page_select() or die();

// Collect info about the currently viewed page
// and check permissions
$wb->get_page_details();

// Collect general website settings
$wb->get_website_settings();
--------------------------------------------------------------*/
// Load functions available to templates, modules and code sections
// also, set some aliases for backward compatibility
require(WB_PATH.'/framework/frontend.functions.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>iframe</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET :'utf-8';?>" />
<meta name="robots" content="noindex,nofollow" />
<link href="comment_frame.css" rel="stylesheet" type="text/css"/>
	
</head>
<body>
<table id="wraptable"><tr><td>
<?php page_content();?>
</td><td>

</td></tr></table>
<script type="text/javascript" src="comments.js"></script>
</body></html>



