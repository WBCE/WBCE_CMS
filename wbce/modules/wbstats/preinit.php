<?php

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

if (isset ($_SERVER['HTTP_REFERER'])) $referer = $_SERVER['HTTP_REFERER'];
