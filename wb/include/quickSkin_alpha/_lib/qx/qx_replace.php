<?php
  /**
  * QuickSkin Extension replace
  * String Replace
  *
  * Usage Example:
  * Content:  $template->assign('PATH', $path_tranlated);  //  C:\Apache\htdocs\php\test.php
  * Template: Script Name: {replace:PATH,'\\','/'}
  * Result:   Script Name: C:/Apache/htdocs/php/test.php
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_replace ( $param, $pattern, $replace ) {
    return str_replace( $pattern, $replace, $param );
  }
