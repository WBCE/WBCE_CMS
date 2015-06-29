<?php
  /**
  * QuickSkin Extension lowercase
  * Converts String to lowercase
  *
  * Usage Example:
  * Content:  $template->assign('NAME', 'John Doe');
  * Template: Username: {lowercase:NAME}
  * Result:   Username: john doe
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_lowercase ( $param ) {
    return strtolower( $param );
  }
