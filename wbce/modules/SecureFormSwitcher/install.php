<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license			WTFPL
 */

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

Settings::Set ("wb_maintainance_mode", false);
$msg = 'Mainatinance mode setting activated, please delete setting in your config.php';

