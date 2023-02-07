<?php
/**
 *
 * @category        admintool
 * @package         wbstats
 * @author          Ruud Eisinga - dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.5.3
 * @lastmodified    October17, 2022
 *
 */


defined('WB_PATH') OR die(header('Location: ../index.php'));

$table_day   = TABLE_PREFIX .'mod_wbstats_day';
$table_ips   = TABLE_PREFIX .'mod_wbstats_ips';
$table_pages = TABLE_PREFIX .'mod_wbstats_pages';
$table_ref   = TABLE_PREFIX .'mod_wbstats_ref';
$table_key   = TABLE_PREFIX .'mod_wbstats_keywords';
$table_lang  = TABLE_PREFIX .'mod_wbstats_lang';
$table_browser = TABLE_PREFIX .'mod_wbstats_browser';
$table_hist = TABLE_PREFIX .'mod_wbstats_hist';
$table_loc = TABLE_PREFIX .'mod_wbstats_loc';
$table_utm = TABLE_PREFIX .'mod_wbstats_utm';

const PLATFORM        = 'platform';
const BROWSER         = 'browser';
const BROWSER_VERSION = 'version';


class counter {

	private $ip;
	private $referer = '';
	private $host = '';
	private $referer_host = '';
	private $referer_spam= 0;
	private $page;
	private $keywords = '';
	private $language = '';
	private $agent = '';
	private $browser = '';
	private $browser_version = '';
	private $os = '';
	private $location = '';
	private $response_code;
	private $session;
	private $utm = array("source" => '',"medium" => '',"campaign" => '',"term" => '',"content" => '',);
	
	
	private $time;
	private $day;
	private $month;
	private $old_data;
	private $old_date;
	private $reload;
	private $online;
	
	function __construct() {
		$this->init();
		$this->count();
	}

	function init() {
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $table_browser, $table_hist, $table_loc, $table_utm;
		$time = time();
		$this->time = $time;
		$this->day   = date("Ymd",$time);
		$this->month = date("Ym",$time);
		$this->old_data = strtotime(date("Ymd", mktime(0, 0, 0, date("n"), date("j") - 90, date("Y")))); // 90 days
		$this->old_date = date("Ymd", mktime(0, 0, 0, date("n"), date("j") - 90, date("Y"))); // 90 days
		$this->reload = 3 * 60 * 60 ;
		$this->online = $time - 3 * 60;
		
		// make sure a visitor only once runs the cleanup!
		if(!isset($_SESSION['cleanstats'])) {
			$_SESSION['cleanstats'] = 'done';
			$database->query("DELETE FROM ".$table_ips." WHERE `time` < '".$this->old_data."'");
			$database->query("DELETE FROM ".$table_pages." WHERE `day` < '".$this->old_date."'");
			$database->query("DELETE FROM ".$table_ref.  " WHERE `day` < '".$this->old_date."'");
			$database->query("DELETE FROM ".$table_key.  " WHERE `day` < '".$this->old_date."'");
			$database->query("DELETE FROM ".$table_lang. " WHERE `day` < '".$this->old_date."'");
			$database->query("DELETE FROM ".$table_browser. " WHERE `day` < '".$this->old_date."'");
			$database->query("DELETE FROM ".$table_hist." WHERE `timestamp` < '".$this->old_data."'");
			$database->query("DELETE FROM ".$table_loc." WHERE `timestamp` < '".$this->old_data."'");
			$database->query("DELETE FROM ".$table_utm." WHERE `timestamp` < '".$this->old_data."'");
		}
		$id = $database->get_one("SELECT `id` FROM ".$table_day." WHERE `day` = '".$this->day."'");
		if (!$id) $database->query("INSERT INTO ".$table_day." (day, user, view) values ('".$this->day."', '0', '0')");
	}
	
