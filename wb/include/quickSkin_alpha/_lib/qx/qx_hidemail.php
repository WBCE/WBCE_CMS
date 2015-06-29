<?php
  /**
  * QuickSkin Extension hidemail
  * Protects email address from being scanned by spam bots
  *
  * Usage Example:
  * Content:  $template->assign('AUTHOR', 'andy@codeworxtech.com' );
  * Template: Author: {hidemail:AUTHOR}
  * Result Source:   Author: andy&#64;codeworxtech&#46;com
  * Result Display:  Author: andy@codeworxtech.com
  *
  * @author Andy Prevost andy@codeworxtech.com
  */
  function qx_hidemail ( $param ) {
    return str_replace('@', '&#64;', str_replace('.', '&#46;', $param));
  }
