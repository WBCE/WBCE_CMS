<?php

/*
functions.php
*/

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.5.1
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010 Christian M. Stefan (Stefek), 2017 Martin Hecht (mrbaseman)
 * @link            https://github.com/WebsiteBaker-modules/outpufilter_dashboard
 * @link            http://forum.websitebaker.org/index.php/topic,28926.0.html
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @link            http://addons.wbce.org/pages/addons.php?do=item&item=53
 * @license         GNU General Public License, Version 3
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.4 and higher
 * 
 * This file is part of OutputFilter-Dashboard, a module for Website Baker CMS.
 * 
 * OutputFilter-Dashboard is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * OutputFilter-Dashboard is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with OutputFilter-Dashboard. If not, see <http://www.gnu.org/licenses/>.
 * 
 **/


// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../index.php'));

// obtain module directory
$mod_dir = basename(dirname(__FILE__));
require(WB_PATH.'/modules/'.$mod_dir.'/info.php');

if(!defined('OPF_PLUGINS_PATH')) 
    define('OPF_PLUGINS_PATH', dirname(__FILE__).'/plugins/');
if(!defined('OPF_PLUGINS_URL')) 
    define('OPF_PLUGINS_URL', WB_URL.'/modules/'.$mod_dir.'/plugins/');

// include module.functions.php 
include_once(WB_PATH . '/framework/module.functions.php');

// include the module language file depending on the backend language of the current user
if (!include(get_module_language_file($mod_dir))) return;

// stupid way to keep filters in order (section --> section_last --> page --> page_last)
if(!defined('OPF_TYPE_SECTION')) {
    define('OPF_TYPE_SECTION','3section');
    define('OPF_TYPE_SECTION_LAST','5section_last');
    define('OPF_TYPE_PAGE','7page');
    define('OPF_TYPE_PAGE_LAST','8page_last');
}

require_once(dirname(__FILE__).'/functions_outputfilter.php');


/* ----------------------------------------------------------------- */
// functions that replace the former pmf calls

if(!defined('OPF_FILELIST_DEPTH')) {
  define('OPF_FILELIST_DEPTH', TRUE);
  define('OPF_FILELIST_NODIRS', FALSE);
  define('__OPF_UPLOAD_DIRNAME', dirname(__FILE__).'/.uploads/');
}

// remove comments from the backend templates

function opf_filter_Comments($content) {
    $pattern = '/(?:<!--\/\*.*?\*\/-->)/si';
    while(preg_match($pattern,$content,$matches)==1)
    {
    $toremove=$matches[0];
    $content = str_replace($toremove, '', $content);
    }
    return($content);
}

// replace quotes by their html representation

function opf_quotes($var) {
  return str_replace(array('"', "'"), array('&quot;','&#039;'), $var);
}



// functions for db-query, but here using the database-class now.

function opf_db_query($q_str) {
  if (!$q_str) return NULL;
  global $database;
  if(func_num_args()>1) {
    $args = func_get_args();
    unset($args[0]);
    // escape args
    $new_args = array();
    foreach($args as $a) $new_args[] = $database->escapeString($a);
    if($new_args) $args = $new_args;
    $q_str = vsprintf($q_str, $args);
  }
  $result = $database->query($q_str);
  if($database->is_error()) { // SQL-query failed -- return FALSE
    return(FALSE);
  }
  $ret=FALSE;
  $results = array();
  while($res = $result->fetchRow()){
    $results[] = $res;
    $ret=TRUE;
  }
  if($ret)
    return($results);
    else return(TRUE);  // success without returning results (e.g. update) 
  
}


// more or less the same, but the result is returned slightly different

function opf_db_query_vars($q_str) {
  if (!$q_str) return NULL;
  global $database;
  if(func_num_args()>1) {
    $args = func_get_args();
    unset($args[0]);
    // escape args
    $new_args = array();
    foreach($args as $a) $new_args[] = $database->escapeString($a);
    if($new_args) $args = $new_args;
    $q_str = vsprintf($q_str, $args);
  }
  $result = $database->query($q_str);
  if($database->is_error()) { // SQL-query failed -- return FALSE
    return(FALSE);
  }
  $results = array();
  if($results = $result->fetchRow()) {
      $results=$results[0];
      if(count($results)==1) 
    if(is_array($results)) 
         return(current($results)); // single value
      // not sure if we ever arrive at thiese lines:
      if(count($results)==0) return(TRUE); // success without results 
      return($results);
  }
  if(empty($results)) return(NULL); // no matches
  return(FALSE);
}

// we need a special variant for insert and delete where no results are returned
function opf_db_run_query($q_str) {
  if (!$q_str) return NULL;
  global $database;
  if(func_num_args()>1) {
    $args = func_get_args();
    unset($args[0]);
    // escape args
    $new_args = array();
    foreach($args as $a) $new_args[] = $database->escapeString($a);
    if($new_args) $args = $new_args;
    $q_str = vsprintf($q_str, $args);
  }
  $result = $database->query($q_str);
  if($database->is_error()) { // SQL-query failed -- return FALSE
    return(FALSE);
  }
  return(TRUE);  // success 
}


// returns the database status and the 
function opf_db_get_error($asstring=TRUE){
  global $database;
  if($database->is_error()) { 
    return( $database->get_error() );
  }
  if($asstring)return("");
  return (FALSE);
}

/*
  Private Functions: some pre-defined filter-functions for use with <opf_fetch_clean()>

    __opf_fetch_clean_int - assume Value to be an int, and clean it.
                See <opf_fetch_clean()>.
    __opf_fetch_clean_string - assume Value to be an ordinary string, and clean it.
                   See <opf_fetch_clean()>.
    __opf_fetch_clean_language - assume Value to be an language-code (e.g. 'DE' or 'NL').
                 See <opf_fetch_clean()>.
    __opf_fetch_clean_bool - assume Value to be of type bool, and clean it.
                 See <opf_fetch_clean()>.
    __opf_fetch_clean_exists - Returns !TRUE! if Value exists. Returns the default-value otherwise.
                   See <opf_fetch_clean()>.
    __opf_fetch_clean_unchanged - Returns the Value itself, unchanged.
                  See <opf_fetch_clean()>.
    __opf_fetch_clean_rmctrl - a special filter that is *applied automatically on all strings*.
                   See <opf_fetch_clean()>.

*/
function __opf_fetch_clean_int(&$val, $k, $args) {
  if(is_numeric($val))
    $val = (int)$val;
  elseif($args['allowbool'] && ($val===TRUE || $val===FALSE))
    ;
  elseif($args['allownull'] && $val===NULL)
    ;
  else
    $val = $args['default'];
}
function __opf_fetch_clean_string(&$val, $k, $args) {
  if(is_string($val) || is_numeric($val))
    $val = htmlspecialchars((string)$val, ENT_QUOTES);
  elseif($args['allowbool'] && ($val===TRUE || $val===FALSE))
    ;
  elseif($args['allownull'] && $val===NULL)
    ;
  else
    $val = $args['default'];
}
function __opf_fetch_clean_bool(&$val, $k, $args) {
  if(is_bool($val))
    ;
  elseif($args['allownull'] && $val===NULL)
    ;
  else
    $val = $args['default'];
}
function __opf_fetch_clean_language(&$val, $k, $args) {
  if(is_string($val) && preg_match('~^[A-Z]{2}$~', $val))
    ;
  elseif($args['allownull'] && $val===NULL)
    ;
  else
    $val = $args['default'];
}
function __opf_fetch_clean_rmctrl(&$val, $k, $args) {
  if(is_string($val))
    $val = preg_replace('~[\x00-\x08\x0b\x0c\x0e-\x1f\x7f]+~', '_', $val);
}
function __opf_fetch_clean_exists(&$val, $k, $args) {
  if(isset($val))
    $val = TRUE;
  else
    $val = $args['default'];
}
function __opf_fetch_clean_unchanged(&$val, $k, $args) {
  ;
}

