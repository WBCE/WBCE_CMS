/**
 * include/PlainMDE/src/plainmde-sync.js
 *
 * Keeps the editor and the preview pane in visual sync, using the
 * .pmde-src-block wrapper divs plainmde-preview.js already produces
 * (data-src-start/data-src-end = source line range for that block).
 *
 * Two independent things, both driven by the same block map:
 *  1. Cursor moves in the editor → highlight the corresponding preview block.
 *  2. Scrolling either pane → scroll the other to the matching block,
 *     interpolating within the block rather than syncing by scroll
 *     *ratio* (a ratio-based sync drifts on any real document, since
 *     source and rendered HTML aren't proportionally the same shape —
 *     a 3-line table source can render much taller than 3 lines of
 *     prose). This was already true of the previous plugin and remains
 *     the right approach here.
 *
 * PlainMDE always renders editor and preview into the same wrapper
 * (`editor.previewEl`), used for both "Preview" and "Side by side" modes —
 * so, unlike the old two-separate-panes version, there's only ever one
 * preview element to look at.
 */
var PlainMDESync = (function () {
    'use strict';

    function attachCursorHighlight(editor) {
        var cm = editor.codemirror;
        cm.on('cursorActivity', function () {
            if (!editor.previewActive && !editor.sideBySideActive) return;
            var line = cm.getCursor().line;
            var blocks = editor.previewEl.querySelectorAll('.pmde-src-block');
            for (var i = 0; i < blocks.length; i++) {
                var b = blocks[i];
                var start = parseInt(b.getAttribute('data-src-start'), 10);
                var end = parseInt(b.getAttribute('data-src-end'), 10);
                b.classList.toggle('pmde-src-block-active', line >= start && line <= end);
            }
        });
    }

    function attachScrollSync(editor) {
        var cm = editor.codemirror;
        var pane = editor.previewEl;
        var syncing = false;

        function blockTop(block) {
            return block.getBoundingClientRect().top - pane.getBoundingClientRect().top + pane.scrollTop;
        }

        function editorToPreview() {
            if (syncing || !(editor.previewActive || editor.sideBySideActive)) return;
            var blocks = pane.querySelectorAll('.pmde-src-block');
            if (!blocks.length) return;

            var topLine = cm.lineAtHeight(cm.getScrollInfo().top, 'local');
            var target = null, frac = 0;
            for (var i = 0; i < blocks.length; i++) {
                var start = parseInt(blocks[i].getAttribute('data-src-start'), 10);
                var end = parseInt(blocks[i].getAttribute('data-src-end'), 10);
                if (topLine <= end) {
                    target = blocks[i];
                    frac = topLine >= start && end > start ? (topLine - start) / (end - start) : 0;
                    break;
                }
            }
            if (!target) { target = blocks[blocks.length - 1]; frac = 1; }

            syncing = true;
            pane.scrollTop = blockTop(target) + frac * target.offsetHeight;
            requestAnimationFrame(function () { syncing = false; });
        }

        function previewToEditor() {
            if (syncing || !editor.sideBySideActive) return; // one-directional in plain preview mode (editor is hidden)
            var blocks = pane.querySelectorAll('.pmde-src-block');
            if (!blocks.length) return;

            var target = blocks[0];
            for (var i = 0; i < blocks.length; i++) {
                if (blockTop(blocks[i]) <= pane.scrollTop + 2) target = blocks[i];
                else break;
            }
            var start = parseInt(target.getAttribute('data-src-start'), 10);
            var end = parseInt(target.getAttribute('data-src-end'), 10);
            var within = target.offsetHeight > 0 ? (pane.scrollTop - blockTop(target)) / target.offsetHeight : 0;
            var line = Math.round(start + Math.max(0, Math.min(1, within)) * (end - start));

            syncing = true;
            cm.scrollTo(null, cm.charCoords({ line: line, ch: 0 }, 'local').top);
            requestAnimationFrame(function () { syncing = false; });
        }

        cm.on('scroll', editorToPreview);
        pane.addEventListener('scroll', previewToEditor);
    }

    function attach(editor) {
        attachCursorHighlight(editor);
        attachScrollSync(editor);
    }

    return { attach: attach };
})();
