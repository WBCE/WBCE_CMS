<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license			WTFPL
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

Settings::Del ("wb_maintainance_mode");
$msg = 'Mainatinance mode setting deleted, now you need to use config.php again ';

