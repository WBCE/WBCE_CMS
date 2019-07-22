<?php 
defined('WB_PATH') or exit("Cannot access this file directly"); 

// the following "templates" are here, because we need them twice:
// once for direct output and secondly for the data loaded via Ajax
$tpl_msg_row ='
<tr id="message_id_{{MESSAGE_ID}}" class="even">
	<td>
		<a class="msg_toggle{{SECTION_ID}} msg_toggle" title="{{TEXT_MESSAGE}}-ID: {{MESSAGE_ID}}" href="#message_id_{{MESSAGE_ID}}">
			<span class="icon">&#9993;</span>
			<small>{{TEXT_MESSAGE}}-ID</small>: {{MESSAGE_ID}}
		</a> 
	</td>
	<td align="right">
		<small><i>{{TEXT_SUBMITTED}}: {{SHOW_DATE}}</i></small>
	</td>
	<td align="right" class="delete_submission">
		<a href="{{DELETE_URL}}{{MESSAGE_ID}}" class="del_icon">&times;</a>
	</td>
</tr>
<tr class="msg_text">
	<td colspan="3">{{MSG_DATA}}</td>
</tr>';
$tpl_row_load_more = '<tr>
	<td colspan="3">
		<div class="show_more_main" id="show_more_main{{MESSAGE_ID}}">
			<span id="{{MESSAGE_ID}}"  rel="{{SECTION_ID}}" class="show_more{{SECTION_ID}} load-more " title="Load more: {{NUMBER_LOAD}}">{{TEXT_LOAD_MORE}} &curarr;</span>
			<span class="loading{{SECTION_ID}}" style="display: none;"><span class="loding_txt">Loading...</span></span>
		</div>
		
	</td>
</tr>';

$sub = $mform->get_history($section_id, $number_load);
$msg_id = 0;
foreach ($sub as $msg) {
	$msg_id = $msg['message_id']; // use latest message_id below the loop for "show more"
	$arr_row_replace = array(
		'{{MESSAGE_ID}}'     => $msg_id,
		'{{SECTION_ID}}'     => $section_id,
		'{{SHOW_DATE}}'      => date(DATE_FORMAT.' - '.TIME_FORMAT,$msg['submitted_when']+TIMEZONE),
		'{{TEXT_MESSAGE}}'   => $TEXT['MESSAGE'],
		'{{TEXT_SUBMITTED}}' => $TEXT['SUBMITTED'],
		'{{MSG_DATA}}'       => $msg['data'],
		'{{DELETE_URL}}'     => $delete_url
	);
	echo strtr($tpl_msg_row, $arr_row_replace);
}

// Count all records except already displayed
$totalRowCount = $database->query(
	"SELECT `message_id` 
		FROM `".TABLE_PREFIX."mod_miniform_data` 
		WHERE `section_id` = ".$section_id." AND `message_id` < ".$msg_id." 
		ORDER BY message_id DESC"
)->numRows();

if($totalRowCount > 0){			
	$next_amount = $totalRowCount > $number_load ? $number_load : $totalRowCount;
	$arr_replace = array(
		'{{MESSAGE_ID}}'     => $msg_id,
		'{{SECTION_ID}}'     => $section_id,
		'{{NUMBER_LOAD}}'    => $number_load,
		'{{TEXT_LOAD_MORE}}' => sprintf($MF['TEXT_SHOW_X_MORE'], $next_amount),
	);
	echo strtr($tpl_row_load_more, $arr_replace);
}

