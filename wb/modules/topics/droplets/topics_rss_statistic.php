<?php

/**
 * @link https://addons.phpmanufaktur.de/de/name/topics/about.php
 * @copyright 2012 phpManufaktur
 * @license MIT License (MIT) http://www.opensource.org/licenses/MIT
 */

global $database;

// set the mode for the chart
$chart_mode = (isset($mode) && in_array($mode, array('day', 'week', 'month'))) ? $mode : 'day';
// how many items maximum?
$max_items = (isset($items)) ? (int) $items : 30;
// width of the chart
$chart_width = (isset($width)) ? (int) $width : 450;
// height of the chart
$chart_height = (isset($height)) ? (int) $height : 300;

if ($chart_mode == 'day') {
  $end_date = date('Y-m-d');
  $start_date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-$max_items, date('Y')));
  $SQL = "SELECT `date`,`callers`,`views` FROM `".TABLE_PREFIX."mod_topics_rss_statistic` WHERE `date`>='$start_date' AND `date`<='$end_date'";
  if (null == ($query = $database->query($SQL)))
    return $database->get_error();
  $title = "RSS Statistik - letzte $max_items Tage";
  $data = "['Tag', 'Abonnenten', 'Abrufe']";
  while (false !== ($day = $query->fetchRow(MYSQL_ASSOC))) {
    $data .= sprintf(",['%s',%d,%d]", date('d.m', strtotime($day['date'])), $day['callers'], $day['views']);
  }
}
elseif ($chart_mode == 'week') {
  $end_week = date('W');
  $start_week = date('W')-$max_items;
  $title = "RSS Statistik - letzte $max_items Wochen";
  $data = "['Woche', 'Abonnenten', 'Abrufe']";
  for ($i=$start_week; $i<$end_week+1; $i++) {
    if ($i < 1) {
      $week = 52+$i;
      $year = date('Y')-1;
    }
    else {
      $week = $i;
      $year = date('Y');
    }
    $monday = date('Y-m-d', strtotime("{$year}-W{$week}"));
    $sunday = date('Y-m-d', strtotime("{$year}-W{$week}-7"));
    $SQL = "SELECT sum(`callers`) AS 'sum_callers', sum(`views`) AS 'sum_views' FROM `".TABLE_PREFIX."mod_topics_rss_statistic` WHERE `date`>='$monday' AND `date`<='$sunday'";
    if (null == ($query = $database->query($SQL)))
      return $database->get_error();
    $week_sum = $query->fetchRow(MYSQL_ASSOC);
    $data .= sprintf(",['%02d/%d',%d,%d]", $week, $year, $week_sum['sum_callers'], $week_sum['sum_views']);
  }
}
else {
  // chart mode is month
  $end_month = date('n');
  $start_month = date('n')-$max_items;
  $title = "RSS Statistik - letzte $max_items Monate";
  $data = "['Monat', 'Abonnenten', 'Abrufe']";
  for ($i=$start_month; $i < $end_month+1; $i++) {
    if ($i < 1) {
      $month = 12+($i % 12);
      $year = date('Y')+ ((int) ($i/12))-1;
    }
    else {
      $month = $i;
      $year = date('Y');
    }
    $first_day = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
    $last_day = date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));
    $SQL = "SELECT sum(`callers`) AS 'sum_callers', sum(`views`) AS 'sum_views' FROM `".TABLE_PREFIX."mod_topics_rss_statistic` WHERE `date`>='$first_day' AND `date`<='$last_day'";
    if (null == ($query = $database->query($SQL)))
      return $database->get_error();
    $month_sum = $query->fetchRow(MYSQL_ASSOC);
    $data .= sprintf(",['%02d.%d',%d,%d]", $month, $year, $month_sum['sum_callers'], $month_sum['sum_views']);
  }
}

// build the chart
$content = <<<EOD
<script type="text/javascript" src="https://www.google.com/jsapi"></script><script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          $data
        ]);
        var options = {
          title: '$title'
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
<div id="chart_div" style="width:{$chart_width}px; height:{$chart_height}px;">&nbsp;</div>
EOD;
return $content;