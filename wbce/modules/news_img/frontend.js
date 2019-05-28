(function() {
    if(typeof window.jQuery == 'undefined') {
        var script = document.createElement('script');
        script.src = WB_URL + '/include/jquery/jquery-min.js';
        script.type = 'text/javascript';
        script.onload = function() {
            var $ = window.jQuery;
        };
        document.getElementsByTagName('head')[0].appendChild(script);
    }
})();