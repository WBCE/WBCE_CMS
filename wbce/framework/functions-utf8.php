<?php
// $Id: functions-utf8.php 1499 2011-08-12 11:21:25Z DarkViper $

/*

 WebsiteBaker Project <http://websitebaker.org/>
 Copyright (C) Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/*
 * A part of this file is based on 'utf8.php' from the DokuWiki-project.
 * (http://www.splitbrain.org/projects/dokuwiki):
 **
 * UTF8 helper functions
 * @license    LGPL (http://www.gnu.org/copyleft/lesser.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 **
 * modified for use with Website Baker
 * from thorn, Jan. 2008
 *
 * most of the original functions appeared to be to slow with large strings, so i replaced them with my own ones
 * thorn, Mar. 2008
 */

// Functions we use in Website Baker:
//   entities_to_7bit()
//   entities_to_umlauts2()
//   umlauts_to_entities2()
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
    require_once(dirname(__FILE__).'/globalExceptionHandler.php');
    throw new IllegalFileException();
}
/* -------------------------------------------------------- */
/*
 * check for mb_string support
 */
//define('UTF8_NOMBSTRING',1); // uncomment this to forbid use of mb_string-functions
if(!defined('UTF8_MBSTRING')){
  if(function_exists('mb_substr') && !defined('UTF8_NOMBSTRING')){
    define('UTF8_MBSTRING',1);
  }else{
    define('UTF8_MBSTRING',0);
  }
}

if(UTF8_MBSTRING){ mb_internal_encoding('UTF-8'); }

require_once(WB_PATH.'/framework/charsets_table.php');

/*
 * Checks if a string contains 7bit ASCII only
 *
 * @author thorn
 */
function utf8_isASCII($str){
    if(preg_match('/[\x80-\xFF]/', $str))
        return false;
    else
        return true;
}

/*
 * Tries to detect if a string is in Unicode encoding
 *
 * @author <bmorel@ssi.fr>
 * @link   http://www.php.net/manual/en/function.utf8-encode.php
 */
function utf8_check($Str) {
 for ($i=0; $i<strlen($Str); $i++) {
  $b = ord($Str[$i]);
  if ($b < 0x80) continue; # 0bbbbbbb
  elseif (($b & 0xE0) == 0xC0) $n=1; # 110bbbbb
  elseif (($b & 0xF0) == 0xE0) $n=2; # 1110bbbb
  elseif (($b & 0xF8) == 0xF0) $n=3; # 11110bbb
  elseif (($b & 0xFC) == 0xF8) $n=4; # 111110bb
  elseif (($b & 0xFE) == 0xFC) $n=5; # 1111110b
  else return false; # Does not match any model
  for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
   if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
   return false;
  }
 }
 return true;
}

/*
 * Romanize a non-latin string
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function utf8_romanize($string){
  if(utf8_isASCII($string)) return $string; //nothing to do

  global $UTF8_ROMANIZATION;
  return strtr($string,$UTF8_ROMANIZATION);
}

/*
 * Removes special characters (nonalphanumeric) from a UTF-8 string
 *
 * This function adds the controlchars 0x00 to 0x19 to the array of
 * stripped chars (they are not included in $UTF8_SPECIAL_CHARS2)
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @param  string $string     The UTF8 string to strip of special chars
 * @param  string $repl       Replace special with this string
 * @param  string $additional Additional chars to strip (used in regexp char class)
 */
function utf8_stripspecials($string,$repl='',$additional=''){
  global $UTF8_SPECIAL_CHARS2;

  static $specials = null;
  if(is_null($specials)){
    $specials = preg_quote($UTF8_SPECIAL_CHARS2, '/');
  }

  return preg_replace('/['.$additional.'\x00-\x19'.$specials.']/u',$repl,$string);
}

/*
 * added functions - thorn
 */

