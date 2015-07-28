<?php

// $Id: jscalendar-functions.php 915 2009-01-21 19:27:01Z Ruebenwurzel $

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

if(!defined('WB_URL')) { exit(header('Location: ../index.php')); }

// convert string from jscalendar to timestamp.
// converts dd.mm.yyyy and mm/dd/yyyy, with or without time.
// strtotime() may fails with e.g. "dd.mm.yyyy" and PHP4
function jscalendar_to_timestamp($str, $offset='') {
	$str = trim($str);
	if($str == '0' || $str == '')
		return('0');
	if($offset == '0')
		$offset = '';
	// convert to yyyy-mm-dd
	// "dd.mm.yyyy"?
	if(preg_match('/^\d{1,2}\.\d{1,2}\.\d{2}(\d{2})?/', $str)) {
		$str = preg_replace('/^(\d{1,2})\.(\d{1,2})\.(\d{2}(\d{2})?)/', '$3-$2-$1', $str);
	}
	// "mm/dd/yyyy"?
	if(preg_match('#^\d{1,2}/\d{1,2}/(\d{2}(\d{2})?)#', $str)) {
		$str = preg_replace('#^(\d{1,2})/(\d{1,2})/(\d{2}(\d{2})?)#', '$3-$1-$2', $str);
	}
	// use strtotime()
	if($offset!='')
		return(strtotime($str, $offset));
	else
	return(strtotime($str));
}

?>