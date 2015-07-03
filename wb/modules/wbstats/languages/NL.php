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
 
$module_description = 'Laat de statistieken van deze website zien';

$WS = array (
	"PLEASEWAIT" 		=> "Een moment a.u.b..",
	"MENU1" 			=> "Overzicht",
	"MENU2" 			=> "Bezoekers",
	"MENU3" 			=> "Geschiedenis",
	"MENU4" 			=> "Help",
	"GENERAL" 			=> "Tellers",
	"LAST24" 			=> "Laatste 24 uur",
	"LAST30" 			=> "Laatste 30 dagen",
	"TOTALS" 			=> "Totalen",
	"TODAY" 			=> "Vandaag",
	"LIVE" 				=> "Live",
	"YESTERDAY" 		=> "Gisteren",
	"MISC" 				=> "Diversen",
	"AVERAGES" 			=> "Gemiddelden",
	"TOTALVISITORS" 	=> "Bezoekers",
	"TOTALPAGES" 		=> "Pagina's",
	"TODAYVISITORS" 	=> "Bezoekers",
	"TODAYPAGES" 		=> "Pagina's",
	"TODAYBOTS" 		=> "Zoekmachinerobots",
	"YESTERVISITORS" 	=> "Bezoekers",
	"YESTERPAGES" 		=> "Pagina's",
	"YESTERDAYBOTS" 	=> "Zoekmachinerobots",
	"CURRENTONLINE" 	=> "Momenteel online",
	"BOUNCES" 			=> "Bounced bezoekers in de laatste 48 uur",
	"AVGPAGESVISIT" 	=> "Pagina's per bezoek",
	"AVG7VISITS" 		=> "Bezoekers per dag - laatste 7 dagen",
	"AVG30VISITS" 		=> "Bezoekers per dag - laatste 30 dagen",
	"REFTOP10" 			=> "Top 10 - Verwijzers",
	"PAGETOP10" 		=> "Top 10 - Pagina's",
	"KEYSTOP10" 		=> "Top 10 - Zoektermen",
	"LANGTOP10" 		=> "Top 10 - Talen",
	"NUMBER" 			=> "Nr.",
	"PERCENT" 			=> "Procent",
	"REFERER" 			=> "Verwijzer",
	"PAGES" 			=> "Pagina",
	"KEYWORDS" 			=> "Zoekterm",
	"LANGUAGES" 		=> "Taal",
	"HISTORY" 			=> "Geschiedenis",
	"TOTALSINCE"		=> "Totaal sinds",
	"SELECTED"			=> "Geselecteerde maand",
	"VISITORS"			=> "Bezoekers",
	"PAGES"				=> "Pagina's",
	"REQUESTS"			=> "Paginaverzoeken",
	"AVGDAY"			=> "Gemiddeld per dag",
	"YEAR"				=> "Jaar"
);

$code2lang = array(
	'ar'=>'Arabisch',	
	'bn'=>'Bengalees',
	'bg'=>'Bulgaars',
	'zh'=>'Chinees',
	'cs'=>'Tsjechisch',
	'da'=>'Deens',
	'en'=>'Engels',
	'et'=>'Estlands',
	'fi'=>'Fins',
	'fr'=>'Frans',
	'de'=>'Duits',
	'el'=>'Grieks',
	'hi'=>'Hindoestaans',
	'id'=>'Indonesisch',
	'it'=>'Italiaans',
	'ja'=>'Japans',
	'kg'=>'Koreaans',
	'nb'=>'Noors',
	'nl'=>'Nederlands',
	'pl'=>'Pools',
	'pt'=>'Portuguees',
	'ro'=>'Roemeens',
	'ru'=>'Russisch',
	'sr'=>'Servisch',
	'sk'=>'Slowaaks',
	'es'=>'Spaans',
	'sv'=>'Zweeds',
	'th'=>'Thais',
	'tr'=>'Turks',
	''=>'');	

$help = array(
	'installhead' 	=> 'Installatie en configuratie',
	'installbody' 	=> 'Om de counter in uw website toe te voegen moet de volgende regel worden toegevoegd in uw template. Plaats hem zo hoog mogelijk in de template, ergens tussen &lt;?php en ?&gt; codes:',
	'refererhead' 	=> 'Verwijzerinformatie in WB 2.8.3 en nieuwer',
	'refererbody' 	=> 'Om verwijzers en zoekwoorden te kunnen detecteren in WebsiteBaker 2.8.3 en nieuwer is het noodzakelijk om de volgende regel in uw <strong>config.php</strong> op te nemen. U vindt dit bestand op uw hosting server, in de root van uw WB-installatie.<br/>
	Plaats de regel net voor de regel : <i>require_once(WB_PATH.\'/framework/initialize.php\');</i>',
	'jqueryhead' 	=> 'jQuery-problemen',
	'jquerybody' 	=> 'In oudere WebsiteBaker Admin-themes (t/m versie 2.8.1) wordt jQuery niet geladen in de &lt;head&gt; sectie van het thema.<br/>
						U moet dit veranderen door het verplaatsten van de regels die starten met &lt;script&gt; onderin het bestand <strong>footer.htt</strong> naar de &lt;head&gt; sectie van het bestand <strong>header.htt</strong>.<br/>
						U vindt deze bestanden in de map /templates/{uw_thema}/templates/<br/><br/>
						<strong>Let op:</strong> Deze admintool zal geen statistieken tonen als jQuery niet correct is ge&#239;nitialiseerd!',
	'donate'		=> 'Deze module is gemaakt door Dev4me en is gratis beschikbaar voor de WebsiteBaker-gebruikers.<br/>Als u deze module waardeert kunt u via de knop hieronder een donatie via PayPal doen.'

);

	
?>