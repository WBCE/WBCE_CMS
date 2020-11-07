$(document).ready(function () {

    /******************************************************************
     * patch some issues for a consistent look as possible            *
     ******************************************************************/

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
            $('button').css('width', 'auto');
            $('input[type=file]').css('height', 'auto');
        }
        // wbSeoTool
        if ($(thisTool).hasClass('wbSeoTool')) {
            $('#pageTree').css('margin', '0');
        }
        // addon_monitor
        if ($(thisTool).hasClass('addon_monitor')) {
            $('#sometabs.tabs').css('width', '100%').css('padding', '0');
            $('#tool').hide();
        }
        // captcha_control
        if ($(thisTool).hasClass('captcha_control')) {
            $('span').css('top', '10px');
        }
        // pagecloner
        if ($(thisTool).hasClass('pagecloner')) {
            $('.pages_list table').css('width', '100%');
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
            $('input[type=text]').css('width', '300px');
        }
        // sitemap
        if ($(thisMod).hasClass('sitemap')) {
            $('select').css('width', '300px');
            $('input').each(function () {
                if ($(this).attr('name') == 'depth') {
                    $(this).attr('type', 'text').addClass('wdt50');
                }
            });
            $('textarea').addClass('code tabbed');

        }

        // to be continued ... ;-)
        //
        //if ($(thisMod).hasClass('')) {
        //}
    }

    //read the location
    var address = window.location.pathname;

    // handle topics subpages
    var module = /topics/;
    match = address.search(module);
    if (match != -1) {
        $('td.content').addClass('fg12 content-box legacy top');
    }

});