// clean up variables and do some checking on types etc.

function opf_fetch_clean($val, $default=NULL, $type='int', $args=FALSE, $from_gpc=FALSE) {
  if(!isset($val))
    return($default);
  // check $args
  if($args===FALSE || !is_array($args))
    $args = array('allowbool'=>FALSE, 'allownull'=>FALSE);
  else {
    if(!isset($args['allowbool']) || !is_bool($args['allowbool']))
      $args['allowbool'] = FALSE;
    if(!isset($args['allownull']) || !is_bool($args['allownull']))
      $args['allownull'] = FALSE;
  }
  $args['default'] = $default;
  // strip slashes
  if($from_gpc && get_magic_quotes_gpc()) {
    if(is_array($val)) {
      array_walk_recursive($val, create_function('&$v,$k','$v = stripslashes($v);'));
    } else
      $val = stripslashes($val);
  }
  // apply filters
  if(!is_array($type))
    $type = array($type);
  $type[] = 'rmctrl';
  foreach($type as $t) {
    if(!is_string($t)) {
      die(sprintf($LANG['MOD_OPF']['TXT_ERR_SECURITY_BREACH'],'opf_fetch_clean'));
    }
    if(function_exists('__opf_fetch_clean_'.$t))
      $filter = '__opf_fetch_clean_'.$t;
    else {
      die(sprintf($LANG['MOD_OPF']['TXT_ERR_SECURITY_BREACH'],'opf_fetch_clean'));
    }
    if(is_array($val)) {
      array_walk_recursive($val, $filter, $args);
    } else {
      $filter($val, FALSE, $args);
    }
  }
  return($val);
}

// optain a value sent by a get-request

function opf_fetch_get($val, $default=NULL, $type='int', $args=FALSE) {
  if(is_string($type) && $type=='exists' && isset($_GET[$val]))
    return(TRUE);
  if(!isset($_GET[$val]))
    return($default);
  return(opf_fetch_clean($_GET[$val], $default, $type, $args, TRUE));
}


// obtain a value sent by post

function opf_fetch_post($val, $default=NULL, $type='int', $args=FALSE) {
  if(is_string($type) && $type=='exists' && isset($_POST[$val]))
    return(TRUE);
  if(!isset($_POST[$val]))
    return($default);
  return(opf_fetch_clean($_POST[$val], $default, $type, $args, TRUE));
}


// filesystem functions

function opf_clean_filename($filename, $ascii=FALSE) {
  $filename = preg_replace('~[][ /\\\\%#&:;`|*?!=\~<>^(){}$,\'\x00-\x1f\x7f"]~', '_', $filename);
  if($ascii) {
    require_once(WB_PATH.'/framework/functions-utf8.php');
    $filename = entities_to_7bit($filename);
  }
  return(trim($filename));
}


function opf_io_mkdir($dir) {
  if(file_exists($dir)) {
    if(is_dir($dir))
      return(TRUE);
    return(FALSE);
  }
  return(mkdir($dir, OCTAL_DIR_MODE));
}


function opf_io_rmdir($dir) {
  $res = TRUE;
  if(!file_exists($dir))
    return(FALSE);
  if(is_file($dir) || is_link($dir))
    return(unlink($dir));
  $dh = opendir($dir);
  while($file = readdir($dh)) {
    if($file=='.' || $file=='..') continue;
    is_dir($dir.'/'.$file)?($res = opf_io_rmdir($dir.'/'.$file))
              :($res = unlink($dir.'/'.$file));
    if($res==FALSE) break;
  }
  closedir($dh);
  if($res==TRUE)
    $res = rmdir($dir);
  return($res);
}

function opf_io_unlink($file) {
  if(!file_exists($file))
    return(FALSE);
  if(is_file($file) || is_link($file))
    return(unlink($file));
  // directory
  return(FALSE);
}


function opf_io_filelist($dir, $depth=OPF_FILELIST_DEPTH, $dirs=OPF_FILELIST_NODIRS, $files=array()) {
  if(!is_dir($dir)) return($files);
  $dir = rtrim($dir, '/');
  $dh = opendir($dir);
  while($file = readdir($dh)) {
    if($file=='.' || $file=='..') continue;
    if(is_dir($dir.'/'.$file)) {
      if($dirs) $files[] = $dir.'/'.$file.'/';
      if($depth) $files = opf_io_filelist($dir.'/'.$file, $depth, $dirs, $files);
    } else {
      $files[] = $dir.'/'.$file;
    }
  }
  closedir($dh);
  return($files);
}


function opf_io_chmod($name) {
  if(strtoupper(substr(PHP_OS, 0, 3))=='WIN')
    return(TRUE); // on windows don't use chmod
  if(!file_exists($name) || !is_writable($name))
    return(FALSE);
  return(chmod($name, is_dir($name)?OCTAL_DIR_MODE:OCTAL_FILE_MODE));
}


function __opf_upload_check_zip($upload) {
  if (file_exists (WB_PATH.'/include/pclzip/pclzip.lib.php'))
     require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
  $archive = new PclZip($upload['tmp_name']);
  if($archive->properties()==0)
    return(FALSE);
  else
    return(TRUE);
}

function opf_upload_check($field, $fileext=array('.jpg','.png','.gif'), $type='image', $maxsize=0) {
  global $LANG;
  if(!is_array($fileext)) $fileext = explode(',', $fileext);
  // check for error
  if(!isset($_FILES[$field]) || empty($_FILES[$field]['tmp_name'])) {
    return(array('status' => FALSE,
                 'result' => $LANG['MOD_OPF']['TXT_ERR_NO_UPLOAD']));
  }
  if($_FILES[$field]['error'] || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
    return(array('status' => FALSE,
             'result' => sprintf($LANG['MOD_OPF']['TXT_ERR_PHP_ERR'],$_FILES[$field]['error'])));
  }

  // get cleaned filename
  $filename = opf_clean_filename($_FILES[$field]['name']);

  // check user-supplied args
  if($maxsize>0 && $_FILES[$field]['size']>$maxsize) {
    return(array('status' => FALSE,
             'result' => sprintf($LANG['MOD_OPF']['TXT_ERR_UPLOAD_SIZE'], $maxsize)));
  }
  if(!empty($fileext)) {
    $ext = '';
    if(!is_array($fileext)) $fileext = array($fileext);
    preg_match('~\.\w+$~D', $filename, $match);
    if(isset($match[0])) $ext = strtolower($match[0]);
    if(!in_array($ext, $fileext)) {
    return(array('status' => FALSE,
                 'result' => sprintf($LANG['MOD_OPF']['TXT_ERR_UPLOAD_EXT'], $fileext)));
    }
  }
  if($type && is_string($type)) {
    if(function_exists('__opf_upload_check_'.$type))
      $filter = '__opf_upload_check_'.$type;
    elseif(function_exists('opf_upload_usercheck_'.$type))
      $filter = 'opf_upload_usercheck_'.$type;
    else {
      die(sprintf($LANG['MOD_OPF']['TXT_ERR_SECURITY_BREACH'],'opf_upload_check'));
    }
    $res = $filter($_FILES[$field]);
    if(!$res) {
    return(array('status' => FALSE,
                 'result' => sprintf($LANG['MOD_OPF']['TXT_ERR_UPLOAD_TYPE'], $type)));
    }
  } else {
      die(sprintf($LANG['MOD_OPF']['TXT_ERR_SECURITY_BREACH'],'opf_upload_check'));
  }

  // store info in DB and move file
  $section_id = 0;
  $id = uniqid(mt_rand(100000000,999999999), TRUE);
  opf_io_mkdir(__OPF_UPLOAD_DIRNAME);
  $dest = __OPF_UPLOAD_DIRNAME.$id;
  if(move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
    return(array('status' => TRUE,
                 'result' => $id));
  }
  return(array( 'status' => FALSE,
                'result' => $LANG['MOD_OPF']['TXT_ERR_UPLOAD_FAILED']));
}



