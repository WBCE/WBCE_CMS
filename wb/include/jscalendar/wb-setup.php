<?php

// $Id: wb-setup.php 1369 2011-01-06 06:17:01Z Luisehahne $

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
	import jscalendar css and scripts
*/

if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

?>
<!--<style type="text/css">-->
<?php
// require_once(WB_PATH.'/include/jscalendar/calendar-system.css');
?>
<!--</style>  -->
<script type="text/javascript" src="<?php echo WB_URL ?>/include/jscalendar/calendar.js"></script>
<?php // some stuff for jscalendar
	// language
	$jscal_lang = defined('LANGUAGE')?strtolower(LANGUAGE):'en';
	$jscal_lang = $jscal_lang!=''?$jscal_lang:'en';
	if(!file_exists(WB_PATH."/include/jscalendar/lang/calendar-$jscal_lang.js")) {
		$jscal_lang = 'en';
	}
	// today
	$jscal_today = gmdate('Y/m/d H:i');
	// first-day-of-week
	$jscal_firstday = '1'; // monday
	if(LANGUAGE=='EN')
		$jscal_firstday = '0'; // sunday
	// date and time format for the text-field and for jscal's "ifFormat". We offer dd.mm.yyyy or yyyy-mm-dd or mm/dd/yyyy
	// ATTN: strtotime() fails with "dd.mm.yyyy" and PHP4. So the string has to be converted to e.g. "yyyy-mm-dd", which will work.
	switch(DATE_FORMAT) {
		case 'd.m.Y':
		case 'd M Y':
		case 'l, jS F, Y':
		case 'jS F, Y':
		case 'D M d, Y':
		case 'd-m-Y':
		case 'd/m/Y':
			$jscal_format = 'd.m.Y'; // dd.mm.yyyy hh:mm
			$jscal_ifformat = '%d.%m.%Y';
			break;
		case 'm/d/Y':
		case 'm-d-Y':
		case 'M d Y':
		case 'm.d.Y':
			$jscal_format = 'm/d/Y'; // mm/dd/yyyy hh:mm
			$jscal_ifformat = '%m/%d/%Y';
			break;
		default:
			$jscal_format = 'Y-m-d'; // yyyy-mm-dd hh:mm
			$jscal_ifformat = '%Y-%m-%d';
			break;
	}
	if(isset($jscal_use_time) && $jscal_use_time==TRUE) {
		$jscal_format .= ' H:i';
		$jscal_ifformat .= ' %H:%M';
	}

	// load scripts for jscalendar
?>
<script type="text/javascript" src="<?php echo WB_URL ?>/include/jscalendar/lang/calendar-<?php echo $jscal_lang ?>.js"></script>
<script type="text/javascript" src="<?php echo WB_URL ?>/include/jscalendar/calendar-setup.js"></script>
