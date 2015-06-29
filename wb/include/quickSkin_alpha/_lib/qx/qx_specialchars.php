<?php
  /**
  * QuickSkin Extension specialchars
  * Converts  HTML Entities 2 Special Characters
  *
  * Usage Example:
  * Content:  $template->assign('NEXT', 'Next Page &gt;&gt;');
  * Template: <a href="next.php">{specialchars:NEXT}</a>
  * Result:   <a href="next.php">Next Page >></a>
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_specialchars ( $param ) {
    return htmlspecialchars( $param );
  }