function opf_upload_move($id, $path, $name='') {
  if(empty($name)) {
    return(FALSE);
  }
  if(!rename(__OPF_UPLOAD_DIRNAME.$id, $path.$name)) {
    return(FALSE);
  } 
  return($name);
}



// end of pmf replacements... 
/* ----------------------------------------------------------------- */ 

// check wether the core contains the patches

function opf_check_patched(){
    // WBCE calls opf_controller directly, wb 2.8.3 sp6 uses the OutputFilterApi 
    $patch_applied=FALSE;
    if($content = file_get_contents(WB_PATH.'/framework/frontend.functions.php')) {
        if(file_exists(WB_PATH.'/framework/functions/frontend.functions.php')) {
          $content = file_get_contents(WB_PATH.'/framework/functions/frontend.functions.php');
        }
        if(preg_match('/opf_controller[^;]*section/', $content) ||
           // detect a bug in a release candidate for sp6:
           preg_match('/OpF\?arg=section\&module/', $content)) {
             if(preg_match('/(opf_controller|OpF)[^;]*special/', $content)) {
                 if($content = file_get_contents(WB_PATH.'/index.php')) {
                    // wbce or patch manually applied
                    if(preg_match('/opf_controller[^;]*page/', $content)) {
                       $patch_applied = TRUE;
                    }
                    if(!file_exists(WB_PATH.'/modules/output_filter/index.php')){
                       $patch_applied = TRUE;
                    } else { 
                        if ( $content = file_get_contents(WB_PATH.'/modules/output_filter/index.php')) {
                            // sp4 and sp5 started to use OutputFilterApi 
                            // but it was broken at that time
                            if(preg_match('/OpF/', $content)) {
                                $patch_applied = TRUE;
                            }
                        }
                    }            
                }
            }
        } 
    }
    return $patch_applied;
}


// correct the umlauts in filter description in short 
function opf_correct_umlauts($arg) {
  $replacements = array (
    '&amp;auml;' => '&auml;',
    '&amp;ouml;' => '&ouml;',
    '&amp;uuml;' => '&uuml;',
    '&amp;Auml;' => '&Auml;',
    '&amp;Ouml;' => '&Ouml;',
    '&amp;Uuml;' => '&Uuml;',
    '&amp;szlig;' => '&szlig;'
  );
  return str_replace(array_keys($replacements),array_values($replacements),$arg);
}


// get array of types
function opf_get_types() {
global $LANG;
    $types = array(
        OPF_TYPE_SECTION => $LANG['MOD_OPF']['TXT_MODULE'], // default! "normal" filters applied to modules, aka. sections
        OPF_TYPE_SECTION_LAST => $LANG['MOD_OPF']['TXT_MODULE_LAST'], // filters which have to be applied last (e.g. highlighting)
        OPF_TYPE_PAGE => $LANG['MOD_OPF']['TXT_PAGE'], // filter applied to the whole page (head, content, menu, snippets, ...)
        OPF_TYPE_PAGE_LAST => $LANG['MOD_OPF']['TXT_PAGE_LAST'], // filter applied after all page-filters
    );
    return($types);
}

//
function opf_type_uses_sections($type) {
    if($type==OPF_TYPE_PAGE || $type==OPF_TYPE_PAGE_LAST)
        return(FALSE);
    if($type==OPF_TYPE_SECTION || $type==OPF_TYPE_SECTION_LAST)
        return(TRUE);
    return(FALSE);
}
//
function opf_type_uses_pages($type) {
    if($type==OPF_TYPE_PAGE || $type==OPF_TYPE_PAGE_LAST)
        return(FALSE);
    if($type==OPF_TYPE_SECTION || $type==OPF_TYPE_SECTION_LAST)
        return(TRUE);
    return(FALSE);
}

// fetches real $name if $name is id
function opf_check_name($name) {
    if(is_numeric($name)) {
        if(!$name = opf_db_query_vars( "SELECT `name` FROM `".TABLE_PREFIX."mod_outputfilter_dashboard` WHERE `id`=%d", $name))
            return(FALSE);
    }
    return($name);
}

// fetch array of all filters
function opf_select_filters($type='') {
    if($type=='') {
        $res = opf_db_query( 
            "SELECT * FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
            . " ORDER BY `type`,`position` ASC");
    } else {
        $res = opf_db_query( "SELECT * FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
         . " WHERE `type`='%s' ORDER BY `position` ASC", $type);
    }
    if(!$res)
        return(array());
    return($res);
}

function opf_get_data($id) {
    $res = opf_db_query( "SELECT * FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
     . " WHERE `id`=%d", $id);
    if($res) return($res[0]);
    else return($res);
}

// get max position
function opf_get_position_max($type) {
    return(
       opf_db_query_vars( 
           "SELECT MAX(`position`) "
           . " FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
           . " WHERE `type`='%s'", $type
        )
    );
}

// get min position
function opf_get_position_min($type) {
    return(
        opf_db_query_vars( 
            "SELECT MIN(`position`)"
            . " FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
            . " WHERE `type`='%s'", $type
        )
    );
}


// get position
function opf_get_position($name, $verbose=TRUE) {
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    if(opf_is_registered($name, $verbose)) {
        return(
           opf_db_query_vars( 
              "SELECT `position`"
              . " FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
              . " WHERE `name`='%s'", $name
           )
       );
    }
    return(FALSE);
}

// get type
function opf_get_type($name,$verbose=TRUE) {
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    if(opf_is_registered($name, $verbose)) {
        return(
           opf_db_query_vars( 
              "SELECT `type`"
               . " FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
               . " WHERE `name`='%s'", $name
           )
        );
    }
    return(FALSE);
}

// check whether OutputFilter "$name" is registered in DB
function opf_is_registered($name, $verbose=FALSE) {
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    if(
       opf_db_query_vars( 
          "SELECT TRUE FROM"
             . " `".TABLE_PREFIX."mod_outputfilter_dashboard`"
             . " WHERE `name`='%s'", $name
       )
    ) return(TRUE);
    else {
        if($verbose)
            trigger_error('opf_is_registered(): Filter not registred: '.$name, E_USER_WARNING);
    }
    return(FALSE);
}

// check if filter is active
function opf_is_active($name) {
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    if(opf_is_registered($name, TRUE)) {
        if(
           opf_db_query_vars( 
              "SELECT `active`"
              . " FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
              . " WHERE `name`='%s'", $name
           )
        ) {
            if(class_exists('Settings') && defined('WBCE_VERSION')){
                // in WBCE check for settings state as well, if enabled there return true 
                if(Settings::Get( opf_filter_name_to_setting($name), TRUE))
                    return TRUE;
                // if disabled but a backend version of this filter exists and it is on:  
                if(Settings::Get( opf_filter_name_to_setting($name).'_be', FALSE)){
                    $filter_settings=opf_filter_get_data($name);
                    // check if backend is also enabled inside of the filter 
                    if($filter_settings){
                        if(in_array('backend', $filter_settings['pages_parent']))
                            return(TRUE);
                    }
                    // if backend is not on inside the filter
                    return(FALSE);
                } 
                // if both, backend, and frontend are off via Settings class
                return(FALSE);
            }
            // other platforms, e.g. wb classic or older WBCE
            return(TRUE);
        }
        // Query failed - e.g. filter is not installed
        return(FALSE);
    }
    // filter is disabled in the dashboard. Really simple db-Query returns that it is off
    return(FALSE);
}

