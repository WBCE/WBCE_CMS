<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link	    https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.4
 * @lastmodified    Februari 11, 2021
 *
 */
 
$module_description = 'Statistiken der Website';

$WS = array (
	"PLEASEWAIT"	=> "Bitte warten..",
	"MENU1"		=> "Übersicht",
	"MENU2"		=> "Besucher",
	"MENU3"		=> "History",
	"MENU4"		=> "Live",
	"MENU5"		=> "Konfigurieren",
	"MENU6"		=> "Hilfe",
	"MENU7"		=> "Logbuch",
	"MENU8" 	=> "Kampagnen",
	"GENERAL"	=> "Zähler",
	"LAST24"	=> "Letzte 24 Stunden",
	"LAST30"	=> "Letzte 30 Tage",
	"TOTALS"	=> "Gesamt",
	"TODAY"		=> "Heute",
	"LIVE"		=> "Live",
	"YESTERDAY"	=> "Gestern",
	"MISC"		=> "Verschiedenes",
	"AVERAGES"	=> "Durchschnitt",
	"TOTALVISITORS"	=> "Besucher",
	"TOTALPAGES"	=> "Seiten",
	"TODAYVISITORS"	=> "Besucher",
	"TODAYPAGES"	=> "Seiten",
	"TODAYBOTS"	=> "Suchmaschinen Robots",
	"TODAYREFSPAM" 	=> "Referer spammers",
	"YESTERVISITORS"=> "Besucher",
	"YESTERPAGES"	=> "Seiten",
	"YESTERDAYBOTS"	=> "Suchmaschinen Robots",
	"YESTERDAYREFSPAM" => "Referer spammers",
	"CURRENTONLINE"	=> "Zur Zeit online",
	"BOUNCES"	=> "Besuche in den letzten 48 Stunden",
	"AVGPAGESVISIT"	=> "Seiten pro Besuch",
	"AVG7VISITS"	=> "Besucher pro Tag - 7 Tage",
	"AVG30VISITS"	=> "Besucher pro Tag - 30 Tage",
	"TOP"		=> "Top",
	"REFTOP"	=> "Referers",
	"PAGETOP"	=> "Seiten",
	"KEYSTOP"	=> "Keywords",
	"LANGTOP"	=> "Sprache",
	"ENTRYTOP" 	=> "Eingangsseiten",
	"EXITTOP" 	=> "Ausgangsseiten",
	"LOCTOP" 	=> "Standorte",
	"BROWSERTOP" 	=> "Browsers",
	"OSTOP" 	=> "OS",
	"NUMBER"	=> "Nr.",
	"PERCENT"	=> "Prozent",
	"REFERER"	=> "Referer",
	"PAGE"		=> "Seite",
	"KEYWORDS"	=> "Keywords",
	"LANGUAGES"	=> "Sprache",
	"LOCATIONS" 	=> "Standorte",
	"HISTORY"	=> "History",
	"TOTALSINCE"	=> "Total seit",
	"SELECTED"	=> "Gewähltes Datum",
	"VISITORS"	=> "Besucher",
	"PAGES"		=> "Seiten",
	"REQUESTS"	=> "Zugriffe",
	"AVGDAY"	=> "Tagesdurchschnitt",
	"YEAR"		=> "Jahr",
	"PAGES_CLOUD"	=> "Anzahl der Seiten pro Besuch",
	"SECONDS_CLOUD"	=> "Dauer pro Besuch",
	"LIVE_DATE" 	=> "Datum",
	"LIVE_TIME" 	=> "Zeit",
	"LIVE_PAGE" 	=> "Aktuelle Seite",
	"LIVE_PAGES" 	=> "Anzahl der Seiten",
	"LIVE_LAST" 	=> "Letzte Aktion",
	"LIVE_ONLINE" 	=> "Zeit online",
	"LOCATION"	=> "Ort",
	"BROWSER"	=> "Browser",
	"OS"		=> "OS",
	"IGNORES" 	=> "Liste ignorierter IP-Adressen",
	"MYIP" 		=> "Aktuelle IP-Adresse"
);

