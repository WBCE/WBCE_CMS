<?php
  /**
  * QuickSkin Extension vardump
  * Prints variable content for debug purpose
  *
  * Usage Example I:
  * Content:  $template->assign('test', array( "name1", "value1",  "name2", "value2" ) );
  *
  * Template: DEBUG: {vardump:test}
  *
  * Result:   DEBUG: Array
  *                  (
  *                      [name1] => value1
  *                      [name2] => value2
  *                  )
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_vardump ( $param ) {
    return '<pre>' . print_r($param, true) . '</pre>';
  }