// set filter active or inactive
function opf_set_active($name, $active=1) {
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    if(!($active===1 || $active===0 || $active===TRUE || $active===FALSE)) {
        trigger_error('opf_set_active(): Wrong status', E_USER_WARNING);
        return(FALSE);
    }
    global $opf_FILTERS;
    unset($opf_FILTERS); 
    opf_preload_filter_definitions(); 
    if(opf_is_registered($name, TRUE)) {
        if(class_exists('Settings') && defined('WBCE_VERSION')){
            Settings::Set( opf_filter_name_to_setting($name), $active);
            $filter_settings=opf_filter_get_data($name);
            if($filter_settings)
                Settings::Set( opf_filter_name_to_setting($name).'_be', $active && 
                    in_array('backend', $filter_settings['pages_parent']));
        }
        return(
           opf_db_run_query( 
               "UPDATE `".TABLE_PREFIX."mod_outputfilter_dashboard`"
               . " SET `active`='%s'"
               . " WHERE `name`='%s'", $active, $name
           )
        );
    }
    return(FALSE);
}

// switch position of two filters (helper for opf_move_up_one() and opf_move_down_one())
function opf_switch_position($type, $pos1, $pos2) {
    $pos1 = (int)$pos1;
    $pos2 = (int)$pos2;
    if(abs($pos1-$pos2)!=1)
        return(FALSE);
    $name1 = opf_db_query_vars( 
       "SELECT `name`"
       . " FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
       . " WHERE `type`='%s'"
       . " AND `position`=%d", $type, $pos1
    );
    $name2 = opf_db_query_vars( 
        "SELECT `name`"
        . " FROM `".TABLE_PREFIX."mod_outputfilter_dashboard`"
        . " WHERE `type`='%s'"
        . " AND `position`=%d", $type, $pos2
    );
    if($name1===FALSE || $name2===FALSE)
        return(FALSE);
    $res1 = opf_db_run_query( 
        "UPDATE `".TABLE_PREFIX."mod_outputfilter_dashboard`"
        . " SET `position`=%d"
        . " WHERE `name`='%s'", $pos2, $name1
    );
    $res2 = opf_db_run_query( 
        "UPDATE `".TABLE_PREFIX."mod_outputfilter_dashboard`"
        . " SET `position`=%d"
        . " WHERE `name`='%s'", $pos1, $name2
    );
    if($res1 && $res2) {
        return(TRUE);
    }
    return(FALSE);
}

// move up position by one
function opf_move_up_one($name,$verbose=TRUE) {
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    $pos = opf_get_position($name,$verbose);
    $type = opf_get_type($name,$verbose);
    if($pos!==FALSE && $type!==FALSE && $pos>0) {
        $pos_new = $pos-1;
        return(opf_switch_position($type, $pos, $pos_new));
    }
    return(FALSE);
}

// move down position by one
function opf_move_down_one($name,$verbose=TRUE) {
    $name = opf_check_name($name);
    if(!$name) return(FALSE);
    $pos = opf_get_position($name,$verbose);
    $type = opf_get_type($name,$verbose);
    if($pos!==FALSE && $type!==FALSE) {
        $max = opf_get_position_max($type);
        if($max && $pos<$max) {
            $pos_new = $pos+1;
            return(opf_switch_position($type, $pos, $pos_new));
        }
    }
    return(FALSE);
}


// returns the WHERE-query for the target-modules, depending if the backend is supported
function opf_get_module_query(){
    $return_value = " WHERE `function`='page' ";
    if (class_exists("Tool") && defined('WBCE_VERSION')){ // backend-filtering supported 
        $module_types = array( 'tool', 'setting', 'panel', 'backend' );
        foreach ($module_types as $m) {
            $return_value .= " OR `function`='$m' ";
        }
    }
    return $return_value;
}


// get list of all installed page-modules useable as target (wysiwyg, news, ...)
function opf_list_target_modules($sorted=FALSE) { // read from table wb_addons
    $m = array();
    if(!$modules 
       = opf_db_query( 
          "SELECT *"
          . " FROM  `".TABLE_PREFIX."addons`"
          . opf_get_module_query()
          . " ORDER BY `name`"
        )
    ) return($m);
    if(!$sorted) {
        foreach($modules as $module) {
            $m[$module['directory']] = $module;
        }
        return($m);
    }
    // sort modules: galleries, shops, page, code, ...
    $m = opf_modules_categories('categories');
    $full_list = opf_modules_categories('modules');
    foreach($modules as $module) {
        // backend-filtering is not supported when there is no class "Tool" 
        if(($module['function'] != 'page') && (!(class_exists ("Tool") && defined('WBCE_VERSION')))) continue;  
        if(isset($full_list[$module['directory']])) {
            $type = $full_list[$module['directory']];
            if($type=='IGNORE') continue;
            $m[$type][] = $module;
        } else {
            if($module['function'] != 'page') $m['backend'][] = $module;
                else $m['various'][] = $module;
        }
    }
    return($m);
}

