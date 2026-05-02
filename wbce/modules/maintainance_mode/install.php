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

Settings::set ("wb_maintainance_mode", false);
$msg = 'Mainatinance mode setting activated. If you have the WB_MAINTAINANCE_MODE constant in your config.php, please remove it';

