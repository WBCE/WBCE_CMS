<?php
  /**
  * QuickSkin Extension TXT
  * This function will replace the string with the value of  language array of the module
  *
  * Usage Example:
  * in Template use:{TXT:"OPEN_FILE"}
  *	returns the translated string
  *  
  * 
  * Important Note:
  * ===============
  * In order to use this function, you will need to follow the standard Naming Convention of WebsiteBaker CMS
  * It is: if your Module has the name "myModule", your language array needs to be named "$MOD_MYMODULE[]"
  * If you follow this rule, you can use this function (this extension) flawlessly
  *
  * @author WebsiteBaker Org e.V.
  *
  */
function qx_TXT ( $var ) {
	
	$ret_val = '';
	switch (TRUE)
	{
		// retrieve the MODULE_NAME
		case isset($GLOBALS['tool']): $MODULE_NAME = $GLOBALS['tool']; break;				// AdminTool
		case isset($GLOBALS['section']['module']): $MODULE_NAME = $GLOBALS['section']['module']; break;     // PageType Module
		case isset($GLOBALS['module_dir']): $MODULE_NAME = $GLOBALS['module_dir']; break;	// SnippetType Module
		default: $MODULE_NAME = FALSE;	
	}
	
	if(!isset($MODULE_NAME))
	{
		$ret_val = 'A problem occured. <br />(QuickSkin Extension) qx_TXT issue.';		
	} 
	else 
	{	
		$MODULE_NAME = strtoupper($MODULE_NAME);
		$LANG_ARRAY = $GLOBALS['MOD_'.$MODULE_NAME];
		$ret_val = isset($LANG_ARRAY[$var]) ? $LANG_ARRAY[$var] : ('<span style="color:#777">'.$var.'</span>');
	}	
	return $ret_val;
}
