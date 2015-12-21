<?php

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

$referer = $_SERVER['HTTP_REFERER'];