if(!isset($js_functions_loaded)){
	// we take care that JS in this if statement is loaded only once
	// even when miniform is used several times on the page
	
	require WB_PATH.'/framework/functions-utf8.php'; // needed for umlauts			
	?>
	<script src="<?=WB_URL?>/modules/miniform/phpjs.js"></script>
	<script>			
	var TPL_MSG_ROW  = <?=json_encode($tpl_msg_row)?>;	
	var TPL_ROW_LOAD_MORE  = <?=json_encode($tpl_row_load_more)?>;	
	var TEXT_ARE_YOU_SURE  = '<?=utf8_fast_entities_to_umlauts($TEXT['ARE_YOU_SURE'])?>';	
	// Ajax Delete Message
	(function($) {
		$.fn.ajaxDeleteRecord = function(options) {

			var aOpts = $.extend({}, $.fn.ajaxDeleteRecord.defaults, options);
			$(this).find('a').removeAttr("href").css('cursor', 'pointer');

			$(this).click(function() {
				var iRecordID  = $(this).parent().attr("id").substring(11);
				var iSectionID = $(this).parent().parent().parent().attr("id").substring(8);
				var oLink      = $(this).find('a');
				var oRecord    = $("tr#" + aOpts.DB_COLUMN +'_'+ iRecordID);

				if (confirm(TEXT_ARE_YOU_SURE)) {

					$.ajax({
						url: "<?=WB_URL ?>/modules/miniform/ajax_delete_message.php",
						type: "POST",
						dataType: 'json',
						
						data: 'purpose=delete_record&action=delete'
							 +'&DB_RECORD_TABLE='+aOpts.DB_RECORD_TABLE
							 +'&DB_COLUMN='+aOpts.DB_COLUMN
							 +'&MODULE='+aOpts.MODULE
							 +'&iRecordID='+iRecordID
							 +'&iSectionID='+iSectionID,
						
						success: function(json_respond) {
							if(json_respond.success == true) {
								oRecord.find("td").animate({backgroundColor:  '#fdbbbb'}, 'slow');
								oRecord.next('tr').fadeOut(1200);
								oRecord.fadeOut(1200);
								//window.location.reload();
							} else {
								alert(json_respond.message);
							}
						}
					});
				}
			});
		}
	})(jQuery);
	
	$(document).ready(function(){				
		// AjaxHelper delete submissions records
		$("td.delete_submission").ajaxDeleteRecord({
			MODULE : 'miniform',
			DB_RECORD_TABLE: 'miniform_data',
			DB_COLUMN: 'message_id',
			sFTAN: ''
		});		
	});
	</script>
<?php 
	$js_functions_loaded = true;
}
?>		
<script>
// Ajax "show more..."
$(document).ready(function(){
	$(".msg_toggle<?=$section_id ?>").click(function(e){
		e.preventDefault();					
		var OPENER = $(this).parent().parent().find('td');
		var open_class = 'msg-open';
		if(OPENER.hasClass(open_class) == false){
			OPENER.addClass(open_class);
		}else{
			OPENER.removeClass(open_class);
		}					
		$(this).closest("tr").next().toggle();
	});
	$(document).on('click','.show_more<?=$section_id?>',function(){
		
		// this script is based on 
		// https://www.codexworld.com/load-more-data-using-jquery-ajax-php-from-database/
		// but it was improved upon in order to have the possibility to use templates 
		// for the rows, thus preventing us from doble maintainance of these HTML chunks
		
		var MESSAGE_ID = $(this).attr('id');
		var SECTION_ID = $(this).attr('rel');
		var LOAD       = $(this).attr('title').split(": ").pop(); //title="Load more posts: <?=$number_load?>"
		$('.show_more'+SECTION_ID).hide();
		$.ajax({
			type:'POST',
			url:'<?=WB_URL ?>/modules/miniform/ajax_load_more.php',
			dataType: 'json',
			data:'message_id='+MESSAGE_ID
				+'&section_id='+SECTION_ID
				+'&load='+LOAD,

			success: function(json_respond) {
				
				if(json_respond.success == true) {					
					$('.loading'+SECTION_ID).show();
					var toHtml = '';
					jQuery.each(json_respond.data, function(index, row) {
						//now you can access properties using dot notation
						var aReplace = {
							'{{SECTION_ID}}'    : SECTION_ID,
							'{{MESSAGE_ID}}'    : row.message_id,
							'{{MSG_DATA}}'      : row.data,
							'{{SHOW_DATE}}'     : row.submitted_when,
							'{{TEXT_MESSAGE}}'  : '<?=$TEXT['MESSAGE']?>',
							'{{TEXT_SUBMITTED}}': '<?=$TEXT['SUBMITTED']?>',
							'{{DELETE_URL}}'    : '<?=$delete_url?>',
							'"even"'            : '"even new-rows"'
						};
						console.log(row);
						if(row.message_id){
							toHtml += replace_placeholders(aReplace, TPL_MSG_ROW);
						}
					});
					if(json_respond.data.load_after){
						var TEXT_SHOW_X_MORE = '<?=$MF['TEXT_SHOW_X_MORE']?>';
						var STARTING_MESSAGE_ID = json_respond.data.load_after;
						var aReplace = {
							'{{MESSAGE_ID}}'    : STARTING_MESSAGE_ID,
							'{{SECTION_ID}}'    : SECTION_ID,
							'{{NUMBER_LOAD}}'   : LOAD,
							'{{TEXT_LOAD_MORE}}': TEXT_SHOW_X_MORE.replace(/%d/g, json_respond.data.next_amount)
						};
						toHtml += replace_placeholders(aReplace, TPL_ROW_LOAD_MORE);
					}
					console.log(MESSAGE_ID);
					$('#show_more_main' + MESSAGE_ID).parent().remove();	
					$('#mf_msgs_'+ SECTION_ID +' tr:last').after(toHtml);
					
					// fade in/out background color of new items
					var $el = $(".new-rows td"),
						x = 1250,
						originalColor = $el.css("background");
					$el.animate({backgroundColor: '#dff7bf'}, 'slow');
					setTimeout(function(){
						$el.animate({backgroundColor:  originalColor}, 'slow');
					}, x);
					$("tr.new-rows").removeClass("new-rows");
					
					
					$(".msg_toggle" + SECTION_ID).click(function(e){
						e.preventDefault();
						$(this).closest("tr").next().toggle();
					});
				} else {
					console.log(json_respond.message);
				}
			}
		});
		
	});				
});
</script>