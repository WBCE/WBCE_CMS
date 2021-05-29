<?php
/**
 * @category        modules
 * @package         Secure Form Switcher
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license			WTFPL
 */

//no direct file access
if (count(get_included_files())==1) {
    header("Location: ../index.php", true, 301);
}

$msg = '';

// Del old switch as now there is only one.
Settings::Del("secure_form_module");

$setError=Settings::Set("wb_session_timeout", WB_SECFORM_TIMEOUT);
