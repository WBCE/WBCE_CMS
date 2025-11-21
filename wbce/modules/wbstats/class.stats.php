<?php
/**
 *
 * @category        admintool
 * @package         wbstats
 * @author          Ruud Eisinga - dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 7 and higher
 * @version         0.2.5.8
 * @lastmodified    November 21, 2025
 *
 */


defined('WB_PATH') OR die(header('Location: ../index.php'));

$table_day   = TABLE_PREFIX .'mod_wbstats_day';
$table_ips   = TABLE_PREFIX .'mod_wbstats_ips';
$table_pages = TABLE_PREFIX .'mod_wbstats_pages';
$table_ref   = TABLE_PREFIX .'mod_wbstats_ref';
$table_key   = TABLE_PREFIX .'mod_wbstats_keywords';
$table_lang  = TABLE_PREFIX .'mod_wbstats_lang';
$table_brwsr = TABLE_PREFIX .'mod_wbstats_browser';
$table_hist = TABLE_PREFIX .'mod_wbstats_hist';
$table_loc = TABLE_PREFIX .'mod_wbstats_loc';
$table_utm = TABLE_PREFIX .'mod_wbstats_utm';

class stats {

	private $ip;
	private $referer;
	private $host;
	private $referer_host;
	private $page;
	private $keywords;
	private $language;
	
	private $time;
	private $day;
	private $month;
	private $old_data;
	private $old_date;
	private $reload;
	private $online;
	
	function __construct($do_clean = true) {
		global $database;
		$database->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$time = time();
		$this->time = $time;
		$this->day   = date("Ymd",$time);
		$this->month = date("Ym",$time);
		$this->old_data = strtotime(date("Ymd", mktime(0, 0, 0, date("n"), date("j") - 90, date("Y")))); // 90 days
		$this->old_date = date("Ymd", mktime(0, 0, 0, date("n"), date("j") - 90, date("Y"))); // 90 days
		$this->reload = 3 * 60 * 60 ;
		$this->online = $time - 5 * 60;
		if($do_clean) $this->cleanup();
	}

