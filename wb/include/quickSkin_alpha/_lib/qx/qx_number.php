<?php
  /**
  * QuickSkin Extension number
  * Format a number with grouped thousands
  *
  * Usage Example:
  * Content:  $template->assign('SUM', 2500000);
  * Template: Current balance: {number:SUM}
  * Result:   Current balance: 2.500.000,00
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_number ( $param ) {
    global $_CONFIG;

    $decimalChar = ',';
    if ( !empty($_CONFIG['decimal_char']) ) {
      $decimalChar = $_CONFIG['decimal_char'];
    }
    $decimalPlaces = 2;
    if ( !empty($_CONFIG['decimal_places']) ) {
      $decimalPlaces = $_CONFIG['decimal_places'];
    }
    $thousandsSep = '.';
    if ( !empty($_CONFIG['thousands_sep']) ) {
      $thousandsSep = $_CONFIG['thousands_sep'];
    }

    return number_format( $param, $decimalChar, $decimalPlaces, $thousandsSep );
  }
