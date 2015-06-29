<?php
  /**
  * QuickSkin Extension header
  * Sends HTTP Header
  *
  * Usage Example:
  * Content:  $template->assign( 'TITLE', 'SVG Template Demo:' );
  *
  * Template:
  *     {header:"Content-type: image/svg+xml"}<?xml version="1.0" ?>
  *     <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
  *     <svg width="300px" height="150px" style="shape-rendering:optimizeQuality;text-rendering:optimizeQuality">
  *       <circle id="ball" cx="150" cy="75" r="50" style="fill:rgb(200,200,255)" />
  *       <text x="70" y="80" id="title" style="font-face:Courier;font-size:12pt">{TITLE}</text>
  *     </svg>
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_header ( $param ) {
    header($param);
  }
