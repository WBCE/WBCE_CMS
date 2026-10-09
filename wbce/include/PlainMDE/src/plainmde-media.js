/**
 * include/PlainMDE/src/plainmde-media.js
 *
 * elFinder image picker for the toolbar's image button. Opens
 * modules/elfinder/ef/elfinder_postmessage.php (a generic bridge shared
 * with modules/tiptap_editor - both dropped depending on CKEditor's own
 * callback protocol, which modules/elfinder/ef/elfinder_cke.php requires)
 * and waits for a postMessage back.
 */
var PlainMDEMedia = (function () {
    'use strict';

    var pending = null;

    window.addEventListener('message', function (event) {
        if (event.origin !== window.location.origin) return;
        var data = event.data;
        if (!data || data.wbceMediaPick !== true || typeof pending !== 'function') return;
        var callback = pending;
        pending = null;
        callback(data.url, data.title);
    });

    /**
     * @param {string} bridgeUrl   URL of elfinder_postmessage.php (PlainMDE.mediaUrl)
     * @param {function} onSelect  called with (url, title) once a file is picked
     * @param {string} [existingUrl] URL already in the document — if given,
     *                                the popup jumps straight to that file's
     *                                real folder and highlights it (falling
     *                                back to a filename search server-side
     *                                if that isn't possible — see
     *                                elfinder_postmessage.php), so clicking
     *                                "image" while on an existing image link
     *                                reopens elFinder scoped to it.
     */
    function pick(bridgeUrl, onSelect, existingUrl) {
        pending = onSelect;

        var target = bridgeUrl;
        if (existingUrl) {
            target += (target.indexOf('?') === -1 ? '?' : '&') + 'select=' + encodeURIComponent(existingUrl);
        }

        var w = 900, h = 600;
        var left = Math.max(0, (screen.width - w) / 2);
        var top = Math.max(0, (screen.height - h) / 2);
        var popup = window.open(
            target,
            'PlainMDEMedia',
            'width=' + w + ',height=' + h + ',left=' + left + ',top=' + top + ',resizable=yes,scrollbars=yes'
        );
        if (popup) popup.focus();
    }

    return { pick: pick };
})();
