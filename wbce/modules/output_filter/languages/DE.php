<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Deutsche Modulbeschreibung
$module_description          = 'Dieses Modul erlaubt die Filterung von Inhalten vor der Anzeige im Frontend.';

// Ueberschriften und Textausgaben
$OPF['HEADING']             = 'Optionen: Ausgabefilterung';
$OPF['HOWTO']               = '&Uuml;ber nachfolgende Optionen kann die Ausgabefilterung konfiguriert werden.<p style="line-height:1.5em;"><strong>Tipp: </strong>Mailto Links k&ouml;nnen mit einer Javascript-Routine verschl&uuml;sselt werden. Um diese Option zu aktivieren, muss der PHP-Befehl <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> im &lt;head&gt; der index.php Ihres Templates eingebunden werden. Ohne diese &Auml;nderungen wird nur das @ Zeichen im mailto:-Teil ersetzt.</p>';
$OPF['WARNING']             = '';

// Text von Form Elementen
$OPF['BASIC_CONF']          = 'Grundeinstellungen';
$OPF['SYS_REL']             = 'Frontendausgabe mit relativen URLs';
$OPF['EMAIL_FILTER']        = 'Filtere E-Mail Adressen im Text';
$OPF['MAILTO_FILTER']       = 'Filtere E-Mail Adressen in mailto-Links';
$OPF['ENABLED']             = 'Aktiviert';
$OPF['DISABLED']            = 'Deaktiviert';

$OPF['REPLACEMENT_CONF']    = 'E-Mail-Ersetzungen';
$OPF['AT_REPLACEMENT']      = 'Ersetze "@" durch';
$OPF['DOT_REPLACEMENT']     = 'Ersetze "." durch';


$OPF['ALL_ON_OFF'] = 'Alle Filter aktivieren/deaktivieren';
$OPF['DROPLETS'] = 'Droplets-Filter';
$OPF['WBLINK'] = 'wblink-Filter';
$OPF['AUTO_PLACEHOLDER'] = 'Platzhalter für Insertfilter einzufügen wenn nicht existent.';
$OPF['INSERT'] = 'CSS-, JS-, Meta-Insert-Filter';
$OPF['JS_MAILTO'] = 'Javascript für Mailto-Filter';
$OPF['SHORT_URL'] = 'Short Url Filter(kein /pages/, kein .php)';
$OPF['CSS_TO_HEAD'] = 'CSS in den Head transferieren';