/*
    Function: opf_modules_categories
        

    (start code)
    // module --> category
    $module['accordion']      = 'listing';
    $module['addressbook']    = 'listing';
    $module['aggregator']     = 'listing';
    $module['anyitems']       = 'shop';
    $module['anytopics']      = 'wrapper';
    $module['Auto_Gallery']   = 'gallery';
    $module['bakery']         = 'shop';
    $module['bookings_v2']    = 'listing';
    $module['bookmarks']      = 'listing';
    $module['cabin']          = 'listing';
    $module['calendar']       = 'calendar';
    $module['color4code']     = 'code';
    $module['code']           = 'code';
    $module['code2']          = 'code';
    $module['concert']        = 'calendar';
    $module['curl']           = 'wrapper';
    $module['dirlist']        = 'listing';
    $module['doodler']        = 'poll';
    $module['download_gallery'] ='page';
    $module['event']          = 'calendar';
    $module['event_calendar'] = 'calendar';
    $module['extcal']         = 'calendar';
    $module['fancy_box']      = 'gallery';
    $module['faqbaker']       = 'listing';
    $module['faqmaker']       = 'listing';
    $module['feedback']       = 'various';
    $module['flickrgallery']  = 'gallery';
    $module['Foldergallery']  = 'gallery';
    $module['form']           = 'form';
    $module['formx']          = 'form';
    $module['gallery']        = 'gallery';
    $module['gdpics']         = 'gallery';
    $module['gocart']         = 'shop';
    $module['guestbook']      = 'page';
    $module['imageflow']      = 'gallery';
    $module['imagegallery']   = 'gallery';
    $module['inlinewrapper']  = 'wrapper';
    $module['lastitems']      = 'shop';
    $module['lightbox2']      = 'gallery';
    $module['lightbox']       = 'gallery';
    $module['manual']         = 'page';
    $module['mapbaker']       = 'page';
    $module['members']        = 'listing';
    $module['mfz']            = 'listing';
    $module['miniform']       = 'form';
    $module['mminigallery']   = 'gallery';
    $module['mpform']         = 'form';
    $module['news']           = 'page';
    $module['newsreader']     = 'various';
    $module['newsarc']        = 'page';
    $module['panoramic_image']= 'gallery';
    $module['pickle']         = 'gallery';
    $module['picturebox']     = 'gallery';
    $module['polls']          = 'poll';
    $module['tiltviewer']     = 'gallery';
    $module['shoutbox']       = 'various';
    $module['show_code']      = 'code';
    $module['show_code_geshi']= 'code';
    $module['simpleviewer']   = 'various';
    $module['sitemap']        = 'listing';
    $module['small_ads']      = 'various';
    $module['smoothgallery']  = 'gallery';
    $module['swift']          = 'gallery';
    $module['slideshow']      = 'gallery';
    $module['wrapper']        = 'wrapper';
    $module['wysiwyg']        = 'page';
    $module['wb-forum']       = 'various';
    $module['zitate']         = 'various';

    // these are ignored
    $module['menu_link']      = 'IGNORE';
    $module['section_picker'] = 'IGNORE';
    $module['massmail']       = 'IGNORE';
    (end)

*/
function opf_modules_categories($type='modules') {
    if($type=='categories') {
        // list of all categories
        $m['page'] = array();
        $m['form'] = array();
        $m['gallery'] = array();
        $m['wrapper'] = array();
        $m['calendar'] = array();
        $m['shop'] = array();
        $m['code'] = array();
        $m['poll'] = array();
        $m['listing'] = array();
        $m['various'] = array();
        if (class_exists ("Tool") && defined('WBCE_VERSION')){ // backend-filtering supported 
            $m['backend'] = array();
        }
        return($m);
    }
    // module --> category
    $module['accordion']      = 'listing';
    $module['addressbook']    = 'listing';
    $module['aggregator']     = 'listing';
    $module['anyitems']       = 'shop';
    $module['anytopics']      = 'wrapper';
    $module['Auto_Gallery']   = 'gallery';
    $module['bakery']         = 'shop';
    $module['bookings_v2']    = 'listing';
    $module['bookmarks']      = 'listing';
    $module['cabin']          = 'listing';
    $module['calendar']       = 'calendar';
    $module['color4code']     = 'code';
    $module['code']           = 'code';
    $module['code2']          = 'code';
    $module['concert']        = 'calendar';
    $module['curl']           = 'wrapper';
    $module['dirlist']        = 'listing';
    $module['doodler']        = 'poll';
    $module['download_gallery'] ='page';
    $module['event']          = 'calendar';
    $module['event_calendar'] = 'calendar';
    $module['extcal']         = 'calendar';
    $module['fancy_box']      = 'gallery';
    $module['faqbaker']       = 'listing';
    $module['faqmaker']       = 'listing';
    $module['feedback']       = 'various';
    $module['flickrgallery']  = 'gallery';
    $module['Foldergallery']  = 'gallery';
    $module['form']           = 'form';
    $module['formx']          = 'form';
    $module['gallery']        = 'gallery';
    $module['gdpics']         = 'gallery';
    $module['gocart']         = 'shop';
    $module['guestbook']      = 'page';
    $module['imageflow']      = 'gallery';
    $module['imagegallery']   = 'gallery';
    $module['inlinewrapper']  = 'wrapper';
    $module['lastitems']      = 'shop';
    $module['lightbox2']      = 'gallery';
    $module['lightbox']       = 'gallery';
    $module['manual']         = 'page';
    $module['mapbaker']       = 'page';
    $module['members']        = 'listing';
    $module['mfz']            = 'listing';
    $module['miniform']       = 'form';
    $module['mminigallery']   = 'gallery';
    $module['mpform']         = 'form';
    $module['news']           = 'page';
    $module['newsreader']     = 'various';
    $module['newsarc']        = 'page';
    $module['panoramic_image']= 'gallery';
    $module['pickle']         = 'gallery';
    $module['picturebox']     = 'gallery';
    $module['polls']          = 'poll';
    $module['tiltviewer']     = 'gallery';
    $module['shoutbox']       = 'various';
    $module['show_code']      = 'code';
    $module['show_code_geshi']= 'code';
    $module['simpleviewer']   = 'various';
    $module['sitemap']        = 'listing';
    $module['small_ads']      = 'various';
    $module['smoothgallery']  = 'gallery';
    $module['swift']          = 'gallery';
    $module['slideshow']      = 'gallery';
    $module['wrapper']        = 'wrapper';
    $module['wysiwyg']        = 'page';
    $module['wb-forum']       = 'various';
    $module['zitate']         = 'various';

    // these are ignored
    $module['menu_link']      = 'IGNORE';
    $module['section_picker'] = 'IGNORE';
    $module['massmail']       = 'IGNORE';

    if($type=='modules')
        return($module);
    elseif($type=='relations') {
        foreach($module as $m => $t)
            $category['all_'.$t.'_types'][] = $m;
        return($category);
    }
    return(FALSE);
}

// load all active filters into global array
// will load _all_ filters
function opf_preload_filter_definitions() {
    global $database;
    global $opf_FILTERS; // global storage of all filter-definitions
    global $opf_HEADER; // global storage of all css- and js-files to put into <head>
    global $opf_BODY; // global storage of all javascript-calls to put into <body>
    global $opf_PAGECHILDS; // store all child--page-relations
    global $opf_PAGES; // global storage of page-data
    global $opf_MODULES; // global storage of modules
    
    if(isset($opf_FILTERS) && is_array($opf_FILTERS)) return(FALSE);    
    
    $opf_FILTERS 
        = $opf_HEADER 
        = $opf_BODY 
        = $opf_PAGECHILDS 
        = $opf_PAGES 
        = $opf_MODULES = array();
    // fetch page-data
    $pages 
       = opf_db_query( 
           "SELECT *"
           . " FROM `".TABLE_PREFIX."pages`"
           . " ORDER BY `level`,`position` ASC"
        );
    if(!is_array($pages)) $pages=array();
    $pages_act = array();
    foreach($pages as $page) {
        $pages_act[(int)$page['page_id']] = $page;
    }
    unset($pages);
    $opf_PAGES = $pages_act;
    // fetch filter-data
    $filters = opf_select_filters();
    if(is_array($filters)) {
        foreach($filters as $filter) {
            $filter['helppath'] = unserialize($filter['helppath']);
            $filter['desc'] = unserialize($filter['desc']);
            $filter['modules'] = unserialize($filter['modules']);
            $filter['pages'] = unserialize($filter['pages']);
            $filter['pages_parent'] = unserialize($filter['pages_parent']);
            $filter['pages_parent'] = opf_update_pages_parent($filter['pages_parent']);
            $filter['current'] = FALSE;
            $filter['activated'] = FALSE;
            $filter['failed'] = FALSE;
            $filter['additional_values'] = unserialize($filter['additional_values']);
            unset($filter['additional_fields']);
            unset($filter['additional_fields_languages']);
            $opf_FILTERS[$filter['name']] = $filter;
        }
    }
    // fetch module-data
    $opf_MODULES = opf_list_target_modules(TRUE);
    // fetch child--page-relations
    foreach($opf_PAGES as $page) {
        $page_id = (int)$page['page_id'];
        $opf_PAGECHILDS[$page_id] = array($page_id);
    }
    foreach($opf_PAGES as $subpage) {
        if($subpage['parent']==0) continue;
        $trail = explode(',', $subpage['page_trail']);
        while($trail) {
            $pid = array_pop($trail);
            foreach($trail as $p) {
                //if(!in_array($pid, $opf_PAGECHILDS[$p])) 
                // much more expensive than the array_unique()-calls below!
                    $opf_PAGECHILDS[$p][] = $pid;
        }
    }
    }
    // clean $opf_PAGECHILDS -- this is much cheaper than the in_array()-calls above!
    $opf_PAGECHILDS_unique = array();
    foreach($opf_PAGECHILDS as $p) {
        $opf_PAGECHILDS_unique[] = array_unique($p);
    }
    $opf_PAGECHILDS = $opf_PAGECHILDS_unique;
}

