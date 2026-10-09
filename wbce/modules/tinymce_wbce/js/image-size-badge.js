/**
 * tinymce_wbce — js/image-size-badge.js
 *
 * When an image is selected in the editor, a small badge in its bottom-right
 * corner shows the current size as a percentage of the image's natural size.
 * Clicking the badge resets the image to 100% (removes width/height so it shows
 * at its intrinsic size).
 *
 * The badge lives inside the iframe body as a data-mce-bogus="all" element, so
 * it is never saved and never edited. Activated per preset from include.php:
 *   setup: function (ed) { if (window.tinymceWbceImageSizeBadge) tinymceWbceImageSizeBadge(ed); }
 *
 * @license GNU GPL2
 */
(function () {
    'use strict';

    window.tinymceWbceImageSizeBadge = function (editor) {
        var badge = null;

        function i18n() {
            var m = window.TINYMCE_WBCE_I18N || {};
            return m.imgBadgeReset || 'Click to reset to 100%';
        }

        function currentImg() {
            var n = editor.selection ? editor.selection.getNode() : null;
            return (n && n.nodeName === 'IMG') ? n : null;
        }

        function ensureBadge() {
            var body = editor.getBody();
            if (!body) { return null; }
            if (badge && badge.parentNode === body) { return badge; }
            var doc = editor.getDoc();
            badge = doc.createElement('span');
            badge.className = 'wbce-img-pct';
            badge.setAttribute('data-mce-bogus', 'all');
            badge.setAttribute('contenteditable', 'false');
            badge.title = i18n();
            badge.style.cssText = 'position:absolute;z-index:9999;display:none;'
                + 'background:rgba(20,20,20,.82);color:#fff;font:600 12px/1 Helvetica,Arial,sans-serif;'
                + 'padding:5px 8px;border-radius:8px;cursor:pointer;user-select:none;pointer-events:auto;'
                + 'box-shadow:0 1px 4px rgba(0,0,0,.4);';
            // Don't let the click move the caret / change selection.
            badge.addEventListener('mousedown', function (e) { e.preventDefault(); e.stopPropagation(); });
            badge.addEventListener('click', function (e) {
                e.preventDefault(); e.stopPropagation();
                var img = currentImg();
                if (!img) { return; }
                editor.undoManager.transact(function () {
                    editor.dom.setAttrib(img, 'width', null);
                    editor.dom.setAttrib(img, 'height', null);
                    img.style.width = '';
                    img.style.height = '';
                    editor.dom.setAttrib(img, 'data-mce-style', null);
                });
                editor.nodeChanged();
                update();
            });
            body.appendChild(badge);
            return badge;
        }

        function hide() { if (badge) { badge.style.display = 'none'; } }

        function update() {
            var img = currentImg();
            if (!img) { hide(); return; }
            var nat = img.naturalWidth || 0;
            if (!nat) {                       // not loaded yet → try again shortly
                hide();
                if (!img._wbcePctWait) {
                    img._wbcePctWait = true;
                    img.addEventListener('load', function () { img._wbcePctWait = false; update(); }, { once: true });
                }
                return;
            }
            var rect = img.getBoundingClientRect();
            var cur  = rect.width || img.width || nat;
            var pct  = Math.round(cur / nat * 100);

            var b = ensureBadge();
            if (!b) { return; }
            b.textContent = pct + '%';
            b.style.display = '';

            var win = editor.getDoc().defaultView;
            var x = rect.left + win.pageXOffset;
            var y = rect.top  + win.pageYOffset;
            b.style.left = Math.round(x + rect.width  - b.offsetWidth  - 6) + 'px';
            b.style.top  = Math.round(y + rect.height - b.offsetHeight - 6) + 'px';
        }

        editor.on('NodeChange',        update);
        editor.on('ObjectResized',     update);
        editor.on('ObjectResizeStart', update);
        editor.on('SetContent',        hide);
        editor.on('blur',              hide);
        editor.on('remove',            hide);
    };
}());
