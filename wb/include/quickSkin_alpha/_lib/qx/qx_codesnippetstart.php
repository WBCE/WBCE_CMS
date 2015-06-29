<?php
  /**
  * QuickSkin Extension codesnippetstart
  * required for the start of a code snippet
  *
  * Usage Example:
  * Template: Code Snippet<br />{codesnippetstart:}
  *
  * @author Andy Prevost andy@codeworxtech.com
  */
  function qx_codesnippetstart ( $param='' ) {
    /*
    nogutter = no line numbers
    nocontrols = no menu at top
    collapse = = display nothing, menu at top will display '+ expand source'
    firstline[value] = starting number to start count
    showcolumns = shows "ruler" at top
    */
    if ( $param = '' ) {
      return '<pre name="code" class="php">';
    } else {
      return '<pre name="code" class="php:' . $param . '">';
    }
  }
