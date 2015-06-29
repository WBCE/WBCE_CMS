<?php
  /**
  * QuickSkin Extension WB_TXT
  * This function will replace the string with the value of WebsiteBaker CMS's $TEXT[] array 
  *
  * Usage Example:
  * in Template use:{WB_TXT:"SAVE"}
  *	returns the translated string
  *
  * @author WebsiteBaker Org e.V.
  *
  */
function qx_WB_TXT ( $var ) {
	$LANG_ARRAY = $GLOBALS['TEXT'];		
	$ret_val = isset($LANG_ARRAY[$var]) ? $LANG_ARRAY[$var] : ('<span style="color:#777">'.$var.'</span>');
	return $ret_val;	
}
