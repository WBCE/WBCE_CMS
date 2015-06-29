<?php
  /**
  * QuickSkin Extension encode
  * Encodes String (md5)
  *
  * Usage Example:
  * Content:  $template->assign('ID', 123);
  * Template: <a href="delete.php?id={encode:ID}">delete</a>
  * Result:   <a href="delete.php?id=7B600B6476167773626A">delete</a>
  *
  * @author Andy Prevost andy@codeworxtech.com
  */

  function qx_encode ( $param ) {
    return md5( $param );
  }
