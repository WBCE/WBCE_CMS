<?php
/**
 * @category        modules
 * @package         Maintainance Mode
 * @author          WBCE Project
 * @copyright       Luisehahne, Norbert Heimsath
 * @license         GPLv2 or any later.
 */

//Module description
$module_description = 'Einige Zusatzeinstellungen f&uuml;r  die Sicherheit';

$SFS['HEADER'] = 'Extra Sicherheitseinstellungen ';
$SFS['DESCRIPTION'] = 'Hier k&ouml;nnen Sie weitere  Sicherheitseistellungen vornehmen';

// Backend variables
$SFS['SUBMIT'] = 'Speichern';
$SFS['RESET_SETTINGS'] = 'Standardeinstellung';
$SFS['ON_OFF'] = 'Ein/Aus';

// Variablen fuer AdminTool Optionen
$SFS['USEIP'] = 'IP-Blocks (1-4, 0=kein Check)';
$SFS['USEIP_TTIP'] = '<em>Hilfe</em>
Diese Anzahl der Segmente einer IP-Adresse werden f&uuml;r den Fingerprint genutzt, d.h. bei "4" die gesamte IP-Adresse (dies macht nur bei festen IPs wie z.B. Servern Sinn). "2" ist ein guter Kompromiss, da im Heimbereich häufig durch 24-Stunden Resets nur die ersten beiden Segmente konstant bleiben. 
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
$SFS['SECRETTIME_ERR'] = 'Secrettime darf nur Zahlen enthalten und max. 5 Stellen lang sein';

$SFS['TIMEOUT'] = 'Session/Token Timeout [0-9] 1-5 Zeichen';
$SFS['TIMEOUT_TTIP'] = '<em>Hilfe</em>Zeit (in Sekunden), bis die Formular-Token und die Session ihre G&uuml;ltigkeit verlieren.';
$SFS['TIMEOUT_ERR'] = 'Timeout darf nur Zahlen enthalten und max. 5 Stellen lang sein';

$SFS['USEFP'] = 'Fingerprinting';
$SFS['USEFP_TTIP'] = '<em>Hilfe</em>Zus&auml;tzlich zur IP-Adresse wird Betriebssystem und Browser zu jeder TAN-Validierung hinzugezogen.';
$SFS['USEFP_Err'] = '';
