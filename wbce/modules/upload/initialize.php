<?php
//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// Register Class to autoloader
WbAuto::AddFile("upload","/modules/phplib/classes/src/class.upload.php");

