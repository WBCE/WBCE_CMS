<?php
  /**
  * QuickSkin Extension trim
  * Removes leading and trailing Whitespaces and Line Feeds
  *
  * Usage Example:
  * Content:  $template->assign('LINK', ' Click Here  ');
  * Template: <a href="/">{trim:LINK}</a>
  * Result:   <a href="/">Click Here</a>
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_trim ( $param ) {
    return trim( $param );
  }
