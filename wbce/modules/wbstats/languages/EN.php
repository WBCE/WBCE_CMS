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
 * @version         0.2.4
 * @lastmodified    Februari 11, 2021
 *
 */
 
 
$WS = array (
	"PLEASEWAIT" 		=> "Please wait..",
	"MENU1" 			=> "Overview",
	"MENU2" 			=> "Visitors",
	"MENU3" 			=> "History",
	"MENU4" 			=> "Live",
	"MENU5" 			=> "Config",
	"MENU6" 			=> "Help",
	"MENU7" 			=> "Logbook",
	"MENU8" 			=> "Campaigns",
	"GENERAL" 			=> "Counters",
	"LAST24" 			=> "Last 24 hours",
	"LAST30" 			=> "Last 30 days",
	"TOTALS" 			=> "Totals",
	"TODAY" 			=> "Today",
	"LIVE" 				=> "Live",
	"YESTERDAY" 		=> "Yesterday",
	"MISC" 				=> "Miscellaneous",
	"AVERAGES" 			=> "Averages",
	"TOTALVISITORS" 	=> "Visitors",
	"TOTALPAGES" 		=> "Pages",
	"TODAYVISITORS" 	=> "Visitors",
	"TODAYPAGES" 		=> "Pages",
	"TODAYBOTS" 		=> "Searchengine robots",
	"TODAYREFSPAM" 		=> "Referer spammers",
	"YESTERVISITORS" 	=> "Visitors",
	"YESTERPAGES" 		=> "Pages",
	"YESTERDAYBOTS" 	=> "Searchengine robots",
	"YESTERDAYREFSPAM" 	=> "Referer spammers",
	"CURRENTONLINE" 	=> "Currently online",
	"BOUNCES" 			=> "Bounced visits last 48 hours",
	"AVGPAGESVISIT" 	=> "Pages per visit",
	"AVG7VISITS" 		=> "Visitors per day - 7 days",
	"AVG30VISITS" 		=> "Visitors per day - 30 days",
	"TOP" 				=> "Top",
	"REFTOP" 			=> "Referers",
	"PAGETOP" 		=> "Pages",
	"KEYSTOP" 		=> "Keywords",
	"LANGTOP" 		=> "Languages",
	"ENTRYTOP" 		=> "Entry pages",
	"EXITTOP" 		=> "Exit pages",
	"LOCTOP" 			=> "Locations",
	"BROWSERTOP" 		=> "Browsers",
	"OSTOP" 			=> "OS",
	"NUMBER" 			=> "Number",
	"PERCENT" 			=> "Percent",
	"REFERER" 			=> "Referer",
	"PAGE" 				=> "Page",
	"KEYWORDS" 			=> "Keywords",
	"LANGUAGES" 		=> "Language",
	"LOCATIONS" 		=> "Locations",
	"HISTORY" 			=> "History",
	"TOTALSINCE"		=> "Total since",
	"SELECTED"			=> "Selected date",
	"VISITORS"			=> "Visitors",
	"PAGES"				=> "Pages",
	"REQUESTS"			=> "Requests",
	"AVGDAY"			=> "Average per day",
	"YEAR"				=> "Year",
	"PAGES_CLOUD"		=> "Number of pages per visit",
	"SECONDS_CLOUD"		=> "Duration per visit",
	"LIVE_DATE" 		=> "Date",
	"LIVE_TIME" 		=> "Time",
	"LIVE_PAGE" 		=> "Current page",
	"LIVE_PAGES" 		=> "Number of pages",
	"LIVE_LAST" 		=> "Last action",
	"LIVE_ONLINE" 		=> "Time online",
	"LOCATION"			=> "Location",
	"BROWSER"			=> "Browser",
	"OS"			=> "OS",
	"IGNORES" 			=> "List of ignored IP addresses",
	"MYIP" 				=> "Current IP address"
);

$pages_cloud[1] = "1 page";
$pages_cloud[2] = "2 pages";
$pages_cloud[3] = "3 pages";
$pages_cloud[4] = "4 pages";
$pages_cloud[5] = "5-6 pages";
$pages_cloud[7] = "7-9 pages";
$pages_cloud[10]= "10-14 pages";
$pages_cloud[15]= "15-19 pages";
$pages_cloud[20]= "20-24 pages";
$pages_cloud[25]= "25+ pages";

$second_cloud[0] = "0-9 seconds";
$second_cloud[10] = "10-29 seconds";
$second_cloud[30] = "30-59 seconds";
$second_cloud[60] = "1-2 minutes";
$second_cloud[120] = "2-4 minutes";
$second_cloud[240] = "4-8 minutes";
$second_cloud[420] = "8-10 minutes";
$second_cloud[600] = "10-15 minutes";
$second_cloud[900] = "15-30 minutes";
$second_cloud[1800] = "30+ minutes";

$code2lang = array(
	'ar'=>'Arabic',	
	'bn'=>'Bengali',
	'bg'=>'Bulgarian',
	'zh'=>'Chinese',
	'cs'=>'Czech',
	'da'=>'Danish',
	'en'=>'English',
	'et'=>'Estonian',
	'fi'=>'Finnish',
	'fr'=>'French',
	'de'=>'German',
	'el'=>'Greek',
	'hi'=>'Hindi',
	'id'=>'Indonesian',
	'it'=>'Italian',
	'ja'=>'Japanese',
	'kg'=>'Korean',
	'nb'=>'Norwegian',
	'nl'=>'Dutch',
	'pl'=>'Polish',
	'pt'=>'Portuguese',
	'ro'=>'Romanian',
	'ru'=>'Russian',
	'sr'=>'Serbian',
	'sk'=>'Slovak',
	'es'=>'Spanish',
	'sv'=>'Swedish',
	'th'=>'Thai',
	'tr'=>'Turkish',
	''=>'');	

$help = array(
	'installhead' 	=> 'Installation and Configuration',
	'installbody' 	=> 'To include the counter to your website add the following line to your template(s), somewhere in the first &lt;?php ... ?&gt; block;',
	'refererhead' 	=> 'Enabling referer information in WB2.8.3 and later',
	'refererbody' 	=> 'To enable referer and searchkey detection in WebsiteBaker version 2.8.3 and later, add the following line to the <strong>config.php</strong> in the root of your website, 
						just before the line: <i>require_once(WB_PATH.\'/framework/initialize.php\');</i>',
	'jqueryhead' 	=> 'jQuery problems',
	'jquerybody' 	=> 'In older WebsiteBaker Admin themes (version 2.8.1 and 2.7) jQuery is not loaded correctly in the head section of the theme.<br/>
						You must change this by moving the lines starting with &lt;script&gt; in the bottom of of the footer.htt file to the &lt;head&gt; section of the file header.htt.<br/>
						You can find these files in the directory /templates/{your_theme}/templates/<br/><br/>
						<strong>Note:</strong> This tool wil not show any statistics if jQuery is not initialized correctly!',
	'donate'		=> 'This module is created by Dev4me and is made available for the WebsiteBakerand/or WBCE community for free.<br/>If you like this module, please consider making a donation through paypal.'

);
	
	
?>