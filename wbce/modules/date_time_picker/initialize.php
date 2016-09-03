<?php
/**
@file
@brief This file registers the date and time picker to the WBCE autoloader.
Initialize is loaded in frontend and backend. 

@category        modules
@author          Norbert Heimsath
@copyright       GPL v2
*/

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Register Class to autoloader
WbAuto::AddFile("DateTimePicker","/modules/date_time_picker/classes/datetimepicker.php");

