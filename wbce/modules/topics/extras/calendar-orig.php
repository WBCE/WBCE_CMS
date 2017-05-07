<?php
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

function getCalender($date,$headline = array('Mo','Di','Mi','Do','Fr','Sa','So')) {
    $sum_days = date('t',$date);
    $LastMonthSum = date('t',mktime(0,0,0,(date('m',$date)-1),0,date('Y',$date)));
    
    foreach( $headline as $key => $value ) {
        echo "<div class=\"day headline\">".$value."</div>\n";
    }
    
    for( $i = 1; $i <= $sum_days; $i++ ) {
        $day_name = date('D',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));
        $day_number = date('w',mktime(0,0,0,date('m',$date),$i,date('Y',$date)));
        
        if( $i == 1) {
            $s = array_search($day_name,array('Mon','Tue','Wed','Thu','Fri','Sat','Sun'));
            for( $b = $s; $b > 0; $b-- ) {
                $x = $LastMonthSum-$b;
                echo "<div class=\"day before\">".sprintf("%02d",$x)."</div>\n";
            }
        } 
        
        if( $i == date('d',$date) && date('m.Y',$date) == date('m.Y')) {
            echo "<div class=\"day current\">".sprintf("%02d",$i)."</div>\n";
        } else {
            echo "<div class=\"day normal\">".sprintf("%02d",$i)."</div>\n";
        }
        
        if( $i == $sum_days) {
            $next_sum = (6 - array_search($day_name,array('Mon','Tue','Wed','Thu','Fri','Sat','Sun')));
            for( $c = 1; $c <=$next_sum; $c++) {
                echo "<div class=\"day after\"> ".sprintf("%02d",$c)." </div>\n"; 
            }
        }
    }
}
?>
<html>
<head>
<style type="text/css">
body {
    font-family:verdana;
    font-size:12px;
}
a {
    color:black;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
    background: #eaeaea;
}
.calender {
    width:280px;
    border:1px solid black;
}
* html .calender,
* + html .calender {
    width:282px;
}
.calender div.after,
.calender div.before{
    color:silver;
}
.day {
    float:left;
    width:40px;
    height:40px;
    line-height: 40px;
    text-align: center;
}
.day.headline {
    background:silver;
}
.day.current {
    font-weight:bold;
}
.clear {
    clear:left;
}
.pagination {
    text-align: center;
    height:20px;
    line-height:20px;
    font-weight: bold;
}
.pagihead { 
   display:inline-block;
   background: white;
   width: 140px;
   height: 20px;
   color: black;
}
.pagination a {
    width:20px;
    height:20px;
}
</style>
</head>
<body>
<?php

if( isset($_REQUEST['timestamp'])) $date = $_REQUEST['timestamp'];
else $date = time();

$arrMonth = array(
    "January" => "Januar",
    "February" => "Februar",
    "March" => "M&auml;rz",
    "April" => "April",
    "May" => "Mai",
    "June" => "Juni",
    "July" => "Juli",
    "August" => "August",
    "September" => "September",
    "October" => "Oktober",
    "November" => "November",
    "December" => "Dezember"
);
    
$headline = array('Mon','Die','Mit','Don','Fre','Sam','Son');

?>

<div class="calender">
    <div class="pagination">
        <a href="?timestamp=<?php echo yearBack($date); ?>" class="last">|&laquo;</a> 
        <a href="?timestamp=<?php echo monthBack($date); ?>" class="last">&laquo;</a> 
        <div class="pagihead">
           <span><?php echo $arrMonth[date('F',$date)];?> <?php echo date('Y',$date); ?></span>
        </div>
        <a href="?timestamp=<?php echo monthForward($date); ?>" class="next">&raquo;</a>
        <a href="?timestamp=<?php echo yearForward($date); ?>" class="next">&raquo;|</a>  
    </div>
    <?php getCalender($date,$headline); ?>
    <div class="clear"></div>
</div>
</body>
</html>