// apply filters of type $type and module $module on $content (passed by reference)
function opf_apply_filters(&$content, $type, $module, $page_id, $section_id, $wb) {
    global $opf_FILTERS;
    global $opf_MODULE, $opf_PAGEID, $opf_SECTIONID;
    $opf_MODULE = $module;
    $opf_PAGEID = $page_id;
    $opf_SECTIONID = $section_id;
    if(!(isset($opf_FILTERS) && is_array($opf_FILTERS))) {
        trigger_error('global Array not defined', E_USER_WARNING);
        return(FALSE);
    }
    foreach($opf_FILTERS as $key => $filter) {
        if(is_array($filter) && isset($filter['pages_parent'])) {
            if($filter['active'] && $filter['type']==$type
                && ($module=='' || in_array($module, $filter['modules']) || in_array('all', $filter['modules']))
                && ( ($page_id == 'backend') 
                 || (in_array('all', $filter['pages']) || in_array($page_id, $filter['pages']))
                 || (in_array('all', $filter['pages_parent']) || in_array($page_id, $filter['pages_parent']))
                )) {
                
                if(!function_exists($filter['funcname'])) {
                    $filter['file'] = str_replace('{SYSVAR:WB_PATH}', WB_PATH, $filter['file']);
                    $filter['file'] = str_replace('{OPF:PLUGIN_PATH}', OPF_PLUGINS_PATH.$filter['plugin'], $filter['file']);
                    if($filter['file'] && file_exists($filter['file'])) {
                        require_once($filter['file']);
                    } else {
                        eval('?>'.$filter['func']);
                    }
                }
                if(function_exists($filter['funcname'])) {
                    $content_backup = $content;
                    $opf_FILTERS[$key]['current'] = TRUE;
                    $res = call_user_func_array($filter['funcname'], array(&$content, $page_id, $section_id, $module, $wb));
                    $opf_FILTERS[$key]['activated'] = TRUE;
                    $opf_FILTERS[$key]['current'] = FALSE;
                    if($res===FALSE) {
                        $content = $content_backup; // filter failed and content maybe broken, restore old content
                        $opf_FILTERS[$key]['failed'] = TRUE;
                    }
                } else {
                    trigger_error('failed to apply filter '.htmlspecialchars($filter['name']), E_USER_WARNING);
                }
            }
        }
    }
}


function opf_apply_get_modules($page_id) {
    // determine page_id and module
    $modules = array();
    if($page_id) { // maybe guestbook or news
        if(!$modules = opf_db_query( "SELECT `module`,`section_id` FROM ".TABLE_PREFIX."sections WHERE `page_id`=%d", $page_id))
            $modules = array();
    } else { // search or account
        if(strpos($_SERVER['PHP_SELF'], '/search/index.php')!==FALSE)
            $modules[0]['module'] = array('searchresult');
        else
            $modules[0]['module'] = array('nothing');
        $modules[0]['section_id'] = 0;
    }
    return($modules);
}


// insert CSS JS files into <head>
function opf_insert_frontend_files(&$content) {
    global $opf_HEADER; // global storage for all entries
    if(!(isset($opf_HEADER) && is_array($opf_HEADER))) {
        trigger_error('global Array opf_HEADER not defined', E_USER_WARNING);
        return(FALSE);
    }
    global $opf_BODY; // global storage for all entries
    if(!(isset($opf_BODY) && is_array($opf_BODY))) {
        trigger_error('global Array opf_BODY not defined', E_USER_WARNING);
        return(FALSE);
    }
    if($opf_HEADER!=array()) {
        // put $opf_HEADER into <head></head>
        $str = implode("\n", $opf_HEADER);
        $content_new = preg_replace('~</head>~',$str."\n</head>",$content);
        $opf_HEADER = array();
        if($content_new===FALSE || $content_new==$content) {
            trigger_error('failed to change html head-section', E_USER_WARNING);
            return(FALSE);
        }
        $content = $content_new;
    }
    if($opf_BODY!=array()) {
        // put $opf_BODY into <body></body>
        $str = implode("\n", $opf_BODY);
        $content_new = preg_replace('~</body>~',$str."\n</body>",$content);
        $opf_BODY = array();
        if($content_new===FALSE || $content_new==$content) {
            trigger_error('failed to change html body-section', E_USER_WARNING);
            return(FALSE);
        }
        $content = $content_new;
    }
    return(TRUE);
}

// Get page-hierarchy
/*
array
108 => 
    array
      'title' => string 'News' (length=4)
      'child' => boolean false
131 => 
    array
      'title' => string 'test2' (length=5)
      'child' => 
    array
      134 => 
          array
        'title' => string 'Test' (length=4)
        'child' => boolean false
      135 => 
          array
        'title' => string 'Test2' (length=5)
        'child' => boolean false
...
*/
function opf_list_page_hierarchy() {
    // fetch all pages from DB
    $pages_all = array();
    $pages = opf_db_query( "SELECT * FROM ".TABLE_PREFIX."pages ORDER BY `level`,`position` ASC");
    if(!is_array($pages))
        $pages = array();
    foreach($pages as $page) {
        $pages_all[(int)$page['page_id']]['title'] = $page['menu_title'];
        $pages_all[(int)$page['page_id']]['pagetrail'] = $page['page_trail'];
    }
    unset($pages);
    // fetch child--page-relations -- ATTN: this relies on page_trail.
    // page_trail keeps the root-page and all parent-pages down to the actual page_id (included)
    // 3:     3:actual page (== root-parent)
    // 3,5    3:root-parent, 5:actual page
    // 3,5,10:    3:root-parent, 5:parent, 10:actual page
    // 3,5,10,11: 3:root-parent, 5:grand-parent, 10:parent, 11:actual page
    $page_hierarchy = array();
    $tmp =& $page_hierarchy; // move $tmp along $page_hierarchy
    foreach($pages_all as $page) {
        $title = $page['title'];
        $pagetrail = explode(',', $page['pagetrail']);
        foreach($pagetrail as $page_id) {
            if(!is_array($tmp) || !isset($tmp[$page_id])) {
                $tmp[$page_id]['child'] = FALSE;
                $tmp[$page_id]['title'] = $title;
            }
            $tmp =& $tmp[$page_id]['child'];
        }
        $tmp =& $page_hierarchy;
    }
    return($page_hierarchy);
}

//
function opf_make_modules_checktree($modules, $type='tree', $force_all_checked=FALSE) {
    global $LANG;
    $mlist = '';
    if($type=='flat') {
        $modules_list = opf_list_target_modules(FALSE);
        $mlist = '<div class="checktreestylearea">';
        if(count($modules)>0) {
            foreach($modules as $module_dir) {
                if(isset($modules_list[$module_dir])) $mlist .= $modules_list[$module_dir]['name'].', ';
                else $mlist .= $module_dir.', ';
            }
        } else echo '&nbsp;';
        $mlist = rtrim($mlist, ', ').'</div>';
    }
    elseif($type=='tree') {
        $modules_list = opf_list_target_modules(TRUE);
        $all_checked = $type_checked = FALSE;
        $mlist = '<div class="checktreestylearea"><ul class="tree1 checktreestyle">';
        if(in_array('all', $modules) || $force_all_checked) { $all_checked = TRUE; $checked = 'checked="checked"'; } else $checked = '';
        $mlist .= '<li><input type="checkbox" name="modules[]" value="all" '.$checked.' /><label>'.$LANG['MOD_OPF']['TXT_ALL_MODULES'].'</label><ul>';
        foreach($modules_list as $module_type => $modules_data) {
            if(count($modules_data)==0) continue;
            if($all_checked || in_array('all_'.$module_type.'_types', $modules)) { $type_checked = TRUE; $checked = 'checked="checked"'; } else  { $type_checked = FALSE; $checked = ''; }
            $mlist .= '<li><input type="checkbox" name="modules[]" value="all_'.$module_type.'_types" '.$checked.' /><label>'.$module_type.'</label><ul>';
            foreach($modules_data as $module) {
                if($all_checked || $type_checked || in_array($module['directory'], $modules)) $checked = 'checked="checked"'; else $checked = '';
                $mlist .= '<li><input type="checkbox" name="modules[]" value="'.$module['directory'].'" '.$checked.' /><label>'.$module['name'].'</label></li>';
            }
            $mlist .= '</ul></li>';
        }
        $mlist .= '</ul></li></ul></div>';
    }
    return($mlist);
}

