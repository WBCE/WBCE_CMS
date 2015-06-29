<?php
  /**
  * QuickSkin Extension session
  * Print Content of Session variables
  *
  * Usage Example:
  * Content:  $_SESSION['userName']  =  'Philipp von Criegern';
  * Template: Current User: {session:"userName"}
  * Result:   Current User: Philipp von Criegern
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_session ( $param ) {
    return $_SESSION[$param];
  }