	function cleanup() {
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $table_brwsr, $table_hist;
		$database->query("DELETE FROM ".$table_ips." WHERE `session`!='ignore' AND `time` < '".$this->old_data."'");
		$database->query("DELETE FROM ".$table_pages." WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_ref.  " WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_key.  " WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_lang. " WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_brwsr. " WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_hist." WHERE `timestamp` < '".$this->old_data."'");
		$id = $database->get_one("SELECT `id` FROM ".$table_day." WHERE `day` = '".$this->day."'");
		if (!$id) $database->query("INSERT INTO ".$table_day." (day, user, view) values ('".$this->day."', '0', '0')");
	}

	function getStats() {
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $WS;
		$result = array();
		$query = $database->query("SELECT sum(user) visitors, sum(view) visits FROM ".$table_day);
		$res = $query->fetchRow();
		$result['visitors']=$res['visitors'];
		$result['visits']=$res['visits'];

		$result['online']  = $database->get_one("SELECT count(id) from ".$table_ips." WHERE `session`!='ignore' AND `online` >= '".$this->online."'");
		$result['online_title'] = '';
		$query  = $database->query("SELECT `ip`,`online`,`last_page` from ".$table_ips." WHERE `session`!='ignore' AND `online` >= '".$this->online."'");
		if($query) {
			$result['online'] = $query->numRows();
			if($result['online']) {
				$result['online_title'] = '<table class=\'popup\' cellpadding=\'2\'><tr><th colspan=\'3\'>'.$WS['CURRENTONLINE'].'</th></tr>';
				while($res = $query->fetchRow()) {
					$result['online_title']  .= '<tr><td>'.date(DATE_FORMAT,$res['online']+TIMEZONE).'</td><td>'.date(TIME_FORMAT,$res['online']+TIMEZONE).'</td><td>'.$res['last_page'].'</td></tr>';
				}
				$result['online_title']  .= '</table>';
				$result['online_title'] = htmlspecialchars($result['online_title']);
			}
		}		
		$result['total']   = $database->get_one("SELECT count(id) from ".$table_ips." WHERE `session`!='ignore'");
		if(!$result['total']) $result['total'] = 1;
		$result['onepage'] = $database->get_one("SELECT count(id) from ".$table_ips." WHERE `session`!='ignore' AND `online` = `time` ");
		$result['bounced']  = $this->safeRound(($result['onepage']/$result['total'])*100,1);		
		
		$from_day_7 = date("Ymd",$this->time - (7*24*60*60)); // 7 days
		$from_day_30 = date("Ymd",$this->time -(30*24*60*60)); // 30 days
		$to_day   = date("Ymd",$this->time - (24*60*60)); 
		$query = $database->query("SELECT AVG(user) avgu, (sum(view)/sum(user)) pages FROM ".$table_day." WHERE `day`>='$from_day_7' AND `day`<='$to_day'");
		if($res = $query->fetchRow()) {
			$result['avg_7'] = $this->safeRound($res['avgu'],2);
			$result['page_user'] = $this->safeRound($res['pages'],1);
			$result['avg_30'] = $this->safeRound($database->get_one ("SELECT AVG(user) from ".$table_day." WHERE `day`>='$from_day_30' AND `day`<='$to_day'"),2);
		} else {
			$result['avg_7'] = 0;
			$result['page_user'] = 0;
			$result['avg_30'] = 0;
		}

		$today = date("Ymd",mktime(0, 0, 0, date("n"), date("j"), date("Y")));
		$query = $database->query("SELECT user, view, bots, suspected, refspam FROM ".$table_day." where `day`='$today'");
		if($res = $query->fetchRow()) {
			$result['today']= (int)$res['user'];
			$result['ptoday']= (int)$res['view'];
			$result['btoday']= (int)$res['bots']+(int)$res['suspected'];
			$result['rtoday']= (int)$res['refspam'];
		} else {
			$result['today']= 0;
			$result['ptoday']= 0;
			$result['btoday']= 0;
			$result['rtoday']= 0;
		}
		

		$yesterday = date("Ymd",mktime(0, 0, 0, date("n"), date("j"), date("Y")) - 24*60*60);
		$query = $database->query("SELECT user, view, bots, suspected, refspam FROM ".$table_day." where `day`='$yesterday'");
		if($res = $query->fetchRow()) {
			$result['yesterday']= (int)$res['user'];
			$result['pyesterday']= (int)$res['view'];
			$result['byesterday']= (int)$res['bots'] + (int)@$res['suspected'];
			$result['ryesterday']= (int)$res['refspam'];
		} else {
			$result['yesterday'] = 0;
			$result['pyesterday'] = 0;
			$result['byesterday'] = 0;
			$result['ryesterday'] = 0;
		}

		// last 24 hours
		for($hour=23; $hour>=0; $hour--) {
			$start = mktime(date("H")-$hour, 0, 0, date("n"), date("j"), date("Y")) ;
			$end = mktime(date("H")-$hour, 59, 59, date("n"), date("j"), date("Y")) ;
			$result['bar'][$hour]['data'] = $database->get_one("SELECT count(id) FROM ".$table_ips." WHERE `session`!='ignore' AND `time`>='$start' AND `time`<=$end");
			$result['bar'][$hour]['title'] = date("H:i",$start+TIMEZONE)." - ".date("G:i",$end+TIMEZONE);			
		}
		// last 30 days
		for($day=29; $day>=0; $day--) {
			$theday = date("Ymd", mktime(0, 0, 0, date("n"), date("j")-$day, date("Y")) );
			$query = $database->query("SELECT user, view, bots, suspected FROM ".$table_day." WHERE `day` = '$theday'");
			if($query && $query->numRows()) {
				$res = $query->fetchRow();
				$result['days'][$day]['data'] = (int)@$res['user'];
				$result['days'][$day]['views'] = (int)@$res['view'];
				$result['days'][$day]['tooltip'] = '<br/>'.@$res['user'].' '.$WS['VISITORS'].'<br/>'.@$res['view'].' '.$WS['PAGES'].' ';
				$result['days'][$day]['title'] = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$day, date("Y")));
			} else {
				$result['days'][$day]['data'] = 0;
				$result['days'][$day]['views'] = 0;
				$result['days'][$day]['tooltip'] = '';
				$result['days'][$day]['title'] = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$day, date("Y")));
			}
		}
		
		
		return $result;
	}
	
	function getVisitors($top = 10) {
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $table_brwsr, $code2lang;
		$result = array();

		$totals = $database->get_one("SELECT sum(view) FROM ".$table_ref);
		//top referers
		$nr = 1;
		$query = $database->query("SELECT referer, SUM(view) AS views from ".$table_ref." WHERE `spam`='0' GROUP BY referer ORDER BY views DESC LIMIT 0, $top");
		while($res = $query->fetchRow()) {
			$referer = htmlspecialchars($res['referer']);
			$short = (strlen($referer) > 55) ? substr($referer,0,50)."...": $referer;
			$views = $res['views'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$bar_width = $this->safeRound((100/$totals)*$views);
			$result['referer'][$nr]['short'] = $short;
			$result['referer'][$nr]['name'] = $referer;
			$result['referer'][$nr]['views'] = $views;
			$result['referer'][$nr]['percent'] = $percent;
			$result['referer'][$nr]['width'] = $bar_width;
			$nr++;
		}

		$totals = $database->get_one("SELECT sum(view) FROM ".$table_pages);
		$nr = 1;
		$query = $database->query("SELECT page, SUM(view) AS views from ".$table_pages." GROUP BY page ORDER BY views DESC LIMIT 0, $top");
		while($res = $query->fetchRow()) {
			$page = htmlspecialchars($res['page']);
			$short = (strlen($page) > 55) ? substr($page,0,50)."...": $page;
			$views = $res['views'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$bar_width = $this->safeRound((100/$totals)*$views);
			$result['pages'][$nr]['short'] = $short;
			$result['pages'][$nr]['name'] = $page;
			$result['pages'][$nr]['views'] = $views;
			$result['pages'][$nr]['percent'] = $percent;
			$result['pages'][$nr]['width'] = $bar_width;
			$nr++;
		}

		$totals = $database->get_one("SELECT sum(view) FROM ".$table_key);
		$nr = 1;
		$query = $database->query("SELECT keyword, SUM(view) AS views from ".$table_key." GROUP BY keyword ORDER BY views DESC LIMIT 0, $top");
		while($res = $query->fetchRow()) {
			//$keyword = htmlspecialchars(urldecode($res['keyword']));
			$keyword = htmlspecialchars($res['keyword']);
			$short = (strlen($keyword) > 55) ? substr($keyword,0,50)."...": $keyword;
			$views = $res['views'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$bar_width = $this->safeRound((100/$totals)*$views);
			$result['keyword'][$nr]['short'] = $short;
			$result['keyword'][$nr]['name'] = $keyword;
			$result['keyword'][$nr]['views'] = $views;
			$result['keyword'][$nr]['percent'] = $percent;
			$result['keyword'][$nr]['width'] = $bar_width;
			$nr++;
		}


		$totals = $database->get_one("SELECT sum(view) FROM ".$table_lang);
		$nr = 1;
		$query = $database->query("SELECT language, SUM(view) AS views from ".$table_lang." GROUP BY language ORDER BY views DESC LIMIT 0, $top");
		while($res = $query->fetchRow()) {
			$language=htmlspecialchars($res['language']);
			if (array_key_exists($language,$code2lang)) $language=$code2lang[$language];
			$short = (strlen($language) > 55) ? substr($language,0,50)."...": $language;
			$views = $res['views'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$bar_width = $this->safeRound((100/$totals)*$views);
			$result['language'][$nr]['short'] = $short;
			$result['language'][$nr]['name'] = $language;
			$result['language'][$nr]['views'] = $views;
			$result['language'][$nr]['percent'] = $percent;
			$result['language'][$nr]['width'] = $bar_width;
			$nr++;
		}
		
		// Entry pages
		$totals = $database->get_one("SELECT count(*) FROM ".$table_ips." WHERE `session`!='ignore'");
		$q = "SELECT page, COUNT(*) AS total FROM ".$table_ips." WHERE `session`!='ignore' GROUP BY page ORDER BY total DESC LIMIT 0,$top";
		$nr = 1;
		$query = $database->query($q);
		while($res = $query->fetchRow()) {
			$views = $res['total'];
			$page = htmlspecialchars($res['page']);
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$short = (strlen($page) > 55) ? substr($page,0,50)."...": $page;
			$bar_width = $this->safeRound((100/$totals)*$views);
			$result['entry'][$nr]['short'] = $short;
			$result['entry'][$nr]['name'] = $page;
			$result['entry'][$nr]['views'] = $views;
			$result['entry'][$nr]['percent'] = $percent;
			$result['entry'][$nr]['width'] = $bar_width;
			$nr++;
		}
		// Exit pages
		$q = "SELECT last_page as page, COUNT(*) AS total FROM ".$table_ips." WHERE last_page != '' GROUP BY last_page ORDER BY total DESC LIMIT 0,$top";
		$nr = 1;
		$query = $database->query($q);
		while($res = $query->fetchRow()) {
			$views = $res['total'];
			$page = htmlspecialchars($res['page']);
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$short = (strlen($page) > 55) ? substr($page,0,50)."...": $page;
			$bar_width = $this->safeRound((100/$totals)*$views);
			$result['exit'][$nr]['short'] = $short;
			$result['exit'][$nr]['name'] = $page;
			$result['exit'][$nr]['views'] = $views;
			$result['exit'][$nr]['percent'] = $percent;
			$result['exit'][$nr]['width'] = $bar_width;
			$nr++;
		}
		
		// Locations
		$totals = $database->get_one("SELECT count(*) FROM ".$table_ips." WHERE `location`!='' AND `session`!='ignore'");
		//$q = "SELECT session as location, COUNT(*) AS total FROM ".$table_ips." WHERE session != '' AND `session`!='ignore' GROUP BY session ORDER BY total DESC LIMIT 0,$top";
		$q = "SELECT location, COUNT(*) AS total FROM ".$table_ips." WHERE location != '' AND `session`!='ignore' GROUP BY location ORDER BY total DESC LIMIT 0,$top";
		$nr = 1;
		$query = $database->query($q);
		while($res = $query->fetchRow()) {
			$views = $res['total'];
			$location = htmlspecialchars($res['location']);
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$short = (strlen($location) > 55) ? substr($location,0,50)."...": $location;
			$bar_width = $this->safeRound((100/$totals)*$views);
			$result['location'][$nr]['short'] = $short;
			$result['location'][$nr]['name'] = $location;
			$result['location'][$nr]['views'] = $views;
			$result['location'][$nr]['percent'] = $percent;
			$result['location'][$nr]['width'] = $bar_width;
			$nr++;
		}	
		// browsers
		$totals = $database->get_one("SELECT count(*) FROM ".$table_brwsr);
		$q = "SELECT *, COUNT(*) AS total FROM ".$table_brwsr." WHERE `browser` != '' GROUP BY `os`,`browser`,`version` ORDER BY `total` DESC LIMIT 0,$top";
		$nr = 1;
		if($query = $database->query($q)) {
		while($res = $query->fetchRow()) {
			$views = $res['total'];
			$browser = $res['browser'].' '.$res['version'];
			$browser .= $res['os'] ? ' ('.$res['os'].')' : '';
			$browser = htmlspecialchars($browser);
			$short = (strlen($browser) > 55) ? substr($browser,0,50)."...": $browser;
			
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$bar_width = $this->safeRound((100/$totals)*$views);
			
			$result['browser'][$nr]['short'] = $short;
			$result['browser'][$nr]['name'] = $browser;
			$result['browser'][$nr]['views'] = $views;
			$result['browser'][$nr]['percent'] = $percent;
			$result['browser'][$nr]['width'] = $bar_width;
			$nr++;
		}
		}
		//echo $q.'<br>';
		//die("Error: ".$database->get_error());

		// OS
		$q = "SELECT `os`, COUNT(*) AS `total` FROM ".$table_brwsr." WHERE `os` != '' GROUP BY `os` ORDER BY `total` DESC LIMIT 0,$top";
		$nr = 1;
		if($query = $database->query($q)) {
		while($res = $query->fetchRow()) {
			$views = $res['total'];
			$os = htmlspecialchars($res['os']);
			$short = (strlen($os) > 55) ? substr($os,0,50)."...": $os;
			
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? $this->safeRound($percent,2) : $this->safeRound($percent,1);
			$bar_width = $this->safeRound((100/$totals)*$views);
			
			$result['os'][$nr]['short'] = $short;
			$result['os'][$nr]['name'] = $os;
			$result['os'][$nr]['views'] = $views;
			$result['os'][$nr]['percent'] = $percent;
			$result['os'][$nr]['width'] = $bar_width;
			$nr++;
		}
		}
		
		

		// aantal pagina's per bezoek
		$q = "SELECT pages FROM ".$table_ips." WHERE `session`!='ignore' ORDER BY pages DESC";
		$query = $database->query($q);
		
		$result['pageviews'][1] = 0;
		$result['pageviews'][2] = 0;
		$result['pageviews'][3] = 0;
		$result['pageviews'][4] = 0;
		$result['pageviews'][5] = 0;
		$result['pageviews'][7] = 0;
		$result['pageviews'][10] = 0;
		$result['pageviews'][15] = 0;
		$result['pageviews'][20] = 0;
		$result['pageviews'][25] = 0;
		
		while($res = $query->fetchRow()) {
			$pages = $res['pages'];
			switch (true) {
				case ($pages >= 25): $result['pageviews'][25]++;break;
				case ($pages >= 20): $result['pageviews'][20]++;break;
				case ($pages >= 15): $result['pageviews'][15]++;break;
				case ($pages >= 10): $result['pageviews'][10]++;break;
				case ($pages >= 7) : $result['pageviews'][7]++;break;
				case ($pages >= 5) : $result['pageviews'][5]++;break;
				case ($pages == 4) : $result['pageviews'][4]++;break;
				case ($pages == 3) : $result['pageviews'][3]++;break;
				case ($pages == 2) : $result['pageviews'][2]++;break;
				case ($pages == 1) : $result['pageviews'][1]++;break;
			}
		}	
		
		// tijd per bezoek
		$q = "SELECT ROUND(`online` - `time`)  AS `length` FROM ".$table_ips." WHERE `session`!='ignore' ORDER BY `length` DESC";
		$query = $database->query($q);
		
		$result['seconds'][0] = 0;
		$result['seconds'][10] = 0;
		$result['seconds'][30] = 0;
		$result['seconds'][60] = 0;
		$result['seconds'][120] = 0;
		$result['seconds'][240] = 0;
		$result['seconds'][420] = 0;
		$result['seconds'][600] = 0;
		$result['seconds'][900] = 0;
		$result['seconds'][1800] = 0;
		
		while($res = $query->fetchRow()) {
			$length = (int)$res['length'];
			switch (true) {
				case ($length >= 1800): $result['seconds'][1800]++;break;
				case ($length >= 900) : $result['seconds'][900]++;break;
				case ($length >= 600) : $result['seconds'][600]++;break;
				case ($length >= 420) : $result['seconds'][420]++;break;
				case ($length >= 240) : $result['seconds'][240]++;break;
				case ($length >= 120) : $result['seconds'][120]++;break;
				case ($length >= 60)  : $result['seconds'][60]++;break;
				case ($length >= 30)  : $result['seconds'][30]++;break;
				case ($length >= 10)  : $result['seconds'][10]++;break;
				case ($length >= 0)   : $result['seconds'][0]++;break;
			}
		}	
		
		return $result;
	}

	function getHistory($show_month,$show_year) {
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $WS;
		$result = array();

		$query = $database->query("SELECT sum(user) users, sum(view) views, min(day) since, avg(user) avgusr FROM ".$table_day);
		$res = $query->fetchRow();
		$result['visitors'] = $res['users'];
		$result['visits'] = $res['views'];
		$result['since'] = $this->mkDay($res['since']);
		$result['average'] = $this->safeRound($res['avgusr'],2);


		$month = date("Ym%",mktime(0, 0, 0, $show_month, 1, $show_year));
		$query = $database->query("SELECT sum(user) users, sum(view) views, avg(user) avgusr FROM ".$table_day." WHERE `day` LIKE '$month'");
		$res = $query->fetchRow();
		$result['mvisitors'] = $res['users'];
		$result['mvisits'] = $res['views'];
		$result['maverage'] = $this->safeRound($res['avgusr'],2);
	
		$query = $database->query("SELECT LEFT(day,7) as month, sum(user) as user_month FROM ".$table_day." GROUP BY `month` ORDER BY `user_month` DESC LIMIT 1");
		$res = $query->fetchRow();
		$result['max_month'] = 0;
		
		for($month=1; $month<=12; $month++) {
			$sel_month = date("Ym%",mktime(0, 0, 0, $month, 1, $show_year));
			$users = $database->get_one("SELECT sum(user) FROM ".$table_day." WHERE `day` LIKE '$sel_month'");
			if($result['max_month'] < $users) $result['max_month'] = $users;
			$result['month'][$month]['data'] = $users;
			$result['month'][$month]['title'] = date("M-Y", mktime(0, 0, 0, $month, 1, $show_year));
		}
		
		$month_days=date("t",mktime(0,0,0,$show_month,1,$show_year));
		for($day=1; $day<=$month_days; $day++) {
			$sel_day = date("Ymd",mktime(0, 0, 0, $show_month, $day, $show_year));
			$query = $database->query("SELECT user, view, bots, suspected FROM ".$table_day." WHERE `day` = '$sel_day'");
			if($query && $query->numRows()) {
				$res = $query->fetchRow();
				$result['days'][$day]['data'] = (int)$res['user'];
				$result['days'][$day]['views'] = (int)$res['view'];
				$result['days'][$day]['tooltip'] = '<br/>'.$res['user'].' '.$WS['VISITORS'].'<br/>'.$res['view'].' '.$WS['PAGES'].' ';
				$result['days'][$day]['title'] = date("Y-m-d", mktime(0, 0, 0, $show_month, $day, $show_year));;
			} else {
				$result['days'][$day]['data'] = 0;
				$result['days'][$day]['views'] = 0;
				$result['days'][$day]['tooltip'] = '';
				$result['days'][$day]['title'] = date("Y-m-d", mktime(0, 0, 0, $show_month, $day, $show_year));
			}
		}
		return $result;
	}
	function hasCampaigns() {
		global $database, $table_utm;
		$count = $database->get_one("SELECT count(*) from ".$table_utm);
		return $count ? true : false;
	}
	
	function getCampaigns() {
		global $database, $table_utm, $WS;
		$result = array();		
		if($query  = $database->query("SELECT *, count(*) as totalcount, sum(pagecount) as pages, sum(pagecount = 1) as bounces, min(day) as first, max(day) as last from ".$table_utm." 
			GROUP BY `campaign`,`medium`,`source`,`term`,`content` 
			ORDER BY `last` DESC, `totalcount` DESC, `campaign`, `content`, `source`")) {
			while($res = $query->fetchRow(MYSQLI_ASSOC)) {
				$tmp = array();
				$tmp['first'] = $res['first'];
				$tmp['last'] = $res['last'];
				$tmp['totalcount'] = $res['totalcount'];
				$tmp['pages'] = $res['pages'];
				$tmp['bounces'] = $res['bounces'];
				$tmp['bounce_perc'] = $this->calc_percentage($res['bounces'],$res['totalcount']);
				$tmp['pages_visit'] =  number_format(($res['pages'] / $res['totalcount']),2);
				//$tmp['pages_visit'] =  number_format(($res['pages']-$res['bounces']) / ($res['totalcount']-$res['bounces']),1);
				$tmp['source'] = $res['source'];
				$tmp['term'] = $res['term'];
				$tmp['content'] = $res['content'];
				$result [$res['campaign']] [$res['content']] [$res['medium']] = $tmp;
			}		
		}
		return $result;
	}
		
	function getLive() 	{
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $WS;
		$result = array();
		$tmp = array();
		$tmp['num'] = "#";
		$tmp['vis'] = 'header';
		$tmp['date'] = $WS['LIVE_DATE'];
		$tmp['time'] = $WS['LIVE_TIME'];
		$tmp['page'] = $WS['LIVE_PAGE'];
		$tmp['count'] = $WS['LIVE_PAGES'];
		$tmp['last'] = $WS['LIVE_LAST'];
		$tmp['duration'] = $WS['LIVE_ONLINE'];
		$tmp['loc'] = $WS['LOCATION'];
		$result[] = $tmp;
		$count = 1;
		//if($query  = $database->query("SELECT * from ".$table_ips." WHERE `online` >= '".$this->online."' AND `session`!='ignore' ORDER BY `online` DESC")) {
		if($query  = $database->query("SELECT * from ".$table_ips." WHERE `session`!='ignore' ORDER BY `online` DESC LIMIT 0, 50 ")) {
			while($res = $query->fetchRow()) {
				
				$array = explode("/",$res['last_page']);
				$last_item_index = count($array) - 2;
				if($last_item_index > 0) {
					$array[$last_item_index] = '<b>'.$array[$last_item_index].'</b>';
				}
				$res['last_page_b'] = implode('/',$array);

				$tmp = array();
				$tmp['num'] = $count++;
				$tmp['vis'] = $res['online'] >= $this->online ? 'online':'old';
				$tmp['date'] = date(DATE_FORMAT,$res['online']+TIMEZONE);
				$tmp['time'] = date(TIME_FORMAT,$res['online']+TIMEZONE);
				$tmp['page'] = '<a href="'.$res['last_page'].'" target="_blank">'.substr($res['last_page_b'],0,70).'</a>';
				if($res['last_status'] && $res['last_status'] != 200) $tmp['page'] .= ' <span style="float:right;font-size:80%;color:red">&nbsp; ['.$res['last_status'].']</span>';

				$tmp['count'] = $res['pages'];
				$tmp['last'] = $this->seconds2human(time()-$res['online']);
				$tmp['duration'] = $this->seconds2human($res['online']-$res['time']);
				$tmp['loc'] = stripslashes($res['location']);
				$result[] = $tmp;
			}
		}
		return $result;
	}
	
	function getLogbookEntries() {
		global $database, $table_ips;
		$count  = $database->get_one("SELECT count(ip) from ".$table_ips." WHERE `session`!='ignore'");
		return $count;
	}
	
	function getLogbook($page = 1) {
		global $database,$table_ips, $table_hist, $WS;
		$start = ($page - 1) * 50;
		$result = array();
		if($query  = $database->query("SELECT *, max(`online`) as online from ".$table_ips." WHERE `session`!='ignore' GROUP BY `ip` ORDER BY `online` DESC  LIMIT $start, 50")) {
		while($res = $query->fetchRow()) {			
				$tmp = array();
				$tmp['vis'] = $res['online'] >= $this->online ? 'online':'old';
				$tmp['date'] = date(DATE_FORMAT,$res['online']+TIMEZONE);
				$tmp['time'] = date(TIME_FORMAT,$res['online']+TIMEZONE);
				$tmp['page'] = '<a href="'.$res['last_page'].'" target="_blank">'.substr($res['last_page'],0,70).'</a>';
				$tmp['count'] = $res['pages'];
				$tmp['last'] = $this->seconds2human(time()-$res['online']);
				$tmp['duration'] = $this->seconds2human($res['online']-$res['time']);
				$tmp['loc'] = stripslashes($res['location']);
				$tmp['browser'] = $res['browser'];
				$tmp['language'] = $res['language'];
				$tmp['os'] = $res['os'];
				$tmp['referer'] = $res['referer'];
				$tmp['history'] = $this->getLogSession($res['ip'],$res['session']);
				$tmp['ua'] = $res['ua'];
				$result[] = $tmp;
			}
		}
		//echo "Error: ".$database->get_error();
		return $result;
	}

	function getLogSession($ip, $session = '') {
		global $database,$table_ips, $table_hist, $WS;
		$result = array();
		if($query  = $database->query("SELECT * from ".$table_hist." WHERE `ip`='$ip' ORDER BY `timestamp` ASC LIMIT 2500")) {
			while($res = $query->fetchRow()) {	
				if(!isset($sess)) $sess = '-';
				$tmp = array();
				$tmp['date'] = date(DATE_FORMAT,$res['timestamp']+TIMEZONE);
				$tmp['time'] = date(TIME_FORMAT,$res['timestamp']+TIMEZONE);
				$tmp['page'] = '<a href="'.$res['page'].'" target="_blank">'.substr($res['page'],0,70).'</a>';
				if($res['status'] && $res['status'] != 200) $tmp['page'] .= ' <span style="float:right; font-size:80%;color:red">&nbsp; ['.$res['status'].']</span>';
				if ($sess != $res['session']) {
					$sess = $res['session'];
					$tmp['page'] .= '<span style="float:right; font-size:80%;">&nbsp; <b>New session</b></span>';
				}
				$result[] = $tmp;
				//$new[] = $tmp;
				//$result = $new + $result;
			}
		}
		return $result;
	}
	
	
	
	function getIgnores() {
		global $database, $table_ips;
		$result = array();
		if($query  = $database->query("SELECT `ip` from ".$table_ips." WHERE `session` = 'ignore'")) {
			while($res = $query->fetchRow()) {
				$result[] = $res['ip'];
			}
		}
		return $result;		
	}
	
	function setIgnores($data = array()) {
		global $database, $table_ips;
		$database->query("DELETE FROM ".$table_ips." WHERE `session` = 'ignore'");
		foreach($data as $ip) {
			if(filter_var($ip, FILTER_VALIDATE_IP)) {
				$ip = $database->escapeString(trim($ip));
				$database->query("INSERT INTO ".$table_ips." (`ip`,`session`,`time`) values ('$ip','ignore','".time()."');"); 
			}
		}
	}

	function seconds2human($ss) {
		$s = $ss % 60;
		$m = (floor(($ss % 3600)/60)>0)?floor(($ss%3600)/60).' min':'';
		$h = (floor(($ss % 86400) / 3600)>0)?floor(($ss % 86400) / 3600).' hrs':'';
		$d = (floor(($ss % 2592000) / 86400)>0)?floor(($ss % 2592000) / 86400).' days':'';
		$M = (floor($ss / 2592000)>0)?floor($ss / 2592000).' months':'';

		return "$M $d $h $m $s sec";
	}
	
	function safeRound($number,$precision = 0, $mode = PHP_ROUND_HALF_UP) {
		if(!$number) $number = 0;
		return round($number,$precision,$mode);
	}
	
	function mkDay($day) {
		return substr($day,0,4).'-'.substr($day,4,2).'-'.substr($day,-2);
	}

	function calc_percentage($num_amount, $num_total) {
		$count1 = $num_amount / $num_total;
		$count2 = $count1 * 100;
		$count = number_format($count2, 0);
		return $count;
	}

	function shuffle_assoc($list) {
	  if (!is_array($list)) return $list;
	  $keys = array_keys($list);
	  shuffle($keys);
	  $random = array();
	  foreach ($keys as $key) $random[$key] = $list[$key];
	  return $random;
	} 
	
	function _getRealUserIp(){
		switch(true){
			case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
			case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
			case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
			default : return $_SERVER['REMOTE_ADDR'];
		}
	}
} // end class

