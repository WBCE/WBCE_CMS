<?php
  /**
  * QuickSkin Extension htmlentities
  * Converts Special Characters to HTML Entities
  *
  * Usage Example:
  * Content:  $template->assign('NEXT', 'Next Page >>');
  * Template: <a href="next.php">{htmlentities:NEXT}</a>
  * Result:   <a href="next.php">Next Page &gt;&gt;</a>
  *
  * @author Andy Prevost andy@codeworxtech.com - original by Philipp v. Criegern philipp@criegern.de
  */
  function qx_htmlentities ( $param ) {
    return htmlentities( $param );
  }
