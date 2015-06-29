<?php
  /**
  * QuickSkin Extension load_config
  * Reads an INI-Style configuration file into an array
  *
  * Usage Example:
  * Configuration File (parameter.ini):
  *
  *     PAGETITLE   =  Default Page Title
  *     [colors]
  *     BACKGROUND  =  #FFFFFF
  *     TEXT        =  #000000
  *
  * Template:
  *
  *     {load_config:"parameter.ini","config"}
  *     <title>{config.PAGETITLE}</title>
  *     <body bgcolor="{config.colors.BACKGROUND}" text="{config.colors.TEXT}">
  *
  * Result:
  *
  *     <title>Default Page Title</title>
  *     <body bgcolor="#FFFFFF" text="#000000">
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_load_config ( $filename,  $name = 'config' ) {
    global $_top;

    $section  =  null;
    if (is_file($filename)) {
      $cfgfile  =  file($filename);
      if (is_array($cfgfile)) {
        foreach ($cfgfile as $line) {
          if (substr($line, 0, 1) != '#') {
            if (substr($line, 0, 1) == '[') {
              if ($rbr = strpos($line, ']')) {
                $section  =  substr($line, 1, $rbr -1);
              }
            }
            if ($tr = strpos($line, '=')) {
              $k  =  trim(substr($line, 0, $tr));
              $v  =  trim(substr($line, $tr+1));
              if (isset($section)) {
                $_top[$name][$section][$k]  =  $v;
              } else {
                $_top[$name][$k]  =  $v;
              }
            }
          }
        }
      }
    }
  }
