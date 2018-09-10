<?php
/**
 *
 * @category        backend,hidden
 * @package         OpF Simple Backend
 * @version         1.3.0
 * @authors         Martin Hecht (mrbaseman)
 * @copyright       (c) 2018, Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */




// Deutsche Modulbeschreibung
$module_description          = 'Konfiguration von Anzeigefiltern/-scripten f&uuml;r Backend und Frontend.';

// Ueberschriften und Textausgaben
$OPF['HEADING']             = 'Ausgabefilter';
$OPF['HOWTO']               = '&Uuml;ber nachfolgende Optionen kann die Ausgabefilterung konfiguriert werden.';
$OPF['WARNING']             = '';

// Text von Form Elementen
$OPF['BASIC_CONF']          = 'Grundeinstellungen';
$OPF['SYS_REL']             = 'Frontendausgabe mit relativen URLs';
$OPF['ENABLED']             = 'Aktiviert';
$OPF['DISABLED']            = 'Deaktiviert';

$OPF['REPLACEMENT_CONF']    = 'E-Mail-Ersetzungen';

$OPF['DROPLETS'] = 'Droplets-Filter, ohne diesen funktionieren Droplets nicht';
$OPF['WBLINK'] = 'wblink-Filter, [wblinkXX] zu URL';
$OPF['AUTO_PLACEHOLDER'] = 'Platzhalter, um Ziele zum Verschieben von JS, CSS und mehr zu haben';
$OPF['MOVE_STUFF'] = 'CSS, JS und mehr werden zum jeweiligen Platzhalter verschoben';
$OPF['REPLACE_STUFF'] = 'Ersetze title, keywords oder description aus Modulen heraus';
$OPF['SHORT_URL'] = 'ShortUrl-Filter (kein /pages/, kein .php; htaccess-Anpassung erforderlich!)';
$OPF['CSS_TO_HEAD'] = 'CSS in den Head transferieren';
$OPF['REMOVE_SYSTEM_PH'] = 'Vom Core automatisch generierte Kommentare l&ouml;schen.';