//
function opf_build_tree_page_hierarchy($page_hierarchy, $pages_parent, $pages, $name) {
global $LANG;
    $output = '';
    if(in_array('all', $pages_parent)) $all_checked_pp = TRUE; else $all_checked_pp = FALSE;
    if(in_array('all', $pages)) $all_checked_p = TRUE; else $all_checked_p = FALSE;
    foreach($page_hierarchy as $page_id => $page) {
        if($all_checked_pp || in_array($page_id, $pages_parent)) $checked = 'checked="checked"'; else $checked = '';
        if(is_array($page['child'])) {
            if($all_checked_p || in_array($page_id, $pages)) $checked_s = 'checked="checked"'; else $checked_s = '';
            $output .= '<li><input type="checkbox" name="'.$name.'[]" value="s'.$page_id.'" '.$checked_s.' /><label>'.$page['title'].' ('.$LANG['MOD_OPF']['TXT_SINGLE_PAGE'].')</label></li>';
            $output .= '<li><input type="checkbox" name="'.$name.'[]" value="'.$page_id.'" '.$checked.' /><label>'.$page['title'].' ('.$LANG['MOD_OPF']['TXT_PAGE_HIERARCHY'].')</label>';
            $output .= '<ul>';
            $output .= opf_build_tree_page_hierarchy($page['child'], $pages_parent, $pages, $name);
            $output .= '</ul></li>';
        } else {
            $output .= '<li><input type="checkbox" name="'.$name.'[]" value="'.$page_id.'" '.$checked.' /><label>'.$page['title'].'</label></li>';
        }
    }
    return($output);
}

//
function opf_update_pages_parent($pages_parent) {
    global $opf_PAGECHILDS;
    if(!(isset($opf_PAGECHILDS) && is_array($opf_PAGECHILDS))) {
        opf_preload_filter_definitions();
        if(!(isset($opf_PAGECHILDS) && is_array($opf_PAGECHILDS))) {
            trigger_error('global Array not defined', E_USER_WARNING);
            return($pages_parent);
        }
    }
    $txt = '';
    foreach($pages_parent as $p)
        $txt .= $p;
    $all_childs = FALSE;
    if(in_array('all', $pages_parent)) $all_childs = TRUE;
    foreach($opf_PAGECHILDS as $page_id => $pages) {
        if($all_childs || in_array($page_id, $pages_parent))
        $pages_parent = array_merge($pages_parent, $pages);
    }
    $pages_parent = array_unique($pages_parent);
    return($pages_parent);
}

//
function opf_make_pages_parent_checktree($pages_parent, $pages, $type='tree') {
global $LANG;
    //$pages_parent = opf_update_pages_parent($pages_parent);
    $page_hierarchy = opf_list_page_hierarchy();
    $plist = '';
    if(in_array('0', $pages_parent)) $search_checked = 'checked="checked"'; else $search_checked = '';
    if(in_array('backend', $pages_parent)) $backend_checked = 'checked="checked"'; else $backend_checked = '';
    if($type=='flat') {
        $plist = '<div class="checktreestylearea">';
        if(count($pages_parent)>0) {
            foreach($pages_parent as $page) { $plist .= $page.', '; }
        } else { echo '&nbsp;'; }
        $plist = rtrim($plist, ', ').'</div>';
    } elseif($type=='tree') {
        $plist  = '<div class="checktreestylearea"><ul class="tree2 checktreestyle">';
        $plist .= '<li><input type="checkbox" name="searchresult" value="0" '.$search_checked.' /><label>'.$LANG['MOD_OPF']['TXT_SEARCH_RESULTS'].'</label></li>';
        if (class_exists ("Tool") && defined('WBCE_VERSION')){ // backend-filtering supported 
            $plist .= '<li><input type="checkbox" name="backend" value="backend" '.$backend_checked.' /><label>'.$LANG['MOD_OPF']['TXT_BACKEND'].'</label></li>';
        }
        $plist .= '<li><input type="checkbox" name="pages_parent[]" value="all" /><label>'.$LANG['MOD_OPF']['TXT_ALL_PAGES'].'</label><ul>';
        $plist .= opf_build_tree_page_hierarchy($page_hierarchy, $pages_parent, $pages, 'pages_parent');
        $plist .= '</ul></li></ul></div>';
    }
    return($plist);
}


//
function opf_css_save() {
    $id = opf_fetch_post( 'id', NULL, 'int');
    $css = opf_fetch_post( 'css', '', 'unchanged');
    
    if(!$id) {
        return NULL;
    }
    
    $csspath = opf_db_query_vars( "SELECT `csspath` FROM ".TABLE_PREFIX."mod_outputfilter_dashboard WHERE `id`=%d", $id);
    if($csspath && file_exists($csspath) && is_writable($csspath)) {
        $fh = fopen($csspath, "wb");
        $bytes = fwrite($fh, $css);
        fclose($fh);
    }
    return $id;
}


