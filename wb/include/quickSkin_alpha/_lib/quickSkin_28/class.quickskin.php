<?php
//error_reporting(E_ALL ^ E_NOTICE);
/*~ class.quickskin.php
.---------------------------------------------------------------------------.
|  Software: QuickSkin Class                                                |
|   Version: 5.0                                                            |
|   Contact: andy.prevost@worxteam.com,andy@codeworx.ca                     |
|      Info: http://quickskin.sourceforge.net                               |
|   Support: http://sourceforge.net/projects/quickskin/                     |
| ------------------------------------------------------------------------- |
|    Author: Andy Prevost andy.prevost@worxteam.com (admin)                 |
|    Author: Manuel 'EndelWar' Dalla Lana endelwar@aregar.it (former admin) |
|    Author: Philipp v. Criegern philipp@criegern.com (original founder)    |
| Copyright (c) 2002-2009, Andy Prevost. All Rights Reserved.               |
|    * NOTE: QuickSkin is the SmartTemplate project renamed. SmartTemplate  |
|            information and downloads can still be accessed at the         |
|            smarttemplate.sourceforge.net site                             |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| We offer a number of paid services:                                       |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'
Last modified: January 01 2009 ~*/

/* designed to work with PHP5 - will NOT work with PHP4 */

/* PURPOSE: 'Compiles' HTML-Templates to PHP Code
 *
 * Usage Example I:
 *
 * $page = new QuickSkin( "template.html" );
 * $page->assign( 'TITLE',  'TemplateDemo - Userlist' );
 * $page->assign( 'user',   DB_read_all( 'select * from ris_user' ) );
 * $page->output();
 *
 * Usage Example II:
 *
 * $data = array(
 *             'TITLE' => 'TemplateDemo - Userlist',
 *             'user'  => DB_read_all( 'select * from ris_user' )
 *         );
 * $page = new QuickSkin( "template.html" );
 * $page->output( $data );
 *
 */

class QuickSkin {

  /////////////////////////////////////////////////
  // PROPERTIES, PUBLIC
  /////////////////////////////////////////////////

  /**
   * Reuse Code
   * Whether to use stored compiled php code or not (for debug purpose)
   * @var bool
   */
  public $reuse_code       = false;

  /**
   * Template Directory
   * Directory where all templates are stored, can be overwritten by
   * global configuration array $_CONFIG['template_dir']
   * @var string
   */
  public $template_dir     = '_skins/';

  /**
   * Temp Directory
   * Where to store compiled templates, can be overwritten by
   * global configuration array $_CONFIG['quickskin_compiled']
   * @var string
   */
  public $temp_dir         = '_skins_tmp/';

  /**
   * Cache Directory
   * Temporary folder for output cache storage, can be overwritten by
   * global configuration array $_CONFIG['quickskin_cache']
   * Note: typically set the same as the Temp Directory, but can be unique
   * @var string
   */
  public $cache_dir         = '_skins_tmp/';

  /**
   * Cache Lifetime
   * Default Output Cache Lifetime in Seconds, can be overwritten by
   * global configuration array $_CONFIG['cache_lifetime']
   * @var int
   */
  public $cache_lifetime    = 600; // seconds

  /**
   * Extensions Directory
   * Directory where all extensions are stored
   * @var string
   */
  public $extensions_dir    = '_lib/qx';      /* Directory where all extensions are stored */

  /**
   * Extension Prefix
   * Filename prefix on all the extensions files
   * @var string
   */
  public $extension_prefix  = 'qx_';

  /**
   * Left Delimiter
   * Default Left delimiter, can be overwritten by
   * global configuration array $_CONFIG['left_delimiter']
   * @var string
   */
  public $left_delimiter    = '{';

  /**
   * Right Delimiter
   * Default Right delimiter, can be overwritten by
   * global configuration array $_CONFIG['right_delimiter']
   * @var string
   */
  public $right_delimiter   = '}';

  /**
   * Extension Tagged
   * List of used QuickSkin Extensions
   * @var array
   */
  public $extension_tagged  = array();

  /**
   * QuickSkin Version Number
   * List of used QuickSkin Extensions
   * @var string
   */
  public $version           = '5.0';

  /////////////////////////////////////////////////
  // PROPERTIES, PRIVATE
  /////////////////////////////////////////////////

  private $cache_filename; /* Temporary file for output cache storage */
  private $tpl_file;       /* The template filename */
  private $cpl_file;       /* The compiled template filename */
  private $data = array(); /* Template content array */
  private $parser;         /* Parser Class */
  private $debugger;       /* Debugger Class */
  private $skins_sub_dir;  /* temporary variable to hold the subdirectory of the main template */
  private $supp_templates = '';   /* supplementary templates */
  private $supptemplate   = '';   /* supplementary template */

