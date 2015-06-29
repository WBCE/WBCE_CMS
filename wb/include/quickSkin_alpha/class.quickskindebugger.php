<?php
/*~ class.quickskindebugger.php
.---------------------------------------------------------------------------.
|  Software: QuickSkinDebugger Class * Used by QuickSkin Class              |
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

class QuickSkinDebugger {

  /* The template Filename
   * @access private
   */
  private $filename;

  /* The template itself
   * @access private
   */
  private $template;

  /* Default Left delimiter
   * Can be overwritten by global configuration array $_CONFIG['left_delimiter']
   * @access public
   */
  private $left_delimiter =  '{';

  /* Default Right delimiter
   * Can be overwritten by global configuration array $_CONFIG['right_delimiter']
   * @access public
   */
  private $right_delimiter =  '}';

  /* QuickSkinDebugger Constructor
   * @param string $template_filename HTML Template Filename
   */
  function __construct( $template_filename, $right_delimiter = '}', $left_delimiter = '{' ) {
    $this->filename  =  $template_filename;

    /* Load Template */
    if ($hd  =  @fopen($template_filename,  'r')) {
      $this->template  =  fread($hd,  filesize($template_filename));
      fclose($hd);
    } else {
      $this->template  =  'QuickSkin Debugger Error: File not found: ' . $template_filename;
    }
    $this->tab[0]  =  '';
    for ($i=1;  $i < 10;  $i++) {
      $this->tab[$i]  =  str_repeat('    ', $i);
    }
    $this->right_delimiter = $right_delimiter;
    $this->left_delimiter = $left_delimiter;
  }

  /* Main Template Parser
   * @param string $compiled_template_filename Compiled Template Filename
   * @desc Creates Compiled PHP Template
   */
  function start ( $vars ) {
    $page  =  $this->template;

    $page  =  preg_replace("/(<!-- BEGIN [ a-zA-Z0-9_.]* -->)/",  "\n$1\n",  $page);
    $page  =  preg_replace("/(<!-- IF .+? -->)/",  "\n$1\n",  $page);
    $page  =  preg_replace("/(<!-- END.*? -->)/",  "\n$1\n",  $page);
    $page  =  preg_replace("/(<!-- ELSEIF .+? -->)/",  "\n$1\n",  $page);
    $page  =  preg_replace("/(<!-- ELSE [ a-zA-Z0-9_.]*-->)/",  "\n$1\n",  $page);

    $page  =  $this->highlight_html($page);

    $rows      =  explode("\n",  $page);
    $page_arr  =  array();
    $level     =  0;
    $blocklvl  =  0;
    $rowcnt    =  0;
    $spancnt   =  0;
    $offset    =  22;
    $lvl_block =  array();
    $lvl_row   =  array();
    $lvl_typ   =  array();
    foreach ($rows as $row) {
      if ($row  =  trim($row)) {
        $closespan  =  false;
        if (substr($row, $offset, 12) == '&lt;!-- END ') {
          if ($level < 1) {
            $level++;
            $error[$rowcnt]  =  'END Without BEGIN';
          } elseif ($lvl_typ[$level] != 'BEGIN') {
            $error[$lvl_row[$level]]  =  'IF without ENDIF';
            $error[$rowcnt]  =  'END Without BEGIN';
          }
          $blocklvl--;
          $level--;
          $closespan  =  true;
        }
        if (substr($row, $offset, 14) == '&lt;!-- ENDIF ') {
          if ($level < 1) {
            $level++;
            $error[$rowcnt]  =  'ENDIF Without IF';
          } elseif ($lvl_typ[$level] != 'IF') {
            $error[$lvl_row[$level]]  =  'BEGIN without END';
            $error[$rowcnt]  =  'ENDIF Without IF';
          }
          $closespan  =  true;
          $level--;
        }
        if ($closespan) {
          $page_arr[$rowcnt-1]  .=  '</span>';
        }
        $this_row  =  $this->tab[$level] . $row;
        if (substr($row, $offset, 12) == '&lt;!-- ELSE') {
          if ($level < 1) {
            $error[$rowcnt]  =  'ELSE Without IF';
          } elseif ($lvl_typ[$level] != 'IF') {
            $error[$rowcnt]  =  'ELSE Without IF';
          } else {
            $this_row  =  $this->tab[$level-1] . $row;
          }
        }
        if (substr($row, $offset, 14) == '&lt;!-- BEGIN ') {
          if ($blocklvl == 0) {
            if ($lp = strpos($row, '--&gt;')) {
              if ($blockname  =  trim(substr($row, $offset + 14, $lp -$offset -14))) {
                if ($nr = count($vars[$blockname])) {
                  $this_row  .=  $this->toggleview($nr . ' Entries');
                } else {
                  $this_row  .=  $this->toggleview('Emtpy');
                }
              }
            }
          } else {
            $this_row  .=  $this->toggleview('[');
          }
          $blocklvl++;
          $level++;
          $lvl_row[$level]  =  $rowcnt;
          $lvl_typ[$level]  =  'BEGIN';
        } elseif (substr($row, $offset, 11) == '&lt;!-- IF ') {
          $level++;
          $lvl_row[$level]  =  $rowcnt;
          $lvl_typ[$level]  =  'IF';
          $this_row  .=  $this->toggleview();
        }
        $page_arr[]  =  $this_row;
        $lvl_block[$rowcnt]  =  $blocklvl;
        $rowcnt++;
      }
    }
    if ($level > 0) {
      $error[$lvl_row[$level]]  =  'Block not closed';
    }

    $page  =  join("\n", $page_arr);
    $rows  =  explode("\n",  $page);
    $cnt   =  count($rows);

    for ($i = 0;  $i < $cnt;  $i++) {
      /* Add Errortext */
      if (isset($error)) {
        if ($err = $error[$i]) {
          $rows[$i]  =  '<b>' . $rows[$i] . '        ERROR: ' . $err . '!</b>';
        }
      }

      /* Replace Scalars */
      $right_delimiter = preg_quote($this->right_delimiter);
      $left_delimiter = preg_quote($this->left_delimiter);
      if (preg_match_all("/$left_delimiter([a-zA-Z0-9_. &;]+)$right_delimiter/", $rows[$i], $var)) {
        foreach ($var[1] as $tag) {
          $fulltag  =  $tag;
          if ($delim = strpos($tag, ' &gt; ')) {
            $tag  =  substr($tag, 0, $delim);
          }
          if (substr($tag, 0, 4) == 'top.') {
            $title  =  $this->tip($vars[substr($tag, 4)]);
          } elseif ($lvl_block[$i] == 0) {
            $title  =  $this->tip($vars[$tag]);
          } else {
            $title  =  '[BLOCK?]';
          }
          $code  =  '<b title="' . $title . '">' . $left_delimiter . $fulltag . $right_delimiter . '</b>';
          $rows[$i]  =  str_replace('{'.$fulltag.'}',  $code,  $rows[$i]);
        }
      }

      /* Replace Extensions */
      if (preg_match_all("/$left_delimiter([a-zA-Z0-9_]+):([^}]*)$right_delimiter/", $rows[$i], $var)) {
        foreach ($var[2] as $tmpcnt => $tag) {
          $fulltag  =  $tag;
          if ($delim = strpos($tag, ' &gt; ')) {
            $tag  =  substr($tag, 0, $delim);
          }
          if (strpos($tag, ',')) {
            list($tag, $addparam)  =  explode(',', $tag, 2);
          }
          $extension  =  $var[1][$tmpcnt];

          if (substr($tag, 0, 4) == 'top.') {
            $title  =  $this->tip($vars[substr($tag, 4)]);
          } elseif ($lvl_block[$i] == 0) {
            $title  =  $this->tip($vars[$tag]);
          } else {
            $title  =  '[BLOCK?]';
          }
          $code  =  '<b title="' . $title . '">' . $this->left_delimiter . $extension . ':' . $fulltag . $this->right_delimiter . '</b>';
          $rows[$i]  =  str_replace($this->left_delimiter . $extension . ':' . $fulltag . $this->right_delimiter,  $code,  $rows[$i]);
        }
      }

      /* 'IF nnn' Blocks */
      if (preg_match_all('/&lt;!-- IF ([a-zA-Z0-9_.]+) --&gt;/', $rows[$i], $var)) {
        foreach ($var[1] as $tag) {
          if (substr($tag, 0, 4) == 'top.') {
            $title  =  $this->tip($vars[substr($tag, 4)]);
          } elseif ($lvl_block[$i] == 0) {
            $title  =  $this->tip($vars[$tag]);
          } else {
            $title  =  '[BLOCK?]';
          }
          $code  =  '<span title="' . $title . '">&lt;!-- IF ' . $tag . ' --&gt;</span>';
          $rows[$i]  =  str_replace("&lt;!-- IF $tag --&gt;",  $code,  $rows[$i]);
          if ($title == '[NULL]') {
            $rows[$i]  =  str_replace('Hide',  'Show',  $rows[$i]);
            $rows[$i]  =  str_replace('block',  'none',  $rows[$i]);
          }
        }
      }
    }
    $page  =  join('<br>', $rows);

    /* Print Header */
    echo '<html><head><script type="text/javascript">
        function toggleVisibility(el, src) {
        var v = el.style.display == "block";
        var str = src.innerHTML;
        el.style.display = v ? "none" : "block";
        src.innerHTML = v ? str.replace(/Hide/, "Show") : str.replace(/Show/, "Hide");}
        </script></head><body>';

    /* Print Index */
    echo '<font face="Arial" Size="3"><b>';
    echo 'QuickSkin Debugger<br>';
    echo '<font size="2"><li>PHP-Script: ' . $_SERVER['SCRIPT_FILENAME'] . '</li><li>Template: ' . $this->filename . '</li></font><hr>';
    echo '<li><a href="#template_code">Template</a></li>';
    echo '<li><a href="#compiled_code">Compiled Template</a></li>';
    echo '<li><a href="#data_code">Data</a></li>';
    echo '</b></font><hr>';

    /* Print Template */
    echo '<a name="template_code"><br><font face="Arial" Size="3"><b>Template:</b>&nbsp;[<a href="javascript:void(\'\');" onclick="toggleVisibility(document.getElementById(\'Template\'), this); return false">Hide Ouptut</a>]</font><br>';
    echo '<table border="0" cellpadding="4" cellspacing="1" width="100%" bgcolor="#C6D3EF"><tr><td bgcolor="#F0F0F0"><pre id="Template" style="display:block">';
    echo $page;
    echo '</pre></td></tr></table>';

    /* Print Compiled Template */
    if (@include_once ("class.quickskinparser.php")) {
      $parser = new QuickSkinParser($this->filename);
      $compiled  =  $parser->compile();
      echo '<a name="compiled_code"><br><br><font face="Arial" Size="3"><b>Compiled Template:</b>&nbsp;[<a href="javascript:void(\'\');" onclick="toggleVisibility(document.getElementById(\'Compiled\'), this); return false">Hide Ouptut</a>]</font><br>';
      echo '<table border="0" cellpadding="4" cellspacing="1" width="100%" bgcolor="#C6D3EF"><tr><td bgcolor="#F0F0F0"><pre id="Compiled" style="display:block">';
      highlight_string($compiled);
      echo '</pre></td></tr></table>';
    } else {
      exit( "QuickSkin Error: Cannot find class.quickskinparser.php; check QuickSkin installation");
    }

    /* Print Data */
    echo '<a name="data_code"><br><br><font face="Arial" Size="3"><b>Data:</b>&nbsp;[<a href="javascript:void(\'\');" onclick="toggleVisibility(document.getElementById(\'Data\'), this); return false">Hide Ouptut</a>]</font><br>';
    echo '<table border="0" cellpadding="4" cellspacing="1" width="100%" bgcolor="#C6D3EF"><tr><td bgcolor="#F0F0F0"><pre id="Data" style="display:block">';
    echo $this->vardump($vars);
    echo '</pre></td></tr></table></body></html>';
  }

  /* Insert Hide/Show Layer Switch
   * @param string $suffix Additional Text
   * @desc Insert Hide/Show Layer Switch
   */
  function toggleview ( $suffix = '') {
    global $spancnt;

    $spancnt++;
    if ($suffix) {
      $suffix  .=  ':';
    }
    $ret =  '[' . $suffix . '<a href="javascript:void(\'\');" onclick="toggleVisibility(document.getElementById(\'Block' . $spancnt . '\'), this); return false">Hide Block</a>]<span id="Block' . $spancnt . '" style="display:block">';
    return $ret;
  }

  /* Create Title Text
   * @param string $value Content
   * @desc Create Title Text
   */
  function tip ( $value ) {
    if (empty($value)) {
      return '[NULL]';
    } else {
      $ret = htmlentities(substr($value,0,200));
      return $ret;
    }
  }

  /* Recursive Variable Display Output
   * @param mixed $var Content
   * @param int $depth Incremented Indent Counter for Recursive Calls
   * @return string Variable Content
   * @access private
   * @desc Recursive Variable Display Output
   */
    function vardump($var, $depth = 0) {
      if (is_array($var)) {
        $result  =  "Array (" . count($var) . ")<BR>";
        foreach(array_keys($var) as $key) {
          $result  .=  $this->tab[$depth] . "<B>$key</B>: " . $this->vardump($var[$key],  $depth+1);
        }
        return $result;
      } else {
        $ret =  htmlentities($var) . '<BR>';
        return $ret;
      }
    }

  /* Splits Template-Style Variable Names into an Array-Name/Key-Name Components
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
      $ret = array('_stack[0]', substr($tag,4));
      return $ret;
    } elseif ($parent_level) {
      $ret = array('_stack[$_stack_cnt-'.$parent_level.']', $tag);
      return $ret;
    } else {
      $ret = array('_obj', $tag);
      return $ret;
    }
  }

  /* Highlight HTML Source
   * @param string $code HTML Source
   * @return string Hightlighte HTML Source
   * @access private
   * @desc Highlight HTML Source
   */
  function highlight_html ( $code ) {
    $code  =  htmlentities($code);
    $code  =  preg_replace('/([a-zA-Z_]+)=/',  '<font color="#FF0000">$1=</font>',  $code);
    $code  =  preg_replace('/(&lt;[\/a-zA-Z0-9&;]+)/',  '<font color="#0000FF">$1</font>',  $code);
    $code  =  str_replace('&lt;!--',  '<font color="#008080">&lt;!--',  $code);
    $code  =  str_replace('--&gt;',  '--&gt;</font>',  $code);
    $code  =  preg_replace('/[\r\n]+/',  "\n",  $code);
    return $code;
  }
}
?>
