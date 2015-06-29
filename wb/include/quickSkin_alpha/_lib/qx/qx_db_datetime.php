<?php
  /**
  * QuickSkin Extension db_datetime
  * Convert Oracle Date (British Format) to Local Formatted Date and Time
  *
  * Usage Example:
  * Content:  $template->assign('UPDATE', $result['LAST_UPDATE_DATE_TIME']);
  * Template: Last update: {db_datetime:UPDATE}
  * Result:   Last update: 30.01.2003 - 12:46:00
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_db_datetime ( $param ) {
    global $configuration;

    if (preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $param)) {
      return date( $configuration['datetime_format'],  strtotime($param) );
    } else {
      return 'Invalid date format!';
    }
  }
