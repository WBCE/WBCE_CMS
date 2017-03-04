
$( document ).ready(function() { 
});


var css_paramArr = css_param.split(',');
var css_paramArrOrig = css_param.split(',');
var inputprop = 'color';
var showinfo = false;

if (localStorage['css_param'] && localStorage['css_param'] != '') {
	css_param = localStorage['css_param'];
	css_paramArr = css_param.split(',');	
	for ( var i = 0; i < css_paramArr.length; i++) {
		var f_id = '#colorpicker #colorset_f'+i;
		$(f_id).val('#'+css_paramArr[i]);
	}		
	add_css();
} else {
	showinfo = true;
}


function showcolorchanges() {
	var all_v = [];	
	for ( var i = 0; i < css_paramArr.length; i++) {
		var f_id = '#colorpicker #colorset_f'+i;
		var v = checkcolor( $(f_id).val() );
		all_v.push(v);
	}
	css_param = all_v.join(',');
	console.log(css_param);
	savecolorchanges();
	add_css();		
}

function savecolorchanges() {
	localStorage.setItem('css_param', css_param);
}

function resetcolorchanges() {
	localStorage.setItem('css_param', '');
	window.location.href = '?r='+Math.random();
}

function submitcolorchanges() {
	showcolorchanges();
	savecolorchanges();
	add_css('submit');
	
	
}




function checkcolor(f) {
	//provisorisch
	f = f.replace("#", "");	
	return f;
}
function add_css(plus) {
	var cssurl = TEMPLATE_DIR+'/colorset/colorset.php?f='+css_param;
	if (plus == 'submit') {cssurl += '&do=save'; }
	var csstag = 'link rel="stylesheet"  href="'+cssurl+'" type="text/css" /';

	$('head').append('<'+csstag+'>');
}


function show_colorpicker() {
	$('#colorpicker').toggle(500);
	$('#colorpickericon').toggle(500);
	if (showinfo == true) {show_colorpickerinfo();}
}

function show_colorpickerinfo() {
	$('#colorpicker-info').toggle(500);
	showinfo = false;
}

function toogle_inputs() {
	if (inputprop == 'color') {inputprop = 'text'} else {inputprop = 'color'}
	$( '#colorpicker input' ).each(function( index ) {
	  	 $(this).prop('type', inputprop);
	});	
}