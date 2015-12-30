<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          Christian Sommer, WB-Project, Werner v.d. Decken
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: upgrade.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/upgrade.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */
//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);


$msg = '';
$sTable = TABLE_PREFIX.'mod_output_filter';
if(($sOldType = $database->getTableEngine($sTable))) {
    if(('myisam' != strtolower($sOldType))) {
        if(!$database->query('ALTER TABLE `'.$sTable.'` Engine = \'MyISAM\' ')) {
            $msg = $database->get_error();
        }
    }
} else {
    $msg = $database->get_error();
}
// ------------------------------------global $i;
$table_name = TABLE_PREFIX .'mod_output_filter';
$field_name = 'sys_rel';
$i = (!isset($i) ? 1 : $i);
print "<div style=\"margin:1em auto;font-size:1.1em;\">";
print "<h4>Step $i: Updating Output Filter</h4>\n";
$i++;
$OK   = "<span class=\"ok\">OK</span>";
$FAIL = "<span class=\"error\">FAILED</span>";
if ( ($database->field_exists($table_name,$field_name) )) {
        print "<br /><strong>'Output Filter already updated'</strong> $OK<br />\n";
} else {
    $description = 'INT NOT NULL DEFAULT \'0\' FIRST';
    if( ($database->field_add($table_name,$field_name,$description )) ) {
        print "<br /><strong>Updating Output Filter</strong> $OK<br />\n";
    } else {
            print "<br /><strong>Updating Output Filter</strong> $FAIL<br />\n";
    }
}
print "</div>";
