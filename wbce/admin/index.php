<?php

// $Id: index.php 1614 2012-02-18 01:51:59Z Luisehahne $

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

// Include config file
if(!defined('WB_URL') && file_exists(realpath('../config.php'))) {
	require('../config.php');
}

// Check if the config file has been set-up
if(!defined('WB_PATH')) {
	header("Location: ../install/index.php");
} else {
	header('Location: '.ADMIN_URL.'/start/index.php');
}
