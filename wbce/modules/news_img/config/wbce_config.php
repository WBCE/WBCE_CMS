<?php

if(!defined('CAT_PATH')) {
    require_once WB_PATH."/include/jscalendar/jscalendar-functions.php";
}

// calendar
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

// datetimepicker
$datetimepicker = 'xdsoft';