	function count() {
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $table_browser, $table_hist, $table_utm;
		$this->getHosts();
		$this->getKeywords();
		$this->getSearch ();
		$this->getUTM ();
		if ($this->newUser()) {
			if($this->referer_host && stristr($this->host, $this->referer_host) === false) {
				if(!$id = $database->get_one("SELECT `id` from ".$table_ref." WHERE `referer`='".$this->referer_host."' AND day='".$this->day."'") ) {
					$database->query("INSERT INTO ".$table_ref." (`day`, `referer`, `view`, `spam`) VALUES ('".$this->day."', '".$this->referer_host."', '1','".$this->referer_spam."' )");
				} else { 
					$database->query("UPDATE ".$table_ref." SET `view`=`view`+1 where `id`='$id'");
				}
			}
			if($this->language) {
				if (!$id = $database->get_one("SELECT `id` from ".$table_lang." WHERE `language`='".$this->language."' AND `day`='".$this->day."'")) {
					$database->query("INSERT INTO ".$table_lang." (`day`, `language`, `view`) VALUES ('".$this->day."', '".$this->language."', '1')");
				} else { 
					$database->query("UPDATE ".$table_lang." SET `view`=`view`+1 where `id`='$id'");
				}
			}
			if($this->agent) {
				if (!$id = $database->get_one("SELECT `id` from ".$table_browser." WHERE `agent`='".$this->agent."' AND `day`='".$this->day."'")) {
					$database->query("INSERT INTO ".$table_browser." (`day`, `agent`, `os`, `browser`, `version`, `view`) 
					VALUES ('".$this->day."', '".$this->agent."', '".$this->os."', '".$this->browser."', '".$this->browser_version."', '1')");
				} else { 
					$database->query("UPDATE ".$table_browser." SET `view`=`view`+1 where `id`='$id'");
				}
			}
		} 
		
		if($this->keywords) {
			if (!$id = $database->get_one("SELECT `id` from ".$table_key." WHERE `keyword`='".$this->keywords."' AND `day`='".$this->day."'")) {
				$database->query("INSERT INTO ".$table_key." (`day`, `keyword`, `view`) VALUES ('".$this->day."', '".$this->keywords."', '1')");
			} else { 
				$database->query("UPDATE ".$table_key." SET `view`=`view`+1 where `id`='$id'");
			}
		}
		if($this->page <> "") {
			if (!$id = $database->get_one("SELECT `id` from ".$table_pages." WHERE `page`='".$this->page."' AND `day`='".$this->day."'")) {
				$database->query("INSERT INTO ".$table_pages." (`day`, `page`, `view`) VALUES ('".$this->day."', '".$this->page."', '1')");
			} else { 
				$database->query("UPDATE ".$table_pages." SET `view`=`view`+1 WHERE id='$id'");
			}
			$database->query("INSERT INTO ".$table_hist." (`timestamp`, `page`, `ip`,`session`,`status`) VALUES ('".time()."', '".$this->page."', '".$this->ip."', '".$this->session."', '".$this->response_code."')");

			if ($id = $database->get_one("SELECT `id` from ".$table_utm." WHERE `ip`='".$this->ip."' AND `session`='".$this->session."' AND `day`='".$this->day."'")) {
				$database->query("UPDATE ".$table_utm." SET `pagecount`=`pagecount`+1 WHERE id='$id'");
				if($this->utm['source']) $this->utm['source'] = ''; // count campaign only once. i.e. page refresh
			}
			if($this->utm['source']) {
				$p = parse_url($this->page, PHP_URL_PATH);
				$database->query("INSERT INTO ".$table_utm." 
					(`timestamp`, `ip`, `campaign`, `source`,`medium`,`term`,`content`,`referer`,`day`,`page`,`session`,`pagecount`) 
					VALUES ('".time()."', '".$this->ip."', '".$this->utm['campaign']."', '".$this->utm['source']."', '".$this->utm['medium']."', '".$this->utm['term']."', '".$this->utm['content']."', '".$this->referer_host."', '".$this->day."', '".$p."', '".$this->session."','1')");
			}
		}
		
	}
	function getHosts() {
		global $referer;
		$fp = $this->getRealUserIp(); //. session_id(); 
		if(isset($_SERVER['HTTP_USER_AGENT'])) $fp .= $_SERVER['HTTP_USER_AGENT'];
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $fp .= $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$this->ip = md5($fp); 
        if (defined( 'ORG_REFERER' )) {
            $this->referer = ORG_REFERER;
		} elseif (isset($referer)) {
			$this->referer = $referer;
		} else {
			if(isset($_SERVER['HTTP_REFERER'])) $this->referer = $_SERVER['HTTP_REFERER'];
		} 	
		$this->page = $_SERVER['REQUEST_URI']; 
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$this->language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
		}
		$this->response_code = http_response_code(); // detect 404
		$this->host=$_SERVER["HTTP_HOST"]; 
		if (substr($this->host,0,4) == "www.") $this->host=substr($this->host,4);
		if($this->referer) {
			$this->referer_host = parse_url($this->referer, PHP_URL_HOST); // Referrer Host
			if ($this->referer_host && substr($this->referer_host,0,4) == "www.") $this->referer_host=substr($this->referer_host,4);
		}
		$this->referer = $this->escapeString($this->referer);
		$this->page = $this->escapeString($this->page);
		$this->language = $this->escapeString($this->language);
		$this->referer_host = $this->escapeString($this->referer_host);
		$this->agent = '';
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			$res = $this->parse_user_agent();
			$this->agent = $this->escapeString($_SERVER['HTTP_USER_AGENT']);
			$this->os = $res['platform']; // .' '.$res['platform_version'];
			$this->browser = $res['browser'];
			$this->browser_version = $res['version'];
			/*
			echo '<!-- ';
			print_r($res);
			print_r($this->agent);
			echo ' -->';
			*/
		}
	}
	
