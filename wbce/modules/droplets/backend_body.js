if (typeof jQuery != 'undefined') {
    jQuery(document).ready(function ($) {
        if (typeof $.tablesorter != 'undefined') {
            $("#myTable").tablesorter({
                textExtraction: function (node) {
                    var attr = $(node).attr('data-sort-timestamp');
                    if (typeof attr !== 'undefined' && attr !== false) {
                        return attr;
                    }
                    return $(node).text();
                },
                headers: {
                    0: {sorter: false},
                    4: {sorter: false}
                }
            });
        }
        
        // Scroll to latest edited doplet in droplets overview
        if ($(".hilite")[0]){
            $('html, body').animate({
                scrollTop: $('.hilite').offset().top -250
            }, 'slow');
        }

        $('*[data-redirect-location]').on("click", function () {
            var uri = $(this).data('redirect-location');
            if ($(this).data('new-window')) {
                window.open(uri, '_blank');
            } else {
                window.location = uri;
            }
        });
    });
}