/*
 * faster replacement for utf8_entities_to_umlauts()
 * not all features of utf8_entities_to_umlauts() --> utf8_unhtml() are supported!
 * @author thorn
 */
function utf8_fast_entities_to_umlauts($str) {
    if(UTF8_MBSTRING) {
        // we need this for use with mb_convert_encoding
        $str = str_replace(array('&amp;','&gt;','&lt;','&quot;','&#039;','&nbsp;'), array('&amp;amp;','&amp;gt;','&amp;lt;','&amp;quot;','&amp;#39;','&amp;nbsp;'), $str);
        // we need two mb_convert_encoding()-calls - is this a bug?
        // mb_convert_encoding("ö&ouml;", 'UTF-8', 'HTML-ENTITIES'); // with string in utf-8-encoding doesn't work. Result: "Ã¶ö"
        // Work-around: convert all umlauts to entities first ("ö&ouml;"->"&ouml;&ouml;"), then all entities to umlauts ("&ouml;&ouml;"->"öö")
        return(mb_convert_encoding(mb_convert_encoding($str, 'HTML-ENTITIES', 'UTF-8'),'UTF-8', 'HTML-ENTITIES'));
    } else {
        global $named_entities;global $numbered_entities;
        $str = str_replace($named_entities, $numbered_entities, $str);
        $str = preg_replace("/&#([0-9]+);/e", "code_to_utf8($1)", $str);
    }
    return($str);
}
// support-function for utf8_fast_entities_to_umlauts()
function code_to_utf8($num) {
    if ($num <= 0x7F) {
        return chr($num);
    } elseif ($num <= 0x7FF) {
        return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
    } elseif ($num <= 0xFFFF) {
         return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    } elseif ($num <= 0x1FFFFF) {
        return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    }
    return "?";
}

/*
 * faster replacement for utf8_umlauts_to_entities()
 * not all features of utf8_umlauts_to_entities() --> utf8_tohtml() are supported!
 * @author thorn
 */