  /* QuickSkin Constructor
   * @access public
   * @param string $template_filename Template Filename
   */
  function __construct( $template_filename = '' ) {
    global $_CONFIG;
	// make extension directory setting
	if (!empty($_CONFIG['extensions_dir'])) {
      $this->extensions_dir  =  $_CONFIG['extensions_dir'];
    }
    if (!empty($_CONFIG['quickskin_compiled'])) {
      $this->temp_dir  =  $_CONFIG['quickskin_compiled'];
    }
    if (!empty($_CONFIG['quickskin_cache'])) {
      $this->cache_dir  =  $_CONFIG['quickskin_cache'];
    }
    if (is_numeric($_CONFIG['cache_lifetime'])) {
      $this->cache_lifetime  =  $_CONFIG['cache_lifetime'];
    }
    if (!empty($_CONFIG['template_dir'])  &&  is_file($_CONFIG['template_dir'] . '/' . $template_filename)) {
      $this->template_dir  =  $_CONFIG['template_dir'];
    }
    $this->tpl_file  =  $template_filename;
    if ( dirname($this->tpl_file) != "") {
      $this->skins_sub_dir = dirname($this->tpl_file);
    }
  }

  /* DEPRECATED METHODS */
  /* Methods used in older parser versions, soon will be removed */
  function set_templatefile ($template_filename)  { $this->tpl_file  =  $template_filename; }
  function add_value ($name, $value )       { $this->assign($name, $value); }
  function add_array ($name, $value )       { $this->append($name, $value); }

  /* Process file or contents to strip out the <body tag (inclusive)
   * and the </body tag to the end
   *
   * Usage Example:
   * $page->getContents( '', '/contents.htm' );
   * or
   * $page->getContents( 'start of data .... end of data' );
   *
   * @access public
   * @param string $contents Parameter contents
   * @param string $filename Parameter filename (fully qualified)
   * @desc strip out body tags and return only page data
   */
  function getContents($contents, $filename="") {
    if ( $contents == '' && $filename != '' && file_exists($filename) ) {
      $contents = file_get_contents($filename);
    }

    // START process any PHP code
    ob_start();
    eval("?>".$contents."<?php ");
    $contents = ob_get_contents();
    ob_end_clean();
    // END process any PHP code
    $lower_contents = strtolower($contents);
    // determine if a <body tag exists and process if necessary
    $bodytag_start = strpos($lower_contents, "<body");
    if ( $bodytag_start !== false ) {
      $bodytag_end    = strpos($lower_contents, ">", $bodytag_start) + 1;
      // get contents with <body tag removed
      $contents       = substr($contents, $bodytag_end);
      $lower_contents = strtolower($contents);
      // work on </body closing tag
      $end_start      = strpos($lower_contents, "</body");
      $end_end        = strpos($lower_contents, ">", $bodytag_start) + 1;
      // return stripped out <body and </body tags
      return $this->getExtensions( substr($contents, 0, $end_start) );
    } else {
      // body tags not found, so return data
      return $this->getExtensions( $contents );
    }
  }

  /* Determine Contents Command from Variable Name
   * {variable}             :  array( "echo",              "variable" )  ->  echo $_obj['variable']
   * {variable > new_name}  :  array( "_obj['new_name']=", "variable" )  ->  $_obj['new_name']= $_obj['variable']
   * @param string $tag Variale Name used in Template
   * @return array  Array Command, Variable
   * @access private
   * @desc Determine Contents Command from Variable Name
   */
  function processCmd($tag) {
    if (preg_match('/^(.+) > ([a-zA-Z0-9_.]+)$/', $tag, $tagvar)) {
      $tag  =  $tagvar[1];
      list($newblock, $newskalar)  =  $this->var_name($tagvar[2]);
      $cmd  =  "\$$newblock"."['$newskalar']=";
    } else {
      $cmd  =  'echo';
    }
    $ret = array($cmd, $tag);
    return $ret;
  }

  /* Load and process the Extensions that may be used in the Contents
   *
   * Usage Example:
   * $tcnt = $this->getExtensions( $param );
   *
   * @access public
   * @param string $param (content to process)
   * @return string
   * @desc Load and process the Extensions that may be used in the Contents
   */
  function getExtensions($contents) {
    $header = '';
    /* Include Extensions */
    if (preg_match_all('/'.$this->left_delimiter.'([a-zA-Z0-9_]+):([^}]*)'.$this->right_delimiter.'/', $contents, $var)) {
      foreach ($var[2] as $cnt => $tag) {
        /* Determine Command (echo / $obj[n]=) */
        list($cmd, $tag)  =  $this->processCmd($tag);

        $extension  =  $var[1][$cnt];
        //if (!isset($this->extension_tagged[$extension])) {
          $header .= 'include_once "'.$this->extensions_dir."/".$this->extension_prefix."$extension.php\";\n";
        //  $this->extension_tagged[$extension]  =  true;
        //}
        if (!strlen($tag)) {
          $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension();\n?>\n";
        } elseif (substr($tag, 0, 1) == '"') {
          $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension($tag);\n?>\n";
        } elseif (strpos($tag, ',')) {
          list($tag, $addparam)  =  explode(',', $tag, 2);
          list($block, $skalar)  =  $this->var_name($tag);
          if (preg_match('/^([a-zA-Z_]+)/', $addparam, $match)) {
            $nexttag   =  $match[1];
            list($nextblock, $nextskalar)  =  $this->var_name($nexttag);
            $addparam  =  substr($addparam, strlen($nexttag));
            $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension(\$$block"."['$skalar'],\$$nextblock"."['$nextskalar']"."$addparam);\n?>\n";
          } else {
            $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension(\$$block"."['$skalar'],$addparam);\n?>\n";
          }
        } else {
          list($block, $skalar) = $this->var_name($tag);
          $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension(\$$block"."['$skalar']);\n?>\n";
        }
        $contents  =  str_replace($var[0][$cnt],  $code,  $contents);
      }
    }
    // START process any PHP code
    ob_start();
    eval($header);
    eval("?>".$contents."<?php ");
    $contents = ob_get_contents();
    ob_end_clean();
    // END process any PHP code
    return $contents;
  }

