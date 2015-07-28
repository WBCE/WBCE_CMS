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
 
 
$WS = array (
	"PLEASEWAIT" 		=> "Please wait..",
	"MENU1" 			=> "Overview",
	"MENU2" 			=> "Visitors",
	"MENU3" 			=> "History",
	"MENU4" 			=> "Help",
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
	"YESTERVISITORS" 	=> "Visitors",
	"YESTERPAGES" 		=> "Pages",
	"YESTERDAYBOTS" 	=> "Searchengine robots",
	"CURRENTONLINE" 	=> "Currently online",
	"BOUNCES" 			=> "Bounced visits last 48 hours",
	"AVGPAGESVISIT" 	=> "Pages per visit",
	"AVG7VISITS" 		=> "Visitors per day - 7 days",
	"AVG30VISITS" 		=> "Visitors per day - 30 days",
	"REFTOP10" 			=> "Top 10 - Referers",
	"PAGETOP10" 		=> "Top 10 - Pages",
	"KEYSTOP10" 		=> "Top 10 - Keywords",
	"LANGTOP10" 		=> "Top 10 - Languages",
	"NUMBER" 			=> "Number",
	"PERCENT" 			=> "Percent",
	"REFERER" 			=> "Referer",
	"PAGES" 			=> "Page",
	"KEYWORDS" 			=> "Keywords",
	"LANGUAGES" 		=> "Language",
	"HISTORY" 			=> "History",
	"TOTALSINCE"		=> "Total since",
	"SELECTED"			=> "Selected date",
	"VISITORS"			=> "Visitors",
	"PAGES"				=> "Pages",
	"REQUESTS"				=> "Requests",
	"AVGDAY"			=> "Average per day",
	"YEAR"				=> "Year"
);

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
	'donate'		=> 'This module is created by Dev4me and is made available for the WebsiteBaker community for free.<br/>If you like this module, please consider making a donation through paypal.'

);
	
	
?>