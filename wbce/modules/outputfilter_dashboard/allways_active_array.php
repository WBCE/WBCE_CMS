<?php
// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));

// the following filters need to be activated at all times
// this list prevents them from being deactivated in the dashboard
return [
    'opff_mod_opf_insert',  
    'opff_mod_opf_wblink',  
    'opff_mod_opf_email',  
    'opff_droplets'
];

