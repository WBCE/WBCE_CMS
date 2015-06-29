<?php
  /**
  * QuickSkin Extension config
  * Print Content of Configuration Parameters
  *
  * Usage Example:
  * Content:  $_CONFIG['webmaster']  =  'philipp@criegern.de';
  * Template: Please Contact Webmaster: {config:"webmaster"}
  * Result:   Please Contact Webmaster: philipp@criegern.de
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_config ( $param ) {
    global $_CONFIG;

    return $_CONFIG[$param];
  }
