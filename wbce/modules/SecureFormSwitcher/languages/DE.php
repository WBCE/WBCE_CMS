<?php
/**
 * @category        modules
 * @package         Secure Form Switcher
 * @author          WBCE Project
 * @copyright       Luisehahne, Norbert Heimsath
 * @license         GPLv2 or any later.
 */

//Module description
$module_description = 'Einige Zusatzeinstellungen f&uuml;r den Formular und CSRF Schutz.';

$SFS['HEADER'] =      'Formular und CSRF Einstellungen.';
$SFS['DESCRIPTION'] = 'Hier k&ouml;nnen sie die Sicherheit f&uuml;r Formulare weiter erh&ouml;hen und weitere Einstellungen vornehmen';

// Backend variables
$SFS['SUBMIT'] = '&uuml;bernehmen';
$SFS['RESET_SETTINGS'] = 'Standardeinstellung';
$SFS['ON_OFF'] = 'Ein/Aus';

// Variablen fuer AdminTool Optionen
$SFS['USEIP'] = 'IP-Blocks (1-4, 0=kein Check)';
$SFS['USEIP_TTIP'] = '<em>Hilfe</em>
Diese Anzahl der Segmente einer IP-Adresse werden f&uuml;r den Fingerprint genutzt. "4" hei&szlig;t die gesamte IP-Adresse (dies macht nur bei festen IPs wie z.B. Servern Sinn). "2" ist ein guter Kompromiss, da im Heimbereich durch 24-Stunden Resets nur die ersten beiden Segmente konstant bleiben. 
<ul>
<li>4= xxx.xxx.xxx.xxx</li>
<li>3= xxx.xxx.xxx</li>
<li>2= xxx.xxx</li>
<li>1= xxx</li>
<li>0=keine Nutzung der IP</li></ul>';
$SFS['USEIP_ERR'] = "Oktets d&uuml;rfen nur 0-4 enthalten";

$SFS['TOKENNAME'] = 'Tokenname [a-zA-Z] 5-20 Zeichen ';
$SFS['TOKENNAME_TTIP'] = '<em>Hilfe</em>Der Name des Tokens. Umgangssprachlich wird Token auch TAN genannt.';
$SFS['TOKENNAME_ERR'] ="Tokenname darf nur a-z und A-Z enthalten und muss zwischen 5 und 20 zeichen lang sein <br />\n";

$SFS['SECRET'] = 'Secret [a-zA-Z0-9] 20-60 Zeichen';
$SFS['SECRET_TTIP'] = '<em>Hilfe</em>Ein zuf&auml;lliger Schl&uuml;ssel, der f&uuml;r die Token-Erstellung verwendet wird. Empfohlen sind mind. 20 Zeichen.';
$SFS['SECRET_ERR'] = 'Das Secret darf a-zA-z0-9 enthalten und muss zwischen 20 und 60 Zeichen lang sein';

$SFS['SECRETTIME'] = 'Secrettime [0-9] 1-5 Zeichen';
$SFS['SECRETTIME_TTIP'] = '<em>Hilfe</em>Zeit (in Sekunden), bis der Secret-Schl&uuml;ssel sich erneuert.';
$SFS['SECRETTIME_ERR'] = 'Secrettime darf nur Zahlen enthalten und max. 5 stellen lang sein';

$SFS['TIMEOUT'] = 'Timeout [0-9] 1-5 Zeichen';
$SFS['TIMEOUT_TTIP'] = '<em>Hilfe</em>Zeit (in Sekunden), bis ein Formular-Token nicht mehr gilt.';
$SFS['TIMEOUT_ERR'] = 'Timeout darf nur Zahlen enthalten und max. 5 stellen lang sein';

$SFS['USEFP'] = 'Fingerprinting';
$SFS['USEFP_TTIP'] = '<em>Hilfe</em>Zus&auml;tzlich zur IP-Adresse wird Betriebssystem und Browser zu jeder TAN-Validierung hinzugezogen.';
$SFS['USEFP_Err'] = '';


