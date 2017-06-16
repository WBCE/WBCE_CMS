<?php
/**
 * @category        modules
 * @package         Maintainance Mode
 * @author          WBCE Project
 * @copyright       Luisehahne, Norbert Heimsath
 * @license         GPLv2 or any later.
 */

//Module description
$module_description = 'This module offers a few additional security settings.';

$SFS['HEADER'] =      'More Security settings .';
$SFS['DESCRIPTION'] = 'Here we have a few additional security settings';

// Backend variables
$SFS['SUBMIT'] = 'Accept';
$SFS['RESET_SETTINGS'] = 'Default setting';
$SFS['ON_OFF'] = 'On/OFF';

// Variablen fuer AdminTool Optionen
$SFS['USEIP'] = 'IP-Blocks (1-4, 0=no check)';
$SFS['USEIP_TTIP'] = '<em>Help</em>
These numbers of segments of an IP address can be used for the fingerprint. "4" is the whole IP address (this makes sense e.g. for servers with a stable IP address). "2" is a good compromise, because at home there\'s often the 24-hour reset and therefore only the first two segments keep constant.
<ul>
<li>4= xxx.xxx.xxx.xxx</li>
<li>3= xxx.xxx.xxx</li>
<li>2= xxx.xxx</li>
<li>1= xxx</li>
<li>0= no usage of the IP</li></ul>';
$SFS['USEIP_ERR'] = "Invalid range for octets (0-4 allowed)<br />\n";

$SFS['TOKENNAME'] = 'Tokenname [a-zA-Z] 5-20 characters';
$SFS['TOKENNAME_TTIP'] = '<em>Help</em>The name of the token. Coll. a token is often called TAN.';
$SFS['TOKENNAME_ERR'] ="Tokenname not saved : Only a-z and A-Z and between 5 to 20 characters<br />\n";

$SFS['SECRET'] = 'Secret [a-zA-Z0-9] 20-60 characters';
$SFS['SECRET_TTIP'] = '<em>Help</em>A random key, that is being used for creating a TAN. Recommend are at least 20 digits.';
$SFS['SECRET_ERR'] = "Secret not saved : Only a-z, A-Z and 0-9 and between 20 to 60 characters<br />\n";

$SFS['SECRETTIME'] = 'Secrettime [0-9] 1-5 characters';
$SFS['SECRETTIME_TTIP'] = '<em>Help</em>Time (in seconds), until the secret-key will be renewed.';
$SFS['SECRETTIME_ERR'] = "Secrettime not saved: Only 0-9 and between 1 to 5 characters<br />\n";

$SFS['TIMEOUT'] = 'Session/token Timeout [0-9] 1-5 characters';
$SFS['TIMEOUT_TTIP'] = '<em>Help</em>Time (in seconds), until the form-token and sessions becomes invalid.';
$SFS['TIMEOUT_ERR'] = "Timeout not saved : Only 0-9 and between 1 to 5 characters<br />\n";

$SFS['USEFP'] = 'Fingerprinting';
$SFS['USEFP_TTIP'] = '<em>Help</em>Require OS and browser for every TAN-validation additionally to the IP-address.';
$SFS['USEFP_Err'] = "";


