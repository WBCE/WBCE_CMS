<?php
//dummy include.php must stay here to detect the modul as snippet!!

//no direct file access
if(count(get_included_files()) ==1){$z="HTTP/1.0 404 Not Found";header($z);die($z);}
?>
