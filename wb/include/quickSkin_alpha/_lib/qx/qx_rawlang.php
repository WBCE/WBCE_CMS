<?php

function qx_rawlang($var) {
	$retval = '{'.$var.'}';
	$arr = explode('_', $var);
	$var0 = array_shift($arr);
	while( sizeof($arr) > 0 ) {
		$var1 = implode('_', $arr);
		$retval = ( isset($GLOBALS[$var0][$var1]) ? $GLOBALS[$var0][$var1] : $retval );
		$var0 .= '_'.array_shift($arr);
	}
	return rawurlencode ($retval);
}