  /* Assign Supplementary Template
   *
   * Usage Example:
   * $page->addtpl( 'sponsors', 'default/poweredby.htm' );
   *
   * @access public
   * @param string $name Parameter Name
   * @param string $value Parameter Value
   * @desc Assign Supplementary Template
   */
  function addtpl ( $name, $value = '' ) {
    if (is_array($name)) {
      foreach ($name as $k => $v) {
        $this->supptemplate[$k]  =  $v;
      }
    } else {
      $this->supptemplate[$name]  =  $value;
    }
  }

  /* Assign Template Content
   *
   * Usage Example:
   * $page->assign( 'TITLE',     'My Document Title' );
   * $page->assign( 'userlist',  array(
   *                               array( 'ID' => 123,  'NAME' => 'John Doe' ),
   *                               array( 'ID' => 124,  'NAME' => 'Jack Doe' ),
   *                             );
   *
   * @access public
   * @param string $name Parameter Name
   * @param mixed $value Parameter Value
   * @desc Assign Template Content
   */
  function assign ( $name, $value = '' ) {
    if (is_array($name)) {
      foreach ($name as $k => $v) {
        $this->data[$k]  =  $v;
      }
    } else {
      $this->data[$name]  =  $value;
    }
  }

  /* Assign Template Content
   *
   * Usage Example:
   * $page->append( 'userlist',  array( 'ID' => 123,  'NAME' => 'John Doe' ) );
   * $page->append( 'userlist',  array( 'ID' => 124,  'NAME' => 'Jack Doe' ) );
   *
   * @access public
   * @param string $name Parameter Name
   * @param mixed $value Parameter Value
   * @desc Assign Template Content
   */
  function append ( $name, $value ) {
    if (is_array($value)) {
      $this->data[$name][]  =  $value;
    } elseif (!is_array($this->data[$name])) {
      $this->data[$name]  .=  $value;
    }
  }

  /* Parser Wrapper
   * Returns Template Output as a String
   *
   * @access public
   * @param array $_top Content Array
   * @return string  Parsed Template
   * @desc Output Buffer Parser Wrapper
   */
  function result ( $_top = '' ) {
    ob_start();
    $this->output( $_top );
    $result  =  ob_get_contents();
    ob_end_clean();
    return $result;
  }

  /* Execute parsed Template
   * Prints Parsing Results to Standard Output
   *
   * @access public
   * @param array $_top Content Array
   * @desc Execute parsed Template
   */
  function output ( $_top = '' ) {
    global $_top;

    $data   = $this->data;
    /* Process supplementary templates */
    if ( is_array($this->supptemplate) && !empty($this->supptemplate) ) { // passed by addtpl functionality
      foreach ($this->supptemplate as $key => $value) {
        $supp_templates[$key] = file_get_contents($value);
      }
    }

    /* Make sure that folder names have a trailing '/' */
    if (strlen($this->template_dir)  &&  substr($this->template_dir, -1) != '/') {
      $this->template_dir  .=  '/';
    }
    if (strlen($this->temp_dir)  &&  substr($this->temp_dir, -1) != '/') {
      $this->temp_dir  .=  '/';
    }

    /* Prepare Template Content*/
    if (!is_array($_top)) {
      if (strlen($_top)) {
        $this->tpl_file  =  $_top;
      }
      $_top  =  $this->data;
    }
    $_obj  =  &$_top;
    $_stack_cnt  =  0;
    $_stack[$_stack_cnt++]  =  $_obj;

    /* Check if template is already compiled */
    $queryString = $_SERVER['QUERY_STRING'];
    $cpl_file_name = preg_replace('/[:\/.\\\\]/', '_', $this->tpl_file . '?' . $queryString);
    if (strlen($cpl_file_name) > 0) {
      $cpl_file_name = 'qs_' . md5($cpl_file_name);
      $this->cpl_file  =  $this->temp_dir . $cpl_file_name . '.php';
      $compile_template  =  true;
      if ($this->reuse_code) {
        if (is_file($this->cpl_file)) {
          if ($this->mtime($this->cpl_file) > $this->mtime($this->template_dir . $this->tpl_file)) {
            $compile_template  =  false;
          }
        }
      }
      if ($compile_template) {
        $this->parser = new QuickSkinParser();
        $this->parser->template_dir     = $this->template_dir;
        $this->parser->skins_sub_dir    = $this->skins_sub_dir;
        $this->parser->tpl_file         = $this->tpl_file;
        $this->parser->left_delimiter   = $this->left_delimiter;
        $this->parser->right_delimiter  = $this->right_delimiter;
        $this->parser->extensions_dir   = $this->extensions_dir;
        $this->parser->extension_prefix = $this->extension_prefix;
        $this->parser->supp_templates   = $this->supp_templates;

        if (!$this->parser->compile($this->cpl_file,$data,$this->supp_templates,$this->extensions_dir)) {
          exit('QuickSkin Parser Error: ' . $this->parser->error);
        }
      }
      /* Execute Compiled Template */
      include($this->cpl_file);
    } else {
      exit('QuickSkin Error: You must set a template file name');
    }
    /* Delete Global Content Array in order to allow multiple use of QuickSkin class in one script */
    unset ($GLOBALS['_top']);
  }

