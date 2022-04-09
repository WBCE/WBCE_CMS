<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.2
 * @lastmodified    December 9, 2020
 *
 */
 
$module_description = 'Statistiken der Website';

$WS = array (
        "PLEASEWAIT"	=> "Bitte warten..",
        "MENU1"			=> "&Uuml;bersicht",
        "MENU2"			=> "Besucher",
        "MENU3"			=> "History",
        "MENU4"			=> "Live",
        "MENU5"			=> "Hilfe",
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
		"TODAYREFSPAM" 		=> "Referer spammers",
        "YESTERVISITORS"	=> "Besucher",
        "YESTERPAGES"	=> "Seiten",
        "YESTERDAYBOTS"	=> "Suchmaschinen Robots",
		"YESTERDAYREFSPAM" 	=> "Referer spammers",
        "CURRENTONLINE"	=> "Zur Zeit online",
        "BOUNCES"		=> "Besuche in den letzten 48 Stunden",
        "AVGPAGESVISIT"	=> "Seiten pro Besuch",
        "AVG7VISITS"	=> "Besucher pro Tag - 7 Tage",
        "AVG30VISITS"	=> "Besucher pro Tag - 30 Tage",
        "TOP"			=> "Top",
        "REFTOP"		=> "Referers",
        "PAGETOP"		=> "Seiten",
        "KEYSTOP"		=> "Keywords",
        "LANGTOP"		=> "Sprache",
		"ENTRYTOP" 		=> "Eingangsseiten",
		"EXITTOP" 		=> "Ausgangsseiten",
        "NUMBER"		=> "Nr.",
        "PERCENT"		=> "Prozent",
        "REFERER"		=> "Referer",
        "PAGES"			=> "Seite",
        "KEYWORDS"		=> "Keywords",
        "LANGUAGES"		=> "Sprache",
        "HISTORY"		=> "History",
        "TOTALSINCE"	=> "Total seit",
        "SELECTED"		=> "Gew&auml;hltes Datum",
        "VISITORS"		=> "Besucher",
        "PAGES"			=> "Seiten",
        "REQUESTS"		=> "Zugriffe",
        "AVGDAY"		=> "Tagesdurchschnitt",
        "YEAR"			=> "Jahr",
		"PAGES_CLOUD"	=> "Anzahl der Seiten pro Besuch",
		"SECONDS_CLOUD"	=> "Dauer pro Besuch",
		"LIVE_DATE" 	=> "Datum",
		"LIVE_TIME" 	=> "Zeit",
		"LIVE_PAGE" 	=> "Aktuelle Seite",
		"LIVE_PAGES" 	=> "Anzahl der Seiten",
		"LIVE_LAST" 	=> "Letzte Aktion",
		"LIVE_ONLINE" 	=> "Zeit online"		
);

$pages_cloud[1] = "1 Seite";
$pages_cloud[2] = "2 Seiten";
$pages_cloud[3] = "3 Seiten";
$pages_cloud[4] = "4 Seiten";
$pages_cloud[5] = "5-6 Seiten";
$pages_cloud[7] = "7-9 Seiten";
$pages_cloud[10]= "10-14 Seiten";
$pages_cloud[15]= "15-19 Seiten";
$pages_cloud[20]= "20-24 Seiten";
$pages_cloud[25]= "25+ Seiten";

$second_cloud[0] = "0-9 Sekunden";
$second_cloud[10] = "10-29 Sekunden";
$second_cloud[30] = "30-59 Sekunden";
$second_cloud[60] = "1-2 Minuten";
$second_cloud[120] = "2-4 Minuten";
$second_cloud[240] = "4-8 Minuten";
$second_cloud[420] = "8-10 Minuten";
$second_cloud[600] = "10-15 Minuten";
$second_cloud[900] = "15-30 Minuten";
$second_cloud[1800] = "30+ Minuten";

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
        'fr'=>'Franz&ouml;sisch',
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
		'donate'		=> 'Dieses Modul wurde von Dev4me programmiert und der WebsiteBaker oder WBCE Community frei zur Verf&uuml;gung gestellt.<br/>Wenn Ihnen dieses Modul gef&auml;llt, w&uuml;rden wir uns &uuml;ber eine kleine Spende via PayPal freuen..'
);

?>

