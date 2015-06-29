<?php
  /**
  * QuickSkin Extension current_time
  * Print Current Time
  *
  * Usage Example:
  * Template: Time: {current_time:}
  * Result:   Time: 12:46:00
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_current_time ($meridiem='A') {
    global $_CONFIG;

    $timeFormat = 'g:i:s ' . $meridiem;
    if ( !empty($_CONFIG['time_format']) ) {
      $timeFormat = $_CONFIG['time_format'];
    }
    return date( $timeFormat );
  }
