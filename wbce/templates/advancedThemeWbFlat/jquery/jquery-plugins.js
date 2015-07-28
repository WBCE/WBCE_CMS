function include_file(filename, filetype) {

	if(!filetype)
		var filetype = 'js'; //js default filetype

	var th = document.getElementsByTagName('head')[0];
	var s = document.createElement((filetype == "js") ? 'script' : 'link');

	s.setAttribute('type',(filetype == "js") ? 'text/javascript' : 'text/css');

	if (filetype == "css")
		s.setAttribute('rel','stylesheet');

	s.setAttribute((filetype == "js") ? 'src' : 'href', filename);
	th.appendChild(s);

}

function redirect_to_page (url, timer) {
	setTimeout('self.location.href="'+url+'"', timer);
}

$(document).ready(function()
{

        if($(".jcalendar").length) {
            $.insert(WB_URL+"/include/jscalendar/calendar-system.css");
          }

        if($(".jsadmin").length) {
            $.insert(WB_URL+"/modules/jsadmin/backend.css");
          }
	//Add external link class to external links -
	$('a[href^="http://"]').filter(function() {
		//Compare the anchor tag's host name with location's host name
	    return this.hostname && this.hostname !== location.hostname;
	  }).addClass("external").attr("target", "_blank");

	/* Add internal link class to external links -   */
	$('a[href^="http://"]').filter(function() {
		//Compare the anchor tag's host name with location's host name
	    return this.hostname && this.hostname == location.hostname;
	  }).addClass("internal");

	$('form').attr('autocomplete', 'off');

});