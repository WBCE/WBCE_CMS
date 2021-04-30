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
    var vars = [], hash;
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

    /*** autoresize height of textareas *******************************/
    autosize(document.querySelectorAll('textarea'));

    /*** enable tabs in textareas (class "tabbed") ********************/
    $(document).delegate('.tabbed', 'keydown', function (e) {
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
    $('.close').click(function () {
        $(this).parent().hide('slow');
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

    /*** scroll to top ************************************************/
    $('.scroll-to-top').click(function () {
        $('html, body').animate({scrollTop: (0)}, 'slow');
    });

    /*** check for unsaved changes ************************************/
    /*
    formmodified = 0;
    $('form *').change(function(){
        formmodified = 1;
    });
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (formmodified == 1) {
            return "Nicht gepeicherte Änderungen.\nDie Seite wirklich verlassen?";
        }
    }
    $("input[name='commit']").click(function() {
        formmodified = 0;
    });
    */
});
