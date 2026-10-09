/**
 * tinymce_wbce — js/image-alt-badge.js
 *
 * Gentle reminder (no enforcement) for images with a missing or empty alt
 * attribute: a small ⚠ marker in the image's top-left corner with a
 * "Missing alt attribute" tooltip.
 *
 * The markers live inside the iframe body as data-mce-bogus="all" elements, so
 * they are never saved and never editable. pointer-events:auto is required for
 * the native title tooltip to appear on hover (the marker is tiny so it barely
 * overlaps the image). Activated per preset from include.php (opt-out) and from
 * the configurator preview:
 *   if (window.tinymceWbceImageAltBadge) tinymceWbceImageAltBadge(editor);
 *
 * @license GNU GPL2
 */
(function () {
    'use strict';

    window.tinymceWbceImageAltBadge = function (editor) {
        var markers = [];

        function label() {
            var m = window.TINYMCE_WBCE_I18N || {};
            return m.imgAltMissing || 'Alt attribute missing';
        }

        function missingAlt(img) {
            var a = img.getAttribute('alt');
            return a === null || String(a).trim() === '';
        }

        function clearAll() {
            markers.forEach(function (el) { if (el && el.parentNode) { el.parentNode.removeChild(el); } });
            markers = [];
        }

        function makeMarker() {
            var el = editor.getDoc().createElement('span');
            el.className = 'wbce-alt-warn';
            el.setAttribute('data-mce-bogus', 'all');
            el.setAttribute('contenteditable', 'false');
            el.title = label();
            el.textContent = '⚠'; // ⚠
            el.style.cssText = 'position:absolute;z-index:9998;display:block;pointer-events:auto;cursor:help;'
                + 'background:rgba(224,168,0,.95);color:#3a2d00;font:600 11px/1 Helvetica,Arial,sans-serif;'
                + 'padding:2px 5px;border-radius:6px;box-shadow:0 1px 3px rgba(0,0,0,.35);';
            return el;
        }

        function refresh() {
            var body = editor.getBody();
            if (!body) { return; }
            clearAll();
            var win = editor.getDoc().defaultView;
            var ox  = win ? win.pageXOffset : 0;
            var oy  = win ? win.pageYOffset : 0;
            editor.dom.select('img').forEach(function (img) {
                if (img.getAttribute('data-mce-bogus')) { return; }
                if (!missingAlt(img)) { return; }
                // A not-yet-loaded image has no reliable box → reposition on load.
                if (!img.complete && !img._wbceAltWait) {
                    img._wbceAltWait = true;
                    img.addEventListener('load', function () { img._wbceAltWait = false; schedule(); }, { once: true });
                }
                var rect = img.getBoundingClientRect();
                var el = makeMarker();
                body.appendChild(el);
                el.style.left = Math.round(rect.left + ox + 4) + 'px';
                el.style.top  = Math.round(rect.top  + oy + 4) + 'px';
                markers.push(el);
            });
        }

        var timer = null;
        function schedule() { if (timer) { clearTimeout(timer); } timer = setTimeout(refresh, 80); }

        editor.on('init', function () {
            schedule();
            setTimeout(schedule, 350); // catch late layout / slow images
            var win = editor.getDoc().defaultView;
            if (win) {
                win.addEventListener('scroll', schedule, true);
                win.addEventListener('resize', schedule);
            }
        });
        editor.on('SetContent NodeChange ObjectResized', schedule);
        editor.on('remove', clearAll);
    };
}());
