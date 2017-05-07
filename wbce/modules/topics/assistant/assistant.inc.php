<?php 
$assistanturl = 'assistant/';

echo '<table class="assistantpresets"><tr><td class="ov_presets">Overview Presets:<br/>';
$Arr = array('ov_standard','ov_grid2_thumb','ov_grid3_thumb','ov_grid2_pic','ov_grid3_pic','ov_grid4_pic','ov_collector_sidebar');
foreach($Arr as $pr) {
	echo '<a href="#" onclick="changepresets(\''.$pr.'\', \'as\'); return false;"><img src="'.$assistanturl.$pr.'.png" alt="" title="'.$pr.'"></a>&nbsp;&nbsp;';
}

echo '</td><td>Topic Presets:<br/>';
$Arr = array('tp_standard','tp_simple','tp_simple_wide');
foreach($Arr as $pr) {
	echo '<a href="#" onclick="changepresets(\''.$pr.'\', \'as\'); return false;"><img src="'.$assistanturl.$pr.'.png" alt="" title="'.$pr.'"></a>&nbsp;&nbsp;';
}

echo '</td><td>Prev/Next:<br/>';
$Arr = array('pnsa_standard', 'pnsa_standard-en');
foreach($Arr as $pr) {
	echo '<a href="#" onclick="changepresets(\''.$pr.'\', \'as\'); return false;"><img src="'.$assistanturl.$pr.'.png" alt="" title="'.$pr.'"></a>&nbsp;&nbsp;';
}

echo '</td></tr></table>';
?>