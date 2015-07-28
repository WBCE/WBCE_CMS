<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.1.8
 * @lastmodified    October 22, 2014
 *
 */
 
$module_description = 'Statistiken der Website';

$WS = array (
        "PLEASEWAIT"	=> "Bitte warten..",
        "MENU1"			=> "&Uuml;bersicht",
        "MENU2"			=> "Besucher",
        "MENU3"			=> "History",
        "MENU4"			=> "Hilfe",
        "GENERAL"		=> "Z&auml;hler",
        "LAST24"		=> "Letzte 24 Stunden",
        "LAST30"		=> "Letzte 30 Tage",
        "TOTALS"		=> "Gesamt",
        "TODAY"			=> "Heute",
        "LIVE"			=> "Live",
        "YESTERDAY"		=> "Gestern",
        "MISC"			=> "Verschiedenes",
        "AVERAGES"		=> "Durchschnitt",
        "TOTALVISITORS"	=> "Besucher",
        "TOTALPAGES"	=> "Seiten",
        "TODAYVISITORS"	=> "Besucher",
        "TODAYPAGES"	=> "Seiten",
        "TODAYBOTS"		=> "Suchmaschinen Robots",
        "YESTERVISITORS"	=> "Besucher",
        "YESTERPAGES"	=> "Seiten",
        "YESTERDAYBOTS"	=> "Suchmaschinen Robots",
        "CURRENTONLINE"	=> "Zur Zeit online",
        "BOUNCES"		=> "Besuche in den letzten 48 Stunden",
        "AVGPAGESVISIT"	=> "Seiten pro Besuch",
        "AVG7VISITS"	=> "Besucher pro Tag - 7 Tage",
        "AVG30VISITS"	=> "Besucher pro Tag - 30 Tage",
        "REFTOP10"		=> "Top 10 - Referers",
        "PAGETOP10"		=> "Top 10 - Seiten",
        "KEYSTOP10"		=> "Top 10 - Kennw&ouml;rter",
        "LANGTOP10"		=> "Top 10 - Sprache",
        "NUMBER"		=> "Nr.",
        "PERCENT"		=> "Prozent",
        "REFERER"		=> "Referer",
        "PAGES"			=> "Seite",
        "KEYWORDS"		=> "Kennw&ouml;rter",
        "LANGUAGES"		=> "Sprache",
        "HISTORY"		=> "History",
        "TOTALSINCE"	=> "Total seit",
        "SELECTED"		=> "Gew&auml;hltes Datum",
        "VISITORS"		=> "Besucher",
        "PAGES"			=> "Seiten",
        "REQUESTS"		=> "Zugriffe",
        "AVGDAY"		=> "Tagesdurchschnitt",
        "YEAR"			=> "Jahr"
);

$code2lang = array(
        'ar'=>'Arabisch',
        'bn'=>'Bengalisch',
        'bg'=>'Bulgarisch',
        'zh'=>'Chinesisch',
        'cs'=>'Tchechisch',
        'da'=>'D&auml;nisch',
        'en'=>'Englisch',
        'et'=>'Estonisch',
        'fi'=>'Finnisch',
        'fr'=>'Franz%ouml;sisch',
        'de'=>'Deutsch',
        'el'=>'Griechisch',
        'hi'=>'Hindi',
        'id'=>'Indonesisch',
        'it'=>'Italienisch',
        'ja'=>'Japanisch',
        'kg'=>'Koreanisch',
        'nb'=>'Norwegisch',
        'nl'=>'Niederl&auml;ndisch',
        'pl'=>'Polnisch',
        'pt'=>'Portugisisch',
        'ro'=>'Rom&auml;nisch',
        'ru'=>'Russisch',
        'sr'=>'Serbisch',
        'sk'=>'Slovakisch',
        'es'=>'Spanisch',
        'sv'=>'Schwedisch',
        'th'=>'Thai',
        'tr'=>'T&uuml;rkisch',
        ''=>'');
		
$help = array(
		'installhead' 	=> 'Installation und Einrichtung',
		'installbody' 	=> 'Um den Z&auml;hler in deine Webseite einzubinden, f&uuml;ge nachfolgene Codezeile in dein(e) Template(s) irgendwo in den ersten PHP-abschnitt zwischen <?php ... ?> ein',
		'refererhead' 	=> 'Referer-Informationen in WB 2.8.3 und neuer',
		'refererbody' 	=> 'F&uuml;r die WebsiteBaker Versionen 2.8.3 und neuer ist es notwendig, unten stehene Codezeile in die Datei <strong>config.php</strong> im WB-Hauptverzeichnis unmittelbar vor dieser Zeile hier einzuf&uuml;gen:  <i>require_once(WB_PATH.\'/framework/initialize.php\');</i>',
		'jqueryhead' 	=> 'JQuery-Probleme',
		'jquerybody' 	=> 'In &auml;lteren WebsiteBaker-Admin-Themes (Version 2.8.1 und 2.7) wird JQuery nicht korrekt im Head-Bereich der Datei eingebunden.<br/>
							Dies kann durch das Verschieben der Code-Zeilen, die mit &lt;script&gt; beginnen von der Datei footer.htt zur Datei header.htt korrigiert werden.<br/>
							Beide Dateien findest du in im Ordner /templates/{dein_theme}/templates.<br/><br/>
							<strong>Hinweis:</strong> Das Modul kann keine Statistiken zeigen, wenn JQuery nicht korrekt initialisiert wird.',
		'donate'		=> 'Dieses Modul wurde von Dev4me programmiert und der WebsiteBaker-Community frei zur Verf&uuml;gung gestellt.<br/>Wenn Ihnen dieses Modul gef&auml;llt, w&uuml;rden wir uns &uuml;ber eine kleine Spende via PayPal freuen..'
);

?>

