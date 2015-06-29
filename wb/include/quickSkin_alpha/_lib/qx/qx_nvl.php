<?php
  /**
  * QuickSkin Extension nvl
  * Insert a default value if variable is empty
  *
  * Usage Example:
  * Content:  $template->assign('PREVIEW1', 'picture_21.gif');
  * Template: <img src="{nvl:PREVIEW1,'not_available.gif'}"> / <img src="{nvl:PREVIEW2,'not_available.gif'}">
  * Result:   <img src="picture_21.gif"> / <img src="not_available.gif">
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_nvl ( $param, $default ) {
    if (strlen($param)) {
      return ( $param );
    } else {
      return ( $default );
    }
  }
