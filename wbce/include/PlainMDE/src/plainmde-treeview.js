/**
 * include/PlainMDE/src/plainmde-treeview.js
 *
 * Inline preview for ```file-tree / ```page-tree fenced blocks: while the
 * cursor is somewhere else the block is shown as the rendered tree, and as
 * soon as the selection enters it — by click, by arrow key, or by editing —
 * the plain source comes back. Same behaviour as the TipTap editor's tree
 * node views (modules/tiptap_editor), built on CodeMirror's own primitive:
 * markText({ replacedWith, atomic, clearOnEnter }) swaps a line range for a
 * DOM node without touching the document, so the text, undo history and the
 * <textarea> sync stay exactly as they were.
 *
 * Rendering is NOT done here. The module looks for window.WbceFileTree
 * (modules/tiptap_editor/assets/filetree.js, vendored as
 * modules/MarkdownWbce/layout/filetree.js) and quietly does nothing when the
 * page does not load one — PlainMDE lives in include/ and must not depend on
 * a module being installed.
 *
 * Attached automatically by plainmde-core.js's constructor; pass
 * { treeView: false } to a PlainMDE instance to opt out.
 */
var PlainMDETreeView = (function () {
    'use strict';

    var FENCE = /^\s*```/;
    var OPEN  = /^\s*```\s*(\S*)/;

    // file-tree / filetree / fileTree / file_tree are the same tag — the very
    // normalisation ParsedownWbce::blockFencedCode() and plainmde-preview.js's
    // fenceTreeKind() use, so all three agree on what a tree fence is.
    function kindOf(tag) {
        var t = String(tag || '').toLowerCase().replace(/[^a-z]/g, '');
        return t === 'pagetree' ? 'page' : (t === 'filetree' ? 'file' : '');
    }

    /* Closed tree fences in the document, as {from, to, kind, text} line ranges.
     * An unclosed block is skipped on purpose: that is what a tree looks like
     * while it is being typed, and replacing it would hide the typing. Other
     * fenced blocks are skipped whole, so a ```php block quoting "```file-tree"
     * never starts one. */
    function treeBlocks(cm) {
        var out = [], i = 0, n = cm.lineCount();
        while (i < n) {
            if (!FENCE.test(cm.getLine(i))) { i++; continue; }

            var m    = cm.getLine(i).match(OPEN);
            var kind = kindOf(m && m[1]);
            var j    = i + 1;
            while (j < n && !FENCE.test(cm.getLine(j))) j++;

            if (kind && j < n) {
                out.push({
                    from: i,
                    to:   j,
                    kind: kind,
                    text: cm.getRange({ line: i + 1, ch: 0 }, { line: j, ch: 0 }).replace(/\n$/, '')
                });
            }
            i = j + 1;
        }
        return out;
    }

    function selectionTouches(cm, from, to) {
        var sels = cm.listSelections(), i, a, b;
        for (i = 0; i < sels.length; i++) {
            a = Math.min(sels[i].anchor.line, sels[i].head.line);
            b = Math.max(sels[i].anchor.line, sels[i].head.line);
            if (!(b < from || a > to)) return true;
        }
        return false;
    }

    function attach(cm) {
        if (!cm) return null;

        var marks = [];
        var signature = null;
        var timer = null;
        var waitedForRenderer = 0;

        function clearMarks() {
            marks.forEach(function (mk) { mk.clear(); });
            marks = [];
        }

        function build() {
            // The renderer is loaded by the host page and may well arrive after
            // the editor does (a BODY-bottom script vs. this constructor), so
            // keep looking for a few seconds before giving up for good.
            if (!window.WbceFileTree || !window.WbceFileTree.render) {
                if (waitedForRenderer < 20) {
                    waitedForRenderer++;
                    clearTimeout(timer);
                    timer = setTimeout(build, 250);
                }
                return;
            }

            var blocks = treeBlocks(cm);
            var sig = blocks.map(function (b) {
                return b.from + ':' + b.to + ':' + b.kind +
                       ':' + (selectionTouches(cm, b.from, b.to) ? 'e' : '-') +
                       ':' + b.text;
            }).join('|');

            // Nothing that affects the widgets changed — leaving the existing
            // marks alone avoids re-rendering (and scroll jitter) on every
            // cursor move.
            if (sig === signature) return;
            signature = sig;

            clearMarks();

            blocks.forEach(function (b) {
                if (selectionTouches(cm, b.from, b.to)) return;

                var holder = document.createElement('div');
                holder.className = 'pmde-treeview pmde-treeview--' + b.kind;
                holder.appendChild(window.WbceFileTree.render(b.text, { kind: b.kind }));

                var mark = cm.markText(
                    { line: b.from, ch: 0 },
                    { line: b.to, ch: cm.getLine(b.to).length },
                    {
                        replacedWith: holder,
                        atomic: true,
                        clearOnEnter: true,
                        inclusiveLeft: false,
                        inclusiveRight: false
                    }
                );
                marks.push(mark);

                // Click into the preview = edit it, cursor on the first content
                // line — the same gesture the TipTap node view uses.
                holder.addEventListener('mousedown', function (ev) {
                    ev.preventDefault();
                    var pos = mark.find();
                    var line = pos ? pos.from.line + 1 : b.from + 1;
                    mark.clear();
                    signature = null;
                    cm.setCursor({ line: Math.min(line, cm.lineCount() - 1), ch: 0 });
                    cm.focus();
                });
            });
        }

        function schedule() {
            clearTimeout(timer);
            timer = setTimeout(build, 120);
        }

        cm.on('changes', schedule);
        cm.on('cursorActivity', schedule);
        schedule();

        return {
            refresh: function () { signature = null; build(); },
            detach: function () {
                clearTimeout(timer);
                cm.off('changes', schedule);
                cm.off('cursorActivity', schedule);
                clearMarks();
            }
        };
    }

    return { attach: attach, treeBlocks: treeBlocks, kindOf: kindOf };
}());
