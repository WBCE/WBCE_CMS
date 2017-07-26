Information about this customized jQuery NiceFileInput Version
##############################################################

Issues
##################

Changing between file input fields with tab-key needs two instead of one strike

Styling focus-effect off button seems not to bee possible with css

Solutions
##################

To change between file input fields with one tab:
--------------------------------------------------

In jQuery: Added property [tabindex="-1"] to one element on line 30

var filename = $('<input type="text" readonly="readonly">')
becomes
var filename = $('<input type="text" tabindex="-1" readonly="readonly">')

To style focus effect of button:
---------------------------------

Add additional jQuery code:

	// ad style to button on focus
	$('input.NFI-current').focus(function(){
		$(this).parent().css('color', 'rgba(255,255,255,0.65)');
	});
	// remove style from button on focus
	$('input.NFI-current').blur(function(){
		$(this).parent().css('color', '');
	});