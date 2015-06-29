<?php
  /**
  * QuickSkin Extension stringformat
  * Inserts a formatted String
  *
  * Usage Example:
  * Content:  $template->assign('SUM', 25);
  * Template: Current balance: {stringformat:SUM,'$ %01.2f'}
  * Result:   Current balance: $ 25.00
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_stringformat ( $param, $format ) {
    return sprintf( $format,  $param );
  }
