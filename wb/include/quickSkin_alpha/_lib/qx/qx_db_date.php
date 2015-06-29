<?php
  /**
  * QuickSkin Extension db_date
  * Convert Oracle Date (British Format) to Local Formatted Date
  *
  * Usage Example:
  * Content:  $template->assign('UPDATE', $result['LAST_UPDATE_DATE_TIME']);
  * Template: Last update: {db_date:UPDATE}
  * Result:   Last update: 30.01.2003
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_db_date ( $param ) {
    global $configuration;

    if (preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $param)) {
      return date( $configuration['date_format'],  strtotime($param) );
    } else {
      return 'Invalid date format!';
    }
  }
