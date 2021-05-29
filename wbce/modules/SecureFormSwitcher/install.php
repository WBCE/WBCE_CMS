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

$setError=Settings::Set("wb_secform_secret", "5609bnefg93jmgi99igjefg");
$setError=Settings::Set("wb_secform_secrettime", '86400');
$setError=Settings::Set("wb_secform_timeout", '7200');
$setError=Settings::Set("wb_session_timeout", '7200');
$setError=Settings::Set("wb_secform_tokenname", 'formtoken');
$setError=Settings::Set("wb_secform_usefp", false);
$setError=Settings::Set("fingerprint_with_ip_octets", "2");
$msg = '';
