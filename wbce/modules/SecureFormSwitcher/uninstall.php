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

 $setError = Settings::del("wb_secform_secret");
 $setError = Settings::del("wb_secform_secrettime");
 $setError = Settings::del("wb_secform_timeout");
 $setError = Settings::del("wb_secform_tokenname");
 $setError = Settings::del("wb_secform_usefp");
 $setError = Settings::del("fingerprint_with_ip_octets");

$msg = 'Secureform switcher setting deleted, now you need to use config.php again ';
