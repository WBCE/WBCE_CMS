<?php
function qx_globals($var) {
	$retval = '{'.$var.'}';
	$retval = (array_key_exists ( $var, $GLOBALS ) ? $GLOBALS[$var] : $retval);
	return $retval;
}
