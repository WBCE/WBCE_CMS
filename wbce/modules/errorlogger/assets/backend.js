/* errorlogger — backend.js
 * - auto-scroll the log to the newest line
 * - client-side live filter for the search box
 * - locale-aware relative time in the table view
 */
(function () {
    'use strict';

    function initAutoScroll() {
        var wrap = document.querySelector('.errorlogger-tool .logviewWrapper');
        if (!wrap) { return; }
        // only auto-scroll the plain/colour views, not the table
        if (wrap.querySelector('table')) { return; }
        setTimeout(function () { wrap.scrollTop = wrap.scrollHeight; }, 150);
    }

    function initFilter() {
        var input = document.getElementById('errlogFilter');
        if (!input) { return; }

        var noRes = document.getElementById('errlogNoResults');
        var rows  = function () {
            return document.querySelectorAll(
                '.errorlogger-tool .logline, .errorlogger-tool .el-logtable tbody tr'
            );
        };
        var t;

        function apply() {
            var q = input.value.trim().toLowerCase();
            var list = rows(), shown = 0;
            for (var i = 0; i < list.length; i++) {
                var hit = q === '' || list[i].textContent.toLowerCase().indexOf(q) !== -1;
                list[i].classList.toggle('el-hidden', !hit);
                if (hit) { shown++; }
            }
            if (noRes) {
                noRes.style.display = (q !== '' && shown === 0 && list.length > 0) ? '' : 'none';
            }
        }

        input.addEventListener('input', function () {
            clearTimeout(t);
            t = setTimeout(apply, 120);
        });
    }

    function initRelTime() {
        var table = document.querySelector('.errorlogger-tool .el-logtable');
        if (!table || typeof Intl === 'undefined' || !Intl.RelativeTimeFormat) { return; }

        var loc = (table.getAttribute('data-locale') || 'en').toLowerCase();
        var rtf;
        try { rtf = new Intl.RelativeTimeFormat(loc, { numeric: 'auto' }); }
        catch (e) {
            try { rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' }); }
            catch (e2) { return; }
        }

        var units = [
            ['year', 31536000], ['month', 2592000], ['week', 604800],
            ['day', 86400], ['hour', 3600], ['minute', 60], ['second', 1]
        ];

        function label(ts) {
            var diff = ts - Math.floor(Date.now() / 1000);
            var abs = Math.abs(diff);
            for (var i = 0; i < units.length; i++) {
                if (abs >= units[i][1] || units[i][0] === 'second') {
                    return rtf.format(Math.round(diff / units[i][1]), units[i][0]);
                }
            }
            return '';
        }

        var els = table.querySelectorAll('.el-ago[data-ts]');
        for (var i = 0; i < els.length; i++) {
            var ts = parseInt(els[i].getAttribute('data-ts'), 10);
            if (ts) {
                var txt = label(ts);
                if (txt) { els[i].textContent = txt; }
            }
        }
    }

    function init() { initAutoScroll(); initFilter(); initRelTime(); }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
