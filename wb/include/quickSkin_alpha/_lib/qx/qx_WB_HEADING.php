<?php
  /**
  * QuickSkin Extension WB_HEADING
  * This function will replace the string with the value of WebsiteBaker CMS's $HEADING[] array 
  *
  * Usage Example:
  * in Template use:{WB_HEADING:"GENERAL_SETTINGS"}
  *	returns the translated string
  *
  * @author WebsiteBaker Org e.V.
  *
  */
function qx_WB_HEADING ( $var ) {
	$LANG_ARRAY = $GLOBALS['HEADING'];		
	$ret_val = isset($LANG_ARRAY[$var]) ? $LANG_ARRAY[$var] : ('<span style="color:#777">'.$var.'</span>');
	return $ret_val;	
}
