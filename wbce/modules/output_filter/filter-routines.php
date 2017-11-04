<?php
//Classic compatibility fix

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

include_once(filter_routines.php);