  /* Debug Template
   *
   * @access public
   * @param array $_top Content Array
   * @desc Debug Template
   */
  function debug ( $_top = '' ) {
    /* Prepare Template Content */
    if (!$_top) {
      $_top  =  $this->data;
    }
    if (@include_once('class.quickskindebugger.php')) {
      $this->debugger = new QuickSkinDebugger($this->template_dir . $this->tpl_file, $this->right_delimiter, $this->left_delimiter);
      $this->debugger->start($_top);
    } else {
      exit( 'QuickSkin Error: Cannot find class.quickskindebugger.php; check QuickSkin installation');
    }
  }

  /* Start Ouput Content Buffering
   *
   * Usage Example:
   * $page = new QuickSkin('template.html');
   * $page->use_cache();
   * ...
   *
   * @access public
   * @desc Output Cache
   */
  function use_cache ( $key = '' ) {
    if (empty($_POST)) {
      $this->cache_filename  =  $this->cache_dir . 'cache_' . md5($_SERVER['REQUEST_URI'] . serialize($key)) . '.ser';
      if (($_SERVER['HTTP_CACHE_CONTROL'] != 'no-cache')  &&  ($_SERVER['HTTP_PRAGMA'] != 'no-cache')  &&  @is_file($this->cache_filename)) {
        if ((time() - filemtime($this->cache_filename)) < $this->cache_lifetime) {
          readfile($this->cache_filename);
          exit;
        }
      }
      ob_start( array( &$this, 'cache_callback' ) );
    }
  }

  /* Output Buffer Callback Function
   *
   * @access private
   * @param string $output
   * @return string $output
   */
  function cache_callback ( $output ) {
    if ($hd = @fopen($this->cache_filename, 'w')) {
      fwrite($hd,  $output);
      fclose($hd);
    } else {
      $output = 'QuickSkin Error: failed to open cache file "' . $this->cache_filename . '"';
    }
    return $output;
  }

  /* Determine Last Filechange Date (if File exists)
   *
   * @access private
   * @param string $filename
   * @return mixed
   * @desc Determine Last Filechange Date
   */
  function mtime ( $filename ) {
    if (@is_file($filename)) {
      $ret = filemtime($filename);
      return $ret;
    }
  }

  /* Set (or reset) Properties (variables)
   *
   * Usage Example:
   * $page->set('reuse_code', true);
   *
   * @access public
   * @param string $name Parameter Name
   * @param mixed $value Parameter Value
   * NOTE: will not work with arrays, there are no arrays to set/reset */
  function set ( $name, $value = '' ) {
    if ( isset($this->$name) ) {
      $this->$name = $value;
    } else {
      exit( 'QuickSkin Error: Attempt to set a non-existant class property: ' . $name);
    }
  }

}

/*~
.---------------------------------------------------------------------------.
|  Software: QuickSkinParser Class * Used by QuickSkin Class                |
|   Version: 5.0                                                            |
|   Contact: andy.prevost@worxteam.com,andy@codeworx.ca                     |
|      Info: http://quickskin.sourceforge.net                               |
|   Support: http://sourceforge.net/projects/quickskin/                     |
| ------------------------------------------------------------------------- |
|    Author: Andy Prevost andy.prevost@worxteam.com (admin)                 |
|    Author: Manuel 'EndelWar' Dalla Lana endelwar@aregar.it (former admin) |
|    Author: Philipp v. Criegern philipp@criegern.com (original founder)    |
| Copyright (c) 2002-2009, Andy Prevost. All Rights Reserved.               |
|    * NOTE: QuickSkin is the SmartTemplate project renamed. SmartTemplate  |
|            information and downloads can still be accessed at the         |
|            smarttemplate.sourceforge.net site                             |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| We offer a number of paid services:                                       |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'
Last modified: January 01 2009 ~*/

class QuickSkinParser {

  /////////////////////////////////////////////////
  // PROPERTIES, PUBLIC
  /////////////////////////////////////////////////

  public $error;               /* Error messages */
  public $template;            /* The template itself */
  public $tpl_file;            /* The template filename */
  public $template_dir;        /* The template filename used to extract the dirname for subtemplates */
  public $skins_sub_dir;       /* The template subdirectory, passed by main class */
  public $extensions_dir;      /* The extension directory */
  public $extension_tagged = array(); /* List of used QuickSkin Extensions */
  public $left_delimiter;      /* Default Left delimiter */
  public $right_delimiter;     /* Default Right delimiter */
  public $supp_templates = ''; /* Contains array or single supplementary template(s) */
  public $extension_prefix;    /* filename prefix on all the extensions files */

