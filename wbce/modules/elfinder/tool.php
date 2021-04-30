<?php

//no direct file access
if (count(get_included_files())==1) {
    header("Location: ../index.php", true, 301);
}

// Whith the no_page marker , we know that we want to show the Connector
if ($noPage) {

    // We serve the connector file via include
    include("ef/php/connector.wbce.php");
} else {

    // we serve the main application
    include($modulePath."templates/elfinder.tpl.php");
    //echo "<pre>"; print_r($_SESSION);echo "</pre>";
    //echo (Settings::Info());
    //echo "RENAME_FILES_ON_UPLOAD:".RENAME_FILES_ON_UPLOAD;
    $sForbidden = str_replace(",", "|", RENAME_FILES_ON_UPLOAD);
    //echo $sForbidden;
    //echo "<pre>"; print_r ($_SESSION); echo "</pre>";
}