$PAGES_CLOUD[1] = "1 Seite";
$PAGES_CLOUD[2] = "2 Seiten";
$PAGES_CLOUD[3] = "3 Seiten";
$PAGES_CLOUD[4] = "4 Seiten";
$PAGES_CLOUD[5] = "5-6 Seiten";
$PAGES_CLOUD[7] = "7-9 Seiten";
$PAGES_CLOUD[10]= "10-14 Seiten";
$PAGES_CLOUD[15]= "15-19 Seiten";
$PAGES_CLOUD[20]= "20-24 Seiten";
$PAGES_CLOUD[25]= "25+ Seiten";

$SECOND_CLOUD[0] = "0-9 Sekunden";
$SECOND_CLOUD[10] = "10-29 Sekunden";
$SECOND_CLOUD[30] = "30-59 Sekunden";
$SECOND_CLOUD[60] = "1-2 Minuten";
$SECOND_CLOUD[120] = "2-4 Minuten";
$SECOND_CLOUD[240] = "4-8 Minuten";
$SECOND_CLOUD[420] = "8-10 Minuten";
$SECOND_CLOUD[600] = "10-15 Minuten";
$SECOND_CLOUD[900] = "15-30 Minuten";
$SECOND_CLOUD[1800] = "30+ Minuten";

$CODE2LANG = array(
        'ar'=>'Arabisch',
        'bn'=>'Bengalisch',
        'bg'=>'Bulgarisch',
        'zh'=>'Chinesisch',
        'cs'=>'Tschechisch',
        'da'=>'Dänisch',
        'en'=>'Englisch',
        'et'=>'Estnisch',
        'fi'=>'Finnisch',
        'fr'=>'Französisch',
        'de'=>'Deutsch',
        'el'=>'Griechisch',
        'hi'=>'Hindi',
        'id'=>'Indonesisch',
        'it'=>'Italienisch',
        'ja'=>'Japanisch',
        'kg'=>'Koreanisch',
        'nb'=>'Norwegisch',
        'nl'=>'Niederländisch',
        'pl'=>'Polnisch',
        'pt'=>'Portugiesisch',
        'ro'=>'Rumänisch',
        'ru'=>'Russisch',
        'sr'=>'Serbisch',
        'sk'=>'Slowakisch',
        'es'=>'Spanisch',
        'sv'=>'Schwedisch',
        'th'=>'Thai',
        'tr'=>'Türkisch',
        ''=>'');
		
$HELP = array(
        'installhead' 	=> 'Installation und Einrichtung',
        'installbody' 	=> 'Um den Zähler in deine Webseite einzubinden, füge nachfolgende Codezeile in dein(e) Template(s) irgendwo in den ersten PHP-Abschnitt zwischen <?php ... ?> ein',
        'refererhead' 	=> 'Referer-Informationen in WB 2.8.3 und neuer',
        'refererbody' 	=> 'Für die WebsiteBaker Versionen 2.8.3 und neuer ist es notwendig, unten stehende Codezeile in die Datei <strong>config.php</strong> im WB-Hauptverzeichnis unmittelbar vor dieser Zeile hier einzufügen: <i>require_once(WB_PATH.\'/framework/initialize.php\');</i>',
        'jqueryhead' 	=> 'JQuery-Probleme',
        'jquerybody' 	=> 'In älteren WebsiteBaker-Admin-Themes (Version 2.8.1 und 2.7) wird JQuery nicht korrekt im Head-Bereich der Datei eingebunden.<br/>
                                                Dies kann durch das Verschieben der Code-Zeilen, die mit &lt;script&gt; beginnen von der Datei footer.htt zur Datei header.htt korrigiert werden.<br/>
                                                Beide Dateien findest du in im Ordner /templates/{dein_theme}/templates.<br/><br/>
                                                <strong>Hinweis:</strong> Das Modul kann keine Statistiken zeigen, wenn JQuery nicht korrekt initialisiert wird.',
        'donate'        => 'Dieses Modul wurde von Dev4me programmiert und der WebsiteBaker oder WBCE Community frei zur Verfügung gestellt.<br/>Wenn Ihnen dieses Modul gefällt, würden wir uns über eine kleine Spende via PayPal freuen..'
);