// save filter, called from Admin-Tool: add or edit
function opf_save() {
    global $database;
    $types = opf_get_types();
    // get values
    $id       = opf_fetch_post( 'id', 0, 'int'); // id is set from opf_edit_filter() only
    $type     = opf_fetch_post( 'type', '', 'unchanged');
    $name     = opf_fetch_post( 'name', '', 'unchanged');
    $func     = opf_fetch_post( 'func', '', 'unchanged');
    $funcname = opf_fetch_post( 'funcname', '', 'unchanged');
    $desc     = opf_fetch_post( 'desc', '', 'unchanged');
    $active   = opf_fetch_post( 'active', 0, 'int');
    $modules  = opf_fetch_post( 'modules', array(), 'unchanged');
    //$pages  = opf_fetch_post( 'pages', array(), 'unchanged');
    $pages_parent = opf_fetch_post( 'pages_parent', array(), 'unchanged');
    $searchres= opf_fetch_post( 'searchresult', FALSE, 'exists');
    $backend  = opf_fetch_post( 'backend', FALSE, 'exists');
    if($searchres!==FALSE) {
        $pages_parent[] = '0';
    }
    if($backend!==FALSE) {
        $pages_parent[] = 'backend';
    }
    // cleanup
    $desc = trim($desc);
    $func = trim($func);
    $name = trim($name);
    $funcname = trim($funcname);
    $type = (array_key_exists($type,$types)?$type:key($types));
    $file = '';
    $additional_values = serialize('');

    // move single-page values from $pages_parent to $pages
    $tmp_pages_parent = $tmp_pages = array();
    foreach($pages_parent as $pid) {
        if(strpos($pid, 's')===0)
            $tmp_pages[] = substr($pid, 1);
        else
            $tmp_pages_parent[] = $pid;
    }
    $pages_parent = $tmp_pages_parent;
    $pages = $tmp_pages;

    // add additional data
    $filter_old = array();
    if($id>0 && opf_db_query_vars( 
        "SELECT TRUE FROM ".TABLE_PREFIX."mod_outputfilter_dashboard"
           . " WHERE `id`=%d", $id)) {
        // comes from edit, so fetch old data from DB
        $filter_old = opf_db_query( "SELECT *"
            . " FROM ".TABLE_PREFIX."mod_outputfilter_dashboard"
            . " WHERE `id`=%d", $id);
        if(!empty($filter_old)){
             $filter_old = $filter_old[0];
             $userfunc = $filter_old['userfunc'];
             $plugin = $filter_old['plugin'];
             $allowedit = $filter_old['allowedit'];
             $allowedittarget = $filter_old['allowedittarget'];
             $configurl = $filter_old['configurl'];
             $helppath = unserialize($filter_old['helppath']);
             $csspath = $filter_old['csspath'];
             $file = $filter_old['file'];
        }
    } else { // comes from add, add default values for inline-filters
        $userfunc = 1;
        $plugin = '';
        $allowedit = 1;
        $allowedittarget = 1;
        $configurl = '';
        $csspath = '';
        $helppath = array();
    }
    // do we have to handle additional data?
    if($id>0 && !empty($filter_old)) { // comes from edit, so check additional_fields
        $additional_fields = unserialize($filter_old['additional_fields']);
        if(!empty($additional_fields)) {
            $additional_values = array();
            foreach($additional_fields as $field) {
                if(isset($_POST[$field['name']])) {
                    if(($field['type']=='textarea' || $field['type']=='editarea') && is_array($field['value'])) {
                        $a = array();
                        preg_match_all("~^\s*'(.*?)'\s*=>\s*'(.*?)'\s*,?\s*$~ms", opf_fetch_post( $field['name'],'','unchanged'), $matches, PREG_SET_ORDER);
                        if(isset($matches) && $matches) {
                            foreach($matches as $match) {
                                $a[$match[1]] = $match[2];
                            }
                        }
                        $additional_values[$field['variable']] = $a;
                    } elseif($field['type']=='array') {
                        $a = array(); $i = 0;
                        while(isset($_POST[$field['name']]['k'][$i])) {
                            // we can't use opf_fetch_post() because we need to read from $_POST[$field['name']]['k'][$i]
                            $a[opf_fetch_clean( $_POST[$field['name']]['k'][$i],'','unchanged',FALSE,TRUE)] = opf_fetch_clean( $_POST[$field['name']]['v'][$i],'','unchanged',FALSE,TRUE);
                            $i++;
                        }
                        $additional_values[$field['variable']] = $a;
                    } else
                        $additional_values[$field['variable']] = opf_fetch_post( $field['name'],'','unchanged');
                }
                else
                    $additional_values[$field['variable']] = FALSE;
            }
        }
    }
    // use old values if we come from edit and allowedit is 0
    if($id>0 && !empty($filter_old)) {
        if($allowedit==0) {
            $name = $filter_old['name'];
            $funcname = $filter_old['funcname'];
            $func = $filter_old['func'];
            $type = $filter_old['type'];
            $desc = unserialize($filter_old['desc']);
            if($allowedittarget==0) {
                $modules = unserialize($filter_old['modules']);
                $pages = unserialize($filter_old['pages']);
                $pages_parent = unserialize($filter_old['pages_parent']);
            }
        }
        if(!empty($filter_old['name'])){
            if(class_exists('Settings') && defined('WBCE_VERSION')){
                Settings::Del( opf_filter_name_to_setting($filter_old['name']));
            }
        }
    }

    // prevent inline-filters from overwriting a different filter with same name
    if($id==0) { // we come from add-filter 
        while(opf_is_registered($name))
            $name .= mt_rand(0,9);
    } else { // we come from edit-filter: allow to overwrite old one (same $id)
        if(opf_check_name($id)!=$name) {
            while(opf_is_registered($name))
                $name .= mt_rand(0,9);
        }
    }

    // register or update filter
    $res = opf_register_filter(array(
        'id' => $id,
        'type' => $type,
        'name' => $name,
        'func' => $func,
        'file' => $file,
        'funcname' => $funcname,
        'modules' => $modules,
        'pages' => $pages,
        'pages_parent' => $pages_parent,
        'desc' => $desc,
        'userfunc' => $userfunc,
        'plugin' => $plugin,
        'active' => $active,
        'allowedit' => $allowedit,
        'allowedittarget' => $allowedittarget,
        'configurl' => $configurl,
        'csspath' => $csspath,
        'helppath' => $helppath,
        'force' => TRUE,
        'filter_installed' => FALSE, // keep this on FALSE
        'additional_values' => $additional_values
    ));
    if($res) {
        if($id==0)
            return($database->getLastInsertId());
        else
            return($id);
    }
    return($res);
}


function opf_controller($arg, $opt=null, $module='', $page_id=0, $section_id=0) {
    global $wb;
    if(defined('PAGE_ID')&&($page_id==0))$page_id=PAGE_ID;

    opf_preload_filter_definitions();

    switch($arg) {
    case('init'):
        // moved this to above but keeping the option for explicit initialization
        break;
    case('page'):
        opf_apply_filters($opt, OPF_TYPE_PAGE, FALSE, $page_id, FALSE, $wb);
        opf_apply_filters($opt, OPF_TYPE_PAGE_LAST, FALSE, $page_id, FALSE, $wb);
        opf_insert_frontend_files($opt);
        return($opt);
        break;
    case('section'):
        opf_apply_filters($opt, OPF_TYPE_SECTION, $module, $page_id, $section_id, $wb);
        opf_apply_filters($opt, OPF_TYPE_SECTION_LAST, $module, $page_id, $section_id, $wb);
        return($opt);
        break;
    case('backend'):
        if(!defined("WB_OPF_BE_OFF")){
            if($module==""){
                opf_apply_filters($opt, OPF_TYPE_PAGE, FALSE, 'backend', 0, $wb);
                opf_apply_filters($opt, OPF_TYPE_PAGE_LAST, FALSE, 'backend', 0, $wb);
            } else {
                opf_apply_filters($opt, OPF_TYPE_SECTION, $module, 'backend', 0, $wb);
                opf_apply_filters($opt, OPF_TYPE_SECTION_LAST, $module, 'backend', 0, $wb);
            }
        }
        return($opt);
        break;
    case('special'):
        foreach(opf_apply_get_modules($page_id) as $module) {
            opf_apply_filters($opt, OPF_TYPE_SECTION, $module['module'], $page_id, $module['section_id'], $wb);
            opf_apply_filters($opt, OPF_TYPE_SECTION_LAST, $module['module'], $page_id, $module['section_id'], $wb);
        }
        return($opt);
        break;
    default:
        return($opt);
    }
}

// fetches entry from array based on LANGUAGE
/*
    Private Function: opf_fetch_entry_language
        fetch a string from an array based on WB's !LANGUAGE! setting

    Prototype: 
        %string% opf_fetch_entry_language( %array% $descs )

    Parameters:
        descs - %array% Array with a string for different languages

    Array:
        >

    Returns:

*/
function opf_fetch_entry_language($descs) {
    if(!is_array($descs) || empty($descs)) return('');
    if(isset($descs[LANGUAGE])) return($descs[LANGUAGE]);
    if(isset($descs['EN'])) return($descs['EN']);
    return(reset($descs)); // return first element
}

// evaluate a variable from a file, e.g. the plugin_info.php
function opf_plugin_info_read($file, $var=FALSE) {
    require($file);
    if(!$var)
        return(isset($plugin_directory)?$plugin_directory:FALSE);
    else
        return(isset($$var)?$$var:FALSE);
}

// a "serialize" function which produces human readable php code
function opf_dump_var($var, $spacing=""){
    $result = "";
    if(is_string($var)) {
        if(strpos($var, 'a:')===0){
            $var=unserialize($var);
        }
    }
    if(is_array($var)){
        $result = "array (\n";
        $numeric_keys=true;
        foreach(array_keys($var) as $key){
            if(!is_numeric($key)) 
                $numeric_keys=false;
        }
        foreach($var as $key => $value){
            if(!is_numeric($key)){
                  $result .= "$spacing    \"$key\" => "
                    . opf_dump_var($value,"$spacing    ")
                    .",\n";
            } else if($numeric_keys){
                  $result .= "$spacing    $key => "
                    . opf_dump_var($value,"$spacing    ")
                    .",\n";
            }
        }
        $result .= "$spacing)";
    } else {
        if(is_string($var)){
            $result = "'".opf_escape_string($var)."'";
        } else {
            $result = "$var";
        }
    }
    return $result;
}

// escapes single quotes in a string to be included in another single-quoted string
function opf_escape_string($str){
    return str_replace(array('\\',"'"), array('\\\\',"\'"), $str);
}


// create a directory name out of an arbitrary string
function opf_create_dirname($str){
    $s=strtolower(preg_replace(array('/\s\s*/','/[^a-zA-Z0-9_]/','/_*$/'), array('_','',''), $str));
    if (strlen($s>63))
       return substr(0,63,$s);
    else
       return $s;
}

