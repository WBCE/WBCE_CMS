<?php
/**
 * @category        modules
 * @package         preferences
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2
 */

// no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Register Class to autoloader
WbAuto::AddFile("Preferences","/modules/preferences/classes/preferences.php");