  /* QuickSkinParser Constructor */
  /*
  function __construct() {
  }
  */

  /* Replace template logical expression in IF..ENDIF (|| or &&) with php logical expression
   * @access private
   * @desc replace template logical expression (|| or &&) with php logical expression
   * @author Bruce Huang (msn: huang_x_c@163.com)
   * @param string $src_page source page intended to be replaced
   */
  function replace_logic_expression( &$src_page ) {
    /* cannot find '||' or '&&' */
    if(!strpos($src_page, '||') && !strpos($src_page, '&&')) {
      return;
    }
    /* match 'ELSE' and the last sub expression */
    if (preg_match_all('/<!-- (ELSE)?IF \s*(\(*).*[|&]{2}\s*\(*\s*([a-zA-Z0-9_.]+)\s*([!=<>]+)\s*(["]?[^"]*?["]?)\s*(\)*)\s* -->/', $src_page, $var)) {
      foreach ($var[3] as $cnt => $tag) {
        list($parent, $block)  =  $this->var_name($tag);
        $cmp   =  $var[4][$cnt];
        $val   =  $var[5][$cnt];
        $else  =  ($var[1][$cnt] == 'ELSE') ? '} else' : '';
        if ($cmp == '=') {
          $cmp  =  '==';
        }
        if (preg_match('/"([^"]*)"/',$val,$matches)) {
          $code_suffix  =  "\$$parent"."['$block'] $cmp \"".$matches[1].$var[6][$cnt]."\"){\n?>";
        } elseif (preg_match('/([^"]*)/',$val,$matches)) {
          list($parent_right, $block_right)  =  $this->var_name($matches[1]);
          $code_suffix  =  "\$$parent"."['$block'] $cmp \$$parent_right"."['$block_right']".$var[6][$cnt]."){\?>";
        }

        /* match other sub expressions */
        if (preg_match_all('/([a-zA-Z0-9_.]+)\s*([!=<>]+)\s*(["]?[^"]*?["]?)\s*(\)*\s*[|&]{2}\s*\(*)\s*/', $var[0][$cnt], $sub_var)) {
          $code_mid = '';
          foreach($sub_var[1] as $sub_cnt => $sub_tag) {
            list($sub_parent, $sub_block) = $this->var_name($sub_tag);
            $cmp = $sub_var[2][$sub_cnt];
            $val = $sub_var[3][$sub_cnt];
            $logic_exp  =  $sub_var[4][$sub_cnt];
            if ($cmp == '=') {
              $cmp  =  '==';
            }
            if (preg_match('/"([^"]*)"/',$val,$matches)) {
              $code_mid  =  $code_mid."\$$sub_parent"."['$sub_block'] $cmp \"".$matches[1]."\"".$logic_exp;
            } elseif (preg_match('/([^"]*)/',$val,$matches)) {
              list($sub_parent_right, $sub_block_right)  =  $this->var_name($matches[1]);
              $code_mid  =  $code_mid."\$$sub_parent"."['$sub_block'] $cmp \$$sub_parent_right"."['$sub_block_right']".$logic_exp;
            }
          }
        }
        $code = "<?php\n".$else.'if ('.$var[2][$cnt].$code_mid.$code_suffix;
        $src_page = str_replace($var[0][$cnt],  $code,  $src_page);
      }
    }
  }

