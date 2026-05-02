<?php
/**
 * @category        modules
 * @package         More Security Settings (SecureFormSwitcher)
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */

//no direct file access
if (count(get_included_files())==1) {
    header("Location: ../index.php", true, 301);
}

$msg = '';

// Del old switch as now there is only one.
Settings::delete("secure_form_module");

$setError = Settings::set("wb_session_timeout", WB_SECFORM_TIMEOUT);
