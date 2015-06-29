<?php
  /**
  * QuickSkin Extension count
  * count element of an array
  *
  * Usage Example:
  * Content:  $template->assign('list', array('a','b'));
  * Template: count: {count:list}
  * Result:   count: 2
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_count ( $param ) {
    $temp = count( $param );
    return $temp;
  }
