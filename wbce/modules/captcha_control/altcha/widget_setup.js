/**
 * ALTCHA widget initialisation helper for WBCE captcha_control.
 *
 * Why a separate file instead of inline <script>?
 * On frontend pages WBCE's template processor performs a greedy replace of
 * all {...} patterns in module output, which destroys any JS curly braces.
 * On admin/login pages the widget HTML is captured via ob_start()/ob_get_clean()
 * before the output filter runs, so I::insertJsCode() items are never emitted.
 * A static <script src="..."> tag survives both code paths unharmed.
 *
 * Per-widget data is carried on the <altcha-widget> element's data-* attributes
 * (plain strings, no curly braces):
 *   data-syncid   — id of the hidden <input> that receives the verified token
 *   data-token    — session token written into that input upon verification
 *   data-hidefooter — "1" to hide the "Powered by ALTCHA" footer line
 *   data-hidelogo   — "1" to hide the ALTCHA logo
 */
(function () {
    'use strict';

    function initWidget(w) {
        var hideFooter = w.getAttribute('data-hidefooter') === '1';
        var hideLogo   = w.getAttribute('data-hidelogo')   === '1';
        var syncId     = w.getAttribute('data-syncid')     || '';
        var token      = w.getAttribute('data-token')      || '';

        // Apply hideFooter / hideLogo via the public configure() API.
        // This replaces the configuration="{...}" HTML attribute approach
        // which was being eaten by WBCE's template processor.
        if ((hideFooter || hideLogo) && typeof w.configure === 'function') {
            var cfg = {};
            if (hideFooter) { cfg.hideFooter = true; }
            if (hideLogo)   { cfg.hideLogo   = true; }
            w.configure(cfg);
        }

        // Fill the hidden sync input when the challenge is solved.
        // This allows legacy module code that checks $_POST['captcha'] to work.
        if (syncId) {
            w.addEventListener('statechange', function (e) {
                if (e.detail && e.detail.state === 'verified') {
                    var f = document.getElementById(syncId);
                    if (f) { f.value = token; }
                }
            });
        }
    }

    function initAll() {
        var widgets = document.querySelectorAll('altcha-widget[data-syncid]');
        for (var i = 0; i < widgets.length; i++) {
            initWidget(widgets[i]);
        }
    }

    // Guard: run immediately if the DOM is ready, otherwise wait.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
}());
