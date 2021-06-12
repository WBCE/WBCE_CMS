<?php

// no direct file access
if (count(get_included_files())==1) {
    header("Location: ../index.php", true, 301);
}

make_dir(WB_PATH. '/var/modules/elfinder/', OCTAL_DIR_MODE, true);

$msg = '';
