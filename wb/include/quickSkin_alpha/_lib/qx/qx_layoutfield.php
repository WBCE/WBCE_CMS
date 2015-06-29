<?php
  /**
  * QuickSkin Extension layoutfield
  * This function is to be used with WebsiteBakers Layout-Fields
  * Layout-Fields are mostly <textarea> fields, where the modules Frontend Template is specified
  *
  * Usage Example:
  * Content:  $template->assign('loop_template', $settings['loop']);  
  * Template: <textarea name="loop" rows="10" cols="1">{layoutfield:loop_template}</textarea>
  *
  * @author WebsiteBaker Org e.V.
  */
  function qx_layoutfield( $db_string ) {
  // Set raw html <'s and >'s to be replaced by friendly html code to be used in Layout Fields
	$raw = array('<', '>');
	$friendly = array('&lt;', '&gt;');
	
    return str_replace( $raw, $friendly, $db_string );
  }
