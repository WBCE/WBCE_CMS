<?php
  /**
  * QuickSkin Extension dateformatgrid
  * Changes Dateformat
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_dateformatgrid ( $param, $format = 'F j, Y' ) {
    list($month,$day,$year,$hour,$minute,$second) = split("[-:T\.]", $param);

    // handle empty values
    if (! $hour) { $hour = '00'; }
    if (! $minute) { $minute = '00'; }
    if (! $second) { $second = '00'; }

    return date( $format, mktime($hour, $minute, $second, $month, $day, $year));
  }