function utf8_fast_umlauts_to_entities($string, $named_entities=true) {
    if(UTF8_MBSTRING)
        return(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
    else {
        global $named_entities;global $numbered_entities;
        $new = "";
        $i=0;
        $len=strlen($string);
        if($len==0) return $string;
        do {
            if(ord($string{$i}) <= 127) $ud = $string{$i++};
            elseif(ord($string{$i}) <= 223) $ud = (ord($string{$i++})-192)*64 + (ord($string{$i++})-128);
            elseif(ord($string{$i}) <= 239) $ud = (ord($string{$i++})-224)*4096 + (ord($string{$i++})-128)*64 + (ord($string{$i++})-128);
            elseif(ord($string{$i}) <= 247) $ud = (ord($string{$i++})-240)*262144 + (ord($string{$i++})-128)*4096 + (ord($string{$i++})-128)*64 + (ord($string{$i++})-128);
            else $ud = ord($string{$i++}); // error!
            if($ud > 127) {
                $new .= "&#$ud;";
            } else {
                $new .= $ud;
            }
        } while($i < $len);
        $string = $new;
        if($named_entities)
            $string = str_replace($numbered_entities, $named_entities, $string);
    }
    return($string);
}

/*
 * Converts from various charsets to UTF-8
 *
 * Will convert a string from various charsets to UTF-8.
 * HTML-entities may be converted, too.
 * In case of error the returned string is unchanged, and a message is emitted.
 * Supported charsets are:
 * direct: iso_8859_1 iso_8859_2 iso_8859_3 iso_8859_4 iso_8859_5
 *         iso_8859_6 iso_8859_7 iso_8859_8 iso_8859_9 iso_8859_10 iso_8859_11
 * mb_convert_encoding: all wb charsets (except those from 'direct'); but not GB2312
 * iconv:  all wb charsets (except those from 'direct')
 *
 * @param  string  A string in supported encoding
 * @param  string  The charset to convert from, defaults to DEFAULT_CHARSET
 * @return string  A string in UTF-8-encoding, with all entities decoded, too.
 *                 String is unchanged in case of error.
 * @author thorn
 */
function charset_to_utf8($str, $charset_in=DEFAULT_CHARSET, $decode_entities=true) {
    global $iso_8859_2_to_utf8, $iso_8859_3_to_utf8, $iso_8859_4_to_utf8, $iso_8859_5_to_utf8, $iso_8859_6_to_utf8, $iso_8859_7_to_utf8, $iso_8859_8_to_utf8, $iso_8859_9_to_utf8, $iso_8859_10_to_utf8, $iso_8859_11_to_utf8;
    $charset_in = strtoupper($charset_in);
    if ($charset_in == "") { $charset_in = 'UTF-8'; }
    $wrong_ISO8859 = false;
    $converted = false;

    if((!function_exists('iconv') && !UTF8_MBSTRING && ($charset_in=='BIG5' || $charset_in=='ISO-2022-JP' || $charset_in=='ISO-2022-KR')) || (!function_exists('iconv') && $charset_in=='GB2312')) {
        // Nothing we can do here :-(
        // Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
        // and we can't use mb_convert_encoding() or iconv();
        // Emit an error-message.
        trigger_error("Can't convert from $charset_in without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING);
        return($str);
    }

    // check if we have UTF-8 or a plain ASCII string
    if($charset_in == 'UTF-8' || utf8_isASCII($str)) {
        // we have utf-8. Just replace HTML-entities and return
        if($decode_entities && preg_match('/&[#0-9a-zA-Z]+;/',$str))
            return(utf8_fast_entities_to_umlauts($str));
        else // nothing to do
            return($str);
    }
    
    // Convert $str to utf8
    if(substr($charset_in,0,8) == 'ISO-8859') {
        switch($charset_in) {
            case 'ISO-8859-1': $str=utf8_encode($str); break;
            case 'ISO-8859-2': $str=strtr($str, $iso_8859_2_to_utf8); break;
            case 'ISO-8859-3': $str=strtr($str, $iso_8859_3_to_utf8); break;
            case 'ISO-8859-4': $str=strtr($str, $iso_8859_4_to_utf8); break;
            case 'ISO-8859-5': $str=strtr($str, $iso_8859_5_to_utf8); break;
            case 'ISO-8859-6': $str=strtr($str, $iso_8859_6_to_utf8); break;
            case 'ISO-8859-7': $str=strtr($str, $iso_8859_7_to_utf8); break;
            case 'ISO-8859-8': $str=strtr($str, $iso_8859_8_to_utf8); break;
            case 'ISO-8859-9': $str=strtr($str, $iso_8859_9_to_utf8); break;
            case 'ISO-8859-10': $str=strtr($str, $iso_8859_10_to_utf8); break;
            case 'ISO-8859-11': $str=strtr($str, $iso_8859_11_to_utf8); break;
            default: $wrong_ISO8859 = true;
        }
        if(!$wrong_ISO8859)
            $converted = true;
    }
    if(!$converted && UTF8_MBSTRING && $charset_in != 'GB2312') {
        // $charset is neither UTF-8 nor a known ISO-8859...
        // Try mb_convert_encoding() - but there's no GB2312 encoding in php's mb_* functions
        $str = mb_convert_encoding($str, 'UTF-8', $charset_in);
        $converted = true;
    } elseif(!$converted) { // Try iconv
        if(function_exists('iconv')) {
            $str = iconv($charset_in, 'UTF-8', $str);
            $converted = true;
        }
    }
    if($converted) {
        // we have utf-8, now replace HTML-entities and return
        if($decode_entities && preg_match('/&[#0-9a-zA-Z]+;/',$str))
            $str = utf8_fast_entities_to_umlauts($str);
        return($str);
    }
    
    // Nothing we can do here :-(
    // Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
    // and we can't use mb_convert_encoding() or iconv();
    // Emit an error-message.
    trigger_error("Can't convert from $charset_in without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING);
    
    return $str;
}

/*
 * Converts from UTF-8 to various charsets
 *
 * Will convert a string from UTF-8 to various charsets.
 * HTML-entities will not! be converted.
 * In case of error the returned string is unchanged, and a message is emitted.
 * Supported charsets are:
 * direct: iso_8859_1 iso_8859_2 iso_8859_3 iso_8859_4 iso_8859_5
 *         iso_8859_6 iso_8859_7 iso_8859_8 iso_8859_9 iso_8859_10 iso_8859_11
 * mb_convert_encoding: all wb charsets (except those from 'direct'); but not GB2312
 * iconv:  all wb charsets (except those from 'direct')
 *
 * @param  string  An UTF-8 encoded string
 * @param  string  The charset to convert to, defaults to DEFAULT_CHARSET
 * @return string  A string in a supported encoding, with all entities decoded, too.
 *                 String is unchanged in case of error.
 * @author thorn
 */
function utf8_to_charset($str, $charset_out=DEFAULT_CHARSET) {
    global $utf8_to_iso_8859_2, $utf8_to_iso_8859_3, $utf8_to_iso_8859_4, $utf8_to_iso_8859_5, $utf8_to_iso_8859_6, $utf8_to_iso_8859_7, $utf8_to_iso_8859_8, $utf8_to_iso_8859_9, $utf8_to_iso_8859_10, $utf8_to_iso_8859_11;
    $charset_out = strtoupper($charset_out);
    $wrong_ISO8859 = false;
    $converted = false;

    if((!function_exists('iconv') && !UTF8_MBSTRING && ($charset_out=='BIG5' || $charset_out=='ISO-2022-JP' || $charset_out=='ISO-2022-KR')) || (!function_exists('iconv') && $charset_out=='GB2312')) {
        // Nothing we can do here :-(
        // Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
        // and we can't use mb_convert_encoding() or iconv();
        // Emit an error-message.
        trigger_error("Can't convert into $charset_out without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING);
        return($str);
    }
    
    // the string comes from charset_to_utf8(), so we can skip this
    // replace HTML-entities first
    //if(preg_match('/&[#0-9a-zA-Z]+;/',$str))
    //    $str = utf8_entities_to_umlauts($str);
    
    // check if we need to convert
    if($charset_out == 'UTF-8' || utf8_isASCII($str)) {
        // Nothing to do. Just return
            return($str);
    }
    
    // Convert $str to $charset_out
    if(substr($charset_out,0,8) == 'ISO-8859') {
        switch($charset_out) {
            case 'ISO-8859-1': $str=utf8_decode($str); break;
            case 'ISO-8859-2': $str=strtr($str, $utf8_to_iso_8859_2); break;
            case 'ISO-8859-3': $str=strtr($str, $utf8_to_iso_8859_3); break;
            case 'ISO-8859-4': $str=strtr($str, $utf8_to_iso_8859_4); break;
            case 'ISO-8859-5': $str=strtr($str, $utf8_to_iso_8859_5); break;
            case 'ISO-8859-6': $str=strtr($str, $utf8_to_iso_8859_6); break;
            case 'ISO-8859-7': $str=strtr($str, $utf8_to_iso_8859_7); break;
            case 'ISO-8859-8': $str=strtr($str, $utf8_to_iso_8859_8); break;
            case 'ISO-8859-9': $str=strtr($str, $utf8_to_iso_8859_9); break;
            case 'ISO-8859-10': $str=strtr($str, $utf8_to_iso_8859_10); break;
            case 'ISO-8859-11': $str=strtr($str, $utf8_to_iso_8859_11); break;
            default: $wrong_ISO8859 = true;
        }
        if(!$wrong_ISO8859)
            $converted = true;
    }
    if(!$converted && UTF8_MBSTRING && $charset_out != 'GB2312') {
        // $charset is neither UTF-8 nor a known ISO-8859...
        // Try mb_convert_encoding() - but there's no GB2312 encoding in php's mb_* functions
        $str = mb_convert_encoding($str, $charset_out, 'UTF-8');
        $converted = true;
    } elseif(!$converted) { // Try iconv
        if(function_exists('iconv')) {
            $str = iconv('UTF-8', $charset_out, $str);
            $converted = true;
        }
    }
    if($converted) {
        return($str);
    }
    
    // Nothing we can do here :-(
    // Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
    // and we can't use mb_convert_encoding() or iconv();
    // Emit an error-message.
    trigger_error("Can't convert into $charset_out without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING);
    
    return $str;
}

/*
 * convert Filenames to ASCII
 *
 * Convert all non-ASCII characters and all HTML-entities to their plain 7bit equivalents
 * Characters without an equivalent will be converted to hex-values.
 * The name entities_to_7bit() is somewhat misleading, but kept for compatibility-reasons.
 *
 * @param  string  Filename to convert (all encodings from charset_to_utf8() are allowed)
 * @return string  ASCII encoded string, to use as filename in wb's page_filename() and media_filename
 * @author thorn
 */
function entities_to_7bit($str) {
    // convert to UTF-8
    $str = charset_to_utf8($str);
    if(!utf8_check($str))
        return($str);
    // replace some specials
    $str = utf8_stripspecials($str, '_');
    // translate non-ASCII characters to ASCII
    $str = utf8_romanize($str);
    // missed some? - Many UTF-8-chars can't be romanized
    // convert to HTML-entities, and replace entites by hex-numbers
    $str = utf8_fast_umlauts_to_entities($str, false);
    $str = str_replace('&#039;', '&apos;', $str);
//    $str = preg_replace_callback('/&#([0-9]+);/', function($matches) {return "dechex($matches[1])";}, $str);
//    $str = preg_replace_callback('/&#38;#([0-9]+);/', function($matches) {return dechex($matches[1]);}, $str);
    if (version_compare(PHP_VERSION, '5.3', '<')) {
        $str = preg_replace('/&#([0-9]+);/e', "dechex('$1')",  $str);
    } else {
        $str = preg_replace_callback('/&#([0-9]+);/', create_function('$aMatches', 'return dechex($aMatches[1]);'),  $str);
    }
    // maybe there are some &gt; &lt; &apos; &quot; &amp; &nbsp; left, replace them too
    $str = str_replace(array('&gt;', '&lt;', '&apos;', '\'', '&quot;', '&amp;'), '', $str);
    $str = str_replace('&amp;', '', $str);
    
    return($str);
}

/*
 * Convert a string from mixed html-entities/umlauts to pure $charset_out-umlauts
 * 
 * Will replace all numeric and named entities except
 * &gt; &lt; &apos; &quot; &#039; &nbsp;
 * @author thorn
 */
function entities_to_umlauts2($string, $charset_out=DEFAULT_CHARSET) {
    $string = charset_to_utf8($string, DEFAULT_CHARSET, true);
    //if(utf8_check($string)) // this check is to much time-consuming (this may fail only if AddDefaultCharset is set)
        $string = utf8_to_charset($string, $charset_out);
    return ($string);
}

/*
 * Convert a string from mixed html-entities/umlauts to pure ASCII with HTML-entities
 * 
 * Will convert a string in $charset_in encoding to a pure ASCII string with HTML-entities.
 * @author thorn
 */
function umlauts_to_entities2($string, $charset_in=DEFAULT_CHARSET) {
    $string = charset_to_utf8($string, $charset_in, false);
    //if(utf8_check($string)) // this check is to much time-consuming (this may fail only if AddDefaultCharset is set)
        $string = utf8_fast_umlauts_to_entities($string, false);
    return($string);
}

