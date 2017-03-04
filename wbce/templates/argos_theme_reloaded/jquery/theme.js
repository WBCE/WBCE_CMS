/*** overall needed confirmation **************************************/
function confirm_link(message, url) {
	if (confirm(message)) {
		location.href = url;
	}
}

/*** get the url variables and return them as an associative array ****/
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

	/******************************************************************
	 * patch some issues for a consistent look as possible            *
	 ******************************************************************/

	// hide the breadcrumbs in settings
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

	// styling for section-info in page -> modify
	$('.section-info').addClass('fg12');

	/*** wrap and patch the admintool-modules in admintools -> tool ***/
	var isMessage = $(".msg-box")[0];
	var isError = $(".error-box")[0];
	if (!isMessage && !isError) {
		$('.adminModuleWrapper').addClass('fg12 content-box legacy top');
	}

	if ($(".adminModuleWrapper")[0]) { // it is an admin-tool
		// let h4 looks like h2
		$('h4').addClass('adminModuleHeader');
		// which tool is loaded?
		var thisTool = $('.adminModuleWrapper');

		// outputfilter_dashboard
		if ($(thisTool).hasClass('outputfilter_dashboard')) {
			$('button').css('width','auto');
			$('input[type=file]').css('height','auto');
		}
		// wbSeoTool
		if ($(thisTool).hasClass('wbSeoTool')) {
			$('#pageTree').css('margin','0');
		}
		// addon_monitor
		if ($(thisTool).hasClass('addon_monitor')) {
			$('#sometabs.tabs').css('width', '100%').css('padding', '0');
			$('#tool').hide();
		}
		// captcha_control
		if ($(thisTool).hasClass('captcha_control')) {
			$('span').css('top','10px');
		}
		// pagecloner
		if ($(thisTool).hasClass('pagecloner')) {
			$('.pages_list table').css('width','100%');
		}

		// to be continued ... ;-)
		//
		//if ($(thisTool).hasClass('')) {
		//}
	}

	/*** wrap the page-modules in pages -> modify *********************/
	$('.pageModuleWrapper').addClass('fg12 content-box legacy');

	if ($('.pageModuleWrapper')[0]) { // it is an page-tool
		// which module is loaded?
		var thisMod = $('.pageModuleWrapper');

		// wrapper
		if ($(thisMod).hasClass('wrapper')) {
			$('input[type=text]').css('width', 'auto');
		}
		// sitemap
		if ($(thisMod).hasClass('sitemap')) {
			$('select').css('width','300px');
			$('input').each(function() {
				if ($(this).attr('name') == 'depth') {
					$(this).attr('type','text').addClass('wdt50');
				}
			});
			$('textarea').addClass('code tabbed');

		}

		// to be continued ... ;-)
		//
		//if ($(thisMod).hasClass('')) {
		//}
	}
});











