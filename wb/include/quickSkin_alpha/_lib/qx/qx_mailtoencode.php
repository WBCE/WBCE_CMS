<?php
  /**
  * QuickSkin Extension mailtoencode
  * Protects email address from being scanned by spam bots
  * and optionally builds full mailto: link
  *
  * Usage Example 1:
  * Content:  $template->assign('AUTHOR', 'yourname@yourdomain.com' );
  * Template: Author: {mailtoencode:AUTHOR}
  * Result:   Author: <a href="&#109&#97&#105&#108&#116&#111&#58&#121;&#111;&#117;&#114;&#110;&#97;&#109;&#101;&#64;&#121;&#111;&#117;&#114;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;">yourname&#64;yourdomain&#46;com</a>
  *
  * Usage Example 2 (Email address only):
  * Template: Author: {mailtoencode:"yourname@yourdomain.com"}
  * Result:   Author: <a href="&#109&#97&#105&#108&#116&#111&#58&#121;&#111;&#117;&#114;&#110;&#97;&#109;&#101;&#64;&#121;&#111;&#117;&#114;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;">yourname&#64;yourdomain&#46;com</a>
  *
  * Usage Example 3 (Email address and name):
  * Template: Author: {mailtoencode:"yourname@yourdomain.com","Your Name"}
  * Result:   Author: <a href="&#109&#97&#105&#108&#116&#111&#58&#121;&#111;&#117;&#114;&#110;&#97;&#109;&#101;&#64;&#121;&#111;&#117;&#114;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;">Your Name</a>
  *
  * Usage Example 4 (Email address and name, encode set to true, and class name):
  * Template: Author: {mailtoencode:"yourname@yourdomain.com","Your Name",true,'white'}
  * Result:   Author: <a href="&#109&#97&#105&#108&#116&#111&#58&#121;&#111;&#117;&#114;&#110;&#97;&#109;&#101;&#64;&#121;&#111;&#117;&#114;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;" class="white">Your Name</a>
  *
  * Usage Example 5 (Email address and name, encode set to true, class name and style defined):
  * Template: Author: {mailtoencode:"yourname@yourdomain.com","Your Name",true,'white','font-size:18px;'}
  * Result:   Author: <a href="&#109&#97&#105&#108&#116&#111&#58&#121;&#111;&#117;&#114;&#110;&#97;&#109;&#101;&#64;&#121;&#111;&#117;&#114;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;" class="white" style="font-size:18px;">Your Name</a>
  *
  * Usage Example 6 (Email address and name, encode set to true, no class name and style defined):
  * Template: Author: {mailtoencode:"yourname@yourdomain.com","Your Name",true,'','font-size:18px;'}
  * Result:   Author: <a href="&#109&#97&#105&#108&#116&#111&#58&#121;&#111;&#117;&#114;&#110;&#97;&#109;&#101;&#64;&#121;&#111;&#117;&#114;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;" style="font-size:18px;">Your Name</a>
  *
  * @author Andy Prevost andy@codeworxtech.com
  */

  if(!function_exists('str_split')){
    function str_split($text, $split = 1){
      $array = array();
      for($i=0; $i < strlen($text); $i++){
        $key = NULL;
        for ($j = 0; $j < $split; $j++){
          $key .= $text[$i];
        }
        array_push($array, $key);
      }
        return $array;
    }
  }

  function qx_mailtoencode ($emailAddy,$text='',$buildLink=true,$class='',$style='') {
    if ( $buildLink ) {
      // mailto: portion
      $obfuscatedMailTo = '';
      $mailto           = "mailto:";
      $length           = strlen($mailto);
      for ($i = 0; $i < $length; $i++) {
        $obfuscatedMailTo .= "&#" . ord($mailto[$i]);
      }
      // END - mailto: portion
      $emailLink  = '<a href="';
      $emailLink .= $obfuscatedMailTo;
    }
    $emailLink .= obfuscate($emailAddy);
    if ( $buildLink ) {
      $emailLink .= '"';
      if ( trim($class) != '' ) {
        $emailLink .= ' class="' . $class . '"';
      }
      if ( trim($style) != '' ) {
        $emailLink .= ' style="' . $style . '"';
      }
      $emailLink .= '>';
      if ( trim($text) != '' ) {
        $newText    = trim($text);
        $newText    = str_replace('@','&#64;',$newText);
        $newText    = str_replace('.','&#46;',$newText);
        $emailLink .= $newText;
      } else {
        $newText    = trim($emailAddy);
        $newText    = str_replace('@','&#64;',$newText);
        $newText    = str_replace('.','&#46;',$newText);
        $emailLink .= $newText;
      }
      $emailLink .= "</a>";
    }
    return $emailLink;
  }

  function obfuscate($text) { // NOTE: Uses function str_split
    preg_match_all("/[-a-z0-9\._]+@[-a-z0-9\._]+\.[a-z]{2,4}/", $text, $emails);
    $patterns = array();
    $replacements = array();
    foreach($emails[0] as $email) {
      if(!in_array("/$email/", $patterns)) {
        $obfuscated = '';
        foreach(str_split($email) as $char) {
          $obfuscated .= '&#'.ord($char).';';
        }
        $patterns[] = "/$email/";
        $replacements[] = $obfuscated;
      }
    }
    $text = preg_replace($patterns, $replacements, $text);
    return $text;
  }
