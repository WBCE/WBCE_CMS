/*** alternative function to $.insert *********************************/
function include_file(filename, filetype) {
	if (!filetype)
		var filetype = 'js'; //js default filetype

	var th = document.getElementsByTagName('head')[0];
	var s = document.createElement((filetype == "js") ? 'script' : 'link');

	s.setAttribute('type', (filetype == "js") ? 'text/javascript' : 'text/css');

	if (filetype == "css")
		s.setAttribute('rel', 'stylesheet');

	s.setAttribute((filetype == "js") ? 'src' : 'href', filename);
	th.appendChild(s);
}

/*** unknown function *************************************************/
function redirect_to_page(url, timer) {
	setTimeout('self.location.href="' + url + '"', timer);
}

/*** overall needed confirmation **************************************/
function confirm_link(message, url) {
	if (confirm(message)) {
		location.href = url;
	}
}

/*** get the url variables and return them as an associative array ****/
function getUrlVars() {
	var vars = [],
		hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for (var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

$(document).ready(function () {

	/*** include jscalendar css, if needed ****************************/
	if ($(".jcalendar").length) {
		$.insert(WB_URL + "/include/jscalendar/calendar-system.css");
	}

	/*** include jsadmin css, if needed *******************************/
	if ($(".jsadmin").length) {
		$.insert(WB_URL + "/modules/jsadmin/backend.css");
	}

	// make sidebar state sticky --> call plugin sticky elements
	// call this first --> otherwise sidebar could be open for a moment
	$('.stickySidebarElement').stickyElements({
		stickyMethod: 'class',
		stickyFormatClass: 'closedsidebar',
		pageModus: 'domainWide'
	}); // ENDE make sidebar state sticky

	// toggle-action for sidebar
	$('#sidebararea_togglebutton').click(function () {
		if ($(this).parent().hasClass('closedsidebar')) {
			// close sidebar
			$('#pagetopmenu').removeClass('closedsidebar', 1000);
			$('#mainarea').removeClass('closedsidebar', 1000);
			$('#sidebararea').removeClass('closedsidebar', 1000);
			$('#mainmenu').removeClass('closedsidebar', 1000);
			$('#mainmenu ul').removeClass('closedsidebar', 1000, function () {
				$('#userbox').removeClass('closedsidebar', 800);
				$('#systeminfo').removeClass('closedsidebar', 800);
			});
		} else {
			// sidebar is open --> close sidebar
			$('#userbox').addClass('closedsidebar', 800);
			$('#systeminfo').addClass('closedsidebar', 800);
			$('#sidebararea').addClass('closedsidebar', 1000);
			$('#mainmenu ul').addClass('closedsidebar', 1000);
			$('#mainmenu').addClass('closedsidebar', 1000);
			$('#pagetopmenu').addClass('closedsidebar', 1000);
			$('#mainarea').addClass('closedsidebar', 1000);
			sidebararea_close_switch = 0;
		}
	}); // ENDE script toggle action for sidebar

	// pagetopmenu --> check if link is active link and add active class to parent <li>
	var pageurl = window.location.pathname,
		urlRegExp = new RegExp(pageurl.replace(/\/$/, '') + "$");
	$('#pagetopmenu a').each(function () {
		if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
			$(this).parent().addClass('current');
		}
	});

	// special for hard coded page pages --> add class 'page_titel' to first head of page
	var page_url = document.URL;
	var page_url_searchstring = ADMIN_URL + '/pages/index';
	if (page_url.indexOf(page_url_searchstring) != -1) {
		$('h2').first().addClass('id_pages_addpages page_titel');
	} // ENDE special add class page_titel to page pages

	// special for themeboxes --> remove commas after links to format links as buttons
	$('.themebox .tb_content').each(function () {
		var tb_replacestring = $(this).html();
		tb_replacestring = tb_replacestring.replace(/<\/a>\, /g, '</a>');
		$(this).html(tb_replacestring);
	}); // ENDE special remove commas from themeboxes

	/*** enable tabs in textareas (class "tabbed") ********************/
	$(document).delegate('.tabbed', 'keydown', function (e) {
		var keyCode = e.keyCode || e.which;

		if (keyCode == 9) {
			e.preventDefault();
			var start = $(this).get(0).selectionStart;
			var end = $(this).get(0).selectionEnd;

			// set textarea value to: text before caret + tab + text after caret
			$(this).val($(this).val().substring(0, start) +
				"\t" +
				$(this).val().substring(end));

			// put caret at right position again
			$(this).get(0).selectionStart =
				$(this).get(0).selectionEnd = start + 1;
		}
	});

	/*** toggle the advanced links in addons **************************/
	$('#show-advanced a').click(function (e) {
		e.preventDefault();
		$('#advanced-block').toggle();
	});

	/*** toggle the upload fields in media ****************************/
	$('#unzip').click(function () {
		if ($('#file2').css('display') == 'block') {
			for (i = 2; i <= 10; i++) {
				$('#file' + i).css('display', 'none');
			}
			$('#delzip').show();
		} else {
			for (i = 2; i <= 10; i++) {
				$('#file' + i).css('display', 'block');
			}
			$('#delzip').hide();
		}
	});

	/*** synchronize the upload target folder in media ****************/
	$('#upload-target').change(function () {
		var targetVal = $(this).val();
		var targetRef = targetVal.substr(6, 100);
		$('#target-folder').val(targetVal);
		browse.location.href = 'browse.php?dir=' + targetRef;
	});
});