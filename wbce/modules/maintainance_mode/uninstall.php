<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

Settings::delete ("wb_maintainance_mode");
$msg = 'Mainatinance mode setting deleted, now you need to use config.php again it you want maintainance mode to be active.';