  /* Main Template Parser
   * @param string $compiled_template_filename Compiled Template Filename
   * @desc Creates Compiled PHP Template
   */
  function compile( $compiled_template_filename = '', $data='', $supp_templates='', $extensions_dir='' ) {

    $this->extension_prefix = preg_quote($this->extension_prefix);

    /* Load Template */
    $template_filename = $this->template_dir . $this->tpl_file;
    if ($hd = @fopen($template_filename, 'r')) {
      if (filesize($template_filename)) {
        $this->template = fread($hd, filesize($template_filename));
        $this->left_delimiter = preg_quote($this->left_delimiter);
        $this->right_delimiter = preg_quote($this->right_delimiter);
      } else {
        $this->template = 'QuickSkin Parser Error: File size is zero byte: ' .$template_filename;
      }
      fclose($hd);
    } else {
      $this->template = 'QuickSkin Parser Error: File not found: ' .$template_filename;
    }

    if (empty($this->template)) {
      return;
    }

    /* Do the variable substitution for paths, urls, subtemplates */
    $this->template = $this->worx_var_swap($this->template, $data, $supp_templates);

    $header = '';

    /* Code to allow subtemplates */
    if(preg_match("/<!-- INCLUDE/is", $this->template)) {
      while ($this->count_subtemplates() > 0) {
        preg_match_all('/<!-- INCLUDE ([a-zA-Z0-9\-_.]+) -->/', $this->template, $tvar);
        foreach($tvar[1] as $subfile) {
          if(file_exists($this->template_dir . '/' . $this->skins_sub_dir . '/' .$subfile)) {
            $subst = implode('',file($this->template_dir . '/' . $this->skins_sub_dir . '/' .$subfile));
          } else {
            $subst = 'QuickSkin Parser Error: Subtemplate not found: \''.$subfile.'\'';
          }
          $this->template = str_replace("<!-- INCLUDE $subfile -->", $subst, $this->template);
        }
      }
    }
    /* END, ELSE Blocks */
    $page  =  preg_replace("/<!-- ENDIF.+?-->/", "<?php\n}\n?>", $this->template);
    $page  =  preg_replace("/<!-- END[ a-zA-Z0-9_.]* -->/",  "<?php\n}\n\$_obj=\$_stack[--\$_stack_cnt];}\n?>", $page);
    $page  =  preg_replace("/<!-- ENDLOOP[ a-zA-Z0-9_.]* -->/",  "<?php\n}\n\$_obj=\$_stack[--\$_stack_cnt];}\n?>", $page);
    $page  =  str_replace("<!-- ELSE -->", "<?php\n} else {\n?>", $page);

    /* 'BEGIN - END' Blocks */
    if (preg_match_all('/<!-- LOOP ([a-zA-Z0-9_.]+) -->/', $page, $var)) {
      foreach ($var[1] as $tag) {
        list($parent, $block)  =  $this->var_name($tag);
        $code  =  "<?php\n"
            . "if (!empty(\$$parent"."['$block'])){\n"
            . "if (!is_array(\$$parent"."['$block']))\n"
            . "\$$parent"."['$block']=array(array('$block'=>\$$parent"."['$block']));\n"
            . "\$_stack[\$_stack_cnt++]=\$_obj;\n"
            . "\$rowcounter = 0;\n"
            . "foreach (\$$parent"."['$block'] as \$rowcnt=>\$$block) {\n"
              . "\$$block"."['ROWCNT']=(\$rowcounter);\n"
              . "\$$block"."['ALTROW']=\$rowcounter%2;\n"
              . "\$$block"."['ROWBIT']=\$rowcounter%2;\n"
              . "\$rowcounter++;"
              . "\$_obj=&\$$block;\n?>";
        $page  =  str_replace("<!-- LOOP $tag -->",  $code,  $page);
      }
    }

    /* replace logical operator in [ELSE]IF */
    $this->replace_logic_expression($page);

    /* 'IF nnn=mmm' Blocks */
    if (preg_match_all('/<!-- (ELSE)?IF ([a-zA-Z0-9_.]+)\s*([!=<>]+)\s*(["]?[^"]*["]?) -->/', $page, $var)) {
      foreach ($var[2] as $cnt => $tag) {
        list($parent, $block)  =  $this->var_name($tag);
        $cmp   =  $var[3][$cnt];
        $val   =  $var[4][$cnt];
        $else  =  ($var[1][$cnt] == 'ELSE') ? '} else' : '';
        if ($cmp == '=') {
          $cmp  =  '==';
        }

        if (preg_match('/"([^"]*)"/',$val,$matches)) {
          $code  =  "<?php\n$else"."if (\$$parent"."['$block'] $cmp \"".$matches[1]."\"){\n?>";
        } elseif (preg_match('/([^"]*)/',$val,$matches)) {
          list($parent_right, $block_right)  =  $this->var_name($matches[1]);
          $code  =  "<?php\n$else"."if (\$$parent"."['$block'] $cmp \$$parent_right"."['$block_right']){\n?>";
        }

        $page  =  str_replace($var[0][$cnt],  $code,  $page);
      }
    }

    /* 'IF nnn' Blocks */
    if (preg_match_all('/<!-- (ELSE)?IF ([a-zA-Z0-9_.]+) -->/', $page, $var)) {
      foreach ($var[2] as $cnt => $tag) {
        $else  =  ($var[1][$cnt] == 'ELSE') ? '} else' : '';
        list($parent, $block)  =  $this->var_name($tag);
        $code  =  "<?php\n$else"."if (!empty(\$$parent"."['$block'])){\n?>";
        $page  =  str_replace($var[0][$cnt],  $code,  $page);
      }
    }

    /* 'IF {extension:variable}'=mmm Blocks
     * e.g.
     * <!-- IF {count:list} > 0 -->
     * List populated
     * <!-- ELSE -->
     * List is empty
     * <!-- ENDIF -->
     * thanks to Khary Sharpe (ksharpe [at] kharysharpe [dot] com) for the initial code
     */
    if (preg_match_all('/<!-- (ELSE)?IF {([a-zA-Z0-9_]+):([^}]*)}\s*([!=<>]+)\s*(["]?[^"]*["]?) -->/', $page, $var)) {
      foreach ($var[2] as $cnt => $tag) {
        list($parent, $block)  =  $this->var_name($tag);
        $cmp   =  $var[4][$cnt];
        $val   =  $var[5][$cnt];
        $else  =  ($var[1][$cnt] == 'ELSE') ? '} else' : '';
        if ($cmp == '=') {
          $cmp  =  '==';
        }

        $extension = $var[2][$cnt];
        $extension_var = $var[3][$cnt];
        if (!isset($this->extension_tagged[$extension])) {
          $header .= 'include_once  "'.$this->extensions_dir."/".$this->extension_prefix."$extension.php\";\n";
          $this->extension_tagged[$extension] = true;
        }
        if (!strlen($extension_var)) {
          $code = "<?php\n$else"."if (".$this->extension_prefix."$extension() $cmp $val) {\n?>\n";
        } elseif (substr($extension_var, 0, 1) == '"') {
          $code = "<?php\n$else"."if (".$this->extension_prefix."$extension($extension_var) $cmp $val) {\n?>\n";
        } elseif (strpos($extension_var, ',')) {
          list($tag, $addparam) = explode(',', $extension_var, 2);
          list($block, $skalar) = $this->var_name($extension_var);
          if (preg_match('/^([a-zA-Z_]+)/', $addparam, $match)) {
            $nexttag = $match[1];
            list($nextblock, $nextskalar) = $this->var_name($nexttag);
            $addparam = substr($addparam, strlen($nexttag));
            $code = "<?php\n$else"."if (".$this->extension_prefix."$extension(\$$block"."['$skalar'],\$$nextblock"."['$nextskalar']"."$addparam) $cmp $val) {\n?>\n";
          } else {
            $code = "<?php\n$else"."if (".$this->extension_prefix."$extension(\$$block"."['$skalar'],$addparam) $cmp $val) {\n?>\n";
          }
        } else {
          list($block, $skalar) = $this->var_name($extension_var);
          $code = "<?php\n$else"."if (".$this->extension_prefix."$extension(\$$block"."['$skalar']) $cmp $val) {\n?>\n";
        }
        $page = str_replace($var[0][$cnt], $code, $page);
      }
    }

    /* Replace Scalars */
    if (preg_match_all('/'.$this->left_delimiter.'([a-zA-Z0-9_. >]+)'.$this->right_delimiter.'/', $page, $var)) {
      foreach ($var[1] as $fulltag) {
        /* Determine Command (echo / $obj[n]=) */
        list($cmd, $tag)  =  $this->cmd_name($fulltag);
        list($block, $skalar)  =  $this->var_name($tag);
        $code  =  "<?php\n$cmd \$$block"."['$skalar'];\n?>\n";
        $page  =  str_replace(stripslashes($this->left_delimiter).$fulltag.stripslashes($this->right_delimiter), $code, $page);
      }
    }

    /* Replace Translations */
    if (preg_match_all('/<"([a-zA-Z0-9_.]+)">/', $page, $var)) {
      foreach ($var[1] as $tag) {
        list($block, $skalar)  =  $this->var_name($tag);
        $code  =  "<?php\necho gettext('$skalar');\n?>\n";
        $page  =  str_replace('<"'.$tag.'">',  $code,  $page);
      }
    }

    /* Include Extensions */
    if (preg_match_all('/'.$this->left_delimiter.'([a-zA-Z0-9_]+):([^}]*)'.$this->right_delimiter.'/', $page, $var)) {
      foreach ($var[2] as $cnt => $tag) {
        /* Determine Command (echo / $obj[n]=) */
        list($cmd, $tag)  =  $this->cmd_name($tag);

        $extension  =  $var[1][$cnt];
        if (!isset($this->extension_tagged[$extension])) {
          $header .= 'include_once "'.$this->extensions_dir."/".$this->extension_prefix."$extension.php\";\n";
          $this->extension_tagged[$extension]  =  true;
        }
        if (!strlen($tag)) {
          $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension();\n?>\n";
        } elseif (substr($tag, 0, 1) == '"') {
          $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension($tag);\n?>\n";
        } elseif (strpos($tag, ',')) {
          list($tag, $addparam)  =  explode(',', $tag, 2);
          list($block, $skalar)  =  $this->var_name($tag);
          if (preg_match('/^([a-zA-Z_]+)/', $addparam, $match)) {
            $nexttag   =  $match[1];
            list($nextblock, $nextskalar)  =  $this->var_name($nexttag);
            $addparam  =  substr($addparam, strlen($nexttag));
            $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension(\$$block"."['$skalar'],\$$nextblock"."['$nextskalar']"."$addparam);\n?>\n";
          } else {
            $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension(\$$block"."['$skalar'],$addparam);\n?>\n";
          }
        } else {
          list($block, $skalar) = $this->var_name($tag);
          $code  =  "<?php\n$cmd ".$this->extension_prefix."$extension(\$$block"."['$skalar']);\n?>\n";
        }
        $page  =  str_replace($var[0][$cnt],  $code,  $page);
      }
    }

    /* Add Include Header */
    if (isset($header) && !empty($header)) {
      $page  =  "<?php\n$header\n?>$page";
    }

	/* use_common_placeholders */
	if(function_exists('use_common_placeholders')){
		$page = use_common_placeholders($page);
	}
	
    /* do substitutions on included supplementary templates */
    $page = $this->worx_tpl_swap($page, $data, $supp_templates);

    /* Store Code to Temp Dir */
    if (strlen($compiled_template_filename)) {
      if ($hd  =  fopen($compiled_template_filename,  'w')) {
        fwrite($hd,  $page);
        fclose($hd);
        return true;
      } else {
        $this->error  =  'Could not write compiled file.';
        return false;
      }
    } else {
      return $page;
    }
  }

