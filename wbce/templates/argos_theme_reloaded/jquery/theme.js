/*** overall needed confirmation **************************************/
function confirm_link(message, url) {
	if (confirm(message)) {
		location.href = url;
	}
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

	/*** handle settings file-perms visibility depending on OS ***/
	// set initial state
	if ($('#os-linux').is(':checked') == true) {
		$('#file-perms').show();
	}
	if ($('#os-windows').is(':checked') == true) {
		$('#file-perms').hide();
	}

	// toggle state
	$('#os-linux').click(function(e) {
		if ($('#os-linux').is(':checked') == true) {
			$('#file-perms').show();
		} else {
			$('#file-perms').hide();
		}
	});
	$('#os-windows').click(function(e) {
		if ($('#os-windows').is(':checked') == true) {
			$('#file-perms').hide();
		} else {
			$('#file-perms').show();
		}
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

	/*** for section-info in page -> modify ***************************/
	$('.section-info').addClass('fg12');


	/*** hide the (unnecessary) breadcrumbs in settings & admintools **/
	$('h4').each(function() {
		$(this).find('a').each(function() {
			if ($(this).hasClass('internal')) {
				$(this).parent().css('display', 'none');
			}
		});
	});

});











