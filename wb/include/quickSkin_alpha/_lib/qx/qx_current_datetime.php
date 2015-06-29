<?php
  /**
  * QuickSkin Extension current_datetime
  * Print Current Date and Time
  *
  * Usage Example:
  * Template: Time: {current_datetime:}
  * Result:   Time: 01.01.2009 - 12:46:00
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_current_datetime () {
    global $_CONFIG;

    $datetimeFormat = 'F j, Y H:i:s';
    if ( !empty($_CONFIG['datetime_format']) ) {
      $datetimeFormat = $_CONFIG['datetime_format'];
    }
    return date( $datetimeFormat );
  }