  /* Splits Template-Style Variable Names into an Array-Name/Key-Name Components
   * {example}               :  array( "_obj",                   "example" )  ->  $_obj['example']
   * {example.value}         :  array( "_obj['example']",        "value" )    ->  $_obj['example']['value']
   * {example.0.value}       :  array( "_obj['example'][0]",     "value" )    ->  $_obj['example'][0]['value']
   * {top.example}           :  array( "_stack[0]",              "example" )  ->  $_stack[0]['example']
   * {parent.example}        :  array( "_stack[$_stack_cnt-1]",  "example" )  ->  $_stack[$_stack_cnt-1]['example']
   * {parent.parent.example} :  array( "_stack[$_stack_cnt-2]",  "example" )  ->  $_stack[$_stack_cnt-2]['example']
   * @param string $tag Variale Name used in Template
   * @return array  Array Name, Key Name
   * @access private
   * @desc Splits Template-Style Variable Names into an Array-Name/Key-Name Components
   */
  function var_name($tag) {
    $parent_level  =  0;
    while (substr($tag, 0, 7) == 'parent.') {
      $tag  =  substr($tag, 7);
      $parent_level++;
    }
    if (substr($tag, 0, 4) == 'top.') {
      $obj  =  '_stack[0]';
      $tag  =  substr($tag,4);
    } elseif ($parent_level) {
      $obj  =  '_stack[$_stack_cnt-'.$parent_level.']';
    } else {
      $obj  =  '_obj';
    }
    while (is_int(strpos($tag, '.'))) {
      list($parent, $tag)  =  explode('.', $tag, 2);
      if (is_numeric($parent)) {
        $obj  .=  "[" . $parent . "]";
      } else {
        $obj  .=  "['" . $parent . "']";
      }
    }
    $ret = array($obj, $tag);
    return $ret;
  }

