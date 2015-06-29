<?php
 /**
  * QuickSkin Extension urlencode
  * Inserts URL-encoded String
  *
  * Usage Example:
  * Content:  $template->assign('PARAM', 'Delete User!');
  * Template: go.php?param={urlencode:PARAM}
  * Result:   go.php?param=Delete+User%21
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_urlencode ( $param ) {
    return urlencode( $param );
  }
