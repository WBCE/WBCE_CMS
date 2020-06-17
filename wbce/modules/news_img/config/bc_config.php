<?php

include __DIR__.'\wbce_config.php';

if(!function_exists('jscalendar_to_timestamp')) {
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
}

// datetimepicker
$datetimepicker = 'xdsoft';