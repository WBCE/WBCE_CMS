<?php
  /**
  * QuickSkin Extension regex
  * Regular Expression String Replace
  *
  * Usage Example:
  * Content:  $template->assign('NAME', '*My Document*');
  * Template: Document Name: {regex:NAME,'/[^a-z0-9]/i','_'}
  * Result:   Document Name: _My_Document_
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_regex ( $param, $pattern, $replace ) {
    return preg_replace( $pattern, $replace, $param );
  }
