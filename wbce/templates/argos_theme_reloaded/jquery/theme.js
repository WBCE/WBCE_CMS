/*** overall needed confirmation **************************************/
function confirm_link(message, url) {
	if (confirm(message)) {
		location.href = url;
	}
}

/*** get the variables and return them as an associative array *********/
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

$(document).ready(function() {

	/*** autoresize height of textareas *******************************/
	autosize(document.querySelectorAll('textarea'));

	/*** enable tabs in textareas (class "tabbed") ********************/
	$(document).delegate('.tabbed', 'keydown', function(e) {
		var keyCode = e.keyCode || e.which;

		if (keyCode == 9) {
			e.preventDefault();
			var start = $(this).get(0).selectionStart;
			var end = $(this).get(0).selectionEnd;

			// set textarea value to: text before caret + tab + text after caret
			$(this).val($(this).val().substring(0, start)
						+ "\t"
						+ $(this).val().substring(end));

			// put caret at right position again
			$(this).get(0).selectionStart =
			$(this).get(0).selectionEnd = start + 1;
		}
	});

	/*** add a click event to close inline msg ************************/
	$('.close').click(function() {
		$(this).parent().hide('slow');
	});

	/*** toggle the advanced links in addons **************************/
	$('#show-advanced a').click(function(e) {
		e.preventDefault();
		$('#advanced-block').toggle();
	});



	/*** toggle the upload fields in media ****************************/
	$('#unzip').click(function() {
		if ($('#file2').css('display') == 'block') {
			for (i=2; i<=10; i++) {
				$('#file' + i).css('display', 'none');
			}
			$('#delzip').show();
		} else {
			for (i=2; i<=10; i++) {
				$('#file' + i).css('display', 'block');
			}
			$('#delzip').hide();
		}
	});

	/*** synchronize the upload target folder in media ****************/
	$('#upload-target').change(function() {
		var targetVal = $(this).val();
		var targetRef = targetVal.substr(6,100);
		$('#target-folder').val(targetVal);
		browse.location.href = 'browse.php?dir=' + targetRef;

	});

	/*** patch some issues for a consistent look as possible **********/

	// hide the breadcrumbs only in settings
	var myKey = /settings/;
	var myStr = window.location.pathname;
	myMatch = myStr.search(myKey);
	if (myMatch != -1) {
		$('h4').each(function() {
			$(this).find('a').each(function() {
				if ($(this).hasClass('internal')) {
					$(this).parent().css('display', 'none');
				}
			});
		});
	}

	// wrap the admintool-modules in admintools -> tool
	$('.adminModuleWrapper').addClass('fg12 content-box legacy top');

	if ($(".adminModuleWrapper")[0]) { // it is an admin-tool

		$('h4').addClass('adminModuleHeader'); // let h4 looks like h2
		var whichTool = getUrlVars()['tool']; // fetch which tool
		if (whichTool == 'outputfilter_dashboard') { // opf-dashboard
			$('button').css('width', 'auto');
			$('input[type=file]').css('height', 'auto');
		}
		if (whichTool == 'wbSeoTool') { // seo-tool
			$('#pageTree').css('margin','0');
		}
		if (whichTool == 'addon_monitor') { // addon-monitor
			$('#sometabs.tabs').css('width', '100%').css('padding', '0');
			$('#tool').hide();
		}
		if (whichTool == 'captcha_control') { // captcha-control
			$('span').css('top','10px');
		}
	}

	// temporarily wrap the page_tree
	$('.pages_list').wrap('<section class="fg12 content-box"></section>');

	// styling for section-info in page -> modify
	$('.section-info').addClass('fg12');

	// wrap the page-modules in pages -> modify
	$('.pageModuleWrapper').addClass('fg12 content-box legacy');

	if ($('.pageModuleWrapper')[0]) { // it is an page-tool
		// add classes to textareas in sitemap-module
		var myStr = $('.section-info').html();

		var myKey = /sitemap/;
		myMatch = myStr.search(myKey);
		if (myMatch != -1) {
			$('textarea').addClass('code tabbed');
		}
		var myKey = /wrapper/;
		myMatch = myStr.search(myKey);
		if (myMatch != -1) {
			$('input[type=text]').css('width', 'auto');
		}
	}



	// wrapper for the edit-forms in pages -> modify
	// not needed if the core-file mod will be accepted !
/*
	var myKey = /pages\/modify.php/;
	var myStr = window.location.pathname;
	myMatch = myStr.search(myKey);
	if (myMatch != -1) {
		$('form').wrap('<section class="fg12 content-box top legacy"></section>');
		$('input[type=submit]').first().before('<hr>');
		$('input[type=button]').first().before('<hr>');
		$('#wbstats_container').wrap('<section class="fg12 content-box top"></section>');
		//$('.topic-modify').wrap('<section class="fg12 content-box top"></section>'); // it's not enough
	}
*/

	// wrap a content-box around the 3rd party admin-tools
	// and do some "cosmetics"
	// not needed if the core-file mod will be accepted !
	/*
	var myKey = /admintools\/tool.php/;
	var myStr = window.location.pathname;
	myMatch = myStr.search(myKey);
	if (myMatch != -1) { // we are in admintools with tool.php
		$('#legacy td.content').addClass('content-box fg12 top legacy');
		$('h2').css('margin', '10px 0 0 0');

		var whichTool = location.search.substr(1);
		if (whichTool != 'tool=addon_monitor' && whichTool != 'tool=droplets') { // !addon-monitor !droplets
			$('input[type=submit]').first().before('<hr>');
		}
		if (whichTool == 'tool=outputfilter_dashboard') { // opf-dashboard
			$('button').css('width', 'auto');
			$('input[type=file]').css('height', 'auto');
		}
		if (whichTool == 'tool=captcha_control') { // captcha-control
			$('span').css('top','10px');
		}
		if (whichTool == 'tool=wbSeoTool') { // seo-tool
			$('#pageTree').css('margin','0');
		}
	}
	*/



});











