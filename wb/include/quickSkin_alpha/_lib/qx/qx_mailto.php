<?php
  /**
  * QuickSkin Extension mailto
  * creates Mailto-Link from email address
  *
  * Usage Example:
  * Content:  $template->assign('CONTACT', 'philipp@criegern.de' );
  * Template: Mail to Webmaster: {mailto:CONTACT}
  * Result:   Mail to Webmaster: <a href="mailto:philipp@criegern.de">philipp@criegern.de</a>
  *
  * @author Andy Prevost andy@codeworxtech.com
  */
  function qx_mailto ( $param,$name='',$encode=false ) {
    if ($encode === false) {
      if ( $name != '' ) {
        return '<a href="mailto:' . $param . '">' . $name . '</a>';
      } else {
        return '<a href="mailto:' . $param . '">' . $param . '</a>';
      }
    } else {
      $obfuscatedMailTo = '';
      $mailto = "mailto:";
      $length = strlen($mailto);
      for ($i = 0; $i < $length; $i++) {
        $obfuscatedMailTo .= "&#" . ord($mailto[$i]);
      }
      $mailto = $param;
      $length = strlen($mailto);
      $param  = '';
      for ($i = 0; $i < $length; $i++) {
        $param .= "&#" . ord($mailto[$i]);
      }
      if ( $name != '' ) {
        $mailto = $name;
        $length = strlen($mailto);
        $name  = '';
        for ($i = 0; $i < $length; $i++) {
          $name .= "&#" . ord($mailto[$i]);
        }
        return '<a href="' . $obfuscatedMailTo . ':' . $param . '">' . $name . '</a>';
      } else {
        return '<a href="' . $obfuscatedMailTo . ':' . $param . '">' . $param . '</a>';
      }

    }
  }