  /* Determine Template Command from Variable Name
   * {variable}             :  array( "echo",              "variable" )  ->  echo $_obj['variable']
   * {variable > new_name}  :  array( "_obj['new_name']=", "variable" )  ->  $_obj['new_name']= $_obj['variable']
   * @param string $tag Variale Name used in Template
   * @return array  Array Command, Variable
   * @access private
   * @desc Determine Template Command from Variable Name
   */
  function cmd_name($tag) {
    if (preg_match('/^(.+) > ([a-zA-Z0-9_.]+)$/', $tag, $tagvar)) {
      $tag  =  $tagvar[1];
      list($newblock, $newskalar)  =  $this->var_name($tagvar[2]);
      $cmd  =  "\$$newblock"."['$newskalar']=";
    } else {
      $cmd  =  'echo';
    }
    $ret = array($cmd, $tag);
    return $ret;
  }

  /* @return int Number of subtemplate included
   * @access private
   * @desc Count number of subtemplates included in current template
   */
  function count_subtemplates() {
    $ret = preg_match_all('/<!-- INCLUDE ([a-zA-Z0-9_.]+) -->/', $this->template, $tvar);
    unset($tvar);
    return $ret;
  }

  function worx_var_swap($tpldata, $data, $supp_templates) { /* do the substitution of the variables here */

    /* replace all the template elements (sub templates) */
    if ( is_array($supp_templates) && !empty($supp_templates) ) {
      foreach ($supp_templates as $key => $val) {
        $tpldata = str_replace("\{$key}", $val, $tpldata);
      }
    }
    /* do the substitution of the directory names here */

    return $tpldata;

  }

  function worx_tpl_swap($tpldata, $data, $supp_templates) { // do the substitution of the sub templates here 

	// do the substitution of the directory names here
	/*/
    // do image link substitution 
    if ( $data['tpl_img'] != '' && $data['url_img'] != '' ) {
      $tpldata = str_replace($data['tpl_img'],$data['url_img'],$tpldata);
      unset($data['tpl_img']);
      unset($data['url_img']);
    } elseif (defined(_URL_USRIMG)) {
      $tpldata = str_replace('tplimgs/',_URL_USRIMG,$tpldata);
    }
    // do javascript link substitution
    if ( $data['tpl_js'] != '' && $data['url_js'] != '' ) {
      $tpldata = str_replace($data['tpl_js'],$data['url_js'],$tpldata);
      unset($data['img_tpl']);
      unset($data['url_js']);
    } elseif (defined(_URL_USRJS)) {
      $tpldata = str_replace('tpljs/',_URL_USRJS,$tpldata);
    }
    // do css link substitution
    if ( $data['tpl_css'] != '' && $data['url_css'] != '' ) {
      $tpldata = str_replace($data['tpl_css'],$data['url_css'],$tpldata);
      unset($data['tpl_css']);
      unset($data['url_css']);
    } elseif (defined(_URL_USRCSS)) {
      $tpldata = str_replace('url_css/',_URL_USRCSS,$tpldata);
    }
	/*/
    return $tpldata;
	
  }

}

?>
