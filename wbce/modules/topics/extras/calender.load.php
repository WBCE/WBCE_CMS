<?php

/*
include via code(2) section:

$the_sections = '32,23,5';
$scriptname = '/extras/calender.load.php'; 
include WB_PATH.'/modules/topics'.$scriptname;

===============================================================

*/

if (!isset($all_days)) $all_days = false;


require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

$pA = explode(DIRECTORY_SEPARATOR,dirname(__FILE__));
array_pop ($pA);
$mod_dir = array_pop ($pA );
$tablename = $mod_dir;

if (!class_exists('wb', false)) {
	require_once WB_PATH . '/framework/class.frontend.php';
	$wb = new frontend();
	$wb->get_website_settings();	
}

if (!isset($topics_directory)) {
	require(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
	require(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');
}
$tp_dir = WB_URL.$topics_directory;

$monthArr = array('','Januar','Februar','M&auml;rz','April','Mai','Juni','Juli','August','September', 'Oktober','November','Dezember');     
$dayArr = array('Mo','Di','Mi','Do','Fr','Sa','So');


//Start Kalender Funktionen:

function get_topic_event($d, $dclass, $k, $eventsArr, $tp_dir) {	
	
	$outp = '<div class="day '.$dclass.'">'.$d.'</div>'; //default
	
	if (array_key_exists($k , $eventsArr ) ) {
		$count = count($eventsArr[$k]);
		if ($count > 0) {
			$ebox = '<span class="events-hint" id="tp_events-hint-'.$k.'"><span class="events-box">';		
			foreach ($eventsArr[$k] as $data) {
				$count ++;
				$topic_id = $data[0];
				$active =  $data[1];
				$link =  $data[2];
				$title =  $data[3];
				$link = $tp_dir.$link.PAGE_EXTENSION;
				 
				$ebox .= '<a href="'.$link.'" class="event active'.$active.'">'.$title.'</a>';			
			}
			$ebox .= '</span></span>';
			
			
			$dclass .= ' has_event';
			$outp = '<div onclick="tp_calendar_show(\''.$k.'\')" class="day '.$dclass.' event">'.$d.$ebox.'</div>';
		}	
	} 
	return 	$outp;
   
}


function monthBack( $timestamp ){
    return mktime(0,0,0, date("m",$timestamp)-1,date("d",$timestamp),date("Y",$timestamp) );
}
function yearBack( $timestamp ){
    return mktime(0,0,0, date("m",$timestamp),date("d",$timestamp),date("Y",$timestamp)-1 );
}
function monthForward( $timestamp ){
    return mktime(0,0,0, date("m",$timestamp)+1,date("d",$timestamp),date("Y",$timestamp) );
}
function yearForward( $timestamp ){
    return mktime(0,0,0, date("m",$timestamp),date("d",$timestamp),date("Y",$timestamp)+1 );
}

function getCalender($date,$dayArr, $eventsArr, $tp_dir) {
	
    $sum_days = date('t',$date);
    $LastMonthSum = date('t',mktime(0,0,0,(date('m',$date)-1),1,date('Y',$date)));	
	$curr_month = date('n',$date);	
	$curr_month_before = $curr_month-1; if ($curr_month_before < 1) {$curr_month_before +=12;}
	$curr_month_after = $curr_month+1; if ($curr_month_after > 12) {$curr_month_after -=12;}
    
    foreach( $dayArr as $key => $value ) {
        echo '<div class="day headline">'.$value.'</div>';
    }
    
	$dclass = 'normal';
	
    for( $i = 1; $i <= $sum_days; $i++ ) {
        $day_name = date('D',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));
        $day_number = date('w',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));

        if( $i == 1) {
            $s = array_search($day_name,array('Mon','Tue','Wed','Thu','Fri','Sat','Sun'));
            for( $b = $s; $b > 0; $b-- ) {
                $x = $LastMonthSum-$b+1;
				$k = $curr_month_before.'-'.$x;
				$ebox = get_topic_event($x, 'before', $k, $eventsArr, $tp_dir);			
                echo $ebox;
            }
        } 
        
		if ($dclass == 'current') {$dclass = 'future';}
        if( $i == date('d',$date) && date('m.Y',$date) == date('m.Y')) {$dclass = 'current';}
		$k = $curr_month.'-'.$i;
		$ebox = get_topic_event($i, $dclass, $k, $eventsArr, $tp_dir);		
        echo $ebox;
       
        
        if( $i == $sum_days) {
            $next_sum = (6 - array_search($day_name,array('Mon','Tue','Wed','Thu','Fri','Sat','Sun')));
            for( $c = 1; $c <=$next_sum; $c++) {
				$dclass = 'after future';
				$k = $curr_month_after.'-'.$c;
				$ebox = get_topic_event($c, $dclass, $k, $eventsArr, $tp_dir);
                echo $ebox; 
            }
        }
    }
}



if( isset($_REQUEST['ts'])) {
	$date = (int) $_REQUEST['ts']; $rawoutout = true;
} else {
	$date = time(); $rawoutout = false;
}

if (!isset($the_sections)) {
	if( isset($_REQUEST['s'])) {
		$the_sections = $_REQUEST['s'];		
	} else {
		echo 'No sections given';
		return;
	}
}
$the_sections2 = preg_replace("/[^0-9,.]+/", "", $the_sections); 
if ($the_sections2 != $the_sections) {
	echo 'Wrong Sections '.$the_sections2;
	return;
}

		

