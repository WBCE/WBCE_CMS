$(document).ready(function() {
	$.insert(WB_URL+'/include/jquery/jquery-ui-min.js');
	$.insert(MOD_URL+'/picupload/jcrob/js/jquery.Jcrop.min.js');
});


 
// Remember to invoke within jQuery(window).load(...)
// If you don't, Jcrop may not initialize properly
$(window).load(function(){
	if (typeof settingsRatio == "undefined") { return; }
	
	$('#cropbox').Jcrop({
		onChange: showPreview,
		onSelect: updateCoords,
		aspectRatio: settingsRatio
	});
});

function showPreview(coords) {	
	var imgWidth = $("#cropbox").width();
	var scale = relWidth / imgWidth;
	
	if  (settingsRatio > 1) {
		var rx = thumbSize / coords.w;
		var ry = thumbSize / settingsRatio / coords.h;
	}
	else {
		var rx = thumbSize * settingsRatio / coords.w;
		var ry = thumbSize / coords.h;
	}
	
	$('#preview').css({
		width: Math.round(rx * relWidth / scale) + 'px',
		height: Math.round(ry * relHeight / scale) + 'px',
		marginLeft: '-' + Math.round(rx * coords.x) + 'px',
		marginTop: '-' + Math.round(ry * coords.y) + 'px'
	});

};


function updateCoords(c) {
	var imgWidth = $("#cropbox").width();
	var scale = relWidth / imgWidth;

	$('#x').val(c.x * scale);
	$('#y').val(c.y * scale);
	$('#w').val(c.w * scale);
	$('#h').val(c.h * scale);
};

function checkCoords()
{
	if (parseInt($('#w').val())) return true;
	alert('Please select a crop region then press submit.');
	return false;
};
