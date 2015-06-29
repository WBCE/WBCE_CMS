<?php
  /**
  * QuickSkin Extension current_date
  * Print Current Date
  *
  * Usage Example:
  * Template: Today: {current_date:}
  * Result:   Today: 30.01.2003
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_current_date () {
    global $_CONFIG;

    $dateFormat = 'F j, Y';
    if ( !empty($_CONFIG['date_format']) ) {
      $dateFormat = $_CONFIG['date_format'];
    }
    return date( $dateFormat );
  }
