// up and down arrows
$( "a.down-arr" ).click(function() {
	$(this).parent().parent().parent().insertAfter( $(this).parent().parent().parent().next()). animate({
  backgroundColor: "#ecf0f5"
}, 200).animate({
  backgroundColor: "#fff"
}, 200);
});		
$( "a.up-arr" ).click(function() {
	$(this).parent().parent().parent().insertBefore( $(this).parent().parent().parent().prev()). animate({
  backgroundColor: "#ecf0f5"
}, 200).animate({
  backgroundColor: "#fff"
}, 200);
});	
		
$( "a.addBtnText" ).click(function() {
	addRow(this, 'text');

});
$( "a.addBtnBool" ).click(function() {
	addRow(this, 'bool');
});	

$( "a.addBtnArray" ).click(function() {
	addRow(this, 'text', 'array');
});

// Add Section
$( "a.addBtnSection" ).click(function() {
	var section = prompt(NEW_SECTION_NAME);
	if (!section)
		return;

		var html = '<fieldset id="button_'+section+'">';
		 html = html +  '<legend><span class="secTitle" onclick="$(this).parent().next().slideToggle();">'+section+'</span>';
		 html = html +  '<span class="pull-right btn-group">';
		 html = html +  ' <a class="addBtnText btn btn-info" href="javascript:;" onclick="addRow(this, \'text\');">'+ADD_TEXTFIELD+'</a>';
		 html = html +  ' <a class="addBtnBool btn btn-info"  href="javascript:;" onclick="addRow(this, \'bool\');">'+ADD_CHECKBOX+'</a>';
		 if (ADD_ARRAYS) {
			html = html +  ' <a class="addBtnText btn btn-info" href="javascript:;" onclick="addRow(this, \'text\', \'array\');">'+ADD_ARRAY+'</a>';
		 }
		 html = html +  '</span>';
		 html = html +  '</legend>';
		 html = html +  '<div class="cpForm be_settings"></div>';
		 html = html +  '</fieldset>';
		
	$(this).parents('.editor-container').find('form').prepend(html);
});

// add new row, a new element
function addRow(obj, type, isarray) {
    var name = prompt(NEW_CONFIG_FIELD_NAME);// 'Which is the name of the new config field?');
    if (!name){
		return;
	}
	var section = $(obj).parents('fieldset').find('legend').find('span.secTitle').text();
	if (isarray == 'array') {	
		if (type == 'bool') {
			// bool
			var html = '<label class="col-form-label"><input type="text" class="move-input" size="1"/>';
			  html = html +  name;
			  html = html +  '</label></div><div class="col-md-8">';
			  html = html +  '<div class="form-group vector"><span>';
			  html = html +  '<input class="form-control" type="checkbox" name="ini#'+section+'#'+name+'#'+type+'[]"/>';
			  html = html +  MOVE_ARROWS;
			  html = html +  sDeleteBtn + '</span></div>';
			
		} else {
			// text
			var namekey = prompt(NEW_CONFIG_FIELD_NAME_FIRST);
			
			  var html = '<div class="formRow">';
		     html = html +  '<div class="settingName">';
		     html = html +  '<div>';
			 html = html +  MOVE_ARROWS;
			 html = html + 	sDeleteBtn;
			 html = html +  '<label title="key:  '+namekey+'">'+namekey+'</label>';
			 html = html +  '</div>';
			html = html +  	'</div>';
			 html = html +  '<div class="settingValue">';
			 html = html +  '<label class="array_key">'+namekey+'</label>';
			 html = html + 	'<textarea rows="1" name="ini#'+section+'#'+name+'#'+type+'['+namekey+']"></textarea>';
			 html = html +  '</div>';
			 html = html +  '</div>';
			
		}	
			html = html + '<table width="100%" class="array_add_value"><tbody>';
			 html = html +  '<tr><td align="center">';
			 html = html +  '<a href="javascript:;" class="btn btn-info" onclick="javascript:addArrayRow(this, \'text\');">';
			 html = html +  'Add value';
			 html = html +  '</a>';
			 html = html +  '</td></tr>';
			 html = html +  '</tbody></table>';
			 html = html +  '</div>';
	} else {
		// single elements		
		 var html = '<div class="settingName">';
		 html = html +  '<div>';
		 html = html +  MOVE_ARROWS ;
		 html = html + 	sDeleteBtn;
		 html = html +  '<label title="key:  '+name+'">'+name+'</label>';
		 html = html +  '</div>';
		 html = html +  '</div>';
		 html = html +  '<div class="settingValue">';
		 
		 if (type == 'bool'){
			html = html +  '<input type="hidden" name="ini#'+section+'#'+name+'#bool" value="0"/>';
			html = html +  '<input class="form_checkbox" value="1" type="checkbox" name="ini#'+section+'#'+name+'#bool"/>';
		 } else {
			html = html +  '<textarea rows="1" name="ini#'+section+'#'+name+'#text"></textarea>';
		 }
		 
		 html = html +  	'</div>';
	}
	
	html = '<div class="formRow">' + html +  '</div>';
	$(obj).parents('fieldset').find('.cpForm').append(html);
	$('.move-input:not(.move-initialized)').keydown(function(e) {
			moveOrder(this, e.which);
	});
}


function moveOrder(obj, which) {
	$(obj).addClass('move-initialized');
	if (which == 38) { // UP
		$(obj).parents('.form-group:first').find('.up-arr').click();
		$(obj).focus();
	}
	if (which == 40) {// DOWN
		$(obj).parents('.form-group:first').find('.down-arr').click();
		$(obj).focus();
	}
		
}	

/*

function addArrayRow(obj, type) {
	//alert('hello world');
	var name = $(obj).parents('.form-group').find('.col-form-label').text();
	var namekey = prompt(NEW_CONFIG_FIELD_NAME);
	var section = $(obj).parents('fieldset').find('legend span:last').text();
	var html = '<span><div class="col-md-10"><label class="array_key">'+namekey+'</label><textarea rows="1" class="form-control" name="ini#'+section+'#'+name+'#'+type+'['+namekey+']"></textarea></div>';
	 html = html +  '<div class="col-md-2"><a href="javascript:;" onclick="$(this).parent().parent().insertAfter( $(this).parent().parent().next())" class="down-arr">&darr;</a> <a href="javascript:;" onclick="$(this).parent().parent().insertBefore( $(this).parent().parent().prev())" class="up-arr">&uarr;</a>"'
	if(ENABLE_DELETE){		
		 html = html +  '<a href="javascript:;" class="remove-btn" onclick="$(this).parent().parent().remove();">X</a>"';
	}
	 html = html +  '</div>';
	$(obj).parents('.col-md-8').find('.form-group:first').append(html);
	$('.move-input:not(.move-initialized)').keydown(function(e) {
			moveOrder(this, e.which);
	});
}
*/