<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.15.0
 * @lastmodified    April 30, 2019
 *
 */


defined('WB_PATH') or exit("Cannot access this file directly"); 

$sLangPath = WB_PATH.'/modules/miniform/languages';
require_once($sLangPath.'/EN.php');
if(LANGUAGE != 'EN'){
	if(file_exists($sFile = $sLangPath.'/'.LANGUAGE.'.php')) {
		require_once($sFile);
	}
}
require_once ('functions.php');

$mform = new mForm();


$number_load = 5; // how many messages should be loaded?



$aMiniforms = $database->get_array("SELECT * FROM `{TP}mod_miniform`");
#debug_dump($aMiniforms);

	
function timeago($iTimestamp) {
	$sRetVal = '';
	if($iTimestamp != NULL){		
	
		$length = array("60","60","24","30","12","10");

		switch(LANGUAGE){
			case 'DE' :
				$aTimeUnits = array(
					'singular' => array("Sekunde", "Minute", "Stunde", "Tag", "Monat", "Jahr"), 
					'plural'   => array("Sekunden", "Minuten", "Stunden", "Tagen", "Monaten", "Jahren"), 
				);					
				$sString = "vor {{AMOUNT}} {{UNITS}}";  // vor 15 Tagen
			break;
			
			case 'NL' :
			break;			
			
			case 'FR' :
			break;
			
			case 'PL' :
				$aTimeUnits = array(
					'singular' => array("sekunda", "minuta", "godzina", "dzień", "miesiąc", "rok"), 
					'plural'   => array("sekund", "minut", "godzin", "dni", "miesięcy", "lat"), 
				);					
				$sString = "{{AMOUNT}} {{UNITS}} temu";  // 15 dni temu
			break;
			
			default:		
				$aTimeUnits = array(
					'singular' => array("second", "minute", "hour", "day", "month", "year"), 
					'plural'   => array("seconds", "minutes", "hours", "days", "months", "years")
				);				
				$sString = "{{AMOUNT}} {{UNITS}} ago";  // 15 days ago
			break;
		}


		$currentTime = time();
		if($currentTime >= $iTimestamp) {
			$diff     = time()- $iTimestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
				$diff = $diff / $length[$i];
			}
			
			$iAmount = round($diff);
			$aRplc = array(
				'AMOUNT' => $iAmount, 
				'UNITS' => $aTimeUnits[($iAmount > 1) ? 'plural' : 'singular'][$i]
			);
			$sRetVal = replace_vars($sString, $aRplc);
		}
	}
	return $sRetVal;
}
if(is_array($aMiniforms)){
	foreach($aMiniforms as $miniform){
		$section_id = $miniform['section_id'];
		$page_id = $database->get_one("SELECT `page_id` FROM `{TP}sections` WHERE `section_id` = ".$section_id);	
		$heighestTimestamp = $database->get_one("SELECT max(`submitted_when`) FROM `{TP}mod_miniform_data` WHERE `section_id` = ".$section_id);
	?>
	<script>
	$(function() {
		$(".msgt<?=$section_id ?> td.line").click(function(e){
			e.preventDefault();
			$(this).children(".msg").slideToggle();
			console.log("click");
		});
	});
	</script>

	<?php 
		$show_messages = false;
		// write/read SESSION to determine whether or not the Submission list should be shown
		if(isset($_GET['show_messages']) && in_array($_GET['show_messages'], array(1,0))){
			// provide it in a way that multiple miniform sections can store their unique toggle state
			if($_GET['section_id'] == $section_id){	
				$_SESSION['show_mf_messages'.$section_id] = $_GET['show_messages'];
			}
		}
		if(isset($_SESSION['show_mf_messages'.$section_id])){
			$show_messages = $_SESSION['show_mf_messages'.$section_id];
		}
		$TEXT['TOGGLE_VIEW'] = $TEXT['SHOW'];
		$toggle = 1;
		if($show_messages == 1){
			$TEXT['TOGGLE_VIEW'] = $TEXT['HIDE'];	
			$toggle = 0;			
		}			
		$sLink = ADMIN_URL."/admintools/tool.php?tool=miniform&%d&section_id=%d&show_messages=%d#mf_msgs_%s";
		$sLink = sprintf($sLink, $page_id, $section_id, $toggle, $section_id);
			
		$msg_count = $mform->count_messages($section_id);
		$count_txt = ($msg_count) > 0 ? $msg_count : '<i>'.strtolower($TEXT['NONE_FOUND']).'</i>';
		$sLinkToSection = ADMIN_URL."/pages/modify.php?page_id=%d&section_id=%d&show_messages=%d#mf_msgs_%d";
		$sLinkToSection = sprintf($sLinkToSection, $page_id, $section_id, 1, $section_id);
		$sLatestMessage = timeago($heighestTimestamp);
		$sLatestMessage = $sLatestMessage == '' ? $TEXT['NONE_FOUND'] : $sLatestMessage;
		$aPage = $database->get_array("SELECT * FROM `{TP}pages` WHERE `page_id` = ".$page_id)[0];
	?>
	<div class="content-box">
	<div  style="padding:0px 7px;">
	<h3>
		<b title="<?=$TEXT['PAGE']?>-ID: #<?=$page_id?>">#<?=$page_id?></b> 
		<span title="<?=$TEXT['PAGE_TITLE']?>: <?=$aPage['page_title']?>"><?=$aPage['page_title']?></span>
		<span style="float:right;"><a href="<?=$sLinkToSection?>">[<?=$MF['GO_TO_SECTION']?>]</a></span>
	</h3>
	<p><span><b><?=$MF['LATEST_MESSAGE']?>:</b> <?=$sLatestMessage?></span></p>
	</div>
	<table id="mf_msgs_<?=$section_id ?>" class="mf_messages" cellpadding="2" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th colspan="2"><?=$MF['RECEIVED'] ?> (<?=$count_txt?>)</th>
				<th colspan="1">
					<?php if($msg_count > 0) { ?>
						<a style="float:right; font-size: 14px;" class="mf-button" href="<?=$sLink?>"><?=$TEXT['TOGGLE_VIEW']?></a>
					<?php }	?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php if($show_messages){ 		
			$delete_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id.'&delete=';
			$delete_url = 'javascript:void(0)';
			include __DIR__ .'/messages_loop.php';
		} ?>
		</tbody>
	</table>
	</div>

	<?php 
	}
}else{
	echo $TEXT['NONE_FOUND'];
}
