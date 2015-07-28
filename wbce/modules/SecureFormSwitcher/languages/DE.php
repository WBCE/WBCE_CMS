<?php
/**
 *
 * @category        modules
 * @package         SecureFormSwitcher
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.2
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: DE.php 1479 2011-07-25 00:42:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/SecureFormSwitcher/languages/DE.php $
 * @lastmodified    $Date: 2011-07-25 02:42:10 +0200 (Mo, 25. Jul 2011) $
 *
*/

//Module description
$module_description = 'Dieses Modul wechselt zwischen <strong>SingleTab SecureForm</strong> und <strong>MultiTab SecureForm</strong>.';

// Backend variables
$SFS_TEXT['TEXT_SWITCH'] = 'Wechseln';
$SFS_TEXT['TXT_FTAN_SWITCH'] = 'Wechsel zu ';
$SFS_TEXT['SECURE_FORM'] = 'SingleTab SecureForm';
$SFS_TEXT['SECURE_FORMMTAB'] = 'Multitab SecureForm';
$SFS_TEXT['FILE_FORMTAB_NOT_GOUND'] = '<strong>Multitab nicht ausführbar!<br />Benötigte Datei \'/framework/SecureForm.mtab.php\' nicht gefunden!</strong><br />
<span>Sie müssen die Datei manuell über FTP hochspielen</span>';
$SFS_TEXT['SUBMIT_FORM'] = 'SingleTab (empfohlen)';
$SFS_TEXT['SUBMIT_FORMTAB'] = 'Multi Tab';
$SFS_TEXT['SUBMIT'] = 'Übernehmen';
$SFS_TEXT['INFO'] = 'Hier können Sie auswählen, ob die Standard-Sicherheitseinstellung oder die Sicherheitseinstellung zur Verwendung von mehreren WebsiteBaker-Instanzen in parallelen Browser-Tabs aktiviert werden soll.';
$SFS_TEXT['RESET_SETTINGS'] = 'Standardeinstellung';
$SFS_TEXT['ON_OFF'] = 'Ein/Aus';

// Variablen fuer AdminTool Optionen
$SFS_TEXT['WB_SECFORM_USEIP'] = 'IP-Blocks (1-4, 0=kein Check)';
$SFS_TEXT['WB_SECFORM_USEIP_TOOLTIP'] = '<span class="custom help"><em>Hilfe</em>
Diese Anzahl der Segmente einer IP-Adresse werden für den Fingerprint genutzt. "4" heißt die gesamte IP-Adresse (dies macht nur bei festen IPs wie z.B. Servern Sinn). "2" ist ein guter Kompromiss, da im Heimbereich durch 24-Stunden Resets nur die ersten beiden Segmente konstant bleiben. 
<ul>
<li>4= xxx.xxx.xxx.xxx</li>
<li>3= xxx.xxx.xxx</li>
<li>2= xxx.xxx</li>
<li>1= xxx</li>
<li>0=keine Nutzung der IP</li></ul></span>';
$SFS_TEXT['WB_SECFORM_TOKENNAME'] = 'Tokenname';
$SFS_TEXT['WB_SECFORM_TOKENNAME_TOOLTIP'] = '<span class="custom help"><em>Hilfe</em>Der Name des Tokens. Umgangssprachlich wird Token auch TAN genannt.</span>';
$SFS_TEXT['WB_SECFORM_SECRET'] = 'Secret (Beliebige Zeichen)';
$SFS_TEXT['WB_SECFORM_SECRET_TOOLTIP'] = '<span class="custom help"><em>Hilfe</em>Ein zufälliger Schlüssel, der für die Token-Erstellung verwendet wird. Empfohlen sind mind. 20 Zeichen.</span>';
$SFS_TEXT['WB_SECFORM_SECRETTIME'] = 'Secrettime';
$SFS_TEXT['WB_SECFORM_SECRETTIME_TOOLTIP'] = '<span class="custom help"><em>Hilfe</em>Zeit (in Sekunden), bis der Secret-Schlüssel sich erneuert.</span>';
$SFS_TEXT['WB_SECFORM_TIMEOUT'] = 'Timeout';
$SFS_TEXT['WB_SECFORM_TIMEOUT_TOOLTIP'] = '<span class="custom help"><em>Hilfe</em>Zeit (in Sekunden), bis ein Formular-Token nicht mehr gilt.</span>';
$SFS_TEXT['WB_SECFORM_USEFP'] = 'Fingerprinting';
$SFS_TEXT['WB_SECFORM_USEFP_TOOLTIP'] = '<span class="custom help"><em>Hilfe</em>Zusätzlich zur IP-Adresse wird Betriebssystem und Browser zu jeder TAN-Validierung hinzugezogen.</span>';
