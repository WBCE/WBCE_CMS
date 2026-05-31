/**
 * CodeMirror 5 — mode: htt
 * WBCE LayoutParser (.htt) template syntax
 *
 * Overlays LayoutParser tokens on top of the HTML mixed mode.
 * Requires: htmlmixed, xml, css, javascript (loaded before this file).
 * Also requires: addon/mode/overlay.js
 */
(function (CodeMirror) {
    "use strict";

    // ── Fold patterns ─────────────────────────────────────────────────────────
    var F_OPEN  = /\{(if|ifnot|foreach|switch)\b/;
    var F_MID   = /\{(else|elseif|case|default)\b/;
    var F_CLOSE = /\{\/(if|ifnot|foreach|switch)\s*\}/;
    var F_MIDS  = {
        'if':      ['else', 'elseif'],
        'ifnot':   ['else'],
        'foreach': ['else'],
        'switch':  ['case', 'default']
    };

    // ── Overlay ───────────────────────────────────────────────────────────────
    function httOverlay() {
        return {
            startState: function () {
                return { inComment: false, inPH: false, phPhase: 'var' };
            },
            copyState: function (s) {
                return { inComment: s.inComment, inPH: s.inPH, phPhase: s.phPhase };
            },
            token: function (stream, state) {

                // multi-line {# comment #} continuation
                if (state.inComment) {
                    var ec = stream.string.indexOf('#}', stream.pos);
                    if (ec !== -1) { stream.pos = ec + 2; state.inComment = false; }
                    else stream.skipToEnd();
                    return 'comment htt-comment';
                }

                // ── inside [ placeholder ] ────────────────────────────────────
                if (state.inPH) {
                    if (stream.peek() === ']') {
                        stream.next(); state.inPH = false; state.phPhase = 'var';
                        return 'bracket htt-ph-close';
                    }
                    if (stream.peek() === '|') {
                        stream.next(); state.phPhase = 'filter';
                        return 'operator htt-pipe';
                    }
                    if (stream.peek() === ':' && state.phPhase === 'filter') {
                        stream.next();
                        if (stream.peek() === '"') { stream.match(/"[^"]*"/); return 'string htt-farg'; }
                        if (stream.match(/[A-Z_][A-Z0-9_.]*/i)) return 'variable-3 htt-fvar';
                        if (stream.match(/\d+/)) return 'number htt-farg';
                        stream.next(); return null;
                    }
                    if (state.phPhase === 'filter') {
                        if (stream.match(/[a-z][a-z0-9_-]*/)) return 'builtin htt-filter';
                        stream.next(); return null;
                    }
                    if (stream.match(/[A-Z_][A-Z0-9_.]*/i)) return 'variable-2 htt-var';
                    stream.next(); return null;
                }

                // fast skip
                if (stream.peek() !== '{' && stream.peek() !== '[') {
                    stream.next(); return null;
                }

                // {# comment #}
                if (stream.match('{#')) {
                    var ec2 = stream.string.indexOf('#}', stream.pos);
                    if (ec2 !== -1) { stream.pos = ec2 + 2; }
                    else { state.inComment = true; stream.skipToEnd(); }
                    return 'comment htt-comment';
                }

                // { block tags }
                if (stream.peek() === '{') {
                    if (stream.match(/^\{\/(?:if|ifnot|foreach|switch)\s*\}/))         return 'keyword htt-close';
                    if (stream.match(/^\{else\s*\}/))                                  return 'keyword htt-mid';
                    if (stream.match(/^\{elseif\s+[^\}]+\}/))                          return 'keyword htt-mid';
                    if (stream.match(/^\{(?:case\s+[^\}]+|default)\s*\}/))             return 'keyword htt-mid';
                    if (stream.match(/^\{if\s+[^\}]+\}/))                              return 'keyword htt-open';
                    if (stream.match(/^\{ifnot\s+[A-Z_][A-Z0-9_.]*\s*\}/i))           return 'keyword htt-open';
                    if (stream.match(/^\{switch\s+[A-Z_][A-Z0-9_.]*\s*\}/i))          return 'keyword htt-open';
                    if (stream.match(/^\{foreach\s+[A-Z_]\w*\s+as\s+(?:\w+\s*=>\s*)?\w+\s*\}/i))
                                                                                        return 'keyword htt-open';
                    if (stream.match(/^\{partial\s+[A-Z_]+(?:\s+with\s+[^\}]*)?\s*\}/i)) return 'def htt-directive';
                    if (stream.match(/^\{include\s+"[^"]*"(?:\s+with\s+[^\}]*)?\s*\}/))  return 'def htt-directive';
                    if (stream.match(/^\{default\s+[A-Z_][A-Z0-9_.]*\s+(?:"[^"]*"|'[^']*'|[^\s\}]+)\s*\}/i))
                                                                                        return 'def htt-directive';
                    if (stream.match(/^\{[^\}]*\}/)) return 'meta htt-unknown';
                    stream.next(); return null;
                }

                // [ placeholder opening
                if (stream.peek() === '[') {
                    stream.next(); state.inPH = true; state.phPhase = 'var';
                    return 'bracket htt-ph-open';
                }

                stream.next(); return null;
            }
        };
    }

    // ── Mode ──────────────────────────────────────────────────────────────────
    CodeMirror.defineMode('htt', function (config) {
        var htmlBase = CodeMirror.getMode(config, 'text/html');
        return CodeMirror.overlayMode(htmlBase, httOverlay(), false);
    });

    CodeMirror.defineMIME('text/x-htt', 'htt');

    // ── Fold helper ───────────────────────────────────────────────────────────
    function fTag(line, re) { var m = line.match(re); return m ? m[1] : null; }

    function foldFwd(cm, startLine, openTag, n) {
        var mids = F_MIDS[openTag] || [], depth = 0;
        var from = CodeMirror.Pos(startLine, cm.getLine(startLine).length);
        for (var r = startLine + 1; r < n; r++) {
            var l = cm.getLine(r);
            var o = fTag(l, F_OPEN), c = fTag(l, F_CLOSE), m = fTag(l, F_MID);
            if (o) { depth++; continue; }
            if (c) {
                if (depth > 0) { depth--; continue; }
                if (c === openTag) { var col = l.search(/\{/); return { from: from, to: CodeMirror.Pos(r, col < 0 ? 0 : col) }; }
                continue;
            }
            if (m && depth === 0 && mids.indexOf(m) !== -1) {
                var col = l.search(/\{/); return { from: from, to: CodeMirror.Pos(r, col < 0 ? 0 : col) };
            }
        }
        return null;
    }

    function findParent(cm, row) {
        var depth = 0;
        for (var r = row - 1; r >= 0; r--) {
            var l = cm.getLine(r), c = fTag(l, F_CLOSE), o = fTag(l, F_OPEN);
            if (c) { depth++; continue; }
            if (o) { if (depth > 0) { depth--; continue; } return o; }
        }
        return null;
    }

    CodeMirror.registerHelper('fold', 'htt', function (cm, start) {
        var n = cm.lineCount(), line = cm.getLine(start.line);
        var openTag = fTag(line, F_OPEN);
        if (openTag) return foldFwd(cm, start.line, openTag, n);
        var midTag = fTag(line, F_MID);
        if (midTag) { var parent = findParent(cm, start.line); if (parent) return foldFwd(cm, start.line, parent, n); }
        return null;
    });

})(CodeMirror);