$curr_month = date('n',$date);
$mstart = mktime(0,0,0,date('m',$date),1,date('Y',$date));
$qstart = $mstart - (3600 * 24 * 6);
$qend = $mstart + (3600 * 24 * 37);
//echo $mstart;

$the_sections_q = '';
if ( isset($the_sections) AND $the_sections != '' ) {
	$the_sections_q = 'AND section_id IN ('.$the_sections.')';
}

$theq = 'SELECT topic_id,active,published_when,published_until,link,title FROM `'.TABLE_PREFIX.'mod_'.$tablename.'` WHERE active > 3 '.$the_sections_q.' AND published_when > '.$qstart.' AND published_when < '.$qend.'  ORDER BY published_when DESC';
$query_topics = $database->query($theq);
$num_topics = $query_topics->numRows();

$eventsArr = array();
while($topic = $query_topics->fetchRow()) {
	$published_when = $topic['published_when'];
	$k = date('n-j',$published_when);
	
	$topic_id = (int) $topic['topic_id'];
	$active = (int) $topic['active'];
	$link = $topic['link'];
	$title = $topic['title'];
	
	$data = array($topic_id,$active,$link, $title );
	$eventsArr[$k][] = $data;
	
	//Mehrere Tage?
	if ($all_days == true) {
		$published_until = $topic['published_until'];
		if ($published_until > $published_when + 8000 ) { // nicht nur kurz laenger
			$days = floor (($published_until - $published_when) / (3600 * 24));
			if ($days > 0) {
				$dnext = $published_when + (3600 * 24);
				for ($dy = 0; $dy < $days; $dy++) {				
					if ($dnext < $published_until) {
						$k2 = date('n-j',$dnext);
						$eventsArr[$k2][] = $data;
					}
					$dnext = $dnext + (3600 * 24);			
				}
			}
			$k2 = date('n-j',$published_until);
			$eventsArr[$k2][] = $data;
		}
	}		
}

//echo $num_topics;


if ($rawoutout == false) {echo '<div id="topics_calender" class="topics_calender">';}
?>
    <div class="pagination">
        <!-- a href="?timestamp=<?php echo yearBack($date); ?>" class="last">|&laquo;</a --> 
		<a onclick="topics_calender_page(<?php echo monthForward($date); ?>); return false;" style="float:right;" class="monthswitch">&raquo;</a>
        <a onclick="topics_calender_page(<?php echo monthBack($date); ?>); return false;" style="float:left;" class="monthswitch">&laquo;</a>        
        <h3><?php echo $monthArr[date('n',$date)];?> <?php echo date('Y',$date); ?></h3>     
       
        <!-- a href="?timestamp=<?php echo yearForward($date); ?>" class="next">&raquo;|</a -->   
    </div>
    <?php getCalender($date,$dayArr, $eventsArr, $tp_dir); ?>
    <div style="clear:both;"></div>




<?php 
if ($rawoutout == false) {
	echo '</div>';
} else {
	return; //hier Ende
}
?>

<style type="text/css">
.topics_calender {  width:99%; border:1px solid #555;}

.topics_calender .day { display:block; float:left;  width:14.28%; height:30px; padding-top:5px; line-height: 100%; text-align: center;}

.topics_calender .day span { display:block; }
.topics_calender .day.headline {  background: rgba(100,100,100,0.1);    height:20px;}
.topics_calender .day.current { font-weight:bold; font-style:italic;}
.topics_calender .day.future{ font-weight:bold;}
.topics_calender .after, .topics_calender a.before { opacity:0.3; }

.topics_calender .day.has_event {background: rgba(100,100,100,0.2);  opacity:1; cursor:pointer;}
.topics_calender .day:hover {background: rgba(100,100,100,0.4); }

.topics_calender .day .events-hint { display:none; width:20px; margin:0 auto; height:10px; background: #0ff; position:relative; z-index:3000;}
.topics_calender .day:hover .events-hint { display:block; }
.topics_calender .day .events-hint .events-box {position:absolute; top:0; left:-60px; width:130px; padding: 5px; background: #fff; box-shadow: 0 5px 7px 2px rgba(0, 0, 0, 0.4); border-radius:5px; font-size:0.9em; line-height:120%;}
.topics_calender .day .events-hint .events-box a {display:block; padding:4px 0;}

.topics_calender .pagination { height:30px; text-align: center;  font-weight: bold; }
.topics_calender .pagination h3 { margin: 0 10%; padding:8px 0 0 0; line-height:100%; font-size:18px; }
.topics_calender .pagination a.monthswitch  { display:block;  width:9%;  height:30px; background: rgba(100,100,100,0.2); text-decoration:none; line-height:100%; font-size:24px; cursor:pointer;}

</style>

<script>
function topics_calender_page(ts) {
	var cal_url = "<?php echo WB_URL; ?>/modules/<?php echo $mod_dir.$scriptname; ?>?ts="+ts+"&s=<?php echo $the_sections; ?>";
	//alert(cal_url);
	$('#topics_calender').load(cal_url);
}

function tp_calendar_show(k) {
	var id = '#tp_events-hint-'+k;
	$('.events-hint').hide(100);
	$(id).show(100);
}

</script>