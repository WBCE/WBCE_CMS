/**
 * include/PlainMDE/src/plainmde-decorations.js
 *
 * Line-level visual polish on top of CodeMirror's own gfm/markdown mode:
 * table row background, a solid full-width background across fenced code
 * blocks, and a little breathing room around list blocks. Also patches
 * Enter-continuation so it keeps the "[ ]" checkbox marker on a new list
 * item, which CodeMirror's stock continuelist addon doesn't know about.
 *
 * This logic is carried over near-verbatim from the previous plugin's
 * wbce-mde.js — it was already well-reasoned and CodeMirror-specific, not
 * something a rewrite improves on. Renamed mdv-* classes to pmde-*.
 */
var PlainMDEDecorations = (function () {
    'use strict';

    function toggle(cm, line, where, cls, currentClasses, shouldHave) {
        var has = currentClasses.indexOf(cls) !== -1;
        if (shouldHave && !has) cm.addLineClass(line, where, cls);
        if (!shouldHave && has) cm.removeLineClass(line, where, cls);
    }

    function markTableLines(cm) {
        for (var i = 0; i < cm.lineCount(); i++) {
            var isTableLine = /^\s*\|.*\|\s*$/.test(cm.getLine(i));
            var hasClass = ((cm.lineInfo(i).textClass) || '').indexOf('pmde-table-row') !== -1;
            if (isTableLine && !hasClass) cm.addLineClass(i, 'text', 'pmde-table-row');
            if (!isTableLine && hasClass) cm.removeLineClass(i, 'text', 'pmde-table-row');
        }
    }

    // Tags every line from an opening ``` to its closing ``` with a
    // full-width line background, so a fenced block renders as one
    // continuous rectangle instead of per-token highlight pills.
    function markFencedCodeBlocks(cm) {
        var inFence = false;
        for (var i = 0; i < cm.lineCount(); i++) {
            var isFenceMarker = /^\s*```/.test(cm.getLine(i));
            var wasInFence = inFence;
            if (isFenceMarker) inFence = !inFence;
            var inBlock = isFenceMarker || wasInFence;
            var isFirst = isFenceMarker && !wasInFence;
            var isLast = isFenceMarker && wasInFence;

            var info = cm.lineInfo(i);
            var bgClass = info.bgClass || '';
            var txClass = info.textClass || '';

            toggle(cm, i, 'background', 'pmde-code-fence-bg', bgClass, inBlock);
            toggle(cm, i, 'background', 'pmde-code-fence-first', bgClass, isFirst);
            toggle(cm, i, 'background', 'pmde-code-fence-last', bgClass, isLast);
            toggle(cm, i, 'text', 'pmde-code-fence-line', txClass, inBlock);
        }
    }

    var LIST_MARKER = /^\s*([-*+]|\d+[.)])\s+/;
    var CONTINUATION = /^\s+\S/;
    var BLANK = /^\s*$/;

    function markListBlocks(cm) {
        var n = cm.lineCount();
        var inList = [];
        var inside = false;
        for (var i = 0; i < n; i++) {
            var line = cm.getLine(i);
            var isMarker = LIST_MARKER.test(line);
            var isBlank = BLANK.test(line);
            var isCont = !isMarker && !isBlank && CONTINUATION.test(line);

            if (isMarker || isCont) {
                inside = true;
            } else if (isBlank && inside) {
                var j = i;
                while (j < n && BLANK.test(cm.getLine(j))) j++;
                inside = j < n && (LIST_MARKER.test(cm.getLine(j)) || CONTINUATION.test(cm.getLine(j)));
            } else {
                inside = false;
            }
            inList[i] = inside;
        }

        for (var k = 0; k < n; k++) {
            var cur = inList[k];
            var next = k < n - 1 ? inList[k + 1] : false;
            var isLast = cur && !next;
            var isBefore = !cur && next;

            var txClass = cm.lineInfo(k).textClass || '';
            toggle(cm, k, 'text', 'pmde-list-block', txClass, cur);
            toggle(cm, k, 'text', 'pmde-list-block-last', txClass, isLast);
            toggle(cm, k, 'text', 'pmde-list-block-before', txClass, isBefore);
        }
    }

    function attach(cm) {
        var run = function () {
            markTableLines(cm);
            markFencedCodeBlocks(cm);
            markListBlocks(cm);
        };
        cm.on('change', run);
        run();
    }

    // Patches the CodeMirror command globally (once), so "- [ ] foo<Enter>"
    // continues as "- [ ] " instead of dropping the checkbox marker.
    var checklistPatched = false;
    var CHECKLIST_RE = /^(\s*[*+-]\s+)\[[ xX]\](\s+)/;

    function enableChecklistContinuation() {
        if (checklistPatched || !window.CodeMirror || !window.CodeMirror.commands.newlineAndIndentContinueMarkdownList) return;
        checklistPatched = true;

        var upstream = CodeMirror.commands.newlineAndIndentContinueMarkdownList;
        CodeMirror.commands.newlineAndIndentContinueMarkdownList = function (cm) {
            var ranges = cm.listSelections();
            if (ranges.length === 1 && ranges[0].empty()) {
                var pos = ranges[0].head;
                var line = cm.getLine(pos.line);
                var match = CHECKLIST_RE.exec(line);
                if (match) {
                    var rest = line.slice(match[0].length);
                    if (rest.trim() === '') {
                        cm.replaceRange('', { line: pos.line, ch: 0 }, { line: pos.line, ch: line.length });
                    } else {
                        cm.replaceSelection('\n' + match[1] + '[ ]' + match[2]);
                    }
                    return;
                }
            }
            return upstream(cm);
        };
    }

    return {
        attach: attach,
        enableChecklistContinuation: enableChecklistContinuation
    };
})();