	function getRealUserIp(){
		$ip = '';
		switch(true){
			case (!empty($_SERVER['HTTP_X_REAL_IP'])) : $ip = $_SERVER['HTTP_X_REAL_IP']; break;
			case (!empty($_SERVER['HTTP_CLIENT_IP'])) : $ip = $_SERVER['HTTP_CLIENT_IP']; break;
			case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; break;
			default : $ip = $_SERVER['REMOTE_ADDR'];
		}
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			return $ip;
		}
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
			return $ip;
		}
		return '0.0.0.0';
	}
	
	function getKeywords () {
		if($ref = parse_url($this->referer, PHP_URL_QUERY)) {
			parse_str( $ref, $parms );
			if(isset($parms['q']) && $parms['q']!="") $this->keywords = urldecode($parms['q']); 
			//elseif(isset($parms['q'])) 		$this->keywords = 'Searchkey not provided'; 
			elseif(isset($parms['p'])) 		$this->keywords = urldecode($parms['p']); 
			elseif(isset($parms['query'])) 	$this->keywords = urldecode($parms['query']); 
			$this->keywords = $this->escapeString($this->keywords);
		}
	}

	function getSearch () {
		if($ref = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) {
			parse_str( $ref, $parms );
			if(isset($parms['string']))  {
				$this->keywords = "Local search: ".urldecode($parms['string']); 
				$this->keywords = $this->escapeString($this->keywords);
			}
		}
	}
		
	function getUTM () {
		if($ref = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) {
			$p = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

			parse_str( $ref, $parms );
			if(isset($parms['fbclid'])) {
				$this->utm['campaign'] = "Facebook external link";
				$this->utm['source'] = "Facebook";
				$this->utm['medium'] = "External";
				$this->utm['content'] = "FBCLID - ".$p;
			}
			if(isset($parms['gclid'])) {
				$this->utm['campaign'] = "Google external link";
				$this->utm['source'] = "Google";
				$this->utm['medium'] = "External / Advertisement";
				$this->utm['content'] = "GCLID - ".$p;
			}
			if(isset($parms['gbraid'])) {
				$this->utm['campaign'] = "Google advertisement IOS (app)";
				$this->utm['source'] = "Google";
				$this->utm['medium'] = "External / Advertisement";
				$this->utm['content'] = "GBRAID - ".$p;
			}
			if(isset($parms['wbraid'])) {
				$this->utm['campaign'] = "Google advertisement IOS (web)";
				$this->utm['source'] = "Google";
				$this->utm['medium'] = "External / Advertisement";
				$this->utm['content'] = "WBRAID - ".$p;
			}
			
			if(isset($parms['utm_campaign'])) 	$this->utm['campaign'] 	= $this->escapeString(urldecode($parms['utm_campaign'])); 
			if(isset($parms['utm_source'])) 	$this->utm['source'] 	= $this->escapeString(urldecode($parms['utm_source'])); 
			if(isset($parms['utm_medium'])) 	$this->utm['medium'] 	= $this->escapeString(urldecode($parms['utm_medium'])); 
			if(isset($parms['utm_term'])) 		$this->utm['term'] 		= $this->escapeString(urldecode($parms['utm_term'])); 
			if(isset($parms['utm_content'])) 	$this->utm['content']	= $this->escapeString(urldecode($parms['utm_content'])); 
			
			if(!$this->utm['campaign']) $this->utm['campaign'] = $this->utm['source'];
			if(!$this->utm['content']) $this->utm['content'] = $this->utm['source'] . " - No content";
		}
	}
	

	function newUser() {
		global $database, $table_day, $table_ips;
		$this->session = session_id();
		$timeout = time() - $this->reload;
		$loggedin = isset($_SESSION['USER_ID']) ? ", `loggedin`='1'":"";
		if($this->isIgnored()) {
			$this->page = ''; //prevent pagecounting
			return false;
		} elseif($this->isBot()) {
			$database->query("UPDATE ".$table_day." SET `bots`=`bots`+1 WHERE `day`='".$this->day."'");
			$this->page = ''; //prevent pagecounting
			return false;		
		} elseif($this->isSuspected()) {
			$database->query("UPDATE ".$table_day." SET `suspected`=`suspected`+1 WHERE `day`='".$this->day."'");
			$this->page = ''; //prevent pagecounting
			return false;
		} elseif($this->isRefererSpam()) {
			$database->query("UPDATE ".$table_day." SET `refspam`=`refspam`+1 WHERE `day`='".$this->day."'");
			$this->page = ''; //prevent pagecounting
			$this->keywords = ''; //prevent pagecounting
			$this->language = ''; //prevent pagecounting
			return true;
		} elseif(!$id = $database->get_one("SELECT `id` FROM ".$table_ips." WHERE `ip`='".$this->ip."' AND `session`='".$this->session."' AND `time` > '$timeout' ORDER BY `id` DESC LIMIT 1")) {
			$city = $database->escapeString($this->getCountryCode());
			$database->query("INSERT INTO ".$table_ips." (`ip`,`session`, `location`, `time`, `online`,`page`,`last_page`,`pages`,`language`,`os`,`browser`,`referer`,`ua`) VALUES 
				('".$this->ip."', '".$this->session."', '".$city."', '".$this->time."', '".$this->time."', '".$this->page."', '".$this->page."','1','".$this->language."', '".$this->os."', '".$this->browser." (".$this->browser_version.")','".$this->referer_host."','".$this->agent."')");
			$database->query("UPDATE ".$table_day." SET `user`=`user`+1, `view`=`view`+1 WHERE `day`='".$this->day."'");
			return true;
		} else {
			$database->query("UPDATE ".$table_ips." SET `online`='".$this->time."', `last_page`='".$this->page."', `pages`=`pages`+1, `last_status`='".$this->response_code."' $loggedin WHERE `id`='$id'");
			$database->query("UPDATE ".$table_day." SET `view`=`view`+1 WHERE `day`='".$this->day."'");
			return false;
		}
	}
	

	function getCountryCode() {
		global $database, $table_loc;
		$ip = $this->getRealUserIp(); 
		$ipkey = md5($ip);
		if(!$city = $database->get_one("SELECT `location` FROM ".$table_loc." WHERE `ip`='".$ipkey."' LIMIT 1")) {
			if($ipdata = unserialize($this->getUrlContent('http://www.geoplugin.net/php.gp?ip='.$ip))) {
				if(!$ipdata['geoplugin_city'])  $ipdata['geoplugin_city'] = '- unknown -';
				if(!$ipdata['geoplugin_countryCode'])  $ipdata['geoplugin_countryCode'] = '';

				$country_code 	= $ipdata['geoplugin_countryCode'];
				$city 			= $ipdata['geoplugin_city'];
				if($country_code) $city = $city.' ('.$country_code.')';
				$city = $database->escapeString($city);
				$database->query("INSERT INTO ".$table_loc." (`ip`,`location`,`timestamp`) VALUES ('".$ipkey."','".$city."','".time()."') ");
			}
		} else {
			// $city .= ' *';
		}
		return $city;
	}	
	
	function getUrlContent($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'WBStats geoplugin');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$data = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return ($httpcode>=200 && $httpcode<300) ? $data : false;
	}	
	
	function isBot() { 
		if(!isset($_SERVER['HTTP_USER_AGENT'])) return true;
		require('botlist.php');
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 
		if(empty($userAgent)) return true; //Empty useraget is mostly a bot
		foreach($botUserAgents as $botUserAgent) { 
			if(stripos($userAgent, $botUserAgent) !== false) { 
				return true; 
			} 
		} 
		return false; 
	}
	function isSuspected() { 
		if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return true; // assuming no language means no human browser
		if($_SERVER['HTTP_ACCEPT_LANGUAGE'] == "*") return true; // assuming no language means no human browser
		// if($this->referer_host == parse_url(WB_URL, PHP_URL_HOST)) return true; // referer same as website domain
		return false; 
	}
	
	function isRefererSpam() {
		if(!$this->referer_host) return false;
		require('referers.php');
		foreach($spamReferers as $spammer) { 
			if(stripos($this->referer_host, $spammer) !== false) { 
				$this->referer_spam = '1';
				return true; 
			} 
		} 
		return false; 
		
	}

	function isIgnored() {
		global $database, $table_ips;
		$ip = $this->getRealUserIp(); // $_SERVER['REMOTE_ADDR'];
		$ip = $this->escapeString($ip);
		$r = $database->get_one("SELECT `ip` from ".$table_ips." WHERE `ip` = '$ip' AND `session`='ignore'");
		return $r == $ip;		
	}

	function escapeString($string) {	
		global $database;
		if(!is_string($string)) return $string;  // make sure the parameter is a string
		if(is_object($database->DbHandle)) { 
			$rval = $database->escapeString($string);
		} else {
			$rval = mysql_real_escape_string ($string);
		}
		return $rval;
	}



	/**
	 * Parses a user agent string into its important parts
	 *
	 * @param string|null $u_agent User agent string to parse or null. Uses $_SERVER['HTTP_USER_AGENT'] on NULL
	 * @return string[] an array with 'browser', 'version' and 'platform' keys
	 * @throws \InvalidArgumentException on not having a proper user agent to parse.
	 */
	function parse_user_agent( $u_agent = null ) {
		if( $u_agent === null && isset($_SERVER['HTTP_USER_AGENT']) ) {
			$u_agent = (string)$_SERVER['HTTP_USER_AGENT'];
		}

		if( $u_agent === null ) {
			throw new \InvalidArgumentException('parse_user_agent requires a user agent');
		}

		$platform = null;
		$browser  = null;
		$version  = null;

		$empty = array( PLATFORM => $platform, BROWSER => $browser, BROWSER_VERSION => $version );

		if( !$u_agent ) {
			return $empty;
		}

		if( preg_match('/\((.*?)\)/m', $u_agent, $parent_matches) ) {
			preg_match_all(<<<'REGEX'
/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|(Open|Net|Free)BSD|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS|Switch)|Xbox(\ One)?)
(?:\ [^;]*)?
(?:;|$)/imx
REGEX
				, $parent_matches[1], $result);

			$priority = array( 'Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'FreeBSD', 'NetBSD', 'OpenBSD', 'CrOS', 'X11' );

			$result[PLATFORM] = array_unique($result[PLATFORM]);
			if( count($result[PLATFORM]) > 1 ) {
				if( $keys = array_intersect($priority, $result[PLATFORM]) ) {
					$platform = reset($keys);
				} else {
					$platform = $result[PLATFORM][0];
				}
			} elseif( isset($result[PLATFORM][0]) ) {
				$platform = $result[PLATFORM][0];
			}
		}

		if( $platform == 'linux-gnu' || $platform == 'X11' ) {
			$platform = 'Linux';
		} elseif( $platform == 'CrOS' ) {
			$platform = 'Chrome OS';
		}

		preg_match_all(<<<'REGEX'
%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|IceCat|Safari|MSIE|Trident|AppleWebKit|
TizenBrowser|(?:Headless)?Chrome|YaBrowser|Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|Edg|CriOS|UCBrowser|Puffin|OculusBrowser|SamsungBrowser|
Baiduspider|Applebot|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
Valve\ Steam\ Tenfoot|
NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
(?:\)?;?)
(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix
REGEX
			, $u_agent, $result);

		// If nothing matched, return null (to avoid undefined index errors)
		if( !isset($result[BROWSER][0]) || !isset($result[BROWSER_VERSION][0]) ) {
			if( preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result) ) {
				return array( PLATFORM => $platform ?: null, BROWSER => $result[BROWSER], BROWSER_VERSION => empty($result[BROWSER_VERSION]) ? null : $result[BROWSER_VERSION] );
			}

			return $empty;
		}

		if( preg_match('/rv:(?P<version>[0-9A-Z.]+)/i', $u_agent, $rv_result) ) {
			$rv_result = $rv_result[BROWSER_VERSION];
		}

		$browser = $result[BROWSER][0];
		$version = $result[BROWSER_VERSION][0];

		$lowerBrowser = array_map('strtolower', $result[BROWSER]);

		$find = function ( $search, &$key = null, &$value = null ) use ( $lowerBrowser ) {
			$search = (array)$search;

			foreach( $search as $val ) {
				$xkey = array_search(strtolower($val), $lowerBrowser);
				if( $xkey !== false ) {
					$value = $val;
					$key   = $xkey;

					return true;
				}
			}

			return false;
		};

		$findT = function ( array $search, &$key = null, &$value = null ) use ( $find ) {
			$value2 = null;
			if( $find(array_keys($search), $key, $value2) ) {
				$value = $search[$value2];

				return true;
			}

			return false;
		};

		$key = 0;
		$val = '';
		if( $findT(array( 'OPR' => 'Opera', 'UCBrowser' => 'UC Browser', 'YaBrowser' => 'Yandex', 'Iceweasel' => 'Firefox', 'Icecat' => 'Firefox', 'CriOS' => 'Chrome', 'Edg' => 'Edge' ), $key, $browser) ) {
			$version = $result[BROWSER_VERSION][$key];
		} elseif( $find('Playstation Vita', $key, $platform) ) {
			$platform = 'PlayStation Vita';
			$browser  = 'Browser';
		} elseif( $find(array( 'Kindle Fire', 'Silk' ), $key, $val) ) {
			$browser  = $val == 'Silk' ? 'Silk' : 'Kindle';
			$platform = 'Kindle Fire';
			if( !($version = $result[BROWSER_VERSION][$key]) || !is_numeric($version[0]) ) {
				$version = $result[BROWSER_VERSION][array_search('Version', $result[BROWSER])];
			}
		} elseif( $find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS' ) {
			$browser = 'NintendoBrowser';
			$version = $result[BROWSER_VERSION][$key];
		} elseif( $find('Kindle', $key, $platform) ) {
			$browser = $result[BROWSER][$key];
			$version = $result[BROWSER_VERSION][$key];
		} elseif( $find('Opera', $key, $browser) ) {
			$find('Version', $key);
			$version = $result[BROWSER_VERSION][$key];
		} elseif( $find('Puffin', $key, $browser) ) {
			$version = $result[BROWSER_VERSION][$key];
			if( strlen($version) > 3 ) {
				$part = substr($version, -2);
				if( ctype_upper($part) ) {
					$version = substr($version, 0, -2);

					$flags = array( 'IP' => 'iPhone', 'IT' => 'iPad', 'AP' => 'Android', 'AT' => 'Android', 'WP' => 'Windows Phone', 'WT' => 'Windows' );
					if( isset($flags[$part]) ) {
						$platform = $flags[$part];
					}
				}
			}
		} elseif( $find(array( 'Applebot', 'IEMobile', 'Edge', 'Midori', 'Vivaldi', 'OculusBrowser', 'SamsungBrowser', 'Valve Steam Tenfoot', 'Chrome', 'HeadlessChrome' ), $key, $browser) ) {
			$version = $result[BROWSER_VERSION][$key];
		} elseif( $rv_result && $find('Trident') ) {
			$browser = 'MSIE';
			$version = $rv_result;
		} elseif( $browser == 'AppleWebKit' ) {
			if ($platform==null) { $platform='';}
			if( $platform == 'Android' ) {
				$browser = 'Android Browser';
			} elseif( strpos($platform, 'BB') === 0 ) {
				$browser  = 'BlackBerry Browser';
				$platform = 'BlackBerry';
			} elseif( $platform == 'BlackBerry' || $platform == 'PlayBook' ) {
				$browser = 'BlackBerry Browser';
			} else {
				$find('Safari', $key, $browser) || $find('TizenBrowser', $key, $browser);
			}

			$find('Version', $key);
			$version = $result[BROWSER_VERSION][$key];
		} elseif( $pKey = preg_grep('/playstation \d/i', $result[BROWSER]) ) {
			$pKey = reset($pKey);

			$platform = 'PlayStation ' . preg_replace('/\D/', '', $pKey);
			$browser  = 'NetFront';
		}
		$version = intval($version);
		return array( PLATFORM => $platform ?: null, BROWSER => $browser ?: null, BROWSER_VERSION => $version ?: null );
	}
} // end class

