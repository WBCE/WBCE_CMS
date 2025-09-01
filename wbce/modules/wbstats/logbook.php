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
 * @version         0.2.5.7
 * @lastmodified    September 1, 2025
 *
 */

defined('WB_PATH') OR die(header('Location: ../index.php'));

$page = 1;
$session = '';
$entries = $stats->getLogbookEntries();
$page = isset($_GET['p']) ? intval($_GET['p']) : 1 ;
if($page <= 0) $page = 1;
$r = $stats->getLogbook($page);

$prev = $page - 1;
if($prev <= 0) $prev = 1;
$next = $page + 1;

echo '<div id="log">';
echo '<div class="sysmenu log">';
echo "<a href='$module_log_link&p=$prev'><< ".$WS['PAGE']." $prev</a> ";
echo "<a class='right' href='$module_log_link&p=$next'>".$WS['PAGE']." $next >></a> ";
echo "</div>";
echo "<div class='full'>";
echo '<table class="res" width="100%" border="0" cellpadding="5" cellspacing="0">';
foreach($r as $v) {
		$language=$v['language'];
		if (array_key_exists($language,$code2lang)) $language=$code2lang[$language];
		echo '<tr>';
		echo '<td style="width:200px;white-space:nowrap;">';
		echo '<b>'.$WS['LIVE_DATE'].'</b>: '.$v['date'].' '.$v['time'].'<br>';
		echo '<b>'.$WS['LOCATION'].'</b>: '.$v['loc'].'<br>';
		echo '<b>'.$WS['BROWSER'].'</b>: <span class="expandright" title="'.htmlspecialchars($v['ua']).'">'.$v['browser'].' - ';
		echo $v['os'].'</span><br>';
		echo '<b>'.$WS['LANGUAGES'].'</b>: '.$language.'<br>';
		echo '<b>'.$WS['REFERER'].'</b>: '.$v['referer'].'<br>';
		echo '</td>';
		echo '<td>';
		if($cnt = count($v['history'])) {
			echo '<b>'. $cnt .' '.($cnt==1 ? $WS['PAGE']:$WS['PAGES']).''.' </b><br><br>';
			echo '<div style="max-height:485px;overflow:auto;">';
			foreach(array_reverse($v['history']) as $rec => $h) {
				echo '<div class="bookrow '.($rec%2 ? 'odd':'even').'">';
				echo '<b>'.($rec + 1).'</b>: ';
				echo $h['date'].' '.$h['time'].' > ';
				echo $h['page'];
				echo '</div>';
			}
			echo '</div>';
		} else {
			echo '<b> - - </b><br><br>';
		}
		echo '</td>';
		echo '</tr>';
}
echo '</table>';
echo '</div>';
echo '<br><div class="sysmenu log">';
echo "<a href='$module_log_link&p=$prev'><< ".$WS['PAGE']." $prev</a> ";
echo "<a class='right' href='$module_log_link&p=$next'>".$WS['PAGE']." $next >></a> ";
echo "</div>";
echo "</div>";
