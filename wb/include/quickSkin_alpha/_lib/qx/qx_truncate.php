<?php
  /**
  * QuickSkin Extension truncate
  * Restricts a String to a specific number characters
  *
  * Usage Example:
  * Content:  $template->assign('TEASER', 'PHP 4.3.0RC1 has been released. This is the first release candidate');
  * Template: News: {truncate:TEASER,50} ... [more]
  * Result:   News: QuickSkin version 5.0 has been released. This is the first ... [more]
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_truncate ( $param, $size ) {
    return substr( $param, 0, $size );
  }
