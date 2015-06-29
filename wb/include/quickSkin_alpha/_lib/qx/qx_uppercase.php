<?php
  /**
  * QuickSkin Extension uppercase
  * Converts String to uppercase
  *
  * Usage Example:
  * Content:  $template->assign('NAME', 'John Doe');
  * Template: Username: {uppercase:NAME}
  * Result:   Username: JOHN DOE
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_uppercase ( $param ) {
    return strtoupper( $param );
  }
