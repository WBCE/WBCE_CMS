<?php
  /**
  * QuickSkin Extension options
  * Creates HTML DropDown Option list from an array
  *
  * Usage Example I:
  * Content:  $template->assign('pick', array( "on", "off" ) );
  * Template: Choose: <select name="onoff"> {options:pick} </select>
  * Result:   Choose: <select name="onoff"> <option>on</option><option>off</option> </select>
  *
  * Usage Example II:
  * Content:  $template->assign('color',   array( "FF0000" => "Red", "00FF00" => "Green", "0000FF" => "Blue" ) );
  *           $template->assign('default', "00FF00" );
  * Template: Color: <select name="col"> {options:color,default} </select>
  * Result:   Color: <select name="col"> <option value="FF0000">Red</option><option value="00FF00" selected>Green</option><option value="0000FF">Blue</option> </select>
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_options ( $param,  $default = '_DEFAULT_' ) {
    $output  =  '';
    if (is_array($param)) {
      $keyindex  =  0;
      foreach ($param as $key => $value) {
        if ($key==$keyindex++ && is_numeric($key)) {
          $output  .=  '<option' . (($value == $default) ? '  selected="selected"' : '') . '>' . $value . '</option>';
        } else {
          $output  .=  '<option value="' . $key . '"' . (($key == $default) ? '  selected="selected"' : '') . '>' . $value . '</option>';
        }
      }
    }
    return $output;
  }
