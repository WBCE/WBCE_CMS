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

defined('WB_PATH') OR die(header('Location: ../index.php'));

$table_day   = TABLE_PREFIX .'mod_wbstats_day';
$table_ips   = TABLE_PREFIX .'mod_wbstats_ips';
$table_pages = TABLE_PREFIX .'mod_wbstats_pages';
$table_ref   = TABLE_PREFIX .'mod_wbstats_ref';
$table_key   = TABLE_PREFIX .'mod_wbstats_keywords';
$table_lang  = TABLE_PREFIX .'mod_wbstats_lang';

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
	
	function __construct() {
		$time = time();
		$this->time = $time;
		$this->day   = date("Ymd",$time);
		$this->month = date("Ym",$time);
		//$this->old_data = mktime(0, 0, 0, date("n"), date("j"), date("Y")) - 48*60*60 ; // 48 hours
		//$this->old_date = date("Ymd", mktime(0, 0, 0, date("n"), date("j") - 7, date("Y"))); // 7 days
		$this->old_data = strtotime(date("Ymd", mktime(0, 0, 0, date("n"), date("j") - 90, date("Y")))); // 90 days
		$this->old_date = date("Ymd", mktime(0, 0, 0, date("n"), date("j") - 90, date("Y"))); // 90 days
		$this->reload = 3 * 60 * 60 ;
		$this->online = $time - 5 * 60;
		$this->cleanup();
	}

	function cleanup() {
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang;
		$database->query("DELETE FROM ".$table_ips." WHERE `time` < '".$this->old_data."'");
		$database->query("DELETE FROM ".$table_pages." WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_ref.  " WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_key.  " WHERE `day` < '".$this->old_date."'");
		$database->query("DELETE FROM ".$table_lang. " WHERE `day` < '".$this->old_date."'");
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

		$result['online']  = $database->get_one("SELECT count(id) from ".$table_ips." WHERE `online` >= '".$this->online."'");
		$result['online_title'] = '';
		$query  = $database->query("SELECT `ip`,`online`,`last_page` from ".$table_ips." WHERE `online` >= '".$this->online."'");
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
		$result['total']   = $database->get_one("SELECT count(id) from ".$table_ips);
		if(!$result['total']) $result['total'] = 1;
		$result['onepage'] = $database->get_one("SELECT count(id) from ".$table_ips." WHERE `online` = `time` ");
		$result['bounced']  = round(($result['onepage']/$result['total'])*100,1);		
		
		$from_day_7 = date("Ymd",$this->time - (7*24*60*60)); // 7 days
		$from_day_30 = date("Ymd",$this->time -(30*24*60*60)); // 30 days
		$to_day   = date("Ymd",$this->time - (24*60*60)); 
		$query = $database->query("SELECT AVG(user) avgu, (sum(view)/sum(user)) pages FROM ".$table_day." WHERE `day`>='$from_day_7' AND `day`<='$to_day'");
		$res = $query->fetchRow();
		$result['avg_7'] = round($res['avgu'],2);
		$result['page_user'] = round($res['pages'],1);
		$result['avg_30'] = round($database->get_one ("SELECT AVG(user) from ".$table_day." WHERE `day`>='$from_day_30' AND `day`<='$to_day'"),2);

		$today = date("Ymd",mktime(0, 0, 0, date("n"), date("j"), date("Y")));
		$query = $database->query("SELECT user, view, bots, refspam FROM ".$table_day." where `day`='$today'");
		$res = $query->fetchRow();
		$result['today']= (int)$res['user'];
		$result['ptoday']= (int)$res['view'];
		$result['btoday']= (int)$res['bots'];
		$result['rtoday']= (int)$res['refspam'];
		
		$yesterday = date("Ymd",mktime(0, 0, 0, date("n"), date("j"), date("Y")) - 24*60*60);
		$query = $database->query("SELECT user, view, bots, refspam FROM ".$table_day." where `day`='$yesterday'");
		$res = $query->fetchRow();
		$result['yesterday']= (int)$res['user'];
		$result['pyesterday']= (int)$res['view'];
		$result['byesterday']= (int)$res['bots'];
		$result['ryesterday']= (int)$res['refspam'];

		
		
		// last 24 hours
		for($hour=23; $hour>=0; $hour--) {
			$start = mktime(date("H")-$hour, 0, 0, date("n"), date("j"), date("Y")) ;
			$end = mktime(date("H")-$hour, 59, 59, date("n"), date("j"), date("Y")) ;
			$result['bar'][$hour]['data'] = $database->get_one("SELECT count(id) FROM ".$table_ips." WHERE `time`>='$start' AND `time`<=$end");
			$result['bar'][$hour]['title'] = date("H:i",$start+TIMEZONE)." - ".date("G:i",$end+TIMEZONE);			
		}
		// last 30 days
		for($day=29; $day>=0; $day--) {
			$theday = date("Ymd", mktime(0, 0, 0, date("n"), date("j")-$day, date("Y")) );
			$query = $database->query("SELECT user, view, bots FROM ".$table_day." WHERE `day` = '$theday'");
			if($query && $query->numRows()) {
				$res = $query->fetchRow();
				$result['days'][$day]['data'] = (int)$res['user'];
				$result['days'][$day]['views'] = (int)$res['view'];
				$result['days'][$day]['tooltip'] = '<br/>'.$res['user'].' '.$WS['VISITORS'].'<br/>'.$res['view'].' '.$WS['PAGES'].' ';
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
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $code2lang;
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
			$percent = ($percent < 0.1 ) ? round($percent,2) : round($percent,1);
			$bar_width = round((100/$totals)*$views);
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
			$percent = ($percent < 0.1 ) ? round($percent,2) : round($percent,1);
			$bar_width = round((100/$totals)*$views);
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
			$keyword = urldecode($res['keyword']);
			$short = (strlen($keyword) > 55) ? substr($keyword,0,50)."...": $keyword;
			$views = $res['views'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? round($percent,2) : round($percent,1);
			$bar_width = round((100/$totals)*$views);
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
			$language=$res['language'];
			if (array_key_exists($language,$code2lang)) $language=$code2lang[$language];
			$short = (strlen($language) > 55) ? substr($language,0,50)."...": $language;
			$views = $res['views'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? round($percent,2) : round($percent,1);
			$bar_width = round((100/$totals)*$views);
			$result['language'][$nr]['short'] = $short;
			$result['language'][$nr]['name'] = $language;
			$result['language'][$nr]['views'] = $views;
			$result['language'][$nr]['percent'] = $percent;
			$result['language'][$nr]['width'] = $bar_width;
			$nr++;
		}
		
		// Entry pages
		$totals = $database->get_one("SELECT count(*) FROM ".$table_ips);
		$q = "SELECT page, COUNT(*) AS total FROM ".$table_ips." GROUP BY page ORDER BY total DESC LIMIT 0,$top";
		$nr = 1;
		$query = $database->query($q);
		while($res = $query->fetchRow()) {
			$views = $res['total'];
			$page = $res['page'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? round($percent,2) : round($percent,1);
			$short = (strlen($page) > 55) ? substr($page,0,50)."...": $page;
			$bar_width = round((100/$totals)*$views);
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
			$page = $res['page'];
			$percent = (100 / $totals) * $views;
			$percent = ($percent < 0.1 ) ? round($percent,2) : round($percent,1);
			$short = (strlen($page) > 55) ? substr($page,0,50)."...": $page;
			$bar_width = round((100/$totals)*$views);
			$result['exit'][$nr]['short'] = $short;
			$result['exit'][$nr]['name'] = $page;
			$result['exit'][$nr]['views'] = $views;
			$result['exit'][$nr]['percent'] = $percent;
			$result['exit'][$nr]['width'] = $bar_width;
			$nr++;
		}

		// aantal pagina's per bezoek
		$q = "SELECT pages FROM ".$table_ips." ORDER BY pages DESC";
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
		$q = "SELECT ROUND(`online` - `time`)  AS `length` FROM ".$table_ips." ORDER BY `length` DESC";
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
		$result['average'] = round($res['avgusr'],2);


		$month = date("Ym%",mktime(0, 0, 0, $show_month, 1, $show_year));
		$query = $database->query("SELECT sum(user) users, sum(view) views, avg(user) avgusr FROM ".$table_day." WHERE day LIKE '$month'");
		$res = $query->fetchRow();
		$result['mvisitors'] = $res['users'];
		$result['mvisits'] = $res['views'];
		$result['maverage'] = round($res['avgusr'],2);
	
		$query = $database->query("SELECT LEFT(day,7) as month, sum(user) as user_month FROM ".$table_day." GROUP BY month ORDER BY user_month DESC LIMIT 1");
		$res = $query->fetchRow();
		$result['max_month'] = 0;
		
		for($month=1; $month<=12; $month++) {
			$sel_month = date("Ym%",mktime(0, 0, 0, $month, 1, $show_year));
			$users = $database->get_one("SELECT sum(user) FROM ".$table_day." WHERE day LIKE '$sel_month'");
			if($result['max_month'] < $users) $result['max_month'] = $users;
			$result['month'][$month]['data'] = $users;
			$result['month'][$month]['title'] = date("M-Y", mktime(0, 0, 0, $month, 1, $show_year));
		}
		
		$month_days=date("t",mktime(0,0,0,$show_month,1,$show_year));
		for($day=1; $day<=$month_days; $day++) {
			$sel_day = date("Ymd",mktime(0, 0, 0, $show_month, $day, $show_year));
			$query = $database->query("SELECT user, view, bots FROM ".$table_day." WHERE `day` = '$sel_day'");
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
		
	function getLive() 	{
		global $database, $table_day, $table_ips, $table_pages, $table_ref, $table_key, $table_lang, $WS;
		$result = array();
		$tmp = array();
		$tmp['num'] = "#";
		$tmp['date'] = $WS['LIVE_DATE'];
		$tmp['time'] = $WS['LIVE_TIME'];
		$tmp['page'] = $WS['LIVE_PAGE'];
		$tmp['count'] = $WS['LIVE_PAGES'];
		$tmp['last'] = $WS['LIVE_LAST'];
		$tmp['duration'] = $WS['LIVE_ONLINE'];
		$result[] = $tmp;
		$count = 1;
		if($query  = $database->query("SELECT * from ".$table_ips." WHERE `online` >= '".$this->online."' ORDER BY `online` DESC")) {
			while($res = $query->fetchRow()) {
				
				$array = explode("/",$res['last_page']);
				$last_item_index = count($array) - 2;
				$array[$last_item_index] = '<b>'.$array[$last_item_index].'</b>';
				$res['last_page'] = implode('/',$array);

				$tmp = array();
				$tmp['num'] = $count++;
				$tmp['date'] = date(DATE_FORMAT,$res['online']+TIMEZONE);
				$tmp['time'] = date(TIME_FORMAT,$res['online']+TIMEZONE);
				$tmp['page'] = $res['last_page'];
				$tmp['count'] = $res['pages'];
				$tmp['last'] = $this->seconds2human(time()-$res['online']);
				$tmp['duration'] = $this->seconds2human(time()-$res['time']);
				$result[] = $tmp;
			}
		}
		return $result;
	}
	function seconds2human($ss) {
		$s = $ss % 60;
		$m = (floor(($ss % 3600)/60)>0)?floor(($ss%3600)/60).' min':'';
		$h = (floor(($ss % 86400) / 3600)>0)?floor(($ss % 86400) / 3600).' hrs':'';
		$d = (floor(($ss % 2592000) / 86400)>0)?floor(($ss % 2592000) / 86400).' days':'';
		$M = (floor($ss / 2592000)>0)?floor($ss / 2592000).' months':'';

		return "$M $d $h $m $s sec";
	}
	
	function mkDay($day) {
		return substr($day,0,4).'-'.substr($day,4,2).'-'.substr($day,-2);
	}

	function shuffle_assoc($list) {
	  if (!is_array($list)) return $list;
	  $keys = array_keys($list);
	  shuffle($keys);
	  $random = array();
	  foreach ($keys as $key) $random[$key] = $list[$key];
	  return $random;
	} 
	
} // end class

